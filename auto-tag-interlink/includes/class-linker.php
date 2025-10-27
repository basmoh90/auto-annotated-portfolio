<?php
/**
 * Linker Class - Core Auto-Linking Logic
 *
 * @package AutoTagInterlink
 */

namespace AutoTagInterlink;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the core auto-linking functionality.
 */
class Linker {

	/**
	 * Settings instance.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Cache instance.
	 *
	 * @var Cache
	 */
	private $cache;

	/**
	 * Round-robin index for distributing links.
	 *
	 * @var array
	 */
	private $link_index = array();

	/**
	 * Constructor.
	 *
	 * @param Settings $settings Settings instance.
	 * @param Cache    $cache    Cache instance.
	 */
	public function __construct( Settings $settings, Cache $cache ) {
		$this->settings = $settings;
		$this->cache    = $cache;
	}

	/**
	 * Process a post and add internal links.
	 *
	 * @param int $post_id Post ID to process.
	 * @return bool True if content was updated, false otherwise.
	 */
	public function process_post( $post_id ) {
		// Get the post.
		$post = get_post( $post_id );

		if ( ! $post || empty( $post->post_content ) ) {
			return false;
		}

		// Get tags and keywords for this post.
		$tags     = $this->get_post_tags( $post_id );
		$keywords = $this->get_post_keywords( $post_id );

		// If no tags or keywords, nothing to link.
		if ( empty( $tags ) && empty( $keywords ) ) {
			return false;
		}

		// Get related posts.
		$related_posts = $this->get_related_posts( $post_id, $tags, $keywords );

		if ( empty( $related_posts ) ) {
			return false;
		}

		// Process the content and add links.
		$new_content = $this->add_links_to_content( $post->post_content, $related_posts, $post_id );

		// Only update if content changed.
		if ( $new_content !== $post->post_content ) {
			// Remove this hook temporarily to prevent infinite loop.
			remove_action( 'transition_post_status', array( Plugin::get_instance(), 'on_post_status_change' ), 10 );

			// Update the post.
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_content' => $new_content,
				)
			);

			// Re-add the hook.
			add_action( 'transition_post_status', array( Plugin::get_instance(), 'on_post_status_change' ), 10, 3 );

