<?php
/**
 * Plugin Name: Auto Tag Interlink
 * Plugin URI: https://example.com/auto-tag-interlink
 * Description: Automatically creates internal links between posts based on shared tags and optional keywords. Improves SEO and user navigation.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: auto-tag-interlink
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 *
 * @package AutoTagInterlink
 */

namespace AutoTagInterlink;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'AUTO_TAG_INTERLINK_VERSION', '1.0.0' );
define( 'AUTO_TAG_INTERLINK_PLUGIN_FILE', __FILE__ );
define( 'AUTO_TAG_INTERLINK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AUTO_TAG_INTERLINK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'AUTO_TAG_INTERLINK_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoloader for plugin classes.
 *
 * @param string $class The fully-qualified class name.
 */
function autoload( $class ) {
	// Check if the class is in our namespace.
	$prefix = 'AutoTagInterlink\\';
	$len    = strlen( $prefix );

	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	// Get the relative class name.
	$relative_class = substr( $class, $len );

	// Convert namespace to file path.
	$file = AUTO_TAG_INTERLINK_PLUGIN_DIR . 'includes/class-' . strtolower( str_replace( '_', '-', $relative_class ) ) . '.php';

	// If the file exists, require it.
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}

spl_autoload_register( __NAMESPACE__ . '\\autoload' );

/**
 * Initialize the plugin.
 */
function init() {
	// Initialize the main plugin class.
	Plugin::get_instance();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );

/**
 * Activation hook.
 */
function activate() {
	// Set default options on activation.
	$default_options = array(
		'enabled'              => true,
		'link_in_headings'     => false,
		'post_types'           => array( 'post' ),
		'keyword_meta_fields'  => 'keywords',
		'max_related_posts'    => 10,
	);

	if ( ! get_option( 'auto_tag_interlink_settings' ) ) {
		add_option( 'auto_tag_interlink_settings', $default_options );
	}

	// Clear cache on activation.
	Cache::clear_all();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

/**
 * Deactivation hook.
 */
function deactivate() {
	// Clear all plugin caches.
	Cache::clear_all();
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );
