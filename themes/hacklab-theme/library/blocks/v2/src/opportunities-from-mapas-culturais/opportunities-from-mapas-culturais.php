<?php

namespace hacklabr\v2;

require __DIR__ . '/includes/helpers.php';

function opportunities_callback( $attributes ) {

    $block_id      = ( ! empty( $attributes['blockId'] ) ) ? esc_attr( $attributes['blockId'] ) : '';
    $custom_class  = isset( $attributes['className'] ) ? sanitize_title( $attributes['className'] ) : '';
    $items_to_show = $attributes['itemsToShow'] ?? 4;
    $heading       = $attributes['heading'] ?? '';

    $block_classes[] = 'opportunities-from-mapas-culturais-block';
    $block_classes[] = $custom_class;

    if ( ! $heading ) {
        $block_classes[] = 'without-title';
    }

    $block_classes = array_filter( $block_classes );

    $has_content  = false;
    $cached_items = false;

    if ( ! is_admin() && ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) ) {
        $cached_items = get_transient( $block_id );
    }

    if ( false === $cached_items ) {
        $query_items = get_mc_opportunities( $items_to_show );

        if ( $query_items ) {
            set_transient( $block_id, $query_items, 3600 );
            $has_content = $query_items;
        } else {
            if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                return '<h2>' . __( 'No content found', 'hacklabr' ) . '</h2>';
            }

            return;
        }
    } else {
        $has_content = $cached_items;
    }

    ob_start();

    echo '<div id="block__' . $block_id . '" class="' . implode( ' ', $block_classes ) . '">';

    if ( ! empty( $heading ) ) {
        echo '<div class="opportunities-from-mapas-culturais-block__heading">';
            echo '<h2>' . \esc_html( $heading ) . '</h2>';
        echo '</div>';
    }

    echo '<div class="opportunities-from-mapas-culturais-block__body">';

    foreach ( $has_content as $opportunity ) {
        echo render_opportunity( $opportunity );
    }

    echo '</div><!-- .opportunities-from-mapas-culturais-block__body -->';

    echo '</div><!-- .opportunities-from-mapas-culturais-block -->';

    $output = ob_get_clean();

    return $output;
}
