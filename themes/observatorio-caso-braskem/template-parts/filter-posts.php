<?php
global $wp;

$current_url = get_post_type_archive_link( get_post_type() ) . '?&';

$taxonomy = ( isset( $args['taxonomy'] ) ) ? $args['taxonomy'] : 'category';
$taxonomy_terms = get_terms( $taxonomy );

if ( $taxonomy_terms && ! is_wp_error( $taxonomy_terms ) ) :

    $selected = '';
    if ( isset( $_GET['filter_term'] ) ) {
        $selected = sanitize_title( $_GET['filter_term'] );
    }
    ?>

    <div class="filter-posts">
        <div class="filter-buttons">
            <a href="<?php echo esc_url( $current_url . 'filter_term=all' ); ?>"
               class="filter-button <?php echo ( $selected == '' || $selected == 'all' ) ? 'active' : ''; ?>">
                <?php _e( 'Todos', 'hacklabr' ); ?>
            </a>
            <?php foreach ( $taxonomy_terms as $term ) : ?>
                <a href="<?php echo esc_url( $current_url . 'filter_term=' . $term->slug ); ?>"
                   class="filter-button <?php echo ( $selected == $term->slug ) ? 'active' : ''; ?>">
                    <?php echo esc_html( $term->name ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>