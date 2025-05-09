<?php
namespace hacklabr;
/**
 * Custom reCAPTCHA script for Contact Form 7
 *
 * @link https://developers.google.com/recaptcha/docs/display?hl=pt-br
 */
function setup_recaptcha() {
    wp_register_script(
        'custom-recaptcha',
        get_template_directory_uri() . '/assets/javascript/functionalities/recaptcha.js',
        array(),
        '1.0',
        true
    );

    if (is_page('quem-somos') || has_shortcode(get_the_content(), 'contact-form-7')) {
        wp_enqueue_script('custom-recaptcha');

        wp_localize_script('custom-recaptcha', 'recaptcha_vars', array(
            'site_key' => '6Le10TMrAAAAALYeWSVIXDWWoB5u2AuyFbEkCgI6'
        ));
    }
}
add_action('wp_enqueue_scripts', 'hacklabr\\setup_recaptcha');
