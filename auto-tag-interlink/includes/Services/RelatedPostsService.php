<?php
namespace ATI\Services;

use ATI\Helpers\Options;

if (!defined('ABSPATH')) {
    exit;
}

class RelatedPostsService
{
    /**
     * Get a map of token => list of target posts (id, url, title), cached.
     *
     * @param string[] $tokens
     * @param int $excludePostId
     * @return array<string, array<int, array{id:int, url:string, title:string}>>
     */
    public function getTargetsMap(array $tokens, int $excludePostId): array
    {
        $tokens = array_values(array_unique(array_filter(array_map('trim', $tokens))));
        if (empty($tokens)) {
            return [];
        }

        $map = [];
        foreach ($tokens as $token) {
            $map[$token] = $this->getTargetsForToken($token, $excludePostId);
        }

        return array_filter($map);
    }

    /**
     * Get related posts for a single token using transients.
     *
     * @param string $token
     * @param int $excludePostId
     * @return array<int, array{id:int, url:string, title:string}>
     */
    private function getTargetsForToken(string $token, int $excludePostId): array
    {
        $cacheKey = $this->buildTransientKey($token);
        $cached = get_transient($cacheKey);
        if (is_array($cached)) {
            // Filter current post id just in case
            return array_values(array_filter($cached, static function ($item) use ($excludePostId) {
                return isset($item['id']) && (int) $item['id'] !== $excludePostId;
            }));
        }

        $max = Options::getMaxRelatedPerToken();
        $postTypes = Options::getIncludedPostTypes();

        // Query by tag name match OR meta keywords containing token
        // We keep it simple: primary by tag taxonomy terms; keywords meta as a secondary pass
        $tag_query = new \WP_Query([
            'post_type' => $postTypes,
            'post_status' => 'publish',
            'posts_per_page' => $max,
            'ignore_sticky_posts' => true,
            'fields' => 'ids',
            'tax_query' => [
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'name',
                    'terms' => [$token],
                    'include_children' => false,
                ],
            ],
        ]);

        $ids = is_array($tag_query->posts) ? $tag_query->posts : [];

        // Supplement with keyword meta matches if needed
        if (count($ids) < $max) {
            $need = $max - count($ids);
            $metaKeys = Options::getKeywordMetaFields();
            $metaQuery = ['relation' => 'OR'];
            foreach ($metaKeys as $key) {
                $metaQuery[] = [
                    'key' => $key,
                    'value' => $token,
                    'compare' => 'LIKE',
                ];
            }

            $meta_q = new \WP_Query([
                'post_type' => $postTypes,
                'post_status' => 'publish',
                'posts_per_page' => $need,
                'ignore_sticky_posts' => true,
                'fields' => 'ids',
                'meta_query' => $metaQuery,
            ]);

            $meta_ids = is_array($meta_q->posts) ? $meta_q->posts : [];
            $ids = array_values(array_unique(array_merge($ids, $meta_ids)));
        }

        // Map to data and filter exclude
        $data = [];
        foreach ($ids as $id) {
            $pid = (int) $id;
            if ($pid === $excludePostId) {
                continue;
            }
            $permalink = get_permalink($pid);
            if (!$permalink) {
                continue;
            }
            $data[] = [
                'id' => $pid,
                'url' => esc_url_raw($permalink),
                'title' => get_the_title($pid) ?: '',
            ];
        }

        // Store for 12 hours with versioned key
        set_transient($cacheKey, $data, 12 * HOUR_IN_SECONDS);

        return $data;
    }

    private function buildTransientKey(string $token): string
    {
        $v = Options::getCacheVersion();
        $hash = substr(md5($token), 0, 12);
        return 'ati_rel_' . $v . '_' . $hash;
    }
}
