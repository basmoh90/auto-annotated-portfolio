    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php _e('معلومات التواصل', 'arabic-local-services'); ?></h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <p><?php echo esc_html(get_theme_mod('business_phone', '+966501234567')); ?></p>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <p><?php echo esc_html(get_theme_mod('business_email', 'info@example.com')); ?></p>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <p><?php echo esc_html(get_theme_mod('business_address', 'شارع الملك فهد')); ?><br>
                               <?php echo esc_html(get_theme_mod('business_city', 'الرياض')); ?></p>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <p><?php echo esc_html(get_theme_mod('business_hours', 'Mo-Su 08:00-18:00')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="footer-section">
                    <h3><?php _e('خدماتنا', 'arabic-local-services'); ?></h3>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('نقل الأثاث', 'arabic-local-services'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('ونش رفع العفش', 'arabic-local-services'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('تنظيف العفش', 'arabic-local-services'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('خدمات التكييف', 'arabic-local-services'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('تسليك المجاري', 'arabic-local-services'); ?></a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3><?php _e('روابط سريعة', 'arabic-local-services'); ?></h3>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('الرئيسية', 'arabic-local-services'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php _e('من نحن', 'arabic-local-services'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('الخدمات', 'arabic-local-services'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('اتصل بنا', 'arabic-local-services'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/faq/')); ?>"><?php _e('الأسئلة الشائعة', 'arabic-local-services'); ?></a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3><?php _e('تابعنا', 'arabic-local-services'); ?></h3>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                    
                    <div class="footer-cta">
                        <h4><?php _e('احصل على عرض سعر مجاني', 'arabic-local-services'); ?></h4>
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary">
                            <?php _e('اتصل الآن', 'arabic-local-services'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('جميع الحقوق محفوظة', 'arabic-local-services'); ?></p>
                    <p><?php _e('تصميم وتطوير بواسطة', 'arabic-local-services'); ?> <a href="#"><?php _e('شركة الخدمات المحلية', 'arabic-local-services'); ?></a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/<?php echo esc_attr(str_replace('+', '', get_theme_mod('whatsapp_number', '+966501234567'))); ?>?text=<?php echo urlencode(__('مرحباً، أريد معلومات عن خدماتكم', 'arabic-local-services')); ?>" 
       class="whatsapp-button" 
       target="_blank" 
       rel="noopener noreferrer"
       aria-label="<?php _e('تواصل معنا عبر الواتساب', 'arabic-local-services'); ?>">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Call Button -->
    <a href="tel:<?php echo esc_attr(get_theme_mod('business_phone', '+966501234567')); ?>" 
       class="call-button"
       aria-label="<?php _e('اتصل بنا', 'arabic-local-services'); ?>">
        <i class="fas fa-phone"></i>
    </a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>