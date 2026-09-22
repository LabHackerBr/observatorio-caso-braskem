<?php
get_header(); ?>

    <div class="archive-header-bibliioteca">
        <div class=" sr-only archive-header-bibliioteca__search-filter container container--wide">
            <h1 class="sr-only"></h1>
            <h1>
                <?php
                $post_type = get_post_type();

                if ( is_post_type_archive('biblioteca') ) {
                    _e( 'Library', 'hacklabr' );
                }

                ?>
            </h1>
        </div>
        <div class="archive-header-bibliioteca__others-filters container container--wide">
            <?php get_template_part( 'template-parts/filters-biblioteca' ); ?>
        </div>
    </div>

    <div class="container container--wide">

        <main class="posts-grid__content">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/post-card', 'vertical' ); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="no-results"><?php _e('No results found.', 'hacklabr') ?></p>
            <?php endif; ?>
        </main>

        <?php
        the_posts_pagination([
            'prev_text' => hacklabr_pagination_arrow( 'previous' ),
            'next_text' => hacklabr_pagination_arrow( 'next' ),

        ]); ?>

    </div><!-- /.container -->

<?php get_footer();
