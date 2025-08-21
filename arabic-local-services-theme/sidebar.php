<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Arabic_Local_Services
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar('sidebar-1'); ?>
    
    <!-- Default Widgets if no widgets are added -->
    <?php if (!is_active_sidebar('sidebar-1')) : ?>
        
        <!-- Contact Information Widget -->
        <div class="widget widget-contact">
            <h3 class="widget-title"><?php _e('معلومات التواصل', 'arabic-local-services'); ?></h3>
            <div class="contact-info-widget">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo esc_html(get_theme_mod('business_phone', '+966501234567')); ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo esc_html(get_theme_mod('business_email', 'info@example.com')); ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo esc_html(get_theme_mod('business_address', 'شارع الملك فهد')); ?><br>
                          <?php echo esc_html(get_theme_mod('business_city', 'الرياض')); ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <span><?php echo esc_html(get_theme_mod('business_hours', 'Mo-Su 08:00-18:00')); ?></span>
                </div>
            </div>
        </div>

        <!-- Services Widget -->
        <div class="widget widget-services">
            <h3 class="widget-title"><?php _e('خدماتنا', 'arabic-local-services'); ?></h3>
            <ul class="services-list">
                <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('نقل الأثاث', 'arabic-local-services'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('ونش رفع العفش', 'arabic-local-services'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('تنظيف العفش', 'arabic-local-services'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('خدمات التكييف', 'arabic-local-services'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('تسليك المجاري', 'arabic-local-services'); ?></a></li>
            </ul>
        </div>

        <!-- Quick Contact Widget -->
        <div class="widget widget-quick-contact">
            <h3 class="widget-title"><?php _e('تواصل سريع', 'arabic-local-services'); ?></h3>
            <div class="quick-contact-buttons">
                <a href="tel:<?php echo esc_attr(get_theme_mod('business_phone', '+966501234567')); ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-phone"></i> <?php _e('اتصل الآن', 'arabic-local-services'); ?>
                </a>
                <a href="https://wa.me/<?php echo esc_attr(str_replace('+', '', get_theme_mod('whatsapp_number', '+966501234567'))); ?>?text=<?php echo urlencode(__('مرحباً، أريد معلومات عن خدماتكم', 'arabic-local-services')); ?>" 
                   class="btn btn-success btn-block" target="_blank">
                    <i class="fab fa-whatsapp"></i> <?php _e('واتساب', 'arabic-local-services'); ?>
                </a>
            </div>
        </div>

        <!-- Recent Posts Widget -->
        <?php
        $recent_posts = wp_get_recent_posts(array(
            'numberposts' => 5,
            'post_status' => 'publish'
        ));
        
        if ($recent_posts) : ?>
            <div class="widget widget-recent-posts">
                <h3 class="widget-title"><?php _e('آخر المقالات', 'arabic-local-services'); ?></h3>
                <ul class="recent-posts-list">
                    <?php foreach ($recent_posts as $post) : ?>
                        <li>
                            <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                                <?php echo esc_html($post['post_title']); ?>
                            </a>
                            <span class="post-date"><?php echo get_the_date('', $post['ID']); ?></span>
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
        
        if ($faqs) : ?>
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

    <?php endif; ?>
</aside>