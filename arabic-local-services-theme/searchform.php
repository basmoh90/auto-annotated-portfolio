<?php
/**
 * The template for displaying search forms
 *
 * @package Arabic_Local_Services
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field">
        <span class="screen-reader-text"><?php _e('البحث عن:', 'arabic-local-services'); ?></span>
    </label>
    <input type="search" id="search-field" class="search-field" placeholder="<?php echo esc_attr_x('البحث في الموقع...', 'placeholder', 'arabic-local-services'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit">
        <i class="fas fa-search"></i>
        <span class="screen-reader-text"><?php _e('البحث', 'arabic-local-services'); ?></span>
    </button>
</form>