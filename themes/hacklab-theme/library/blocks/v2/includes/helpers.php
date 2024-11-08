<?php

namespace hacklabr\v2;

/**
 * Builds a WP_Query args array to query posts for a block.
 *
 * This takes in the block attributes and an optional array of post IDs
 * to exclude. It returns a WP_Query args array to query posts according
 * to the attributes, excluding the given IDs.
 *
 * @param array $attributes Block attributes.
 * @param array $post__not_in Optional array of post IDs to exclude.
 * @return array WP_Query args array.
 */
function build_posts_query( $attributes, $post__not_in = [] ) {

    $compare       = ! empty( $attributes['compare'] ) ? $attributes['compare'] : 'OR';
    $post_type     = isset( $attributes['postType'] ) ? $attributes['postType'] : 'post';
    $posts_to_show = isset( $attributes['postsToShow'] ) ? intval( $attributes['postsToShow'] ) : 3;
    $taxonomy      = isset( $attributes['taxonomy'] ) ? $attributes['taxonomy'] : '';
    $query_terms   = isset( $attributes['queryTerms'] ) ? $attributes['queryTerms'] : [];
    $return_ids    = isset( $attributes['returnIds'] ) ? $attributes['returnIds'] : false;
    $show_children = ! empty( $attributes['showChildren'] );

    // Exclude posts
    $no_compare     = ! empty( $attributes['noCompare'] ) ? $attributes['noCompare'] : 'OR';
    $no_post_type   = ! empty( $attributes['noPostType'] ) ? $attributes['postType'] : '';
    $no_taxonomy    = ! empty( $attributes['noTaxonomy'] ) ? $attributes['noTaxonomy'] : '';
    $no_query_terms = ! empty( $attributes['noQueryTerms'] ) ? $attributes['noQueryTerms'] : [];

    $no_post__not_in = [];

    if ( $no_post_type ) {
        $no_args = [
            'post_type'      => $no_post_type,
            'posts_per_page' => -1,
            'fields'         => 'ids'
        ];

        if ( $no_taxonomy && $no_query_terms ) {
            $no_args['tax_query'] = ['relation' => $no_compare];

            foreach ( $no_query_terms as $no_term ) {
                $no_args['tax_query'][] = [
                    'taxonomy' => $no_taxonomy,
                    'field'    => 'term_id',
                    'terms'    => [$no_term['id']]
                ];
            }

            $no_post__not_in = get_posts( $no_args );
        }
    }

    $order    = isset( $attributes['order'] ) ? $attributes['order'] : 'desc';
    $order_by = isset( $attributes['orderBy'] ) ? $attributes['orderBy'] : 'date';

    $args = [
        'ignore_sticky_posts' => true,
        'order'               => $order,
        'orderby'             => $order_by,
        'post_type'           => $post_type,
        'posts_per_page'      => $posts_to_show
    ];

    if ( $taxonomy && $query_terms ) {
        $args['tax_query'] = ['relation' => $compare];

        foreach ( $query_terms as $term ) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => [$term['id']]
            ];
        }
    }

    if ( $return_ids ) {
        $args['fields'] = 'ids';
    }

    if ( ! $show_children ) {
        $args['post_parent'] = 0;
    }

    $args['post__not_in'] = array_merge(
        $no_post__not_in,
        $post__not_in,
        get_the_ID() ? [get_the_ID()] : []
    );

    return $args;

}

function filter_save_post( $post_id, $post ) {
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    clear_block_transients( $post, 'hacklabr/latest-horizontal-posts', 'hacklabr_horizontal_' );
    clear_block_transients( $post, 'hacklabr/latest-horizontal-posts', 'hacklabr_columnists_' );
    clear_block_transients( $post, 'hacklabr/latest-vertical-posts', 'hacklabr_vertical_' );
}

add_action( 'save_post', 'hacklabr\\v2\\filter_save_post', 10, 2 );
add_action( 'delete_post', 'hacklabr\\v2\\filter_save_post', 10, 2 );

function clear_block_transients( $post, $block_name, $transient_name ) {
    if ( has_block( $block_name, $post ) ) {
        global $wpdb;

        $transient_name = '_transient_' . $transient_name;
        $cache_keys = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '$transient_name%'" );

        foreach ( $cache_keys as $key ) {
            $transient_name = str_replace( '_transient_', '', $key );
            $delete_transient = delete_transient( $transient_name );
        }
    }
}

