<?php get_header(); ?>

<div class="error-404">
    <div class="error-404__header container">
    <h1 class="error-404__title"><?php _e('404', 'hacklabr') ?>
        <span class="error-404__title-symbol"><?php _e(':(', 'hacklabr') ?></span>
    </h1>
    </div>

    <div class="error-404__content container container--wide">
        <h2 class="error-404__subtitle">
            <span class="error-404__subtitle-exclamation">
                <?php _e('Oops', 'hacklabr') ?>
                <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/exclamation-point.png" alt="exclamation-point" class="exclamation-icon">
            </span>
            <?php _e("We couldn't find the page you tried to access!", 'hacklabr') ?>
        </h2>

        <p class="error-404__text"><?php _e("We couldn't find what you're looking for, type what you need in the search bar up there.", 'hacklabr') ?></p>

        <a href="<?php echo home_url(); ?>" class="error-404__button"><?php _e('BACK TO HOMEPAGE', 'hacklabr') ?></a>
    </div>
</div>

<?php get_footer(); ?>
