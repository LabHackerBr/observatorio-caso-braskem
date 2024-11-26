<?php

namespace hacklabr\v2;

require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/settings.php';
require __DIR__ . '/includes/api.php';

function blocks_init() {
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // Add compatibility for Newspack blocks.
    global $newspack_blocks_post_id;

    if ( ! $newspack_blocks_post_id ) {
        $newspack_blocks_post_id = [];
    }

    global $latest_blocks_posts_ids;

    if ( ! $latest_blocks_posts_ids ) {
        $latest_blocks_posts_ids = [];
    }

    $active_blocks = [
        'events-from-mapas-culturais' => [
            'render_callback' => 'hacklabr\v2\events_from_mapas_culturais_callback'
        ],
        'latest-horizontal-posts' => [
            'render_callback' => 'hacklabr\v2\latest_horizontal_posts_callback'
        ],
        'latest-vertical-posts' => [
            'render_callback' => 'hacklabr\v2\latest_vertical_posts_callback'
        ]
    ];

    $active_blocks = apply_filters( 'hacklabr/v2/active_blocks', $active_blocks );

    foreach ( $active_blocks as $block_name => $block_args ) {
        $args = [];

        if ( $block_args ) {
            $file = __DIR__ . '/src/' . $block_name . '/' . $block_name . '.php';

            if ( file_exists( $file ) ) {
                include_once $file;
                foreach ( $block_args as $arg => $value ) {
                    $args[$arg] = $value;
                }
            }
        }

        register_block_type( __DIR__ . '/build/'. $block_name, $args );
    }
}

add_action( 'init', 'hacklabr\v2\blocks_init' );
