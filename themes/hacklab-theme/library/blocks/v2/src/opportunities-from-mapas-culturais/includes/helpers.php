<?php

namespace hacklabr\v2;

function build_oportunidades_api_url( $api_url, $select = null, $order = null, $limit = 20, $page = 1 ) {
    $query_params = [];

    $query_params[] = '%40select=' . sanitize_encode( $select ? $select : 'name,type,shortDescription,files.avatar,seals,terms,registrationFrom,registrationTo' );
    $query_params[] = '%40order=' . sanitize_encode( $order ? $order : 'createTimestamp DESC' );
    $query_params[] = '%40limit=' . intval( $limit );
    $query_params[] = '%40page=' . intval( $page );

    return esc_url( $api_url ) . '?' . implode( '&', $query_params );
}

function get_mc_opportunities( $limit ) {
    $base_url = get_option( 'mapas_culturais_url', '' );

    $base_url = rtrim( $base_url, '/' ) . '/';
    $api_url = $base_url . 'api/opportunity/find';

    $url = build_oportunidades_api_url( $api_url, null, null, $limit );

    $response = wp_remote_get( $url );

    if ( is_wp_error( $response ) ) {
        do_action( 'logger', [
            'line'    => __LINE__,
            'file'    => __FILE__,
            'message' => 'Erro ao buscar eventos do Mapas Culturais: ' . $response->get_error_message(),
        ] );
    } else {
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        return $data;
    }

    return false;
}

function render_opportunity( $entity ) {
    $html = '';
    $html .= '<article class="mc-card__item">';

    if ( ! empty( $entity['terms']['area'][0] ) ) {
        $html .= '<div class="mc-card__tag">';
        $html .= '<span class="mc-card__tag-label">' . $entity['terms']['area'][0] . '</span>';
        $html .= '</div>';
    }

    if ( ! empty( $entity['files']['avatar']['transformations']['avatarBig']['url'] ) ) {
        $html .= '<img class="mc-card__image" src="' . $entity['files']['avatar']['transformations']['avatarBig']['url'] . '" alt="' . $entity['name'] . '">';
    }

    $html .= '<h3 class="mc-card__item-title">';
    $html .= $entity['name'];
    $html .= '</h3>';
    $html .= '<div class="mc-card__info">';
    $html .= '<span class="mc-card__date">' . format_registration_dates( $entity['registrationFrom'], $entity['registrationTo'] ) . '</span>';
    $html .= '</div>';
    $html .= '</article>';

    return $html;
}

function format_registration_dates( $from, $to ) {
    $fromDateTime = new \DateTime( $from['date'], new \DateTimeZone( $from['timezone'] ) );
    $toDateTime = new \DateTime( $to['date'], new \DateTimeZone( $to['timezone'] ) );

    $formattedFrom = $fromDateTime->format( 'd/m/Y ');
    $formattedTo = $toDateTime->format( 'd/m/Y' );

    $today = new \DateTime( 'now', new \DateTimeZone( $from['timezone'] ) );

    if ( $toDateTime->format( 'Y-m-d' ) === $today->format( 'Y-m-d' ) ) {
        return "De {$formattedFrom} até hoje";
    } elseif ( $toDateTime->modify( '+1 day' )->format( 'Y-m-d' ) === $today->format( 'Y-m-d' ) ) {
        return "De {$formattedFrom} até amanhã";
    } else {
        return "De {$formattedFrom} até {$formattedTo}";
    }
}
