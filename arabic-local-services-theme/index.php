<?php
/**
 * The main template file
 *
 * @package Arabic_Local_Services
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (is_home() && !is_front_page()) : ?>
        <header class="page-header">
            <div class="container">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </div>
        </header>
    <?php endif; ?>

    <?php if (have_posts()) : ?>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <?php
                    while (have_posts()) :
                        the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?>>
                            <header class="entry-header">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('large'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
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
                                </div>
                            </header>

                            <div class="entry-content">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php _e('اقرأ المزيد', 'arabic-local-services'); ?>
                                </a>
                            </footer>
                        </article>
                        <?php
                    endwhile;
                    ?>

                    <?php
                    // Pagination
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => '<i class="fas fa-chevron-right"></i> ' . __('السابق', 'arabic-local-services'),
                        'next_text' => __('التالي', 'arabic-local-services') . ' <i class="fas fa-chevron-left"></i>',
                    ));
                    ?>

                </div>

                <div class="col-md-4">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>

    <?php else : ?>
        <div class="container">
            <section class="no-results not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('لا يوجد محتوى', 'arabic-local-services'); ?></h1>
                </header>

                <div class="page-content">
                    <?php if (is_search()) : ?>
                        <p><?php _e('عذراً، لا توجد نتائج تطابق بحثك. يرجى المحاولة مرة أخرى بكلمات مختلفة.', 'arabic-local-services'); ?></p>
                        <?php get_search_form(); ?>
                    <?php elseif (is_home() && current_user_can('publish_posts')) : ?>
                        <p>
                            <?php
                            printf(
                                wp_kses(
                                    __('جاهز لنشر أول منشور؟ <a href="%1$s">ابدأ هنا</a>.', 'arabic-local-services'),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ),
                                esc_url(admin_url('post-new.php'))
                            );
                            ?>
                        </p>
                    <?php else : ?>
                        <p><?php _e('يبدو أننا لا نستطيع العثور على ما تبحث عنه. ربما يمكن للبحث أن يساعد.', 'arabic-local-services'); ?></p>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    <?php endif; ?>

</main>

<?php
get_sidebar();
get_footer();