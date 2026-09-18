<?php
get_header();
?>


<div class="container container--wide">
    <div class="search-container">
        <?php get_template_part( 'template-parts/title/archive-header' ); ?>

        <main class="posts-grid__content">
            <?php
            if ( have_posts() ) {
                while ( have_posts() ) : the_post();
                    get_template_part( 'template-parts/post-card', 'horizontal' );
                endwhile;
            } else {
                get_template_part( 'template-parts/content/no-post' );
            }; ?>
        </main>

        <?php
        the_posts_pagination([
            'prev_text' => hacklabr_pagination_arrow( 'previous' ),
            'next_text' => hacklabr_pagination_arrow( 'next' ),

        ]); ?>

        <aside class="">
            <?php dynamic_sidebar( 'sidebar-search' ) ?>
        </aside>
    </div>
</div><!-- /.container -->


<?php get_footer();
