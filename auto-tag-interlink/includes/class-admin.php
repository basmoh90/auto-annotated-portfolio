<?php
/**
 * Admin Settings Page Class
 *
 * @package AutoTagInterlink
 */

namespace AutoTagInterlink;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the admin settings page and UI.
 */
class Admin {

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
	 * Constructor.
	 *
	 * @param Settings $settings Settings instance.
	 * @param Cache    $cache    Cache instance.
	 */
	public function __construct( Settings $settings, Cache $cache ) {
		$this->settings = $settings;
		$this->cache    = $cache;

		$this->setup_hooks();
	}

	/**
	 * Setup admin hooks.
	 */
	private function setup_hooks() {
		// Add settings page.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );

		// Register settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Enqueue admin assets.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		// Handle AJAX cache clear.
		add_action( 'wp_ajax_ati_clear_cache', array( $this, 'ajax_clear_cache' ) );

		// Add settings link on plugins page.
		add_filter( 'plugin_action_links_' . AUTO_TAG_INTERLINK_PLUGIN_BASENAME, array( $this, 'add_plugin_action_links' ) );
	}

	/**
	 * Add settings page to WordPress admin menu.
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'Auto Tag Interlink Settings', 'auto-tag-interlink' ),
			__( 'Auto Tag Interlink', 'auto-tag-interlink' ),
			'manage_options',
			'auto-tag-interlink',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting(
			'auto_tag_interlink_settings_group',
			Settings::OPTION_NAME,
			array(
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);

		// General settings section.
		add_settings_section(
			'ati_general_section',
			__( 'General Settings', 'auto-tag-interlink' ),
			array( $this, 'render_general_section' ),
			'auto-tag-interlink'
		);

		// Enabled field.
		add_settings_field(
			'enabled',
			__( 'Enable Auto-Linking', 'auto-tag-interlink' ),
			array( $this, 'render_checkbox_field' ),
			'auto-tag-interlink',
			'ati_general_section',
			array(
				'id'          => 'enabled',
				'label'       => __( 'Enable automatic internal linking', 'auto-tag-interlink' ),
				'description' => __( 'Turn this off to temporarily disable the plugin without deactivating it.', 'auto-tag-interlink' ),
			)
		);

		// Link in headings field.
		add_settings_field(
			'link_in_headings',
			__( 'Link in Headings', 'auto-tag-interlink' ),
			array( $this, 'render_checkbox_field' ),
			'auto-tag-interlink',
			'ati_general_section',
			array(
				'id'          => 'link_in_headings',
				'label'       => __( 'Allow links inside H1-H6 heading tags', 'auto-tag-interlink' ),
				'description' => __( 'By default, links are not added to headings. Enable this to include headings.', 'auto-tag-interlink' ),
			)
		);

		// Post types field.
		add_settings_field(
			'post_types',
			__( 'Post Types', 'auto-tag-interlink' ),
			array( $this, 'render_post_types_field' ),
			'auto-tag-interlink',
			'ati_general_section',
			array(
				'id'          => 'post_types',
				'description' => __( 'Select which post types should have auto-linking enabled.', 'auto-tag-interlink' ),
			)
		);

		// Advanced settings section.
		add_settings_section(
			'ati_advanced_section',
			__( 'Advanced Settings', 'auto-tag-interlink' ),
			array( $this, 'render_advanced_section' ),
			'auto-tag-interlink'
		);

		// Keyword meta fields.
		add_settings_field(
			'keyword_meta_fields',
			__( 'Keyword Meta Fields', 'auto-tag-interlink' ),
			array( $this, 'render_text_field' ),
			'auto-tag-interlink',
			'ati_advanced_section',
			array(
				'id'          => 'keyword_meta_fields',
				'placeholder' => 'keywords',
				'description' => __( 'Comma-separated list of custom field names to use as keywords (e.g., keywords, seo_keywords).', 'auto-tag-interlink' ),
			)
		);

		// Max related posts.
		add_settings_field(
			'max_related_posts',
			__( 'Max Related Posts', 'auto-tag-interlink' ),
			array( $this, 'render_number_field' ),
			'auto-tag-interlink',
			'ati_advanced_section',
			array(
				'id'          => 'max_related_posts',
				'min'         => 1,
				'max'         => 100,
				'description' => __( 'Maximum number of related posts to find per tag (1-100).', 'auto-tag-interlink' ),
			)
		);

		// Cache section.
		add_settings_section(
			'ati_cache_section',
			__( 'Cache Management', 'auto-tag-interlink' ),
			array( $this, 'render_cache_section' ),
			'auto-tag-interlink'
		);
	}

	/**
	 * Sanitize settings before saving.
	 *
	 * @param array $input Input settings.
	 * @return array Sanitized settings.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = array();

		// Sanitize enabled checkbox.
		$sanitized['enabled'] = isset( $input['enabled'] ) ? (bool) $input['enabled'] : false;

		// Sanitize link in headings checkbox.
		$sanitized['link_in_headings'] = isset( $input['link_in_headings'] ) ? (bool) $input['link_in_headings'] : false;

		// Sanitize post types.
		$sanitized['post_types'] = isset( $input['post_types'] ) && is_array( $input['post_types'] )
			? array_map( 'sanitize_text_field', $input['post_types'] )
			: array( 'post' );

		// Sanitize keyword meta fields.
		$sanitized['keyword_meta_fields'] = isset( $input['keyword_meta_fields'] )
			? sanitize_text_field( $input['keyword_meta_fields'] )
			: 'keywords';

		// Sanitize max related posts.
		$sanitized['max_related_posts'] = isset( $input['max_related_posts'] )
			? absint( $input['max_related_posts'] )
			: 10;

		// Ensure max_related_posts is within range.
		if ( $sanitized['max_related_posts'] < 1 ) {
			$sanitized['max_related_posts'] = 1;
		} elseif ( $sanitized['max_related_posts'] > 100 ) {
			$sanitized['max_related_posts'] = 100;
		}

		return $sanitized;
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'auto-tag-interlink' ) );
		}
		?>
		<div class="wrap ati-settings-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<div class="ati-settings-container">
				<div class="ati-settings-main">
					<form method="post" action="options.php">
						<?php
						settings_fields( 'auto_tag_interlink_settings_group' );
						do_settings_sections( 'auto-tag-interlink' );
						submit_button();
						?>
					</form>
				</div>

				<div class="ati-settings-sidebar">
					<div class="ati-sidebar-box">
						<h3><?php esc_html_e( 'How It Works', 'auto-tag-interlink' ); ?></h3>
						<ol>
							<li><?php esc_html_e( 'When a post is published or updated, the plugin detects its tags and keywords.', 'auto-tag-interlink' ); ?></li>
							<li><?php esc_html_e( 'It searches for other posts with matching tags/keywords.', 'auto-tag-interlink' ); ?></li>
							<li><?php esc_html_e( 'Internal links are automatically inserted into the post content.', 'auto-tag-interlink' ); ?></li>
							<li><?php esc_html_e( 'Links are distributed among related posts using round-robin.', 'auto-tag-interlink' ); ?></li>
						</ol>
					</div>

					<div class="ati-sidebar-box">
						<h3><?php esc_html_e( 'Plugin Info', 'auto-tag-interlink' ); ?></h3>
						<p><strong><?php esc_html_e( 'Version:', 'auto-tag-interlink' ); ?></strong> <?php echo esc_html( AUTO_TAG_INTERLINK_VERSION ); ?></p>
						<p><strong><?php esc_html_e( 'Status:', 'auto-tag-interlink' ); ?></strong>
							<?php if ( $this->settings->is_enabled() ) : ?>
								<span class="ati-status-active"><?php esc_html_e( 'Active', 'auto-tag-interlink' ); ?></span>
							<?php else : ?>
								<span class="ati-status-inactive"><?php esc_html_e( 'Inactive', 'auto-tag-interlink' ); ?></span>
							<?php endif; ?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render general section description.
	 */
	public function render_general_section() {
		echo '<p>' . esc_html__( 'Configure the basic settings for auto-linking functionality.', 'auto-tag-interlink' ) . '</p>';
	}

