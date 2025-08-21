<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Arabic_Local_Services
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <section class="error-404 not-found">
                    <header class="page-header">
                        <h1 class="page-title"><?php _e('عذراً! الصفحة غير موجودة', 'arabic-local-services'); ?></h1>
                    </header>

                    <div class="page-content">
                        <div class="error-content">
                            <div class="error-number">404</div>
                            <h2><?php _e('الصفحة التي تبحث عنها غير موجودة', 'arabic-local-services'); ?></h2>
                            <p><?php _e('قد تكون الصفحة قد تم نقلها أو حذفها أو أن الرابط غير صحيح.', 'arabic-local-services'); ?></p>
                        </div>

                        <div class="error-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <i class="fas fa-home"></i> <?php _e('العودة للرئيسية', 'arabic-local-services'); ?>
                            </a>
                            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-secondary">
                                <i class="fas fa-phone"></i> <?php _e('اتصل بنا', 'arabic-local-services'); ?>
                            </a>
                        </div>

                        <div class="search-section">
                            <h3><?php _e('البحث في الموقع', 'arabic-local-services'); ?></h3>
                            <p><?php _e('جرب البحث عن المحتوى الذي تبحث عنه:', 'arabic-local-services'); ?></p>
                            <?php get_search_form(); ?>
                        </div>

                        <div class="popular-services">
                            <h3><?php _e('خدماتنا الشائعة', 'arabic-local-services'); ?></h3>
                            <div class="services-grid">
                                <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                    <i class="fas fa-truck"></i>
                                    <span><?php _e('نقل الأثاث', 'arabic-local-services'); ?></span>
                                </a>
                                <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                    <i class="fas fa-crane"></i>
                                    <span><?php _e('ونش رفع العفش', 'arabic-local-services'); ?></span>
                                </a>
                                <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                    <i class="fas fa-broom"></i>
                                    <span><?php _e('تنظيف العفش', 'arabic-local-services'); ?></span>
                                </a>
                                <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                    <i class="fas fa-snowflake"></i>
                                    <span><?php _e('خدمات التكييف', 'arabic-local-services'); ?></span>
                                </a>
                                <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                    <i class="fas fa-wrench"></i>
                                    <span><?php _e('تسليك المجاري', 'arabic-local-services'); ?></span>
                                </a>
                            </div>
                        </div>

                        <div class="contact-info">
                            <h3><?php _e('معلومات التواصل', 'arabic-local-services'); ?></h3>
                            <div class="contact-details">
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
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
?>