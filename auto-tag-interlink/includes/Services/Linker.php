<?php
namespace ATI\Services;

if (!defined('ABSPATH')) {
    exit;
}

class Linker
{
    /**
     * Link content using DOMDocument, skipping certain elements.
     *
     * @param string $html Original post content (HTML)
     * @param array<string, array<int, array{id:int,url:string,title:string}>> $targetsMap Map of token=>targets
     * @param int $currentPostId Current post ID to avoid self-linking
     * @param bool $linkInHeadings Whether to allow linking inside headings
     * @return string Processed HTML
     */
    public function linkContent(string $html, array $targetsMap, int $currentPostId, bool $linkInHeadings = false): string
    {
        if ($html === '' || empty($targetsMap)) {
            return $html;
        }

        // Prepare rotation indices for round-robin across targets for each token
        $rotation = [];
        foreach ($targetsMap as $token => $targets) {
            $rotation[$token] = 0;
        }

        // Use DOMDocument with UTF-8 handling
        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        // Wrap with a temporary container to maintain fragment parsing
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . '<div id="ati-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $dom->getElementById('ati-root');
        if (!$root) {
            return $html;
        }

        // Walk text nodes and replace tokens with links
        $this->walkAndLink($dom, $root, $targetsMap, $rotation, $linkInHeadings);

        // Extract inner HTML of wrapper
        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }
        return $result;
    }

    /**
     * Recursively traverse DOM and link in text nodes.
     * Skip inside a, code, pre, script, style, and optionally headings h1-h6.
     *
     * @param \DOMDocument $dom
     * @param \DOMNode $node
     * @param array<string, array<int, array{id:int,url:string,title:string}>> $targetsMap
     * @param array<string, int> $rotation
     * @param bool $linkInHeadings
     */
    private function walkAndLink(\DOMDocument $dom, \DOMNode $node, array $targetsMap, array &$rotation, bool $linkInHeadings): void
    {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            $tag = strtolower($node->nodeName);
            $skipTags = ['a', 'code', 'pre', 'script', 'style'];
            if (!$linkInHeadings && in_array($tag, ['h1','h2','h3','h4','h5','h6'], true)) {
                return; // skip entire heading subtree
            }
            if (in_array($tag, $skipTags, true)) {
                return; // skip prohibited tags
            }
        }

        if ($node->nodeType === XML_TEXT_NODE) {
            $this->linkTextNode($dom, $node, $targetsMap, $rotation);
            return;
        }

        // Iterate copy since we may modify children
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }
        foreach ($children as $child) {
            $this->walkAndLink($dom, $child, $targetsMap, $rotation, $linkInHeadings);
        }
    }

    /**
     * Replace tokens in a text node with anchors. Allows unlimited replacements.
     * Uses round-robin among target posts for each token.
     *
     * @param \DOMDocument $dom
     * @param \DOMNode $textNode
     * @param array<string, array<int, array{id:int,url:string,title:string}>> $targetsMap
     * @param array<string, int> $rotation
     */
    private function linkTextNode(\DOMDocument $dom, \DOMNode $textNode, array $targetsMap, array &$rotation): void
    {
        $text = $textNode->nodeValue ?? '';
        if ($text === '') {
            return;
        }

        // Build regex pattern for all tokens, longest-first to reduce partial overlaps
        $tokens = array_keys($targetsMap);
        usort($tokens, static function ($a, $b) {
            return mb_strlen($b) <=> mb_strlen($a);
        });
        $escaped = array_map(static function ($t) {
            return preg_quote($t, '/');
        }, $tokens);
        if (empty($escaped)) {
            return;
        }

        $pattern = '/(' . implode('|', $escaped) . ')/ui';

        $parts = preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts === false || count($parts) <= 1) {
            return; // nothing to replace
        }

        $fragment = $dom->createDocumentFragment();

        for ($i = 0; $i < count($parts); $i++) {
            $segment = $parts[$i];
            if ($segment === '') {
                continue;
            }
            // Even indexes are non-matching text, odd indexes are matched tokens
            if ($i % 2 === 0) {
                $fragment->appendChild($dom->createTextNode($segment));
            } else {
                $tokenMatched = $segment;
                $tokenKey = $this->findTokenKey($tokenMatched, $targetsMap);
                if ($tokenKey === null) {
                    $fragment->appendChild($dom->createTextNode($segment));
                    continue;
                }
                $targets = $targetsMap[$tokenKey];
                if (empty($targets)) {
                    $fragment->appendChild($dom->createTextNode($segment));
                    continue;
                }
                $idx = $rotation[$tokenKey] % count($targets);
                $rotation[$tokenKey]++;
                $target = $targets[$idx];

                $a = $dom->createElement('a');
                $a->setAttribute('href', $target['url']);
                $a->setAttribute('class', 'ati-auto-link');
                $a->setAttribute('data-ati-post-id', (string) $target['id']);
                $a->setAttribute('title', $target['title']);
                $a->appendChild($dom->createTextNode($segment));
                $fragment->appendChild($a);
            }
        }

        $textNode->parentNode->replaceChild($fragment, $textNode);
    }

    /**
     * Find canonical token key matching the given segment case-insensitively.
     * @param string $segment
     * @param array<string, mixed> $targetsMap
     * @return string|null
     */
    private function findTokenKey(string $segment, array $targetsMap): ?string
    {
        $lower = mb_strtolower($segment);
        foreach ($targetsMap as $token => $_) {
            if (mb_strtolower($token) === $lower) {
                return $token;
            }
        }
        return null;
    }
}
