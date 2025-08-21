<?php
/**
 * The front page template file
 *
 * @package Arabic_Local_Services
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1><?php _e('خدمات نقل وتنظيف الأثاث', 'arabic-local-services'); ?></h1>
            <p><?php _e('نقدم أفضل خدمات نقل الأثاث وتنظيف العفش وخدمات التكييف في جميع أنحاء المملكة', 'arabic-local-services'); ?></p>
            <div class="hero-buttons">
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary">
                    <?php _e('احجز خدمة الآن', 'arabic-local-services'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn btn-secondary">
                    <?php _e('تعرف على خدماتنا', 'arabic-local-services'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section">
    <div class="container">
        <div class="section-title">
            <h2><?php _e('خدماتنا المميزة', 'arabic-local-services'); ?></h2>
            <p><?php _e('نقدم مجموعة شاملة من الخدمات المنزلية بأعلى جودة وأفضل الأسعار', 'arabic-local-services'); ?></p>
        </div>

        <div class="row">
            <?php
            $services = array(
                array(
                    'title' => __('نقل الأثاث', 'arabic-local-services'),
                    'icon' => 'fas fa-truck',
                    'description' => __('خدمة نقل الأثاث الآمنة والمضمونة مع فريق متخصص', 'arabic-local-services'),
                    'features' => array(
                        __('فريق متخصص', 'arabic-local-services'),
                        __('تأمين شامل', 'arabic-local-services'),
                        __('خدمة 24/7', 'arabic-local-services')
                    )
                ),
                array(
                    'title' => __('ونش رفع العفش', 'arabic-local-services'),
                    'icon' => 'fas fa-crane',
                    'description' => __('خدمة ونش رفع العفش للطوابق العليا بأحدث المعدات', 'arabic-local-services'),
                    'features' => array(
                        __('معدات حديثة', 'arabic-local-services'),
                        __('فريق مدرب', 'arabic-local-services'),
                        __('أمان تام', 'arabic-local-services')
                    )
                ),
                array(
                    'title' => __('تنظيف العفش', 'arabic-local-services'),
                    'icon' => 'fas fa-broom',
                    'description' => __('خدمة تنظيف الأثاث والعفش بمواد آمنة ومتخصصة', 'arabic-local-services'),
                    'features' => array(
                        __('مواد آمنة', 'arabic-local-services'),
                        __('تنظيف شامل', 'arabic-local-services'),
                        __('ضمان الجودة', 'arabic-local-services')
                    )
                ),
                array(
                    'title' => __('خدمات التكييف', 'arabic-local-services'),
                    'icon' => 'fas fa-snowflake',
                    'description' => __('صيانة وتنظيف أجهزة التكييف بأسعار منافسة', 'arabic-local-services'),
                    'features' => array(
                        __('صيانة شاملة', 'arabic-local-services'),
                        __('تنظيف عميق', 'arabic-local-services'),
                        __('ضمان الخدمة', 'arabic-local-services')
                    )
                ),
                array(
                    'title' => __('تسليك المجاري', 'arabic-local-services'),
                    'icon' => 'fas fa-wrench',
                    'description' => __('خدمة تسليك المجاري والسباكة بأساليب حديثة', 'arabic-local-services'),
                    'features' => array(
                        __('أجهزة حديثة', 'arabic-local-services'),
                        __('حلول سريعة', 'arabic-local-services'),
                        __('ضمان النتيجة', 'arabic-local-services')
                    )
                )
            );

            foreach ($services as $service) : ?>
                <div class="col-md-4 col-sm-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="<?php echo esc_attr($service['icon']); ?>"></i>
                        </div>
                        <h3><?php echo esc_html($service['title']); ?></h3>
                        <p><?php echo esc_html($service['description']); ?></p>
                        <ul class="service-features">
                            <?php foreach ($service['features'] as $feature) : ?>
                                <li><i class="fas fa-check"></i> <?php echo esc_html($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary">
                            <?php _e('احجز الآن', 'arabic-local-services'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="about-content">
                    <h2><?php _e('لماذا تختارنا؟', 'arabic-local-services'); ?></h2>
                    <p><?php _e('نحن شركة رائدة في مجال الخدمات المنزلية، نتميز بالخبرة الطويلة والجودة العالية في جميع خدماتنا.', 'arabic-local-services'); ?></p>
                    
                    <div class="features-list">
                        <div class="feature-item">
                            <i class="fas fa-medal"></i>
                            <div>
                                <h4><?php _e('جودة عالية', 'arabic-local-services'); ?></h4>
                                <p><?php _e('نقدم خدمات بأعلى معايير الجودة', 'arabic-local-services'); ?></p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4><?php _e('سرعة في الأداء', 'arabic-local-services'); ?></h4>
                                <p><?php _e('نلتزم بالمواعيد وننهي العمل بسرعة', 'arabic-local-services'); ?></p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <h4><?php _e('ضمان شامل', 'arabic-local-services'); ?></h4>
                                <p><?php _e('نضمن جودة جميع خدماتنا', 'arabic-local-services'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="about-image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/about-us.jpg" alt="<?php _e('فريق العمل', 'arabic-local-services'); ?>" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-title">
            <h2><?php _e('آراء عملائنا', 'arabic-local-services'); ?></h2>
            <p><?php _e('ماذا يقول عملاؤنا عن خدماتنا', 'arabic-local-services'); ?></p>
        </div>

        <div class="row">
            <?php
            $testimonials = get_posts(array(
                'post_type' => 'testimonials',
                'posts_per_page' => 3,
                'post_status' => 'publish'
            ));

            if ($testimonials) :
                foreach ($testimonials as $testimonial) :
                    $customer_name = get_post_meta($testimonial->ID, '_customer_name', true);
                    $customer_location = get_post_meta($testimonial->ID, '_customer_location', true);
                    $rating = get_post_meta($testimonial->ID, '_rating', true);
                    ?>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <?php echo wp_kses_post($testimonial->post_content); ?>
                            </div>
                            <div class="testimonial-author">
                                <h4><?php echo esc_html($customer_name ?: $testimonial->post_title); ?></h4>
                                <p><?php echo esc_html($customer_location); ?></p>
                                <?php if ($rating) : ?>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <i class="fas fa-star<?php echo $i <= $rating ? '' : '-o'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;
            else :
                // Fallback testimonials
                $fallback_testimonials = array(
                    array(
                        'content' => __('خدمة ممتازة وسعر معقول. الفريق محترف جداً وأداء العمل كان رائع.', 'arabic-local-services'),
                        'name' => __('أحمد محمد', 'arabic-local-services'),
                        'location' => __('الرياض', 'arabic-local-services'),
                        'rating' => 5
                    ),
                    array(
                        'content' => __('أفضل شركة نقل أثاث تعاملت معها. أنصح الجميع بالتعامل معهم.', 'arabic-local-services'),
                        'name' => __('فاطمة علي', 'arabic-local-services'),
                        'location' => __('جدة', 'arabic-local-services'),
                        'rating' => 5
                    ),
                    array(
                        'content' => __('خدمة تنظيف التكييف كانت ممتازة. الفريق متخصص والأسعار منافسة.', 'arabic-local-services'),
                        'name' => __('خالد عبدالله', 'arabic-local-services'),
                        'location' => __('الدمام', 'arabic-local-services'),
                        'rating' => 5
                    )
                );

                foreach ($fallback_testimonials as $testimonial) : ?>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p><?php echo esc_html($testimonial['content']); ?></p>
                            </div>
                            <div class="testimonial-author">
                                <h4><?php echo esc_html($testimonial['name']); ?></h4>
                                <p><?php echo esc_html($testimonial['location']); ?></p>
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <i class="fas fa-star<?php echo $i <= $testimonial['rating'] ? '' : '-o'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="section-title">
            <h2><?php _e('تواصل معنا', 'arabic-local-services'); ?></h2>
            <p><?php _e('احصل على عرض سعر مجاني لجميع خدماتنا', 'arabic-local-services'); ?></p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <h4><?php _e('اتصل بنا', 'arabic-local-services'); ?></h4>
                        <p><?php echo esc_html(get_theme_mod('business_phone', '+966501234567')); ?></p>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <h4><?php _e('راسلنا', 'arabic-local-services'); ?></h4>
                        <p><?php echo esc_html(get_theme_mod('business_email', 'info@example.com')); ?></p>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4><?php _e('موقعنا', 'arabic-local-services'); ?></h4>
                        <p><?php echo esc_html(get_theme_mod('business_address', 'شارع الملك فهد')); ?><br>
                           <?php echo esc_html(get_theme_mod('business_city', 'الرياض')); ?></p>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <h4><?php _e('ساعات العمل', 'arabic-local-services'); ?></h4>
                        <p><?php echo esc_html(get_theme_mod('business_hours', 'Mo-Su 08:00-18:00')); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="contact-form">
                    <form id="contact-form" method="post">
                        <div class="form-group">
                            <label for="name"><?php _e('الاسم الكامل *', 'arabic-local-services'); ?></label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email"><?php _e('البريد الإلكتروني *', 'arabic-local-services'); ?></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone"><?php _e('رقم الهاتف *', 'arabic-local-services'); ?></label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="service"><?php _e('الخدمة المطلوبة', 'arabic-local-services'); ?></label>
                            <select id="service" name="service">
                                <option value=""><?php _e('اختر الخدمة', 'arabic-local-services'); ?></option>
                                <option value="نقل الأثاث"><?php _e('نقل الأثاث', 'arabic-local-services'); ?></option>
                                <option value="ونش رفع العفش"><?php _e('ونش رفع العفش', 'arabic-local-services'); ?></option>
                                <option value="تنظيف العفش"><?php _e('تنظيف العفش', 'arabic-local-services'); ?></option>
                                <option value="خدمات التكييف"><?php _e('خدمات التكييف', 'arabic-local-services'); ?></option>
                                <option value="تسليك المجاري"><?php _e('تسليك المجاري', 'arabic-local-services'); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message"><?php _e('الرسالة', 'arabic-local-services'); ?></label>
                            <textarea id="message" name="message" rows="5"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <?php _e('إرسال الطلب', 'arabic-local-services'); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>