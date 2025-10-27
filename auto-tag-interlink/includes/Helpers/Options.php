<?php
namespace ATI\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

class Options
{
    public const OPTION_KEY = 'ati_options';
    private const CACHE_VERSION_KEY = 'ati_cache_version';

    /**
     * Default options.
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'enabled' => 1,
            'link_in_headings' => 0,
            'post_types' => ['post'],
            'keyword_meta_fields' => 'keywords',
            'max_related_per_tag' => 10,
        ];
    }

    /**
     * Get merged options.
     * @return array<string, mixed>
     */
    public static function getOptions(): array
    {
        $stored = get_option(self::OPTION_KEY, []);
        if (!is_array($stored)) {
            $stored = [];
        }
        return wp_parse_args($stored, self::defaults());
    }

    /**
     * Return included post types as array of slugs.
     * @return string[]
     */
    public static function getIncludedPostTypes(): array
    {
        $opts = self::getOptions();
        $types = $opts['post_types'] ?? ['post'];
        if (is_string($types)) {
            $types = array_map('trim', explode(',', $types));
        }
        if (!is_array($types)) {
            $types = ['post'];
        }
        // sanitize
        $types = array_values(array_filter(array_map('sanitize_key', $types)));
        return !empty($types) ? $types : ['post'];
    }

    /**
     * Get keyword meta fields as array.
     * @return string[]
     */
    public static function getKeywordMetaFields(): array
    {
        $opts = self::getOptions();
        $value = $opts['keyword_meta_fields'] ?? 'keywords';
        if (is_string($value)) {
            $fields = array_map('trim', explode(',', $value));
        } elseif (is_array($value)) {
            $fields = $value;
        } else {
            $fields = ['keywords'];
        }
        $fields = array_values(array_filter(array_map('sanitize_key', $fields)));
        return !empty($fields) ? $fields : ['keywords'];
    }

    /**
     * Max related posts per token.
     */
    public static function getMaxRelatedPerToken(): int
    {
        $opts = self::getOptions();
        $max = (int) ($opts['max_related_per_tag'] ?? 10);
        if ($max < 1) {
            $max = 1;
        }
        if ($max > 100) {
            $max = 100;
        }
        return $max;
    }

    /**
     * Cache version used for transient keys to invalidate en masse.
     */
    public static function getCacheVersion(): int
    {
        $version = (int) get_option(self::CACHE_VERSION_KEY, 1);
        if ($version < 1) {
            $version = 1;
        }
        return $version;
    }

    /**
     * Increment cache version to invalidate all transients logically.
     */
    public static function bumpCacheVersion(): void
    {
        $v = self::getCacheVersion();
        update_option(self::CACHE_VERSION_KEY, $v + 1, false);
    }
}