	/**
	 * Render advanced section description.
	 */
	public function render_advanced_section() {
		echo '<p>' . esc_html__( 'Advanced configuration options for fine-tuning the plugin behavior.', 'auto-tag-interlink' ) . '</p>';
	}

	/**
	 * Render cache section with clear button.
	 */
	public function render_cache_section() {
		echo '<p>' . esc_html__( 'The plugin uses caching to improve performance. Clear the cache if you notice outdated links.', 'auto-tag-interlink' ) . '</p>';
		echo '<button type="button" id="ati-clear-cache" class="button button-secondary">' . esc_html__( 'Clear Cache', 'auto-tag-interlink' ) . '</button>';
		echo '<span id="ati-cache-message" style="margin-left: 10px;"></span>';
	}

	/**
	 * Render checkbox field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_checkbox_field( $args ) {
		$settings = $this->settings->get_all();
		$value    = isset( $settings[ $args['id'] ] ) ? $settings[ $args['id'] ] : false;
		?>
		<label>
			<input type="checkbox"
				   name="<?php echo esc_attr( Settings::OPTION_NAME . '[' . $args['id'] . ']' ); ?>"
				   value="1"
				   <?php checked( $value, true ); ?> />
			<?php echo esc_html( $args['label'] ); ?>
		</label>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render text field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_text_field( $args ) {
		$settings    = $this->settings->get_all();
		$value       = isset( $settings[ $args['id'] ] ) ? $settings[ $args['id'] ] : '';
		$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
		?>
		<input type="text"
			   name="<?php echo esc_attr( Settings::OPTION_NAME . '[' . $args['id'] . ']' ); ?>"
			   value="<?php echo esc_attr( $value ); ?>"
			   placeholder="<?php echo esc_attr( $placeholder ); ?>"
			   class="regular-text" />
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render number field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_number_field( $args ) {
		$settings = $this->settings->get_all();
		$value    = isset( $settings[ $args['id'] ] ) ? $settings[ $args['id'] ] : '';
		$min      = isset( $args['min'] ) ? $args['min'] : '';
		$max      = isset( $args['max'] ) ? $args['max'] : '';
		?>
		<input type="number"
			   name="<?php echo esc_attr( Settings::OPTION_NAME . '[' . $args['id'] . ']' ); ?>"
			   value="<?php echo esc_attr( $value ); ?>"
			   min="<?php echo esc_attr( $min ); ?>"
			   max="<?php echo esc_attr( $max ); ?>"
			   class="small-text" />
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render post types field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_post_types_field( $args ) {
		$settings       = $this->settings->get_all();
		$selected_types = isset( $settings['post_types'] ) ? $settings['post_types'] : array( 'post' );

		// Get all public post types.
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		// Exclude certain post types.
		$exclude = array( 'attachment', 'revision', 'nav_menu_item' );

		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type->name, $exclude, true ) ) {
				continue;
			}
			?>
			<label style="display: block; margin-bottom: 5px;">
				<input type="checkbox"
					   name="<?php echo esc_attr( Settings::OPTION_NAME . '[post_types][]' ); ?>"
					   value="<?php echo esc_attr( $post_type->name ); ?>"
					   <?php checked( in_array( $post_type->name, $selected_types, true ) ); ?> />
				<?php echo esc_html( $post_type->label ); ?>
			</label>
			<?php
		}

		if ( ! empty( $args['description'] ) ) {
			echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
		}
	}

	/**
	 * AJAX handler for clearing cache.
	 */
	public function ajax_clear_cache() {
		// Verify nonce.
		check_ajax_referer( 'ati_clear_cache_nonce', 'nonce' );

		// Check permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'auto-tag-interlink' ) ) );
		}

		// Clear cache.
		Cache::clear_all();

		wp_send_json_success( array( 'message' => __( 'Cache cleared successfully!', 'auto-tag-interlink' ) ) );
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on our settings page.
		if ( 'settings_page_auto-tag-interlink' !== $hook ) {
			return;
		}

		// Enqueue CSS.
		wp_enqueue_style(
			'ati-admin-css',
			AUTO_TAG_INTERLINK_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			AUTO_TAG_INTERLINK_VERSION
		);

		// Enqueue JS.
		wp_enqueue_script(
			'ati-admin-js',
			AUTO_TAG_INTERLINK_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			AUTO_TAG_INTERLINK_VERSION,
			true
		);

		// Localize script.
		wp_localize_script(
			'ati-admin-js',
			'atiAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ati_clear_cache_nonce' ),
			)
		);
	}

	/**
	 * Add settings link to plugin action links.
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function add_plugin_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=auto-tag-interlink' ),
			__( 'Settings', 'auto-tag-interlink' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}
}
