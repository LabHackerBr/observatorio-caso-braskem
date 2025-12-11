<?php
get_header();
?>
<div class="who-we-are has-primary-dark-background-color">
    <div class="container container--wide">
        <div class="post-header post-header__separator">
            <h1 class="sr-only"></h1>
            <h1 class=" post-header__title">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/exclamation-point.png" alt="exclamation-point" class="exclamation-icon">
                <?php the_title() ?>
            </h1>
        </div>
        <div class="post-content content content--normal">
            <?php the_content() ?>
        </div>
    </div>
</div>
<?php get_footer();