			return true;
		}

		return false;
	}

	/**
	 * Get tags for a post.
	 *
	 * @param int $post_id Post ID.
	 * @return array Array of tag names.
	 */
	private function get_post_tags( $post_id ) {
		$tags = wp_get_post_tags( $post_id, array( 'fields' => 'names' ) );
		return is_array( $tags ) ? $tags : array();
	}

	/**
	 * Get keywords from custom fields.
	 *
	 * @param int $post_id Post ID.
	 * @return array Array of keywords.
	 */
	private function get_post_keywords( $post_id ) {
		$keywords       = array();
		$meta_fields    = $this->settings->get_keyword_meta_fields();

		foreach ( $meta_fields as $field ) {
			$value = get_post_meta( $post_id, $field, true );

			if ( ! empty( $value ) ) {
				if ( is_string( $value ) ) {
					// Split by comma if it's a comma-separated string.
					$words = array_map( 'trim', explode( ',', $value ) );
					$keywords = array_merge( $keywords, $words );
				} elseif ( is_array( $value ) ) {
					$keywords = array_merge( $keywords, $value );
				}
			}
		}

		return array_filter( array_unique( $keywords ) );
	}

	/**
	 * Get related posts based on tags and keywords.
	 *
	 * @param int   $post_id  Current post ID.
	 * @param array $tags     Tags to search for.
	 * @param array $keywords Keywords to search for.
	 * @return array Array of related posts with their data.
	 */
	private function get_related_posts( $post_id, $tags, $keywords ) {
		$all_terms = array_merge( $tags, $keywords );

		if ( empty( $all_terms ) ) {
			return array();
		}

		// Try to get from cache.
		$tag_ids = array();
		if ( ! empty( $tags ) ) {
			$tag_objects = get_terms(
				array(
					'taxonomy' => 'post_tag',
					'name'     => $tags,
					'fields'   => 'ids',
				)
			);
			$tag_ids = is_array( $tag_objects ) ? $tag_objects : array();
		}

		$cached = $this->cache->get_related_posts( $post_id, $tag_ids, $keywords, $this->settings->get_max_related_posts() );
		if ( false !== $cached ) {
			return $cached;
		}

		// Build the query.
		$args = array(
			'post_type'      => $this->settings->get_post_types(),
			'post_status'    => 'publish',
			'posts_per_page' => $this->settings->get_max_related_posts(),
			'post__not_in'   => array( $post_id ),
			'fields'         => 'ids',
			'no_found_rows'  => true,
		);

		// Add tax query for tags if available.
		if ( ! empty( $tag_ids ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tag_ids,
					'operator' => 'IN',
				),
			);
		}

		// Execute query.
		$query = new \WP_Query( $args );
		$post_ids = $query->posts;

		// Build result array with post data.
		$related_posts = array();

		foreach ( $post_ids as $related_post_id ) {
			$related_post = get_post( $related_post_id );

			if ( ! $related_post ) {
				continue;
			}

			// Get the tags and keywords for this related post.
			$related_tags     = $this->get_post_tags( $related_post_id );
			$related_keywords = $this->get_post_keywords( $related_post_id );
			$related_terms    = array_merge( $related_tags, $related_keywords );

			// Find matching terms.
			$matching_terms = array_intersect( $all_terms, $related_terms );

			if ( ! empty( $matching_terms ) ) {
				$related_posts[] = array(
					'id'    => $related_post_id,
					'title' => $related_post->post_title,
					'url'   => get_permalink( $related_post_id ),
					'terms' => array_values( $matching_terms ),
				);
			}
		}

		// Cache the results.
		$this->cache->set_related_posts( $post_id, $tag_ids, $keywords, $this->settings->get_max_related_posts(), $related_posts );

		return $related_posts;
	}

	/**
	 * Add links to content using DOMDocument for safe HTML parsing.
	 *
	 * @param string $content       Post content.
	 * @param array  $related_posts Array of related posts.
	 * @param int    $current_post_id Current post ID.
	 * @return string Modified content with links.
	 */
	private function add_links_to_content( $content, $related_posts, $current_post_id ) {
		// Build a mapping of terms to posts.
		$term_to_posts = array();

		foreach ( $related_posts as $post_data ) {
			foreach ( $post_data['terms'] as $term ) {
				if ( ! isset( $term_to_posts[ $term ] ) ) {
					$term_to_posts[ $term ] = array();
				}
				$term_to_posts[ $term ][] = $post_data;
			}
		}

		if ( empty( $term_to_posts ) ) {
			return $content;
		}

		// Use DOMDocument for safe HTML manipulation.
		libxml_use_internal_errors( true );

		$dom = new \DOMDocument( '1.0', 'UTF-8' );

		// Add UTF-8 encoding wrapper and load HTML.
		$dom->loadHTML( '<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

		// Process text nodes.
		$this->process_dom_node( $dom->documentElement, $term_to_posts );

		// Get the modified HTML.
		$new_content = $dom->saveHTML( $dom->documentElement );

		// Remove the XML encoding declaration we added.
		$new_content = preg_replace( '/^<!DOCTYPE.+?>/', '', $new_content );
		$new_content = str_replace( array( '<html>', '</html>', '<body>', '</body>' ), '', $new_content );

		libxml_clear_errors();

		return $new_content;
	}

	/**
	 * Recursively process DOM nodes to add links.
	 *
	 * @param \DOMNode $node         DOM node to process.
	 * @param array    $term_to_posts Mapping of terms to posts.
	 */
	private function process_dom_node( $node, $term_to_posts ) {
		// Skip certain node types.
		if ( ! $node ) {
			return;
		}

		// Skip nodes we shouldn't link inside.
		if ( $this->should_skip_node( $node ) ) {
			return;
		}

		// If it's a text node, process it.
		if ( $node->nodeType === XML_TEXT_NODE ) {
			$this->add_links_to_text_node( $node, $term_to_posts );
			return;
		}

		// Process child nodes.
		if ( $node->hasChildNodes() ) {
			// Convert to array to avoid issues with live NodeList.
			$children = array();
			foreach ( $node->childNodes as $child ) {
				$children[] = $child;
			}

			foreach ( $children as $child ) {
				$this->process_dom_node( $child, $term_to_posts );
			}
		}
	}

	/**
	 * Check if a node should be skipped for linking.
	 *
	 * @param \DOMNode $node DOM node.
	 * @return bool True if should skip, false otherwise.
	 */
	private function should_skip_node( $node ) {
		// Skip if not an element or text node.
		if ( ! in_array( $node->nodeType, array( XML_ELEMENT_NODE, XML_TEXT_NODE ), true ) ) {
			return true;
		}

		// For text nodes, check parent.
		if ( $node->nodeType === XML_TEXT_NODE ) {
			$parent = $node->parentNode;
			if ( $parent && $parent->nodeType === XML_ELEMENT_NODE ) {
				return $this->should_skip_element( $parent );
			}
		}

		// For element nodes, check directly.
		if ( $node->nodeType === XML_ELEMENT_NODE ) {
			return $this->should_skip_element( $node );
		}

		return false;
	}

	/**
	 * Check if an element should be skipped.
	 *
	 * @param \DOMElement $element DOM element.
	 * @return bool True if should skip, false otherwise.
	 */
	private function should_skip_element( $element ) {
		$skip_tags = array( 'a', 'code', 'pre', 'script', 'style' );

		// Add heading tags if setting disabled.
		if ( ! $this->settings->link_in_headings() ) {
			$skip_tags = array_merge( $skip_tags, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) );
		}

		$tag_name = strtolower( $element->nodeName );

		// Check if current element should be skipped.
		if ( in_array( $tag_name, $skip_tags, true ) ) {
			return true;
		}

		// Check if any parent should be skipped.
		$parent = $element->parentNode;
		while ( $parent && $parent->nodeType === XML_ELEMENT_NODE ) {
			if ( in_array( strtolower( $parent->nodeName ), $skip_tags, true ) ) {
				return true;
			}
			$parent = $parent->parentNode;
		}

		return false;
	}

	/**
	 * Add links to a text node.
	 *
	 * @param \DOMText $text_node    Text node.
	 * @param array    $term_to_posts Mapping of terms to posts.
	 */
	private function add_links_to_text_node( $text_node, $term_to_posts ) {
		$text = $text_node->nodeValue;

		if ( empty( $text ) || strlen( trim( $text ) ) === 0 ) {
			return;
		}

		// Sort terms by length (longest first) to avoid partial matches.
		$terms = array_keys( $term_to_posts );
		usort(
			$terms,
			function ( $a, $b ) {
				return strlen( $b ) - strlen( $a );
			}
		);

		$modified = false;

		// Replace each term with a link (case-insensitive).
		foreach ( $terms as $term ) {
			// Build regex pattern for whole word matching.
			$pattern = '/\b(' . preg_quote( $term, '/' ) . ')\b/iu';

			// Check if term exists in text.
			if ( preg_match( $pattern, $text ) ) {
				// Get the post to link to (round-robin).
				$post_data = $this->get_next_post_for_term( $term, $term_to_posts );

				if ( ! $post_data ) {
					continue;
				}

				// Create link HTML.
				$link = sprintf(
					'<a href="%s" title="%s">$1</a>',
					esc_url( $post_data['url'] ),
					esc_attr( $post_data['title'] )
				);

				// Replace all occurrences (unlimited linking as per requirements).
				$new_text = preg_replace( $pattern, $link, $text );

				if ( $new_text !== $text ) {
					$text     = $new_text;
					$modified = true;
				}
			}
		}

		// If text was modified, replace the text node with a document fragment.
		if ( $modified ) {
			$fragment = $text_node->ownerDocument->createDocumentFragment();
			$fragment->appendXML( $text );

			$text_node->parentNode->replaceChild( $fragment, $text_node );
		}
	}

	/**
	 * Get the next post for a term (round-robin distribution).
	 *
	 * @param string $term          Term to get post for.
	 * @param array  $term_to_posts Mapping of terms to posts.
	 * @return array|null Post data or null.
	 */
	private function get_next_post_for_term( $term, $term_to_posts ) {
		if ( ! isset( $term_to_posts[ $term ] ) || empty( $term_to_posts[ $term ] ) ) {
			return null;
		}

		$posts = $term_to_posts[ $term ];

		// Initialize index for this term if not set.
		if ( ! isset( $this->link_index[ $term ] ) ) {
			$this->link_index[ $term ] = 0;
		}

		// Get the current post.
		$post = $posts[ $this->link_index[ $term ] ];

		// Increment index (with wrap-around).
		$this->link_index[ $term ] = ( $this->link_index[ $term ] + 1 ) % count( $posts );

		return $post;
	}
}
