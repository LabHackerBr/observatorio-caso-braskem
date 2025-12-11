<?php
get_header();
?>
<div class="cpi-header">
    <div class="container--full">
        <div class="post-header post-header__separator container container--wide">
            <h1 class="sr-only"></h1>
            <h1 class="post-header__title">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/exclamation-point.png" alt="exclamation-point" class="exclamation-icon">
                <?php the_title() ?>
            </h1>
        </div>
    </div>
</div>
<div class="post-content content content--normal">
    <?php the_content() ?>
</div>
<?php get_footer();
