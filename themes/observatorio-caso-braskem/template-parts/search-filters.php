<?php
/**
 * Template part para exibir os filtros da página de busca.
 */
?>
<div class="search-filters">
    <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-filters__form">
        <input type="hidden" name="s" value="<?php echo get_search_query(); ?>">
        <?php
        foreach ( $_GET as $key => $value ) {
            if ( ! in_array( $key, array( 's', 'filter_by_taxonomy', 'orderby', 'order' ) ) ) {
                if (is_array($value)) {
                    foreach ($value as $v_item) {
                        if (is_scalar($v_item)) {
                             echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $v_item ) . '">';
                        }
                    }
                } elseif (is_scalar($value)) {
                   echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
                }
            }
        }
        ?>

        <div class="filter-control search-filters__filter-by">
            <label for="filter_by_taxonomy" class="screen-reader-text"><?php _e('Filtrar por:', 'hacklabr'); ?></label>
            <div class="select-wrapper filter-by-taxonomy-wrapper">
                <select name="filter_by_taxonomy" id="filter_by_taxonomy" class="filter-by-taxonomy custom-select-with-icon" onchange="this.form.submit()">
                    <option value=""><?php _e('FILTRAR POR', 'hacklabr'); ?></option>
                    <?php
                    $current_filter_val = isset( $_GET['filter_by_taxonomy'] ) ? sanitize_text_field( $_GET['filter_by_taxonomy'] ) : '';
                    $categories = get_categories( array( 'hide_empty' => 1 ) );
                    foreach ( $categories as $category ) {
                        $option_value = 'category:' . esc_attr( $category->slug );
                        echo '<option value="' . $option_value . '" ' . selected( $current_filter_val, $option_value, false ) . '>' . esc_html( $category->name ) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="filter-control search-filters__organize-by">
            <label for="organize_by" class="screen-reader-text"><?php _e('Organizar por:', 'hacklabr'); ?></label>
            <div class="select-wrapper organize-by-wrapper">
                <select name="orderby" id="organize_by" class="organize-by custom-select-with-icon" onchange="this.form.submit()">
                    <option value="relevance"><?php _e('ORGANIZAR POR', 'hacklabr'); ?></option>
                    <?php
                    $current_orderby_get = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'relevance';
                    $orderby_options = array(
                        'date_desc' => __('Mais recentes', 'hacklabr'),
                        'date_asc'  => __('Mais antigos', 'hacklabr'),
                        'title_asc' => __('Título (A-Z)', 'hacklabr'),
                        'title_asc'=> __('Alfabética', 'hacklabr'),
                    );
                    foreach ( $orderby_options as $value => $label ) {
                        echo '<option value="' . esc_attr( $value ) . '" ' . selected( $current_orderby_get, $value, false ) . '>' . esc_html( $label ) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="order" id="hidden_order_input" value="<?php echo esc_attr( isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'DESC' ); ?>">
        </div>
    </form>
</div>