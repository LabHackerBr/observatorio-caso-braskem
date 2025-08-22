<?php
get_header(); ?>

    <div class="archive-header-bibliioteca">
        <div class="archive-header-bibliioteca__search-filter container container--wide">
            <h1>
                <?php
                $post_type = get_post_type();

                if ( is_post_type_archive('biblioteca') ) {
                    _e( 'Library', 'hacklabr' );
                }

                ?>
            </h1>
            <div class="archive-search">
                <form method="get" action="<?php echo esc_url( get_post_type_archive_link('biblioteca') ); ?>">
                    <input type="text" name="biblioteca_search" placeholder="<?php esc_attr_e('Quick search...', 'hacklabr'); ?>" value="<?php echo isset($_GET['biblioteca_search']) ? esc_attr($_GET['biblioteca_search']) : ''; ?>" />
                    <button type="submit"><iconify-icon icon="mdi:magnify"></iconify-icon></button>
                </form>
            </div>
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
            'prev_text' => __( '<iconify-icon icon="iconamoon:arrow-left-2-bold"></iconify-icon>', 'hacklbr'),
            'next_text' => __( '<iconify-icon icon="iconamoon:arrow-right-2-bold"></iconify-icon>', 'hacklbr'),

        ]); ?>

    </div><!-- /.container -->

<?php get_footer();
