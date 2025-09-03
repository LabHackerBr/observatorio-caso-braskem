<?php
$post_id = get_the_ID();

if ( is_singular('biblioteca') ) {
    // 🔹 Se for single da CPT biblioteca → últimos 3 posts desse CPT
    $args = [
        'post_type'      => 'biblioteca',
        'posts_per_page' => 3,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
} else {
    // 🔹 Padrão para posts normais → últimos 4 na mesma categoria
    $projects = get_the_terms( $post_id, 'category' );

    if ( $projects && ! is_wp_error( $projects ) ) {
        $projects = wp_list_pluck( $projects, 'term_id' );
    }

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 4,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => [
            [
                'taxonomy' => 'category',
                'terms'    => $projects
            ]
        ],
    ];
}

$related_posts = new WP_Query( $args );

if ( $related_posts->have_posts() ) : ?>
    <div class="related-posts container container--wide">
        <h2 class="related-posts__title">
            <?php
            if ( is_singular('biblioteca') ) {
                _e('Related content: ', 'hacklabr');
            } else {
                _e('See also: ', 'hacklabr');
            }
            ?>
        </h2>
        <div class="related-posts__content">
            <?php while( $related_posts->have_posts() ) :
                $related_posts->the_post();
                get_template_part( 'template-parts/post-card');
            endwhile; ?>
        </div>
    </div>
<?php endif;

wp_reset_postdata();
?>
