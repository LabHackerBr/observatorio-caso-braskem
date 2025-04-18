<?php

namespace hacklabr\v2;

function columnists_get_contents( $block_id ) {
    $cache_key = 'hacklabr_columnists_'  . $block_id;
    $cached_data = get_transient( $cache_key );

    if ( false !== $cached_data ) {
        return $cached_data;
    }

    $data = [];

    $args = [
        'echo'                    => false,
        'number'                  => 999,
        'authors_with_posts_only' => true,
        'orderby'                 => 'count'
    ];

    $authors = coauthors_get_users( $args );
    shuffle( $authors );

    if ( ! empty( $authors ) ) {
        foreach ( array_slice( $authors, 0, 10 ) as $author ) {
            $data[] = $author;
        }
    }

    if ( ! empty( $data ) ) {
        set_transient( $cache_key, $data, 3600 );
    }

    return $data;
}
