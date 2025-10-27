<?php
/**
 * Cache Management Class
 *
 * @package AutoTagInterlink
 */

namespace AutoTagInterlink;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles caching using WordPress transients.
 */
class Cache {

	/**
	 * Cache key prefix.
	 *
	 * @var string
	 */
	const PREFIX = 'ati_';

	/**
	 * Default cache expiration (24 hours).
	 *
	 * @var int
	 */
	const EXPIRATION = 86400;

	/**
	 * Get cached data.
	 *
	 * @param string $key Cache key.
	 * @return mixed|false False if not found or expired.
	 */
	public function get( $key ) {
		return get_transient( self::PREFIX . $key );
	}

	/**
	 * Set cached data.
	 *
	 * @param string $key        Cache key.
	 * @param mixed  $data       Data to cache.
	 * @param int    $expiration Expiration time in seconds.
	 * @return bool
	 */
	public function set( $key, $data, $expiration = self::EXPIRATION ) {
		return set_transient( self::PREFIX . $key, $data, $expiration );
	}

	/**
	 * Delete cached data.
	 *
	 * @param string $key Cache key.
	 * @return bool
	 */
	public function delete( $key ) {
		return delete_transient( self::PREFIX . $key );
	}

	/**
	 * Clear cache for a specific post.
	 *
	 * @param int $post_id Post ID.
	 */
	public function clear_post_cache( $post_id ) {
		$this->delete( 'related_posts_' . $post_id );
		$this->delete( 'keywords_' . $post_id );
	}

	/**
	 * Clear all plugin caches.
	 */
	public static function clear_all() {
		global $wpdb;

		// Delete all transients with our prefix.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				$wpdb->esc_like( '_transient_' . self::PREFIX ) . '%',
				$wpdb->esc_like( '_transient_timeout_' . self::PREFIX ) . '%'
			)
		);
	}

	/**
	 * Get related posts for a given post ID (with caching).
	 *
	 * @param int   $post_id  Post ID.
	 * @param array $tag_ids  Tag IDs to search for.
	 * @param array $keywords Keywords to search for.
	 * @param int   $limit    Maximum number of posts to return.
	 * @return array
	 */
	public function get_related_posts( $post_id, $tag_ids, $keywords, $limit ) {
		$cache_key = 'related_posts_' . $post_id . '_' . md5( serialize( array( $tag_ids, $keywords, $limit ) ) );
		$cached    = $this->get( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		// Cache miss - will be set by the caller.
		return false;
	}

	/**
	 * Cache related posts.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $tag_ids Tag IDs.
	 * @param array $keywords Keywords.
	 * @param int   $limit   Limit.
	 * @param array $posts   Posts to cache.
	 */
	public function set_related_posts( $post_id, $tag_ids, $keywords, $limit, $posts ) {
		$cache_key = 'related_posts_' . $post_id . '_' . md5( serialize( array( $tag_ids, $keywords, $limit ) ) );
		$this->set( $cache_key, $posts );
	}
}
