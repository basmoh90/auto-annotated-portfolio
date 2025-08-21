<?php
/**
 * Theme Functions
 *
 * @package ar-local-services
 */

if ( ! defined( 'AR_LS_VERSION' ) ) {
    define( 'AR_LS_VERSION', '1.0.0' );
}

/**
 * Theme setup.
 */
function ar_ls_theme_setup() {
    load_theme_textdomain( 'ar-local-services', get_template_directory() . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo', array( 'height' => 60, 'width' => 200, 'flex-width' => true ) );
    add_theme_support( 'align-wide' );

    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu', 'ar-local-services' ),
        )
    );
}
add_action( 'after_setup_theme', 'ar_ls_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
function ar_ls_enqueue_assets() {
    // Google Fonts: Tajawal (lightweight Arabic font)
    wp_enqueue_style( 'ar-ls-google-fonts', 'https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap', array(), null );

    // Main stylesheet
    wp_enqueue_style( 'ar-ls-style', get_stylesheet_uri(), array( 'ar-ls-google-fonts' ), AR_LS_VERSION );

    // RTL stylesheet will be loaded automatically by WordPress when is_rtl() === true.

    // Main JS (empty for now but extendable)
    wp_enqueue_script( 'ar-ls-main', get_template_directory_uri() . '/assets/js/main.js', array(), AR_LS_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'ar_ls_enqueue_assets' );

/**
 * Add async & defer to specific scripts for better performance.
 *
 * @param string $tag    HTML tag for the enqueued script.
 * @param string $handle Script handle.
 *
 * @return string
 */
function ar_ls_modify_script_tag( $tag, $handle ) {
    if ( in_array( $handle, array( 'ar-ls-main' ), true ) ) {
        return str_replace( 'src', 'async defer src', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'ar_ls_modify_script_tag', 10, 2 );

/**
 * Register Custom Post Type: Service.
 */
function ar_ls_register_service_cpt() {
    $labels = array(
        'name'               => _x( 'الخدمات', 'post type general name', 'ar-local-services' ),
        'singular_name'      => _x( 'خدمة', 'post type singular name', 'ar-local-services' ),
        'menu_name'          => _x( 'الخدمات', 'admin menu', 'ar-local-services' ),
        'name_admin_bar'     => _x( 'خدمة', 'add new on admin bar', 'ar-local-services' ),
        'add_new'            => _x( 'أضف خدمة جديدة', 'service', 'ar-local-services' ),
        'add_new_item'       => __( 'أضف خدمة جديدة', 'ar-local-services' ),
        'new_item'           => __( 'خدمة جديدة', 'ar-local-services' ),
        'edit_item'          => __( 'تعديل الخدمة', 'ar-local-services' ),
        'view_item'          => __( 'عرض الخدمة', 'ar-local-services' ),
        'all_items'          => __( 'كل الخدمات', 'ar-local-services' ),
        'search_items'       => __( 'بحث في الخدمات', 'ar-local-services' ),
        'parent_item_colon'  => __( 'خدمة أصلية:', 'ar-local-services' ),
        'not_found'          => __( 'لا توجد خدمات.', 'ar-local-services' ),
        'not_found_in_trash' => __( 'لا توجد خدمات في سلة المهملات.', 'ar-local-services' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'خدمة' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'service', $args );
}
add_action( 'init', 'ar_ls_register_service_cpt' );

/**
 * JSON-LD: Output LocalBusiness schema in the head.
 */
function ar_ls_output_localbusiness_schema() {
    if ( ! is_front_page() && ! is_home() ) {
        return;
    }

    $business_data = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'LocalBusiness',
        'name'          => get_bloginfo( 'name' ),
        'image'         => get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] : '',
        'url'           => home_url(),
        'telephone'     => get_option( 'ar_ls_phone', '+201234567890' ),
        'address'       => array(
            '@type'           => 'PostalAddress',
            'addressLocality' => get_option( 'ar_ls_city', 'القاهرة' ),
            'addressRegion'   => get_option( 'ar_ls_region', 'القاهرة' ),
            'postalCode'      => get_option( 'ar_ls_postal_code', '' ),
            'streetAddress'   => get_option( 'ar_ls_street', '' ),
        ),
        'geo'           => array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => get_option( 'ar_ls_latitude', '30.0444' ),
            'longitude' => get_option( 'ar_ls_longitude', '31.2357' ),
        ),
        'openingHours'  => get_option( 'ar_ls_opening_hours', 'Mo,Tu,We,Th,Fr,Sa,Su 08:00-22:00' ),
        'areaServed'    => get_option( 'ar_ls_area_served', 'القاهرة، الجيزة' ),
        'sameAs'        => array(
            get_option( 'ar_ls_facebook', '' ),
            get_option( 'ar_ls_instagram', '' ),
            get_option( 'ar_ls_twitter', '' ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $business_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}
add_action( 'wp_head', 'ar_ls_output_localbusiness_schema', 5 );

/**
 * JSON-LD: Output Service schema on single service pages.
 */
function ar_ls_output_service_schema() {
    if ( ! is_singular( 'service' ) ) {
        return;
    }

    global $post;
    $service_data = array(
        '@context'     => 'https://schema.org',
        '@type'        => 'Service',
        'serviceType'  => get_the_title(),
        'description'  => wp_strip_all_tags( get_the_excerpt() ),
        'provider'     => array(
            '@type' => 'LocalBusiness',
            'name'  => get_bloginfo( 'name' ),
        ),
        'areaServed'   => get_option( 'ar_ls_area_served', 'القاهرة، الجيزة' ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $service_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}
add_action( 'wp_head', 'ar_ls_output_service_schema', 10 );

/**
 * Breadcrumbs JSON-LD.
 */
function ar_ls_output_breadcrumb_schema() {
    if ( is_front_page() ) {
        return;
    }

    $position = 1;
    $item_list = array();
    $item_list[] = array(
        '@type'    => 'ListItem',
        'position' => $position,
        'name'     => __( 'الصفحة الرئيسية', 'ar-local-services' ),
        'item'     => home_url(),
    );

    if ( is_singular() ) {
        $position++;
        $item_list[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif ( is_archive() ) {
        $position++;
        $item_list[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_archive_title(),
            'item'     => get_post_type_archive_link( get_post_type() ),
        );
    }

    $breadcrumbs = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $item_list,
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumbs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}
add_action( 'wp_head', 'ar_ls_output_breadcrumb_schema', 15 );

/**
 * Lazy loading for images (native in WP ≥5.5), but we ensure older versions.
 */
function ar_ls_add_lazy_loading( $content ) {
    if ( ! preg_match_all( '/<img[^>]+>/', $content, $matches ) ) {
        return $content;
    }

    foreach ( $matches[0] as $img_html ) {
        // Skip if loading attribute already exists.
        if ( strpos( $img_html, 'loading=' ) !== false ) {
            continue;
        }

        $new_img_html = str_replace( '<img', '<img loading="lazy"', $img_html );
        $content      = str_replace( $img_html, $new_img_html, $content );
    }

    return $content;
}
add_filter( 'the_content', 'ar_ls_add_lazy_loading' );

/**
 * Disable emoji scripts/styles for performance.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );