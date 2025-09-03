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

        <?php
            $raw_value = get_post_meta( get_the_ID(), 'tipo_de_midia', true );

            $values = is_array($raw_value) ? $raw_value : ( $raw_value ? [ $raw_value ] : [] );
            $slugs  = [];

            foreach ( $values as $v ) {
                if ( is_array($v) ) {
                    if ( isset($v['slug']) )        $slugs[] = sanitize_title($v['slug']);
                    elseif ( isset($v['post_name']) ) $slugs[] = sanitize_title($v['post_name']);
                    elseif ( isset($v['name']) )      $slugs[] = sanitize_title($v['name']);
                } else {
                    $slugs[] = sanitize_title( (string) $v );
                }
            }
            $slugs = array_unique($slugs);

            $labels = [
                'texto'  => __('In this Text:', 'hacklabr'),
                'video'  => __('In this Video:', 'hacklabr'),
                'imagem' => __('In this Gallery:', 'hacklabr'),
                // 'audio'  => __('Neste Áudio:', 'hacklabr'),
            ];

            $label_to_print = null;
            foreach ( $slugs as $s ) {
                if ( isset($labels[$s]) ) { $label_to_print = $labels[$s]; break; }
            }

            if ( $label_to_print ) {
                echo '<span class="post-header__midia-label">' . esc_html($label_to_print) . '</span>';
            }
            ?>

        <?php if( $excerpt ) : ?>
            <p class="post-header__excerpt"><?= get_the_excerpt() ?></p>
        <?php endif; ?>

        <div class="post-header__author">
            <div class="post-header__published-container">
            <?php
                $coauthors = get_coauthors();
                foreach ( $coauthors as $author ) :
                    $avatar = get_avatar_url( $author->ID, ['size' => 96] );
                    $name   = $author->display_name;
                    $link   = get_author_posts_url( $author->ID, $author->user_nicename );
                ?>
                    <div class="post-header__published-container">
                        <img class="post-header__author-image" src="<?= esc_url($avatar) ?>" alt="<?= esc_attr($name) ?>"/>
                        <div class="post-header__author-published-date">
                            <p class="post-header__author-name">
                                <?php _e('By ', 'hacklabr') ?>
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