/**
 * Get the primary term of a given taxonomy
 * @param int $post_id Post ID
 * @param string $taxonomy Taxonomy slug
 * @param bool $force_primary If should avoid returning fallback if the primary term is not set
 * @return \WP_Term|false The primary term, or `false` on failure
 */
function get_primary_term( $post_id, $taxonomy, $force_primary = false ) {
    $primary_term_id = get_post_meta( $post_id, '_yoast_wpseo_primary_' . $taxonomy, true );

    // Returns the primary term, if it exists
    if ( ! empty( $primary_term_id ) ) {
        $primary_term = get_term( $primary_term_id, $taxonomy );

        if ( ! empty( $primary_term ) ) {
            return $primary_term;
        }
    }

    // Returns an assorted term, if primary term does not exists
    if ( ! $force_primary ) {
        $terms = get_the_terms( $post_id, $taxonomy );

        if ( ! empty( $terms ) ) {
            return $terms[0];
        }
    }

    // On failure, returns false
    return false;
}

/**
 * Retorna a data formatada como "x tempo atrás" ou no formato especificado
 *
 * @param string $date_format Formato da data, padrão 'd M Y'
 * @return string A data formatada
*/
function get_the_time_ago( $date_format = 'd M Y' ) {

    if ( get_the_time( 'U' ) >= strtotime( '-1 week' ) ) {
        return sprintf(
            esc_html__( '%s ago', 'hacklabr' ),
            human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) )
        );
    } else {
        return get_the_date( $date_format );
    }

}

if ( function_exists( 'get_coauthors' ) && ! function_exists( 'get_list_coauthors' ) ) {
    /**
     * Get list of coauthors using Co Authors Plus plugin.
     */
    function get_list_coauthors( $post_id = 0 )
    {
        $all_authors = \get_coauthors( $post_id );

        $output = '';

        $count_authors = count($all_authors);
        $i = 0;

        foreach ($all_authors as $author) {
            $i++;
            if (is_a($author, 'WP_User')) {
                $output .= '<span>' . $author->data->display_name . '</span>';
            } else {
                $output .= '<span>' . $author->display_name . '</span>';
            }

            if ($i < $count_authors) {
                $output .= '<span class="comma">, </span>';
            }
        }

        return $output;
    }
}

/**
 * Splits a Flickr title into its components.
 *
 * The title is expected to be in the format "Title • Location | Date", where the location and date are optional.
 *
 * @param string $title The Flickr title to split.
 * @return array An array containing the title, location, and date components of the title. If the title cannot be split, the array will contain the original title and two false values.
 */
function split_hacklabr_flickr_title( $title ) {
    $separators = [ ' • ', ' · ', ' | ' ];
    foreach ( $separators as $separator ) {
        $parts = explode( $separator, $title );
        $count = count( $parts );
        if ( $count > 3 ) {
            return [ implode( $separator, array_slice( $parts, 0, $count - 2 ) ), $parts[ $count - 2 ], $parts[ $count - 1 ] ];
        } else if ( $count === 3 ) {
            return $parts;
        } else if ( $count === 2 ) {
            return [ $parts[0], $parts[1], false ];
        }
    }
    return [ $title, false, false ]; // Name couldn't be split
}

/**
 * Counts the number of posts for a given guest author.
 *
 * If the provided `$guest_author_slug` does not start with 'cap-', it will be prefixed with 'cap-'.
 * The function then retrieves the term object for the given author slug, and returns the count of posts
 * associated with that term. If the term is not found, 0 is returned.
 *
 * @param string $guest_author_slug The slug of the guest author to count posts for.
 * @return int The number of posts associated with the given guest author.
 */
function count_guest_author_posts( $guest_author_slug ) {
    if ( strpos( $guest_author_slug, 'cap-') !== 0 ) {
        $guest_author_slug = 'cap-' . $guest_author_slug;
    }

    $term = get_term_by( 'slug', $guest_author_slug, 'author' );

    if ( $term ) {
        return $term->count;
    }

    return 0;
}
