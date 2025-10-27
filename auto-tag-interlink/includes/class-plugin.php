<?php
/**
 * Main Plugin Class
 *
 * @package AutoTagInterlink
 */

namespace AutoTagInterlink;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class that bootstraps the functionality.
 */
class Plugin {

	/**
	 * Single instance of the class.
	 *
	 * @var Plugin
	 */
	private static $instance = null;

	/**
	 * Settings instance.
	 *
	 * @var Settings
	 */
	public $settings;

	/**
	 * Admin instance.
	 *
	 * @var Admin
	 */
	public $admin;

	/**
	 * Linker instance.
	 *
	 * @var Linker
	 */
	public $linker;

	/**
	 * Cache instance.
	 *
	 * @var Cache
	 */
	public $cache;

	/**
	 * Get singleton instance.
	 *
	 * @return Plugin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->init_components();
		$this->setup_hooks();
	}

	/**
	 * Initialize plugin components.
	 */
	private function init_components() {
		$this->settings = new Settings();
		$this->cache    = new Cache();
		$this->linker   = new Linker( $this->settings, $this->cache );

		// Initialize admin only in admin area.
		if ( is_admin() ) {
			$this->admin = new Admin( $this->settings, $this->cache );
		}
	}

	/**
	 * Setup WordPress hooks.
	 */
	private function setup_hooks() {
		// Hook into post save to apply auto-linking.
		add_action( 'transition_post_status', array( $this, 'on_post_status_change' ), 10, 3 );

		// Clear cache when tags are updated.
		add_action( 'set_object_terms', array( $this, 'on_terms_change' ), 10, 6 );

		// Load text domain for translations.
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Handle post status changes.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param \WP_Post $post       Post object.
	 */
	public function on_post_status_change( $new_status, $old_status, $post ) {
		// Only process if plugin is enabled.
		if ( ! $this->settings->is_enabled() ) {
			return;
		}

		// Only process allowed post types.
		if ( ! in_array( $post->post_type, $this->settings->get_post_types(), true ) ) {
			return;
		}

		// Process on publish or update.
		if ( 'publish' === $new_status ) {
			$this->linker->process_post( $post->ID );
		}
	}

	/**
	 * Handle term changes (tag updates).
	 *
	 * @param int    $object_id  Object ID.
	 * @param array  $terms      An array of object terms.
	 * @param array  $tt_ids     An array of term taxonomy IDs.
	 * @param string $taxonomy   Taxonomy slug.
	 * @param bool   $append     Whether to append new terms to the old terms.
	 * @param array  $old_tt_ids Old array of term taxonomy IDs.
	 */
	public function on_terms_change( $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ) {
		// Only clear cache for post_tag taxonomy.
		if ( 'post_tag' === $taxonomy ) {
			$this->cache->clear_post_cache( $object_id );
		}
	}

	/**
	 * Load plugin text domain for translations.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'auto-tag-interlink',
			false,
			dirname( AUTO_TAG_INTERLINK_PLUGIN_BASENAME ) . '/languages'
		);
	}
}
