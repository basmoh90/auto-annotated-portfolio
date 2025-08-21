<?php
/**
 * The template for displaying search results pages
 *
 * @package Arabic_Local_Services
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php if (have_posts()) : ?>
                    <header class="page-header">
                        <h1 class="page-title">
                            <?php
                            printf(
                                /* translators: %s: search query. */
                                esc_html__('نتائج البحث عن: %s', 'arabic-local-services'),
                                '<span>' . get_search_query() . '</span>'
                            );
                            ?>
                        </h1>
                    </header>

                    <div class="search-results">
                        <?php
                        while (have_posts()) :
                            the_post();
                            ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('search-result'); ?>>
                                <div class="row">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="col-md-3">
                                            <div class="post-thumbnail">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                    <?php else : ?>
                                        <div class="col-md-12">
                                    <?php endif; ?>
                                    
                                    <header class="entry-header">
                                        <h2 class="entry-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="entry-meta">
                                            <span class="posted-on">
                                                <i class="fas fa-calendar"></i>
                                                <?php echo get_the_date(); ?>
                                            </span>
                                            <span class="post-type">
                                                <i class="fas fa-file"></i>
                                                <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                                            </span>
                                        </div>
                                    </header>

                                    <div class="entry-summary">
                                        <?php the_excerpt(); ?>
                                    </div>

                                    <footer class="entry-footer">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                            <?php _e('اقرأ المزيد', 'arabic-local-services'); ?>
                                        </a>
                                    </footer>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        ?>
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
                    <section class="no-results not-found">
                        <header class="page-header">
                            <h1 class="page-title"><?php _e('لا توجد نتائج', 'arabic-local-services'); ?></h1>
                        </header>

                        <div class="page-content">
                            <p><?php _e('عذراً، لا توجد نتائج تطابق بحثك. يرجى المحاولة مرة أخرى بكلمات مختلفة.', 'arabic-local-services'); ?></p>
                            
                            <div class="search-suggestions">
                                <h3><?php _e('اقتراحات للبحث:', 'arabic-local-services'); ?></h3>
                                <ul>
                                    <li><?php _e('تأكد من كتابة الكلمات بشكل صحيح', 'arabic-local-services'); ?></li>
                                    <li><?php _e('جرب كلمات بحث مختلفة', 'arabic-local-services'); ?></li>
                                    <li><?php _e('استخدم كلمات أقل', 'arabic-local-services'); ?></li>
                                    <li><?php _e('تصفح الخدمات من القائمة الرئيسية', 'arabic-local-services'); ?></li>
                                </ul>
                            </div>
                            
                            <?php get_search_form(); ?>
                            
                            <div class="popular-services">
                                <h3><?php _e('خدماتنا الشائعة:', 'arabic-local-services'); ?></h3>
                                <div class="services-grid">
                                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                        <i class="fas fa-truck"></i>
                                        <?php _e('نقل الأثاث', 'arabic-local-services'); ?>
                                    </a>
                                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                        <i class="fas fa-crane"></i>
                                        <?php _e('ونش رفع العفش', 'arabic-local-services'); ?>
                                    </a>
                                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                        <i class="fas fa-broom"></i>
                                        <?php _e('تنظيف العفش', 'arabic-local-services'); ?>
                                    </a>
                                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="service-link">
                                        <i class="fas fa-snowflake"></i>
                                        <?php _e('خدمات التكييف', 'arabic-local-services'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
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