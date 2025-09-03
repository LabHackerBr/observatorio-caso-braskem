<?php

get_header();
the_post();
$categories = get_the_category();
$excerpt = !empty( $post->post_excerpt ) ? wp_kses_post( $post->post_excerpt ) : '';
?>

<?php get_template_part('template-parts/share-links', null, ['link'=>get_the_permalink()]) ?>

<div class="biblioteca-content">

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
            <?php
                $coauthors = get_coauthors(); // retorna todos os autores (users + guest-authors)
                foreach ( $coauthors as $author ) :
                    $avatar = get_avatar_url( $author->ID, ['size' => 96] ); // pega o avatar
                    $name   = $author->display_name; // nome público
                    $link   = get_author_posts_url( $author->ID, $author->user_nicename ); // link p/ página do autor
                ?>
                    <div class="post-header__published-container">
                        <img class="post-header__author-image" src="<?= esc_url($avatar) ?>" alt="<?= esc_attr($name) ?>"/>
                        <div class="post-header__author-published-date">
                            <p class="post-header__author-name">
                                <?php _e('Published by ', 'hacklabr') ?>
                                <a href="<?= esc_url($link) ?>"><?= esc_html($name) ?></a>
                            </p>
                            <p class="post-header__date"><?= get_the_date("j/m/Y")?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </header>



</div>

<?php get_template_part('template-parts/content/related-posts') ?>




<?php get_footer();
