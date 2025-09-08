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
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                            <path d="M9.39564 18.9097C6.76968 18.9097 4.54749 18 2.72907 16.1806C0.910655 14.3612 0.00096442 12.139 7.64806e-07 9.51405C-0.00096289 6.88905 0.908728 4.66686 2.72907 2.84748C4.54942 1.0281 6.77161 0.118408 9.39564 0.118408C12.0197 0.118408 14.2423 1.0281 16.0637 2.84748C17.885 4.66686 18.7942 6.88905 18.7913 9.51405C18.7913 10.5741 18.6226 11.5739 18.2854 12.5134C17.9481 13.453 17.4903 14.2841 16.9122 15.0069L25.0069 23.1016C25.2719 23.3666 25.4044 23.7039 25.4044 24.1134C25.4044 24.523 25.2719 24.8603 25.0069 25.1253C24.7418 25.3903 24.4046 25.5228 23.995 25.5228C23.5855 25.5228 23.2482 25.3903 22.9832 25.1253L14.8885 17.0306C14.1657 17.6088 13.3346 18.0665 12.395 18.4038C11.4555 18.741 10.4557 18.9097 9.39564 18.9097ZM9.39564 16.0187C11.2025 16.0187 12.7386 15.3866 14.0038 14.1222C15.2691 12.8579 15.9013 11.3219 15.9003 9.51405C15.8993 7.70623 15.2672 6.17065 14.0038 4.90729C12.7405 3.64394 11.2044 3.0113 9.39564 3.00937C7.58686 3.00745 6.05127 3.64009 4.78889 4.90729C3.5265 6.1745 2.89386 7.71008 2.89097 9.51405C2.88808 11.318 3.52072 12.8541 4.78889 14.1222C6.05706 15.3904 7.59264 16.0226 9.39564 16.0187Z" fill="#151846"/>
                        </svg>
                    </button>
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
