<?php

namespace hacklabr;

function register_file_upload_assets() {
    // Registrar o script
    wp_register_script(
        'hacktlabr-file-upload',
        get_template_directory_uri() . '/assets/javascript/functionalities/file-upload.js',
        array(),
        filemtime(get_template_directory() . '/assets/javascript/functionalities/file-upload.js'),
        true
    );

    // Enfileirar o script
    wp_enqueue_script('hacktlabr-file-upload');

    // Garantir que Dashicons esteja carregado
    wp_enqueue_style('dashicons');
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\register_file_upload_assets');
