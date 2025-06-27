<?php
get_header(); ?>

    <div class="archive-title">
        <h1 class="container container--wide">
            <?php
            $post_type = get_post_type();

            if ( is_post_type_archive('biblioteca') ) {
                echo 'Biblioteca';
            }

            ?>
        </h1>
    </div>

    <div class="container container--wide">

        <main class="posts-grid__content">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/post-card', 'vertical' ); ?>
            <?php endwhile; ?>
        </main>

        <?php
        the_posts_pagination([
            'prev_text' => __( '<iconify-icon icon="iconamoon:arrow-left-2-bold"></iconify-icon>', 'hacklbr'),
            'next_text' => __( '<iconify-icon icon="iconamoon:arrow-right-2-bold"></iconify-icon>', 'hacklbr'),

        ]); ?>

    </div><!-- /.container -->

<?php get_footer();
