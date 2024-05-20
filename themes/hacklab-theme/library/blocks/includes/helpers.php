<?php

namespace hacklabr;

/**
 * Builds a WP_Query args array to query posts for a block.
 *
 * This takes in the block attributes and an optional array of post IDs
 * to exclude. It returns a WP_Query args array to query posts according
 * to the attributes, excluding the given IDs.
 *
 * @param array $attributes Block attributes.
 * @param int Number of posts to show.
 * @param array $post__not_in Optional array of post IDs to exclude.
 * @return array WP_Query args array.
 */
function build_posts_query ($attributes, $posts_to_show, $post__not_in = []) {
    $compare = $attributes['compare'] ?: 'OR';
    $post_type = $attributes['postType'] ?: 'post';
    $taxonomy = $attributes['taxonomy'] ?: '';
    $query_terms = $attributes['queryTerms'] ?: [];
    $order = $attributes['order'] ?: 'desc';
    $order_by = $attributes['orderBy'] ?: 'date';
    $show_children = $attributes['showChildren'] ?: true;

    $no_compare = $attributes['noCompare'] ?: 'OR';
    $no_post_type = $attributes['noPostType'] ?: 'post';
    $no_taxonomy = $attributes['noTaxonomy'] ?: '';
    $no_query_terms = $attributes['noQueryTerms'] ?: [];

    $no_post__not_in = [];

    if ($no_post_type) {
        $no_args = [
            'post_type' => $no_post_type,
            'posts_per_page' => -1,
            'fields' => 'ids',
        ];

        if ($no_taxonomy && $no_query_terms) {
            $no_args['tax_query'] = ['relation' => $no_compare];

            foreach ($no_query_terms as $no_term) {
                $no_args['tax_query'][] = [
                    'taxonomy' => $no_taxonomy,
                    'field' => 'slug',
                    'terms' => [$no_term],
                ];
            }

            $no_post__not_in = get_posts($no_args);
        }
    }

    $args = [
        'ignore_sticky_posts' => true,
        'order' => $order,
        'order_by' => $order_by,
        'post_type' => $post_type,
        'posts_per_page' => $posts_to_show,
    ];

    if ($taxonomy && $query_terms) {
        $args['tax_query'] = ['relation' => $compare];

        foreach ($query_terms as $term) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => [$term],
            ];
        }
    }

    if (!$show_children) {
        $args['post_parent'] = 0;
    }

    $args['post__not_in'] = array_merge(
        $no_post__not_in,
        $post__not_in,
        get_the_ID() ? [get_the_ID()]: []
    );

    return $args;
}

function compute_block_transient_key ( string $namespace, array $attributes = [] ) {
    return $namespace . '_' . md5( serialize( $attributes ) );
}

/**
 * Deletes the transient matching the namespace and attributes.
 *
 * @param string $namespace The block id, or a more generic identifier.
 * @param array $attributes A dictionary of block attributes.
 * @return bool True if the transient was deleted, false otherwise.
 */
function delete_block_transient ( string $namespace, array $attributes = [] ) {
    return delete_transient( compute_block_transient_key( $namespace, $attributes ) );
}

/**
 * Retrives the value of the transient matching the namespace and attributes.
 *
 * If the transient does not exist, does not have a value, or has expired, then the return value will be false.
 *
 * @param string $namespace The block id, or a more generic identifier.
 * @param array $attributes A dictionary of block attributes.
 * @return mixed Value of the transient.
 */
function get_block_transient ( string $namespace, array $attributes = [] ) {
    return get_transient( compute_block_transient_key( $namespace, $attributes ) );
}

/**
 * Retrives the IDs of the posts already displayed by other Gutenberg blocks.
 *
 * @return array The list of post IDs.
 */
function get_used_post_ids () {
    global $newspack_blocks_post_id;
    global $hacklabr_blocks_post_ids;

    if ( ! $newspack_blocks_post_id ) {
        $newspack_blocks_post_id = [];
    }

    if ( ! $hacklabr_blocks_post_ids ) {
        $hacklabr_blocks_post_ids = [];
    }

    $post__not_in = array_merge( $hacklabr_blocks_post_ids, array_keys( $newspack_blocks_post_id ) );
    $post__not_in = array_unique( $post__not_in, SORT_STRING );

    return $post__not_in;
}

/**
 * Mark the post ID as displayed, so the same post won't appear in other Gutenberg posts.
 *
 * @param int $post_id The post ID.
 * @return void
 */
function mark_post_id_as_used ( int $post_id ) {
    global $newspack_blocks_post_id;
    global $hacklabr_blocks_post_ids;

    if ( ! $newspack_blocks_post_id ) {
        $newspack_blocks_post_id = [];
    }

    if ( ! $hacklabr_blocks_post_ids ) {
        $hacklabr_blocks_post_ids = [];
    }

    $hacklabr_blocks_post_ids[] = $post_id;
    $newspack_blocks_post_id[$post_id] = true;
}

/**
 * Sets/updates the value for the transient matching the namespace and attributes.
 *
 * @param string $namespace The block id, or a more generic identifier.
 * @param array $attributes A dictionary of block attributes.
 * @param mixed $value Transient value. Must be serializable if non-scalar.
 * @param int $expiration Time until expiration in seconds. If 0, no expiration.
 * @return bool True if the value was set, false otherwise.
 */
function set_block_transient ( string $namespace, array $attributes, $value, int $expiration = HOUR_IN_SECONDS ) {
    return set_transient( compute_block_transient_key( $namespace, $attributes ), $value, $expiration );
}
