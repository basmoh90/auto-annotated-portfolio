<?php
/**
 * Plugin Name: Auto Tag Interlink
 * Plugin URI: https://example.com/auto-tag-interlink
 * Description: Automatically inserts internal links between posts based on shared tags and optional keywords.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: auto-tag-interlink
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Minimum PHP version check
if (version_compare(PHP_VERSION, '7.4', '<')) {
    if (is_admin()) {
        add_action('admin_notices', static function () {
            echo '<div class="notice notice-error"><p>' . esc_html__('Auto Tag Interlink requires PHP 7.4 or higher.', 'auto-tag-interlink') . '</p></div>';
        });
    }
    return;
}

// PSR-4 like autoloader for this plugin only
spl_autoload_register(static function ($class) {
    $prefix = 'ATI\\';
    $base_dir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    // Replace backslashes in the class with directory separators
    $relative_path = str_replace(chr(92), '/', $relative_class) . '.php';
    $file = $base_dir . $relative_path;

    if (file_exists($file)) {
        require $file;
    }
});

// Boot the plugin
add_action('plugins_loaded', static function () {
    // Load text domain
    load_plugin_textdomain('auto-tag-interlink', false, dirname(plugin_basename(__FILE__)) . '/languages');

    $container = new ATI\Plugin();
    $container->init();
});
