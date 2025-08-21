<?php
/**
 * The template for displaying the footer
 *
 * @package ar-local-services
 */
?>
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <p class="footer-branding">&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
        <p class="footer-contact">
            <?php
            $phone_number = get_option( 'ar_ls_phone', '+201234567890' );
            $city        = get_option( 'ar_ls_city', 'القاهرة' );
            $region      = get_option( 'ar_ls_region', 'القاهرة' );
            ?>
            <span class="footer-phone"><a href="tel:<?php echo esc_attr( $phone_number ); ?>"><?php echo esc_html( $phone_number ); ?></a></span> |
            <span class="footer-address"><?php echo esc_html( $city . '، ' . $region ); ?></span>
        </p>
        <?php
        if ( has_nav_menu( 'footer' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'footer',
                'menu_id'        => 'footer-menu',
                'depth'          => 1,
            ) );
        }
        ?>
    </div>
</footer><!-- .site-footer -->
<?php wp_footer(); ?>
</body>
</html>