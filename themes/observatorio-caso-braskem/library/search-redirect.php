<?php
namespace hacklabr;

function redirect_empty_search_to_404() {
    if (is_search() && !have_posts()) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        get_template_part('404');
        exit();
    }
}
add_action('template_redirect', __NAMESPACE__ . '\\redirect_empty_search_to_404');
