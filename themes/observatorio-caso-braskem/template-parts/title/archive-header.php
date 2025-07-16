<?php

/**
 * Mount the title
 */
$title = '';

$count = (int) $wp_query->found_posts;
$results_text = ($count === 1) ? __('result', 'hacklabr') : __('results', 'hacklabr');
if ( is_search() ) {
    $title = sprintf(
        '%s <span class="search-page-results-count">(%d %s)</span>',
        __('SEARCH RESULT', 'hacklabr'),
        $count,
        $results_text
    );

} elseif ( is_category() || is_tag() || is_tax() ) {
    $title = sprintf(
        '%s <span class="search-page-results-count">(%d %s)</span>',
        __('SEARCH TERM', 'hacklabr'),
        $count,
        $results_text
    );
} elseif ( is_archive() ) {
    $title = get_the_archive_title();

} else {
    $title = get_the_title();
}


?>

<header class="archive-header__content">
    <div class="archive-header__background"></div>

    <div class="archive-header__content-overlay">
        <div class="archive-header__title-container">
            <div class="archive-header__title">
                <h1>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/exclamation-point.png" alt="exclamation-point" class="exclamation-icon">
                    <?php echo apply_filters( 'the_title' , $title ); ?>
                </h1>

                <?php if ( is_search() || ( is_category() ) || ( is_tag() ) || ( is_tax() ) ) : ?>
                    <div class="search-form-wrapper-on-archive">
                        <?php
                        get_search_form();
                        ?>
                        <button type="submit" class="search-submit-icon" form="main-search-page-form">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-icon-black.svg" alt="<?php esc_attr_e( 'Search', 'hacklabr' ); ?>">
                        </button>
                    </div>
                <?php endif; ?>

            </div>

            <?php
            if ( is_search() ) {
                get_template_part( 'template-parts/search-filters' );
            } elseif ( is_category() || is_tag() || is_tax() ) {
                get_template_part( 'template-parts/archive-term-filter' );
            }
            ?>
        </div>

        <div class="archive-header__excerpt">
            <?php the_archive_description(); ?>
        </div>

        <!-- <div class="archive-header__results">
            <p><span><?= $wp_query->found_posts;?></span><?php _e(' Results Found', 'hacklabr');?></p>
        </div> -->
    </div>
</header><!-- /.c-title.title-default -->
