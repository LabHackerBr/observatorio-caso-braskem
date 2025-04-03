<?php

namespace hacklabr\v2;

require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/settings.php';

function events_from_mapas_culturais_callback( $attributes ) {

    $block_id       = ( ! empty( $attributes['blockId'] ) ) ? esc_attr( $attributes['blockId'] ) : '';
    $custom_class   = isset( $attributes['className'] ) ? sanitize_title( $attributes['className'] ) : '';
    $events_to_show = $attributes['eventsToShow'] ?? 6;
    $heading        = $attributes['heading'] ?? '';

    $block_classes[] = 'events-from-mapas-culturais-block';
    $block_classes[] = $custom_class;

    if ( ! $heading ) {
        $block_classes[] = 'without-title';
    }

    $block_classes = array_filter( $block_classes );

    $has_content  = false;
    $cached_posts = false;

    if ( ! is_admin() && ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) ) {
        $cached_posts = get_transient( $block_id );
    }

    if ( false === $cached_posts ) {
        $events_query = get_events_from_mapas_culturais( date( 'Y-m-d' ), date( 'Y-m-d', strtotime( '+15 days' ) ), $events_to_show );

        if ( $events_query ) {
            set_transient( $block_id, $events_query, 3600 );
            $has_content = $events_query;
        } else {
            if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
                return '<h2>' . __( 'No content found', 'hacklabr' ) . '</h2>';
            }

            return;
        }
    } else {
        $has_content = $cached_posts;
    }

    ob_start();

    echo '<div id="block__' . $block_id . '" class="' . implode( ' ', $block_classes ) . '">';

    if ( ! empty( $heading ) ) {
        echo '<div class="events-from-mapas-culturais-block__heading">';
            echo '<h2>' . \esc_html( $heading ) . '</h2>';
        echo '</div>';
    }

    echo '<div class="events-from-mapas-culturais-block__body">';

    foreach ( $has_content as $event ) {
        echo render_event( $event );
    }

    echo '</div><!-- .events-from-mapas-culturais-block__body -->';

    echo '</div><!-- .events-from-mapas-culturais-block -->';

    $output = ob_get_clean();

    return $output;
}
