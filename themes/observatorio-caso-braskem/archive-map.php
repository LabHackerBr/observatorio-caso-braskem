<?php get_header(); ?>

<div class="archive-title">
    <h1 class="container container--wide">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/exclamation-point.png" alt="exclamation-point" class="exclamation-icon">

        <?php
        if (is_post_type_archive('map')) {
            _e('Maps', 'hacklabr');
        }
        ?>
    </h1>
</div>

<div class="container container--wide">

    <div class="post-grid-latest-posts">
        <div class="post-grid-latest-posts__featured">
            <?php
            // Primeiro post mais recente
            $args_featured = [
                'post_type'           => 'storymap',
                'posts_per_page'      => 1,
                'ignore_sticky_posts' => true,
                'orderby'             => 'date',
                'order'               => 'DESC',
                'no_found_rows'       => true,
            ];

            $featured_query = new WP_Query($args_featured);
            $featured_id = null;

            if ($featured_query->have_posts()) :
                while ($featured_query->have_posts()) : $featured_query->the_post();
                    $featured_id = get_the_ID();
                    get_template_part('template-parts/post-card', 'horizontal');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>

    <div class="post-grid-filters">
        <main class="posts-grid__content">
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;

            $args_posts = [
                'post_type'           => 'storymap',
                'posts_per_page'      => 4,
                'paged'               => $paged,
                'post__not_in'        => $featured_id ? [$featured_id] : [],
                'ignore_sticky_posts' => true,
            ];

            $posts_query = new WP_Query($args_posts);
            if ($posts_query->have_posts()) :
                while ($posts_query->have_posts()) : $posts_query->the_post();
                    get_template_part('template-parts/post-card', 'cover');
                endwhile;
            else :
                echo '<p>' . __('Nenhum post encontrado.', 'escola-de-dados') . '</p>';
            endif;
            ?>
        </main>

        <aside class="archive__sidebar-desktop">
            <?php dynamic_sidebar('sidebar-default'); ?>
        </aside>
    </div>

    <?php
    the_posts_pagination([
        'prev_text' => __('<iconify-icon icon="iconamoon:arrow-left-2-bold"></iconify-icon>', 'hacklbr'),
        'next_text' => __('<iconify-icon icon="iconamoon:arrow-right-2-bold"></iconify-icon>', 'hacklbr'),
    ]);
    ?>

        <aside class="archive__sidebar-mobile">
            <?php dynamic_sidebar('sidebar-default'); ?>
        </aside>

</div><!-- /.container -->

<?php get_footer(); ?>
