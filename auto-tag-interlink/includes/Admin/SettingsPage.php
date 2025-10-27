<?php
namespace ATI\Admin;

use ATI\Helpers\Options;

if (!defined('ABSPATH')) {
    exit;
}

class SettingsPage
{
    public function registerMenu(): void
    {
        add_options_page(
            __('Auto Tag Interlink', 'auto-tag-interlink'),
            __('Auto Tag Interlink', 'auto-tag-interlink'),
            'manage_options',
            'ati-settings',
            [$this, 'render']
        );
    }

    public function registerSettings(): void
    {
        register_setting('ati_settings_group', Options::OPTION_KEY, [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize'],
            'default' => Options::defaults(),
        ]);

        add_settings_section('ati_main', __('General Settings', 'auto-tag-interlink'), function () {
            echo '<p>' . esc_html__('Configure automatic interlinking behavior.', 'auto-tag-interlink') . '</p>';
        }, 'ati-settings');

        add_settings_field('enabled', __('Enable auto-linking', 'auto-tag-interlink'), function () {
            $opts = Options::getOptions();
            printf('<input type="checkbox" name="%1$s[enabled]" value="1" %2$s />', esc_attr(Options::OPTION_KEY), checked(!empty($opts['enabled']), true, false));
        }, 'ati-settings', 'ati_main');

        add_settings_field('link_in_headings', __('Link inside headings (H1–H6)', 'auto-tag-interlink'), function () {
            $opts = Options::getOptions();
            printf('<input type="checkbox" name="%1$s[link_in_headings]" value="1" %2$s />', esc_attr(Options::OPTION_KEY), checked(!empty($opts['link_in_headings']), true, false));
        }, 'ati-settings', 'ati_main');

        add_settings_field('post_types', __('Included post types', 'auto-tag-interlink'), function () {
            $opts = Options::getOptions();
            $types = get_post_types(['public' => true], 'objects');
            echo '<select multiple size="5" name="' . esc_attr(Options::OPTION_KEY) . '[post_types][]">';
            foreach ($types as $type) {
                $selected = in_array($type->name, Options::getIncludedPostTypes(), true) ? 'selected' : '';
                echo '<option value="' . esc_attr($type->name) . '" ' . $selected . '>' . esc_html($type->labels->singular_name) . ' (' . esc_html($type->name) . ')</option>';
            }
            echo '</select>';
            echo '<p class="description">' . esc_html__('Hold Ctrl/⌘ to select multiple post types.', 'auto-tag-interlink') . '</p>';
        }, 'ati-settings', 'ati_main');

        add_settings_field('keyword_meta_fields', __('Keyword meta fields (comma-separated)', 'auto-tag-interlink'), function () {
            $opts = Options::getOptions();
            $value = is_array($opts['keyword_meta_fields'] ?? null) ? implode(',', (array) $opts['keyword_meta_fields']) : ($opts['keyword_meta_fields'] ?? 'keywords');
            printf('<input type="text" class="regular-text" name="%1$s[keyword_meta_fields]" value="%2$s" />', esc_attr(Options::OPTION_KEY), esc_attr($value));
        }, 'ati-settings', 'ati_main');

        add_settings_field('max_related_per_tag', __('Max related posts per tag', 'auto-tag-interlink'), function () {
            $opts = Options::getOptions();
            $value = (int) ($opts['max_related_per_tag'] ?? 10);
            printf('<input type="number" min="1" max="100" name="%1$s[max_related_per_tag]" value="%2$d" />', esc_attr(Options::OPTION_KEY), $value);
        }, 'ati-settings', 'ati_main');
    }

    /**
     * Sanitize input options from settings form.
     *
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function sanitize($input): array
    {
        $defaults = Options::defaults();
        $output = [];

        $output['enabled'] = !empty($input['enabled']) ? 1 : 0;
        $output['link_in_headings'] = !empty($input['link_in_headings']) ? 1 : 0;

        // Post types
        $types = $input['post_types'] ?? $defaults['post_types'];
        if (is_string($types)) {
            $types = array_map('trim', explode(',', $types));
        }
        if (!is_array($types)) {
            $types = ['post'];
        }
        $output['post_types'] = array_values(array_filter(array_map('sanitize_key', $types)));
        if (empty($output['post_types'])) {
            $output['post_types'] = ['post'];
        }

        // Keyword meta fields
        $fields = $input['keyword_meta_fields'] ?? $defaults['keyword_meta_fields'];
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        $fieldList = array_values(array_filter(array_map('sanitize_key', array_map('trim', explode(',', (string) $fields)))));
        $output['keyword_meta_fields'] = !empty($fieldList) ? $fieldList : ['keywords'];

        // Max related per tag
        $max = isset($input['max_related_per_tag']) ? (int) $input['max_related_per_tag'] : (int) $defaults['max_related_per_tag'];
        if ($max < 1) {
            $max = 1;
        }
        if ($max > 100) {
            $max = 100;
        }
        $output['max_related_per_tag'] = $max;

        // Bump cache because settings changed
        Options::bumpCacheVersion();

        return $output;
    }

    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $cleared = isset($_GET['ati_cache_cleared']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Auto Tag Interlink', 'auto-tag-interlink') . '</h1>';
        if ($cleared) {
            echo '<div class="notice notice-success"><p>' . esc_html__('Cache cleared.', 'auto-tag-interlink') . '</p></div>';
        }
        echo '<form method="post" action="options.php">';
        settings_fields('ati_settings_group');
        do_settings_sections('ati-settings');
        submit_button();
        echo '</form>';

        echo '<hr />';
        echo '<h2>' . esc_html__('Maintenance', 'auto-tag-interlink') . '</h2>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('ati_clear_cache');
        echo '<input type="hidden" name="action" value="ati_clear_cache" />';
        submit_button(__('Clear Cache', 'auto-tag-interlink'), 'secondary');
        echo '</form>';

        echo '</div>';
    }
}
