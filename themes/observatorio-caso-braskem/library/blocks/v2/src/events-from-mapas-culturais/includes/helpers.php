<?php

namespace hacklabr\v2;

function render_event( $event ) {
    $html = '';

    $start_date = new \DateTime( $event['starts']['date'] );

    $html .= '<div class="event-card">';
    $html .= '<div class="event-card__date">';
    $html .= '<div class="event-card__day">' . date_i18n( 'd', $start_date->getTimestamp() ) . '</div>';
    $html .= '<div class="event-card__month">' . date_i18n( 'M', $start_date->getTimestamp() ) . '</div>';
    $html .= '</div>';
    $html .= '<div class="event-card__content">';
    $html .= '<h3 class="event-card__title">' . $event['event']['name'] . '</h3>';
    $html .= '<p class="event-card__time">' . format_event_dates( $event['starts'], $event['ends'] ) . '</p>';
    $html .= '<p class="event-card__location">';
    $html .= format_space( $event['space'] );
    $html .= '</p>';
    $html .= '</div>';
    $html .= '<div class="event-card__image">';
    $html .= format_thumbnail( $event['event']['files'], $event['event']['name'] );
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

function format_event_dates( $starts, $ends ) {
    $start_date = new \DateTime( $starts['date'] );
    $end_date = new \DateTime( $ends['date'] );

    $today = new \DateTime();
    $tomorrow = ( clone $today )->modify( '+1 day' );

    $start_time = date_i18n( 'H:i', $start_date->getTimestamp() );
    $end_time = date_i18n( 'H:i', $end_date->getTimestamp() );

    if ( $start_date->format( 'Y-m-d' ) === $today->format( 'Y-m-d' ) ) {
        return __( 'Today', 'hacklabr' ) . ", $start_time - $end_time";
    }

    if ( $start_date->format( 'Y-m-d' ) === $tomorrow->format( 'Y-m-d' ) ) {
        return __( 'Tomorrow', 'hacklabr' ) . ", $start_time - $end_time";
    }

    if ( $start_date->format( 'Y-m-d' ) === $end_date->format( 'Y-m-d' ) ) {
        return sprintf(
            '%s, %s - %s',
            ucfirst( date_i18n( 'D.', $start_date->getTimestamp() ) ),
            date_i18n( 'H:i', $start_date->getTimestamp() ),
            $end_time
        );
    }

    return sprintf(
        '%s, %d de %s - %s, %d de %s',
        ucfirst( date_i18n( 'D.', $start_date->getTimestamp() ) ),
        (int) $start_date->format( 'd' ),
        date_i18n( 'F', $start_date->getTimestamp() ),
        ucfirst( date_i18n( 'D.', $end_date->getTimestamp() ) ),
        (int) $end_date->format( 'd' ),
        date_i18n( 'F', $end_date->getTimestamp() )
    );
}

function format_space( $space ) {
    $output = '';
    if ( ! empty( $space['name'] ) ) {
        $output .= $space['name'];
    }

    if ( ! empty( $space['endereco'] ) ) {
        if ( ! empty( $space['name'] ) ) {
            $output .= ', ';
        }

        $output .= $space['endereco'];
    }

    return $output;
}

function format_thumbnail( $files, $alt = '' ) {
    $output = '';

    if ( ! empty( $files['avatar']['transformations']['avatarBig'] ) ) {
        $output .= '<img src="' . $files['avatar']['transformations']['avatarBig']['url'] . '" alt="' . $alt . '" />';
    } elseif ( ! empty( $files['avatar']['transformations']['avatarMedium'] ) ) {
        $output .= '<img src="' . $files['avatar']['transformations']['avatarMedium']['url'] . '" alt="' . $alt . '" />';
    } elseif ( ! empty( $files['avatar']['transformations']['avatarSmall'] ) ) {
        $output .= '<img src="' . $files['avatar']['transformations']['avatarSmall']['url'] . '" alt="' . $alt . '" />';
    } elseif ( ! empty( $files['avatar']['url'] ) ) {
        $output .= '<img src="' . $files['avatar']['url'] . '" alt="' . $alt . '" />';
    }

    return $output;
}
