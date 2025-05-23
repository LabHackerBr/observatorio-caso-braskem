<?php

namespace hacklabr;

/**
 * @todo refactor filters on search
 */
function join_meta_table( $join ) {
    global $wpdb;

    if ( is_search() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' hl_meta ON '. $wpdb->posts . '.ID = hl_meta.post_id ';
    }

    return $join;
}

// add_filter( 'posts_join', 'hacklabr\\join_meta_table' );

function modify_where_clause( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (hl_meta.meta_value LIKE $1)", $where );
    }
    return $where;
}

// add_filter( 'posts_where', 'hacklabr\\modify_where_clause' );

function prevent_duplicates( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }
    return $where;
}

// add_filter( 'posts_distinct', 'hacklabr\\prevent_duplicates' );

function post_types_in_search_results( $query ) {
    if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {

        $post_types = apply_filters( 'post_types_in_search_results', ['post', 'page'] );
        $query->set( 'post_type', $post_types );

        // Lógica para "Filtrar por" (Taxonomia)
        if ( isset( $_GET['filter_by_taxonomy'] ) && ! empty( $_GET['filter_by_taxonomy'] ) ) {
            $filter_value = sanitize_text_field( $_GET['filter_by_taxonomy'] );
            if ( strpos( $filter_value, ':' ) !== false ) {
                list( $taxonomy, $term_slug ) = explode( ':', $filter_value, 2 );

                if ( !empty( $taxonomy ) && !empty( $term_slug ) ) {
                    $tax_query = $query->get('tax_query') ? $query->get('tax_query') : array();
                    if ( !isset($tax_query['relation']) && count($tax_query) > 0 && count($tax_query) != count($tax_query, COUNT_RECURSIVE) ) {
                        $tax_query = array(
                            'relation' => 'AND',
                            $tax_query
                        );
                    } elseif(!isset($tax_query['relation'])) {
                        $tax_query['relation'] = 'AND';
                    }

                    $tax_query[] = array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'slug',
                        'terms'    => $term_slug,
                    );
                    $query->set( 'tax_query', $tax_query );
                }
            }
        }

        // Lógica para "Organizar por"
        if ( isset( $_GET['orderby'] ) && ! empty( $_GET['orderby'] ) ) {
            $orderby_value = sanitize_text_field( $_GET['orderby'] );
            $order = 'DESC';

            if ( isset( $_GET['order'] ) && in_array( strtoupper( $_GET['order'] ), array('ASC', 'DESC') ) ) {
                $order = strtoupper( $_GET['order'] );
            }

            switch ( $orderby_value ) {
                case 'date_desc':
                    $query->set( 'orderby', 'date' );
                    $query->set( 'order', 'DESC' );
                    break;
                case 'date_asc':
                    $query->set( 'orderby', 'date' );
                    $query->set( 'order', 'ASC' );
                    break;
                case 'title_asc':
                    $query->set( 'orderby', 'title' );
                    $query->set( 'order', 'ASC' );
                    break;
                case 'title_desc':
                    $query->set( 'orderby', 'title' );
                    $query->set( 'order', 'DESC' );
                    break;
                case 'relevance':
                default:
                    if ( $query->get('s') ) {
                         $query->set( 'orderby', 'relevance' );
                         $query->set( 'order', 'DESC');
                    } else {
                        $query->set( 'orderby', 'date' );
                        $query->set( 'order', 'DESC' );
                    }
                    break;
            }
        } else {
            if ( $query->get('s') ) {
                $query->set( 'orderby', 'relevance' );
                $query->set( 'order', 'DESC');
            } else {
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'DESC' );
            }
        }
    }
}
add_action( 'pre_get_posts', 'hacklabr\\post_types_in_search_results' );
