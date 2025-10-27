<?php
/**
 * Settings Management Class
 *
 * @package AutoTagInterlink
 */

namespace AutoTagInterlink;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles plugin settings storage and retrieval.
 */
class Settings {

	/**
	 * Option name in wp_options table.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'auto_tag_interlink_settings';

	/**
	 * Settings array.
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->load_settings();
	}

	/**
	 * Load settings from database.
	 */
	private function load_settings() {
		$defaults = $this->get_defaults();
		$saved    = get_option( self::OPTION_NAME, array() );

		// Merge with defaults to ensure all keys exist.
		$this->settings = wp_parse_args( $saved, $defaults );
	}

	/**
	 * Get default settings.
	 *
	 * @return array
	 */
	private function get_defaults() {
		return array(
			'enabled'              => true,
			'link_in_headings'     => false,
			'post_types'           => array( 'post' ),
			'keyword_meta_fields'  => 'keywords',
			'max_related_posts'    => 10,
		);
	}

	/**
	 * Get all settings.
	 *
	 * @return array
	 */
	public function get_all() {
		return $this->settings;
	}

	/**
	 * Get a specific setting.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Default value if not found.
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default;
	}

	/**
	 * Update settings.
	 *
	 * @param array $new_settings New settings array.
	 * @return bool
	 */
	public function update( $new_settings ) {
		$this->settings = wp_parse_args( $new_settings, $this->get_defaults() );
		$updated        = update_option( self::OPTION_NAME, $this->settings );

		// Clear cache when settings are updated.
		if ( $updated ) {
			Cache::clear_all();
		}

		return $updated;
	}

	/**
	 * Check if auto-linking is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return (bool) $this->get( 'enabled', true );
	}

	/**
	 * Check if linking in headings is enabled.
	 *
	 * @return bool
	 */
	public function link_in_headings() {
		return (bool) $this->get( 'link_in_headings', false );
	}

	/**
	 * Get enabled post types.
	 *
	 * @return array
	 */
	public function get_post_types() {
		$post_types = $this->get( 'post_types', array( 'post' ) );
		return is_array( $post_types ) ? $post_types : array( 'post' );
	}

	/**
	 * Get keyword meta field names.
	 *
	 * @return array
	 */
	public function get_keyword_meta_fields() {
		$fields = $this->get( 'keyword_meta_fields', 'keywords' );

		// Convert comma-separated string to array.
		if ( is_string( $fields ) ) {
			$fields = array_map( 'trim', explode( ',', $fields ) );
		}

		return is_array( $fields ) ? array_filter( $fields ) : array( 'keywords' );
	}

	/**
	 * Get max related posts per tag.
	 *
	 * @return int
	 */
	public function get_max_related_posts() {
		return absint( $this->get( 'max_related_posts', 10 ) );
	}
}
