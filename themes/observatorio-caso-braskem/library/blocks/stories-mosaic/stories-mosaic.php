<?php

namespace hacklabr;

function render_stories_mosaic_callback( $attributes ) {
    add_action('wp_footer', function () { ?>
        <div id="stories-modal">
            <div class="story-preview previous-story"></div>
            <button type="button" class="close-modal">
                <svg role="img" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <rect width="16" height="2" x="0" y="2"/>
                    <rect width="16" height="2" x="0" y="12"/>
                </svg>
            </button>
            <div class="story-preview next-story"></div>
        </div><?php
    });

    global $newspack_blocks_post_id;
    global $latest_blocks_posts_ids;

    $block_id        = esc_attr( $attributes['blockId'] );
    $block_classes[] = 'stories-mosaic-block';

    $link          = ( ! empty( $attributes['linkUrl'] ) ) ? esc_url( $attributes['linkUrl'] ) : false;

    $custom_class    = isset( $attributes['className'] ) ? sanitize_title( $attributes['className'] ) : '';
    $block_classes[] = $custom_class;

    $block_classes = array_filter( $block_classes );

    $has_content = false;

    $counter = 0;

    // Determina quantos posts serão exibidos em cada slide
    $posts_to_show  = $attributes['postsToShow'] ?? 11;

        // Posts
        $attributes_hash = md5( $block_id );

        $cache_key = 'hacklabr_vertical_' . $attributes_hash;
        $cached_posts = false;

        if ( ! is_admin() && ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) ) {
            $cached_posts = get_transient( $cache_key );
        }

        if ( is_archive() || false === $cached_posts ) {
            $post__not_in = [];

        if ( ! is_archive() ) {
            $post__not_in = array_merge( $latest_blocks_posts_ids, array_keys( $newspack_blocks_post_id ) );
            $post__not_in = array_unique( $post__not_in, SORT_STRING );
        }
            // Normalize attributes before calling `build_posts_query`
        $query_attributes = normalize_posts_query($attributes);
        $query_attributes['postsPerPage'] = $posts_to_show;
        $args = build_posts_query( $query_attributes, $post__not_in );

        if ( is_archive() ) {
            $queried_object = get_queried_object();
            $taxonomy = $queried_object->taxonomy;
            $term_id = $queried_object->term_id;

            $tax_query = [
                [
                    'field'    => 'term_id',
                    'taxonomy' => $taxonomy,
                    'terms'    => [$term_id]
                ]
            ];

            $args['tax_query'] = $tax_query;
        }

        $posts_query = new \WP_Query( $args );

        if ( false === $posts_query->have_posts() ) {
            if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                return '<h2>'. __( 'No content found', 'hacklabr' ). '</h2>';
            }

            return;
        }

        set_transient( $cache_key, $posts_query, 3600 );
    } else {
        $posts_query = $cached_posts;
    }

    $has_content = $posts_query;


    if ( ! $has_content ) {
        if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return '<h2>' . __( 'No content found', 'hacklabr' ) . '</h2>';
        }

        return;
    }

    ob_start();

    // Start the block structure
    echo '<div id="block__' . $block_id . '" class="' . implode( ' ', $block_classes ) . ' dragscroll">';

    $heading = $attributes['heading'] ?? '';

    echo '<div class="stories-mosaic-block__heading">';
        if ( ! empty( $link ) ) {
            echo '<h2><a href="' . esc_url( $link ) . '">' . $heading . '</a></h2>';
        } else {
            echo '<h2>' . $heading . '</h2>';
        }
    echo '</div>';


    if ( $has_content->have_posts() ) :

        $attributes['counter_posts'] = 0;

        while ( $has_content->have_posts() ) :
            $has_content->the_post();
            global $post;

            $latest_blocks_posts_ids[] = $post->ID;
            $newspack_blocks_post_id[$post->ID] = true;
            $counter++;

            if ( $counter == 1 ) {
                echo "<div class='inner'>";
            }

            $attributes['counter_posts']++;

            get_template_part( 'library/blocks/stories-mosaic/template-parts/post', '', ['post' => $post, 'attributes' => $attributes] );

            if ( $counter == 10 ) {
                echo "</div>";
                echo "<div class='inner inner--more'>";
            }

        endwhile;

        echo "</div>";

    endif;

    wp_reset_postdata();


    echo '</div><!-- .stories-mosaic-block -->';

    $output = ob_get_clean();

    return $output;

}
