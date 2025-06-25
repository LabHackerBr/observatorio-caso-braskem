<?php get_header(); ?>

<div class="archive-title">
    <h1 class="container container--wide">
        <?php
        $post_type = get_post_type();

        if ( is_post_type_archive('storymap') ) {
            echo 'Mapas';
        }

        ?>
    </h1>
</div>

<div class="container container--wide">

    <div class="post-grid-latest-posts">

        <div class="post-grid-latest-posts__featured">
            <?php
            $args_last = [
                'post_type' => 'storymap',
                'posts_per_page' => 1,
                'ignore_sticky_posts' => true,
                'no_found_rows' => true
            ];
            $last_post_query = new WP_Query($args_last);
            if ($last_post_query->have_posts()) :
                while ($last_post_query->have_posts()) : $last_post_query->the_post();
                    get_template_part('template-parts/post-card', 'cover');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <div class="post-grid-latest-posts__others">
            <?php
            $args_two_posts = [
                'post_type' => 'storymap',
                'posts_per_page' => 2,
                'offset' => 1,
                'ignore_sticky_posts' => true,
                'no_found_rows' => true
            ];
            $two_posts_query = new WP_Query($args_two_posts);
            if ($two_posts_query->have_posts()) :
                while ($two_posts_query->have_posts()) : $two_posts_query->the_post();
                    get_template_part('template-parts/post-card', 'cover');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

    </div>

    <div class="archive-header__infos">
        <?php get_template_part('template-parts/filter', 'posts', ['taxonomy' => 'category']); ?>
    </div>

    <div class="post-grid-filters">
        <main class="posts-grid__content">
            <?php

            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $offset = 3;

            $tax_query = [];
            if (!empty($_GET['filter_term']) && $_GET['filter_term'] !== 'all') {
                $tax_query[] = [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET['filter_term']),
                ];
            }

            $args_filtered = [
                'post_type'      => 'storymap',
                'posts_per_page' => 6,
                'paged'          => $paged,
                'tax_query'      => $tax_query,
            ];

            $filtered_query = new WP_Query($args_filtered);
            if ($filtered_query->have_posts()) :
                while ($filtered_query->have_posts()) : $filtered_query->the_post();
                    get_template_part('template-parts/post-card', 'vertical');
                endwhile;
            else :
                echo '<p>' . __('Nenhum post encontrado.', 'escola-de-dados') . '</p>';
            endif;
            ?>
        </main>

        <aside class="archive__sidebar">
            <?php dynamic_sidebar('sidebar-default'); ?>
        </aside>
    </div>

    <?php
     the_posts_pagination([
        'prev_text' => __( '<iconify-icon icon="iconamoon:arrow-left-2-bold"></iconify-icon>', 'hacklbr'),
        'next_text' => __( '<iconify-icon icon="iconamoon:arrow-right-2-bold"></iconify-icon>', 'hacklbr'),

    ]); ?>


</div><!-- /.container -->

<?php get_footer(); ?>
