<?php
/**
 * The template for displaying all single posts
 */

get_header();
the_post();
$categories = get_the_category();
$excerpt = !empty( $post->post_excerpt ) ? wp_kses_post( $post->post_excerpt ) : '';
?>

<div class="single">
    <div class="single__background"></div>

    <div class="single__content-overlay">
        <header class="post-header container">
            <div class="post-header__featured-image">
                <?= get_the_post_thumbnail(null, 'post-thumbnail',['class'=>'post-header__featured-image']); ?>
            </div>

            <div class="post-header__tags">
            <?php
                    if( !empty($categories) ){
                        foreach($categories as $category){ ?>
                            <a class="tag tag--<?= $category->slug ?>" href="<?= get_term_link($category, 'category') ?>">
                                <?= $category->name ?>
                            </a>
                        <?php
                        }
                    }
                ?>
            </div>

            <h1 class="post-header__title"> <?php the_title(); ?> </h1>

            <?php if( $excerpt ) : ?>
                <p class="post-header__excerpt"><?= get_the_excerpt() ?></p>
            <?php endif; ?>

            <!-- <div class="post-header__meta container">
                <?php get_template_part('template-parts/share-links', null, ['link'=>get_the_permalink()]) ?>
            </div> -->

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

        <main class="post-content container">
            <?php the_content() ?>
        </main>
    </div>
</div>


<?php get_template_part('template-parts/content/related-posts') ?>

<?php get_footer();
