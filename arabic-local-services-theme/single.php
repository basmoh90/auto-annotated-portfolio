<?php
/**
 * The template for displaying all single posts
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
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                        <header class="entry-header">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo get_the_date(); ?>
                                </span>
                                <span class="byline">
                                    <i class="fas fa-user"></i>
                                    <?php the_author(); ?>
                                </span>
                                <?php if (has_category()) : ?>
                                    <span class="cat-links">
                                        <i class="fas fa-folder"></i>
                                        <?php the_category(', '); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (has_tag()) : ?>
                                    <span class="tags-links">
                                        <i class="fas fa-tags"></i>
                                        <?php the_tags('', ', '); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>

                        <div class="entry-content">
                            <?php
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . __('الصفحات:', 'arabic-local-services'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <footer class="entry-footer">
                            <?php
                            // Post navigation
                            the_post_navigation(array(
                                'prev_text' => '<i class="fas fa-chevron-right"></i> %title',
                                'next_text' => '%title <i class="fas fa-chevron-left"></i>',
                            ));
                            ?>
                        </footer>
                    </article>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>

                <?php endwhile; ?>
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