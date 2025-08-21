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
        <div class="archive-filters">
            <details class="filter-ano">
                <summary><?php _e('Year', 'hacklabr'); ?></summary>
                <ul>
                    <?php
                    global $wpdb;
                    $meta_key = 'ano';
                    $post_type = 'biblioteca';

                    $datas = $wpdb->get_col( $wpdb->prepare(
                        "
                        SELECT pm.meta_value
                        FROM {$wpdb->postmeta} pm
                        INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                        WHERE pm.meta_key = %s
                        AND p.post_type = %s
                        AND p.post_status = 'publish'
                        ",
                        $meta_key,
                        $post_type
                    ) );

                    $anos = array();
                    foreach ( $datas as $data ) {
                        if ( strtotime($data) ) {
                            $ano = date('Y', strtotime($data));
                            if ( ! in_array($ano, $anos) ) {
                                $anos[] = $ano;
                            }
                        }
                    }
                    rsort($anos);

                    foreach($anos as $ano) :
                        // monta link mantendo a busca atual
                        $link = add_query_arg(array(
                            'biblioteca_ano'    => $ano,
                            'biblioteca_search' => $_GET['biblioteca_search'] ?? '',
                        ), get_post_type_archive_link('biblioteca'));
                    ?>
                        <li>
                            <a href="<?php echo esc_url($link); ?>" <?php echo (isset($_GET['biblioteca_ano']) && $_GET['biblioteca_ano'] == $ano) ? 'class="active"' : ''; ?>>
                                <?php echo esc_html($ano); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>
        </div>
        <div class="archive-active-filters">

            <?php
            $has_filters = false; // flag para verificar se existe algum filtro ativo
            ?>

            <?php if ( isset($_GET['biblioteca_search']) && $_GET['biblioteca_search'] != '' ) : $has_filters = true; ?>
                <span class="active-filter">
                    <?php printf(__('Search: %s', 'hacklabr'), esc_html($_GET['biblioteca_search'])); ?>
                    <a href="<?php echo esc_url( remove_query_arg('biblioteca_search', get_post_type_archive_link('biblioteca')) ); ?>" class="clear-filter">×</a>
                </span>
            <?php endif; ?>

            <?php if ( isset($_GET['biblioteca_ano']) && $_GET['biblioteca_ano'] != '' ) : $has_filters = true; ?>
                <span class="active-filter">
                    <?php printf(__('Year: %s', 'hacklabr'), esc_html($_GET['biblioteca_ano'])); ?>
                    <a href="<?php echo esc_url( remove_query_arg('biblioteca_ano', add_query_arg('biblioteca_search', $_GET['biblioteca_search'], get_post_type_archive_link('biblioteca'))) ); ?>" class="clear-filter">×</a>
                </span>
            <?php endif; ?>

            <?php if ( $has_filters ) : ?>
                <span class="active-filter clear-all">
                    <a href="<?php echo esc_url( get_post_type_archive_link('biblioteca') ); ?>">
                        <?php _e('Clear all filters', 'hacklabr'); ?>
                    </a>
                </span>
            <?php endif; ?>

            </div>
        </div>
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
