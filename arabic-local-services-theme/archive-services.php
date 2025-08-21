<?php
/**
 * The template for displaying services archive
 *
 * @package Arabic_Local_Services
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php _e('خدماتنا', 'arabic-local-services'); ?></h1>
            <p class="page-description"><?php _e('نقدم مجموعة شاملة من الخدمات المنزلية بأعلى جودة وأفضل الأسعار', 'arabic-local-services'); ?></p>
        </header>

        <?php if (have_posts()) : ?>
            <div class="services-grid">
                <div class="row">
                    <?php
                    while (have_posts()) :
                        the_post();
                        
                        // Get service meta data
                        $service_icon = get_post_meta(get_the_ID(), '_service_icon', true);
                        $service_price = get_post_meta(get_the_ID(), '_service_price', true);
                        $service_duration = get_post_meta(get_the_ID(), '_service_duration', true);
                        $service_features = get_post_meta(get_the_ID(), '_service_features', true);
                        ?>
                        
                        <div class="col-md-6 col-lg-4">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('service-card'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="service-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('service-thumbnail'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="service-content">
                                    <?php if ($service_icon) : ?>
                                        <div class="service-icon">
                                            <i class="<?php echo esc_attr($service_icon); ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h2 class="service-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    
                                    <div class="service-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <?php if ($service_price || $service_duration) : ?>
                                        <div class="service-meta">
                                            <?php if ($service_price) : ?>
                                                <span class="service-price">
                                                    <i class="fas fa-tag"></i>
                                                    <?php echo esc_html($service_price); ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if ($service_duration) : ?>
                                                <span class="service-duration">
                                                    <i class="fas fa-clock"></i>
                                                    <?php echo esc_html($service_duration); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($service_features) : ?>
                                        <div class="service-features">
                                            <ul class="features-list">
                                                <?php
                                                $features = explode("\n", $service_features);
                                                $feature_count = 0;
                                                foreach ($features as $feature) {
                                                    $feature = trim($feature);
                                                    if (!empty($feature) && $feature_count < 3) {
                                                        echo '<li><i class="fas fa-check"></i> ' . esc_html($feature) . '</li>';
                                                        $feature_count++;
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="service-actions">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                            <?php _e('تفاصيل الخدمة', 'arabic-local-services'); ?>
                                        </a>
                                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-secondary">
                                            <?php _e('احجز الآن', 'arabic-local-services'); ?>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php
                    endwhile;
                    ?>
                </div>
            </div>

            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '<i class="fas fa-chevron-right"></i> ' . __('السابق', 'arabic-local-services'),
                'next_text' => __('التالي', 'arabic-local-services') . ' <i class="fas fa-chevron-left"></i>',
            ));
            ?>

        <?php else : ?>
            <section class="no-services">
                <div class="no-services-content">
                    <i class="fas fa-tools"></i>
                    <h2><?php _e('لا توجد خدمات متاحة حالياً', 'arabic-local-services'); ?></h2>
                    <p><?php _e('سنقوم بإضافة خدمات جديدة قريباً. يرجى التواصل معنا للمزيد من المعلومات.', 'arabic-local-services'); ?></p>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary">
                        <?php _e('تواصل معنا', 'arabic-local-services'); ?>
                    </a>
                </div>
            </section>
        <?php endif; ?>

        <!-- Services CTA Section -->
        <section class="services-cta">
            <div class="cta-content">
                <h2><?php _e('لا تجد الخدمة التي تبحث عنها؟', 'arabic-local-services'); ?></h2>
                <p><?php _e('تواصل معنا وسنقدم لك الحل المناسب لجميع احتياجاتك', 'arabic-local-services'); ?></p>
                <div class="cta-buttons">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-large">
                        <i class="fas fa-phone"></i> <?php _e('اتصل بنا', 'arabic-local-services'); ?>
                    </a>
                    <a href="https://wa.me/<?php echo esc_attr(str_replace('+', '', get_theme_mod('whatsapp_number', '+966501234567'))); ?>?text=<?php echo urlencode(__('مرحباً، أريد معلومات عن خدماتكم', 'arabic-local-services')); ?>" 
                       class="btn btn-success btn-large" target="_blank">
                        <i class="fab fa-whatsapp"></i> <?php _e('واتساب', 'arabic-local-services'); ?>
                    </a>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <?php
        $faqs = get_posts(array(
            'post_type' => 'faq',
            'posts_per_page' => 6,
            'post_status' => 'publish'
        ));
        
        if ($faqs) :
            ?>
            <section class="faq-section">
                <div class="section-header">
                    <h2><?php _e('الأسئلة الشائعة', 'arabic-local-services'); ?></h2>
                    <p><?php _e('إجابات على أكثر الأسئلة شيوعاً حول خدماتنا', 'arabic-local-services'); ?></p>
                </div>
                
                <div class="faq-grid">
                    <div class="row">
                        <?php foreach ($faqs as $faq) : ?>
                            <div class="col-md-6">
                                <div class="faq-item">
                                    <h3 class="faq-question">
                                        <a href="<?php echo esc_url(get_permalink($faq->ID)); ?>">
                                            <?php echo esc_html($faq->post_title); ?>
                                        </a>
                                    </h3>
                                    <div class="faq-answer">
                                        <?php echo wp_trim_words($faq->post_content, 20); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="faq-cta">
                    <a href="<?php echo esc_url(get_post_type_archive_link('faq')); ?>" class="btn btn-secondary">
                        <?php _e('عرض جميع الأسئلة', 'arabic-local-services'); ?>
                    </a>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
?>