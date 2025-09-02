<?php
/**
 * The template for displaying all single posts
 */

get_header();
the_post();
$categories = get_the_category();
$excerpt = !empty( $post->post_excerpt ) ? wp_kses_post( $post->post_excerpt ) : '';
?>

<?php get_template_part('template-parts/share-links', null, ['link'=>get_the_permalink()]) ?>

<div class="single">

    <main class="post-content container">
        <?php the_content() ?>
    </main>

    <header class="post-header container">

        <h1 class="post-header__title"> <?php the_title(); ?> </h1>

        <?php if( $excerpt ) : ?>
            <p class="post-header__excerpt"><?= get_the_excerpt() ?></p>
        <?php endif; ?>

        <div class="post-header__author">
            <div class="post-header__published-container">
                <img class="post-header__author-image" src="<?= get_avatar_url(get_the_author_meta('ID')) ?>" alt="<?= get_the_author() ?>"/>
                <div class="post-header__author-published-date">
                    <p class="post-header__author-name"><?php _e('Published by ', 'hacklabr') ?><?= get_the_author() ?></p>
                    <p class="post-header__date"><?= get_the_date("j/m/Y")?></p>
                </div>
            </div>
        </div>
    </header>



</div>

<?php get_template_part('template-parts/content/related-posts') ?>




<?php get_footer();
