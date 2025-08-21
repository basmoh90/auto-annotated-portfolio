<?php
/**
 * Template for displaying single Service posts
 *
 * @package ar-local-services
 */
get_header();
?>
<main id="primary" class="site-main container" role="main">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'service-single' ); ?> >
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <figure class="featured-image">
                    <?php the_post_thumbnail( 'large' ); ?>
                </figure>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <footer class="entry-footer">
                <a href="tel:<?php echo esc_attr( get_option( 'ar_ls_phone', '+201234567890' ) ); ?>" class="btn"><?php esc_html_e( 'احجز خدمة', 'ar-local-services' ); ?></a>
            </footer>
        </article>

        <?php
        // If comments are open or we have at least one comment, load comments template.
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }

    endwhile; // End of the loop.
    ?>
</main><!-- #primary -->
<?php
get_footer();