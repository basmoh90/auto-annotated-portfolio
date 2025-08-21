<?php
/**
 * Arabic Local Services Theme Functions
 * 
 * @package Arabic_Local_Services
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function arabic_local_services_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    // Add RTL support
    add_theme_support('rtl');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('القائمة الرئيسية', 'arabic-local-services'),
        'footer' => __('قائمة التذييل', 'arabic-local-services'),
    ));
    
    // Add image sizes
    add_image_size('service-thumbnail', 400, 300, true);
    add_image_size('hero-image', 1200, 600, true);
    add_image_size('testimonial-avatar', 100, 100, true);
}
add_action('after_setup_theme', 'arabic_local_services_setup');

/**
 * Enqueue Scripts and Styles
 */
function arabic_local_services_scripts() {
    // Enqueue Google Fonts (Cairo for Arabic)
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap', array(), null);
    
    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    
    // Enqueue main stylesheet
    wp_enqueue_style('arabic-local-services-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue custom CSS
    wp_enqueue_style('arabic-local-services-custom', get_template_directory_uri() . '/assets/css/custom.css', array(), '1.0.0');
    
    // Enqueue JavaScript
    wp_enqueue_script('arabic-local-services-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('arabic-local-services-script', 'arabic_local_services_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('arabic_local_services_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'arabic_local_services_scripts');

/**
 * Register Widget Areas
 */
function arabic_local_services_widgets_init() {
    register_sidebar(array(
        'name' => __('الشريط الجانبي الرئيسي', 'arabic-local-services'),
        'id' => 'sidebar-1',
        'description' => __('يظهر في جميع الصفحات', 'arabic-local-services'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('تذييل الصفحة - العمود الأول', 'arabic-local-services'),
        'id' => 'footer-1',
        'description' => __('العمود الأول في تذييل الصفحة', 'arabic-local-services'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('تذييل الصفحة - العمود الثاني', 'arabic-local-services'),
        'id' => 'footer-2',
        'description' => __('العمود الثاني في تذييل الصفحة', 'arabic-local-services'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('تذييل الصفحة - العمود الثالث', 'arabic-local-services'),
        'id' => 'footer-3',
        'description' => __('العمود الثالث في تذييل الصفحة', 'arabic-local-services'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'arabic_local_services_widgets_init');

/**
 * Custom Post Types
 */
function arabic_local_services_custom_post_types() {
    // Services Post Type
    register_post_type('services', array(
        'labels' => array(
            'name' => __('الخدمات', 'arabic-local-services'),
            'singular_name' => __('خدمة', 'arabic-local-services'),
            'add_new' => __('إضافة خدمة جديدة', 'arabic-local-services'),
            'add_new_item' => __('إضافة خدمة جديدة', 'arabic-local-services'),
            'edit_item' => __('تعديل الخدمة', 'arabic-local-services'),
            'new_item' => __('خدمة جديدة', 'arabic-local-services'),
            'view_item' => __('عرض الخدمة', 'arabic-local-services'),
            'search_items' => __('البحث في الخدمات', 'arabic-local-services'),
            'not_found' => __('لم يتم العثور على خدمات', 'arabic-local-services'),
            'not_found_in_trash' => __('لم يتم العثور على خدمات في سلة المحذوفات', 'arabic-local-services'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-admin-tools',
        'rewrite' => array('slug' => 'services'),
        'show_in_rest' => true,
    ));
    
    // Testimonials Post Type
    register_post_type('testimonials', array(
        'labels' => array(
            'name' => __('آراء العملاء', 'arabic-local-services'),
            'singular_name' => __('رأي العميل', 'arabic-local-services'),
            'add_new' => __('إضافة رأي جديد', 'arabic-local-services'),
            'add_new_item' => __('إضافة رأي جديد', 'arabic-local-services'),
            'edit_item' => __('تعديل الرأي', 'arabic-local-services'),
            'new_item' => __('رأي جديد', 'arabic-local-services'),
            'view_item' => __('عرض الرأي', 'arabic-local-services'),
            'search_items' => __('البحث في الآراء', 'arabic-local-services'),
            'not_found' => __('لم يتم العثور على آراء', 'arabic-local-services'),
            'not_found_in_trash' => __('لم يتم العثور على آراء في سلة المحذوفات', 'arabic-local-services'),
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-format-quote',
        'show_in_rest' => true,
    ));
    
    // FAQ Post Type
    register_post_type('faq', array(
        'labels' => array(
            'name' => __('الأسئلة الشائعة', 'arabic-local-services'),
            'singular_name' => __('سؤال شائع', 'arabic-local-services'),
            'add_new' => __('إضافة سؤال جديد', 'arabic-local-services'),
            'add_new_item' => __('إضافة سؤال جديد', 'arabic-local-services'),
            'edit_item' => __('تعديل السؤال', 'arabic-local-services'),
            'new_item' => __('سؤال جديد', 'arabic-local-services'),
            'view_item' => __('عرض السؤال', 'arabic-local-services'),
            'search_items' => __('البحث في الأسئلة', 'arabic-local-services'),
            'not_found' => __('لم يتم العثور على أسئلة', 'arabic-local-services'),
            'not_found_in_trash' => __('لم يتم العثور على أسئلة في سلة المحذوفات', 'arabic-local-services'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor'),
        'menu_icon' => 'dashicons-editor-help',
        'rewrite' => array('slug' => 'faq'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'arabic_local_services_custom_post_types');

/**
 * Custom Meta Boxes
 */
function arabic_local_services_meta_boxes() {
    // Service Meta Box
    add_meta_box(
        'service_details',
        __('تفاصيل الخدمة', 'arabic-local-services'),
        'arabic_local_services_service_meta_box',
        'services',
        'normal',
        'high'
    );
    
    // Testimonial Meta Box
    add_meta_box(
        'testimonial_details',
        __('تفاصيل العميل', 'arabic-local-services'),
        'arabic_local_services_testimonial_meta_box',
        'testimonials',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'arabic_local_services_meta_boxes');

/**
 * Service Meta Box Callback
 */
function arabic_local_services_service_meta_box($post) {
    wp_nonce_field('arabic_local_services_save_meta', 'arabic_local_services_meta_nonce');
    
    $service_icon = get_post_meta($post->ID, '_service_icon', true);
    $service_price = get_post_meta($post->ID, '_service_price', true);
    $service_duration = get_post_meta($post->ID, '_service_duration', true);
    $service_features = get_post_meta($post->ID, '_service_features', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="service_icon"><?php _e('أيقونة الخدمة (Font Awesome Class)', 'arabic-local-services'); ?></label></th>
            <td><input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($service_icon); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="service_price"><?php _e('سعر الخدمة', 'arabic-local-services'); ?></label></th>
            <td><input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($service_price); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="service_duration"><?php _e('مدة الخدمة', 'arabic-local-services'); ?></label></th>
            <td><input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($service_duration); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="service_features"><?php _e('مميزات الخدمة (سطر واحد لكل ميزة)', 'arabic-local-services'); ?></label></th>
            <td><textarea id="service_features" name="service_features" rows="5" class="large-text"><?php echo esc_textarea($service_features); ?></textarea></td>
        </tr>
    </table>
    <?php
}

/**
 * Testimonial Meta Box Callback
 */
function arabic_local_services_testimonial_meta_box($post) {
    wp_nonce_field('arabic_local_services_save_meta', 'arabic_local_services_meta_nonce');
    
    $customer_name = get_post_meta($post->ID, '_customer_name', true);
    $customer_location = get_post_meta($post->ID, '_customer_location', true);
    $rating = get_post_meta($post->ID, '_rating', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="customer_name"><?php _e('اسم العميل', 'arabic-local-services'); ?></label></th>
            <td><input type="text" id="customer_name" name="customer_name" value="<?php echo esc_attr($customer_name); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="customer_location"><?php _e('موقع العميل', 'arabic-local-services'); ?></label></th>
            <td><input type="text" id="customer_location" name="customer_location" value="<?php echo esc_attr($customer_location); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="rating"><?php _e('التقييم (1-5)', 'arabic-local-services'); ?></label></th>
            <td><input type="number" id="rating" name="rating" value="<?php echo esc_attr($rating); ?>" min="1" max="5" /></td>
        </tr>
    </table>
    <?php
}

/**
 * Save Meta Box Data
 */
function arabic_local_services_save_meta($post_id) {
    if (!isset($_POST['arabic_local_services_meta_nonce']) || !wp_verify_nonce($_POST['arabic_local_services_meta_nonce'], 'arabic_local_services_save_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save service meta
    if (isset($_POST['service_icon'])) {
        update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon']));
    }
    if (isset($_POST['service_price'])) {
        update_post_meta($post_id, '_service_price', sanitize_text_field($_POST['service_price']));
    }
    if (isset($_POST['service_duration'])) {
        update_post_meta($post_id, '_service_duration', sanitize_text_field($_POST['service_duration']));
    }
    if (isset($_POST['service_features'])) {
        update_post_meta($post_id, '_service_features', sanitize_textarea_field($_POST['service_features']));
    }
    
    // Save testimonial meta
    if (isset($_POST['customer_name'])) {
        update_post_meta($post_id, '_customer_name', sanitize_text_field($_POST['customer_name']));
    }
    if (isset($_POST['customer_location'])) {
        update_post_meta($post_id, '_customer_location', sanitize_text_field($_POST['customer_location']));
    }
    if (isset($_POST['rating'])) {
        update_post_meta($post_id, '_rating', intval($_POST['rating']));
    }
}
add_action('save_post', 'arabic_local_services_save_meta');

/**
 * Schema.org JSON-LD Markup
 */
function arabic_local_services_schema_markup() {
    // Local Business Schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url(),
        'telephone' => get_theme_mod('business_phone', '+966501234567'),
        'email' => get_theme_mod('business_email', 'info@example.com'),
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => get_theme_mod('business_address', 'شارع الملك فهد'),
            'addressLocality' => get_theme_mod('business_city', 'الرياض'),
            'addressRegion' => get_theme_mod('business_region', 'الرياض'),
            'postalCode' => get_theme_mod('business_postal_code', '12345'),
            'addressCountry' => 'SA'
        ),
        'geo' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => get_theme_mod('business_latitude', '24.7136'),
            'longitude' => get_theme_mod('business_longitude', '46.6753')
        ),
        'openingHours' => get_theme_mod('business_hours', 'Mo-Su 08:00-18:00'),
        'priceRange' => '$$',
        'areaServed' => array(
            get_theme_mod('business_area_served', 'الرياض'),
            'الدمام',
            'جدة'
        ),
        'serviceType' => array(
            'نقل الأثاث',
            'ونش رفع العفش',
            'تنظيف العفش',
            'خدمات التكييف',
            'تسليك المجاري'
        )
    );
    
    // Add logo if exists
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($logo_url) {
            $schema['logo'] = $logo_url;
        }
    }
    
    // Add image if exists
    $schema['image'] = get_theme_mod('business_image', get_template_directory_uri() . '/assets/images/business-default.jpg');
    
    // Output schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    
    // Service Schema for service pages
    if (is_singular('services')) {
        $service_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'provider' => array(
                '@type' => 'LocalBusiness',
                'name' => get_bloginfo('name')
            ),
            'areaServed' => array(
                get_theme_mod('business_area_served', 'الرياض'),
                'الدمام',
                'جدة'
            )
        );
        
        $service_price = get_post_meta(get_the_ID(), '_service_price', true);
        if ($service_price) {
            $service_schema['offers'] = array(
                '@type' => 'Offer',
                'price' => $service_price,
                'priceCurrency' => 'SAR'
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($service_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
    
    // FAQ Schema for FAQ pages
    if (is_singular('faq') || is_post_type_archive('faq')) {
        $faq_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array()
        );
        
        $faqs = get_posts(array(
            'post_type' => 'faq',
            'posts_per_page' => -1
        ));
        
        foreach ($faqs as $faq) {
            $faq_schema['mainEntity'][] = array(
                '@type' => 'Question',
                'name' => $faq->post_title,
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => wp_strip_all_tags($faq->post_content)
                )
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
    
    // Breadcrumb Schema
    if (!is_front_page()) {
        $breadcrumb_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array()
        );
        
        $position = 1;
        $breadcrumb_schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_bloginfo('name'),
            'item' => home_url()
        );
        $position++;
        
        if (is_singular('services')) {
            $breadcrumb_schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => __('الخدمات', 'arabic-local-services'),
                'item' => get_post_type_archive_link('services')
            );
            $position++;
        }
        
        if (is_singular('faq')) {
            $breadcrumb_schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => __('الأسئلة الشائعة', 'arabic-local-services'),
                'item' => get_post_type_archive_link('faq')
            );
            $position++;
        }
        
        $breadcrumb_schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title()
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
}
add_action('wp_head', 'arabic_local_services_schema_markup');

/**
 * SEO Optimization
 */
function arabic_local_services_seo_optimization() {
    // Add meta description
    if (is_singular()) {
        $excerpt = get_the_excerpt();
        if ($excerpt) {
            echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($excerpt)) . '" />' . "\n";
        }
    }
    
    // Add Open Graph tags
    if (is_singular()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        
        if (has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            echo '<meta property="og:image" content="' . esc_url($image_url) . '" />' . "\n";
        }
        
        $excerpt = get_the_excerpt();
        if ($excerpt) {
            echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($excerpt)) . '" />' . "\n";
        }
    }
    
    // Add Twitter Card tags
    if (is_singular()) {
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        
        $excerpt = get_the_excerpt();
        if ($excerpt) {
            echo '<meta name="twitter:description" content="' . esc_attr(wp_strip_all_tags($excerpt)) . '" />' . "\n";
        }
        
        if (has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            echo '<meta name="twitter:image" content="' . esc_url($image_url) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'arabic_local_services_seo_optimization');

/**
 * Contact Form Handler
 */
function arabic_local_services_contact_form_handler() {
    check_ajax_referer('arabic_local_services_nonce', 'nonce');
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $service = sanitize_text_field($_POST['service']);
    $message = sanitize_textarea_field($_POST['message']);
    
    if (empty($name) || empty($email) || empty($phone)) {
        wp_die(__('يرجى ملء جميع الحقول المطلوبة', 'arabic-local-services'));
    }
    
    $to = get_option('admin_email');
    $subject = sprintf(__('طلب خدمة جديد من %s', 'arabic-local-services'), $name);
    $body = sprintf(
        __("اسم العميل: %s\nالبريد الإلكتروني: %s\nرقم الهاتف: %s\nالخدمة المطلوبة: %s\nالرسالة: %s", 'arabic-local-services'),
        $name,
        $email,
        $phone,
        $service,
        $message
    );
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    $sent = wp_mail($to, $subject, $body, $headers);
    
    if ($sent) {
        wp_send_json_success(__('تم إرسال طلبك بنجاح! سنتواصل معك قريباً.', 'arabic-local-services'));
    } else {
        wp_send_json_error(__('حدث خطأ في إرسال الطلب. يرجى المحاولة مرة أخرى.', 'arabic-local-services'));
    }
}
add_action('wp_ajax_contact_form', 'arabic_local_services_contact_form_handler');
add_action('wp_ajax_nopriv_contact_form', 'arabic_local_services_contact_form_handler');

/**
 * Customizer Settings
 */
function arabic_local_services_customize_register($wp_customize) {
    // Business Information Section
    $wp_customize->add_section('business_info', array(
        'title' => __('معلومات النشاط التجاري', 'arabic-local-services'),
        'priority' => 30,
    ));
    
    // Business Phone
    $wp_customize->add_setting('business_phone', array(
        'default' => '+966501234567',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('business_phone', array(
        'label' => __('رقم الهاتف', 'arabic-local-services'),
        'section' => 'business_info',
        'type' => 'text',
    ));
    
    // Business Email
    $wp_customize->add_setting('business_email', array(
        'default' => 'info@example.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('business_email', array(
        'label' => __('البريد الإلكتروني', 'arabic-local-services'),
        'section' => 'business_info',
        'type' => 'email',
    ));
    
    // Business Address
    $wp_customize->add_setting('business_address', array(
        'default' => 'شارع الملك فهد',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('business_address', array(
        'label' => __('العنوان', 'arabic-local-services'),
        'section' => 'business_info',
        'type' => 'text',
    ));
    
    // Business City
    $wp_customize->add_setting('business_city', array(
        'default' => 'الرياض',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('business_city', array(
        'label' => __('المدينة', 'arabic-local-services'),
        'section' => 'business_info',
        'type' => 'text',
    ));
    
    // Business Hours
    $wp_customize->add_setting('business_hours', array(
        'default' => 'Mo-Su 08:00-18:00',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('business_hours', array(
        'label' => __('ساعات العمل', 'arabic-local-services'),
        'section' => 'business_info',
        'type' => 'text',
    ));
    
    // WhatsApp Number
    $wp_customize->add_setting('whatsapp_number', array(
        'default' => '+966501234567',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('whatsapp_number', array(
        'label' => __('رقم الواتساب', 'arabic-local-services'),
        'section' => 'business_info',
        'type' => 'text',
    ));
}
add_action('customize_register', 'arabic_local_services_customize_register');

/**
 * Performance Optimization
 */
function arabic_local_services_performance_optimization() {
    // Remove unnecessary WordPress features
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // Disable emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Add preload for critical resources
    add_action('wp_head', function() {
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/custom.css" as="style">' . "\n";
        echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" as="style">' . "\n";
    });
}
add_action('init', 'arabic_local_services_performance_optimization');

/**
 * Lazy Loading for Images
 */
function arabic_local_services_lazy_loading($content) {
    return preg_replace('/<img(.*?)>/', '<img$1 loading="lazy">', $content);
}
add_filter('the_content', 'arabic_local_services_lazy_loading');

/**
 * Custom Excerpt Length
 */
function arabic_local_services_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'arabic_local_services_excerpt_length');

/**
 * Custom Excerpt More
 */
function arabic_local_services_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'arabic_local_services_excerpt_more');

/**
 * Load Text Domain
 */
function arabic_local_services_load_textdomain() {
    load_theme_textdomain('arabic-local-services', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'arabic_local_services_load_textdomain');

/**
 * Fallback Menu Function
 */
function arabic_local_services_fallback_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . __('الرئيسية', 'arabic-local-services') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/services/')) . '">' . __('الخدمات', 'arabic-local-services') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about/')) . '">' . __('من نحن', 'arabic-local-services') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact/')) . '">' . __('اتصل بنا', 'arabic-local-services') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/faq/')) . '">' . __('الأسئلة الشائعة', 'arabic-local-services') . '</a></li>';
    echo '</ul>';
}