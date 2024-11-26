<?php

namespace hacklabr\v2;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'hacklabr\v2\add_settings_page' );

function add_settings_page() {
    add_options_page(
        __( 'Configurações de integração com o Mapas Culturais', 'hacklabr' ),
        __( 'Mapas Culturais', 'hacklabr' ),
        'manage_options',
        'mapas-culturais-settings',
        'hacklabr\v2\mapas_culturais_render_settings_page',
        'dashicons-admin-generic',
        80
    );
}

function mapas_culturais_render_settings_page() {
    if ( isset( $_POST['mapas_culturais_url_nonce'] ) && wp_verify_nonce( $_POST['mapas_culturais_url_nonce'], 'save_mapas_culturais_url' )) {
        update_option( 'mapas_culturais_url', sanitize_text_field( $_POST['mapas_culturais_url'] ) );
        echo '<div class="updated"><p>' . __( 'Configurações salvas com sucesso!', 'hacklabr' ) . '</p></div>';
    }

    $api_key = get_option( 'mapas_culturais_url', '' );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Configurações de integração com o Mapas Culturais', 'hacklabr' ); ?></h1>
        <form method="post">
            <?php wp_nonce_field( 'save_mapas_culturais_url', 'mapas_culturais_url_nonce' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="mapas_culturais_url"><?php _e( 'Endereço da instalação do Mapas Culturais', 'hacklabr' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="mapas_culturais_url" id="mapas_culturais_url" value="<?php echo esc_url( $api_key ); ?>" class="regular-text" />
                        <p class="description"><?php _e( 'Insira a URL da instalação do Mapas Culturais que deseja utilizar nas integrações.', 'hacklabr' ); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button( __( 'Salvar Configurações', 'hacklabr' ) ); ?>
        </form>
    </div>
    <?php
}
