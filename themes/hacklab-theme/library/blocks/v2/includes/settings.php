<?php

namespace hacklabr\v2;

add_action( 'admin_init', 'hacklabr\\v2\\register_settings_fields' );

function register_settings_fields() {
    register_setting(
        'general',
        'youtube_key',
        'esc_attr'
    );

    add_settings_field(
        'youtube_key',
        '<label for="youtube_key">' . __( 'YouTube API Key', 'hacklabr' ) . '</label>',
        'hacklabr\\v2\\youtube_key_html',
        'general'
    );
}

function youtube_key_html() {
    $youtube_key_option = sanitize_text_field( get_option( 'youtube_key', '' ) );
    echo '<input type="text" name="youtube_key" id="youtube_key" value="' . $youtube_key_option . '" autocomplete="off">';
    echo '<p><i>Crie uma chave de API do YouTube em <a href="https://console.cloud.google.com/apis/credentials">https://console.cloud.google.com/apis/credentials</a></i></p>';
}
