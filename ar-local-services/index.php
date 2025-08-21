<?php
/**
 * Main template file
 *
 * @package ar-local-services
 */
get_header();
?>
<main id="primary" class="site-main container" role="main">
    <?php if ( have_posts() ) : ?>
        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header class="page-header">
                <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php
        // Start the Loop.
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );

        endwhile;

        // Pagination.
        the_posts_pagination();
        ?>
    <?php else : ?>
        <?php get_template_part( 'template-parts/content', 'none' ); ?>
    <?php endif; ?>
</main><!-- #primary -->
<?php
get_footer();