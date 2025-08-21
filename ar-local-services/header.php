<?php
/**
 * The header for our theme.
 *
 * @package ar-local-services
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<?php wp_body_open(); ?>
<header class="site-header" role="banner">
    <div class="container">
        <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title"><?php bloginfo( 'name' ); ?></a>
            <?php endif; ?>
        </div><!-- .site-branding -->

        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'ar-local-services' ); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">&#9776;</button>
            <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                ) );
            ?>
        </nav><!-- #site-navigation -->
    </div><!-- .container -->
</header><!-- .site-header -->

<!-- Sticky CTA Buttons -->
<div class="sticky-cta" style="<?php echo is_rtl() ? 'left:20px;' : 'right:20px;'; ?>">
    <?php $phone_number = get_option( 'ar_ls_phone', '+201234567890' ); ?>
    <a href="https://wa.me/<?php echo preg_replace( '/[^0-9]/', '', $phone_number ); ?>" class="cta-btn cta-whatsapp" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'تواصل عبر واتساب', 'ar-local-services' ); ?>">
        <span class="dashicons dashicons-whatsapp"></span>
    </a>
    <a href="tel:<?php echo esc_attr( $phone_number ); ?>" class="cta-btn cta-call" aria-label="<?php esc_attr_e( 'اتصل الآن', 'ar-local-services' ); ?>">
        <span class="dashicons dashicons-phone"></span>
    </a>
</div>