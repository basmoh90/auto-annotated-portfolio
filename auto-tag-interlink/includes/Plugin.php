<?php
namespace ATI;

use ATI\Admin\SettingsPage;
use ATI\Helpers\Options;
use ATI\Services\RelatedPostsService;
use ATI\Services\Linker;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    /**
     * Flag to prevent recursive save_post processing.
     * @var bool
     */
    private $isProcessing = false;

    /** @var SettingsPage */
    private $settingsPage;

    /** @var RelatedPostsService */
    private $relatedService;

    /** @var Linker */
    private $linker;

    public function __construct()
    {
        $this->settingsPage = new SettingsPage();
        $this->relatedService = new RelatedPostsService();
        $this->linker = new Linker();
    }

    /**
     * Initialize plugin: hooks and admin settings.
     */
    public function init(): void
    {
        // Admin settings
        add_action('admin_menu', [$this->settingsPage, 'registerMenu']);
        add_action('admin_init', [$this->settingsPage, 'registerSettings']);
        add_action('admin_post_ati_clear_cache', [$this, 'handleClearCacheAction']);

        // Content processing on save
        add_action('save_post', [$this, 'maybeProcessPost'], 20, 3);
        // Bump caches when any post updates
        add_action('post_updated', [$this, 'handlePostUpdated'], 10, 3);

        // Invalidate cache on term/meta/settings changes
        add_action('set_object_terms', [$this, 'handleTermsChanged'], 10, 6);
        add_action('updated_post_meta', [$this, 'handleMetaUpdated'], 10, 4);
        add_action('added_post_meta', [$this, 'handleMetaUpdated'], 10, 4);
        add_action('delete_post_meta', [$this, 'handleMetaUpdated'], 10, 4);
        add_action('updated_option', [$this, 'handleAnyOptionUpdated'], 10, 3);
    }

    /**
     * Bump cache on post updates to recalculate related lookups.
     */
    public function handlePostUpdated($postId, $postAfter, $postBefore): void
    {
        if (wp_is_post_revision($postId) || wp_is_post_autosave($postId)) {
            return;
        }
        if (!in_array(get_post_type($postId), Options::getIncludedPostTypes(), true)) {
            return;
        }
        Options::bumpCacheVersion();
    }

    /**
     * Handle clear cache admin action.
     */
    public function handleClearCacheAction(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to perform this action.', 'auto-tag-interlink'));
        }

        check_admin_referer('ati_clear_cache');

        Options::bumpCacheVersion();

        wp_safe_redirect(add_query_arg([
            'page' => 'ati-settings',
            'ati_cache_cleared' => '1',
        ], admin_url('options-general.php')));
        exit;
    }

    /**
     * Bump cache on any relevant option update.
     */
    public function handleAnyOptionUpdated($option, $oldValue, $value): void
    {
        if ($option === Options::OPTION_KEY) {
            Options::bumpCacheVersion();
        }
    }

    /**
     * Bump cache version when tags change.
     */
    public function handleTermsChanged($object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids): void
    {
        if ($taxonomy === 'post_tag') {
            Options::bumpCacheVersion();
        }
    }

    /**
     * Bump cache version when configured keyword meta fields are updated.
     */
    public function handleMetaUpdated($meta_id, $object_id, $meta_key, $_meta_value): void
    {
        $metaFields = Options::getKeywordMetaFields();
        if (in_array($meta_key, $metaFields, true)) {
            Options::bumpCacheVersion();
        }
    }

    /**
     * Process post content if feature is enabled.
     */
    public function maybeProcessPost(int $postId, $post, bool $update): void
    {
        if ($this->isProcessing) {
            return;
        }

        // Preconditions: enabled, allowed post type, not autosave/revision, published or scheduled
        $opts = Options::getOptions();
        if (empty($opts['enabled'])) {
            return;
        }

        if (!in_array(get_post_type($postId), Options::getIncludedPostTypes(), true)) {
            return;
        }

        if (wp_is_post_revision($postId) || wp_is_post_autosave($postId)) {
            return;
        }

        $status = get_post_status($postId);
        if (!in_array($status, ['publish', 'future'], true)) {
            return;
        }

        $originalContent = (string) get_post_field('post_content', $postId, 'raw');
        if ($originalContent === '') {
            return;
        }

        // Build tokens from tags and configured keyword meta fields
        $tokens = $this->collectTokensForPost($postId);
        if (empty($tokens)) {
            return;
        }

        // Build targets map using caching
        $targetsMap = $this->relatedService->getTargetsMap($tokens, $postId);
        if (empty($targetsMap)) {
            return; // nothing to link to
        }

        $linkInHeadings = !empty($opts['link_in_headings']);
        $processed = $this->linker->linkContent($originalContent, $targetsMap, (int) $postId, $linkInHeadings);

        if (is_string($processed) && $processed !== $originalContent) {
            $this->isProcessing = true;
            // Temporarily unhook to avoid recursion
            remove_action('save_post', [$this, 'maybeProcessPost'], 20);

            wp_update_post([
                'ID' => $postId,
                'post_content' => $processed,
            ]);

            // Re-hook and reset flag
            add_action('save_post', [$this, 'maybeProcessPost'], 20, 3);
            $this->isProcessing = false;
        }
    }

    /**
     * Collect tag names and custom keyword values for the given post.
     *
     * @param int $postId
     * @return string[] Unique list of tokens to link against
     */
    private function collectTokensForPost(int $postId): array
    {
        $tokens = [];

        // Tags
        $tags = get_the_terms($postId, 'post_tag');
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                if (!empty($tag->name)) {
                    $tokens[] = sanitize_text_field($tag->name);
                }
            }
        }

        // Keywords from configured meta fields (comma-separated list)
        foreach (Options::getKeywordMetaFields() as $metaKey) {
            $raw = get_post_meta($postId, $metaKey, true);
            if (is_string($raw) && $raw !== '') {
                $parts = array_map('trim', explode(',', $raw));
                foreach ($parts as $part) {
                    if ($part !== '') {
                        $tokens[] = sanitize_text_field($part);
                    }
                }
            }
        }

        // Unique, keep original case for display matching
        $tokens = array_values(array_unique($tokens));

        // Filter out extremely short tokens (e.g., 1-char) to reduce noise
        $tokens = array_values(array_filter($tokens, static function ($t) {
            return mb_strlen($t) >= 2;
        }));

        return $tokens;
    }
}
