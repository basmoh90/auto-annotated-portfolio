<?php
/**
 * The template for displaying single service pages
 *
 * @package Arabic_Local_Services
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                while (have_posts()) :
                    the_post();
                    
                    // Get service meta data
                    $service_icon = get_post_meta(get_the_ID(), '_service_icon', true);
                    $service_price = get_post_meta(get_the_ID(), '_service_price', true);
                    $service_duration = get_post_meta(get_the_ID(), '_service_duration', true);
                    $service_features = get_post_meta(get_the_ID(), '_service_features', true);
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('service-single'); ?>>
                        <header class="entry-header">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="service-thumbnail">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-header-content">
                                <h1 class="entry-title"><?php the_title(); ?></h1>
                                
                                <?php if ($service_icon) : ?>
                                    <div class="service-icon-large">
                                        <i class="<?php echo esc_attr($service_icon); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="service-meta">
                                    <?php if ($service_price) : ?>
                                        <div class="service-price">
                                            <i class="fas fa-tag"></i>
                                            <span><?php echo esc_html($service_price); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($service_duration) : ?>
                                        <div class="service-duration">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo esc_html($service_duration); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </header>

                        <div class="entry-content">
                            <?php the_content(); ?>
                            
                            <?php if ($service_features) : ?>
                                <div class="service-features-section">
                                    <h3><?php _e('مميزات الخدمة', 'arabic-local-services'); ?></h3>
                                    <ul class="service-features-list">
                                        <?php
                                        $features = explode("\n", $service_features);
                                        foreach ($features as $feature) {
                                            $feature = trim($feature);
                                            if (!empty($feature)) {
                                                echo '<li><i class="fas fa-check"></i> ' . esc_html($feature) . '</li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="service-cta">
                            <div class="cta-buttons">
                                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-large">
                                    <i class="fas fa-phone"></i> <?php _e('احجز الخدمة الآن', 'arabic-local-services'); ?>
                                </a>
                                <a href="https://wa.me/<?php echo esc_attr(str_replace('+', '', get_theme_mod('whatsapp_number', '+966501234567'))); ?>?text=<?php echo urlencode(sprintf(__('مرحباً، أريد معلومات عن خدمة %s', 'arabic-local-services'), get_the_title())); ?>" 
                                   class="btn btn-success btn-large" target="_blank">
                                    <i class="fab fa-whatsapp"></i> <?php _e('تواصل عبر الواتساب', 'arabic-local-services'); ?>
                                </a>
                            </div>
                        </div>

                        <footer class="entry-footer">
                            <?php
                            // Service navigation
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            
                            if ($prev_post || $next_post) :
                                ?>
                                <nav class="service-navigation">
                                    <div class="nav-links">
                                        <?php if ($prev_post) : ?>
                                            <div class="nav-previous">
                                                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>">
                                                    <i class="fas fa-chevron-right"></i>
                                                    <span class="nav-subtitle"><?php _e('الخدمة السابقة', 'arabic-local-services'); ?></span>
                                                    <span class="nav-title"><?php echo esc_html($prev_post->post_title); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($next_post) : ?>
                                            <div class="nav-next">
                                                <a href="<?php echo esc_url(get_permalink($next_post)); ?>">
                                                    <span class="nav-subtitle"><?php _e('الخدمة التالية', 'arabic-local-services'); ?></span>
                                                    <span class="nav-title"><?php echo esc_html($next_post->post_title); ?></span>
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </nav>
                                <?php
                            endif;
                            ?>
                        </footer>
                    </article>

                <?php endwhile; ?>
            </div>

            <div class="col-md-4">
                <aside class="service-sidebar">
                    <!-- Contact Information -->
                    <div class="widget widget-contact-info">
                        <h3 class="widget-title"><?php _e('معلومات التواصل', 'arabic-local-services'); ?></h3>
                        <div class="contact-info-list">
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong><?php _e('اتصل بنا', 'arabic-local-services'); ?></strong>
                                    <p><?php echo esc_html(get_theme_mod('business_phone', '+966501234567')); ?></p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong><?php _e('راسلنا', 'arabic-local-services'); ?></strong>
                                    <p><?php echo esc_html(get_theme_mod('business_email', 'info@example.com')); ?></p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong><?php _e('ساعات العمل', 'arabic-local-services'); ?></strong>
                                    <p><?php echo esc_html(get_theme_mod('business_hours', 'Mo-Su 08:00-18:00')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Contact Form -->
                    <div class="widget widget-quick-contact">
                        <h3 class="widget-title"><?php _e('احصل على عرض سعر', 'arabic-local-services'); ?></h3>
                        <form class="quick-contact-form" method="post">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="<?php _e('الاسم الكامل', 'arabic-local-services'); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <input type="tel" name="phone" placeholder="<?php _e('رقم الهاتف', 'arabic-local-services'); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <textarea name="message" placeholder="<?php _e('تفاصيل الطلب', 'arabic-local-services'); ?>" rows="3"></textarea>
                            </div>
                            
                            <input type="hidden" name="service" value="<?php echo esc_attr(get_the_title()); ?>">
                            
                            <button type="submit" class="btn btn-primary btn-block">
                                <?php _e('إرسال الطلب', 'arabic-local-services'); ?>
                            </button>
                        </form>
                    </div>

                    <!-- Related Services -->
                    <?php
                    $related_services = get_posts(array(
                        'post_type' => 'services',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'post_status' => 'publish'
                    ));
                    
                    if ($related_services) :
                        ?>
                        <div class="widget widget-related-services">
                            <h3 class="widget-title"><?php _e('خدمات ذات صلة', 'arabic-local-services'); ?></h3>
                            <ul class="related-services-list">
                                <?php foreach ($related_services as $service) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_permalink($service->ID)); ?>">
                                            <?php if (has_post_thumbnail($service->ID)) : ?>
                                                <div class="service-thumb">
                                                    <?php echo get_the_post_thumbnail($service->ID, 'thumbnail'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="service-info">
                                                <h4><?php echo esc_html($service->post_title); ?></h4>
                                                <p><?php echo esc_html(wp_trim_words($service->post_excerpt, 10)); ?></p>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- FAQ Widget -->
                    <?php
                    $faqs = get_posts(array(
                        'post_type' => 'faq',
                        'posts_per_page' => 3,
                        'post_status' => 'publish'
                    ));
                    
                    if ($faqs) :
                        ?>
                        <div class="widget widget-faq">
                            <h3 class="widget-title"><?php _e('الأسئلة الشائعة', 'arabic-local-services'); ?></h3>
                            <ul class="faq-list">
                                <?php foreach ($faqs as $faq) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_permalink($faq->ID)); ?>">
                                            <?php echo esc_html($faq->post_title); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="<?php echo esc_url(get_post_type_archive_link('faq')); ?>" class="btn btn-secondary btn-block">
                                <?php _e('عرض جميع الأسئلة', 'arabic-local-services'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
?>