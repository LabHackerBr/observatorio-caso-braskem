<?php
/**
 * Template part para exibir os filtros da página de archive de termo selecionado
 */

$current_filter_val = isset($_GET['filter_by_taxonomy']) ? sanitize_text_field($_GET['filter_by_taxonomy']) : '';
$all_categories = get_categories(array('hide_empty' => 1));

$current_filter_button_label = __('FILTER BY', 'hacklabr');
if ($current_filter_val === '') {
    if (array_key_exists('filter_by_taxonomy', $_GET) && $_GET['filter_by_taxonomy'] === '') {
        $current_filter_button_label = __('ALL', 'hacklabr');
    }
} else {
    foreach ($all_categories as $category_obj) {
        if ('category:' . $category_obj->slug === $current_filter_val) {
            $current_filter_button_label = esc_html(strtoupper($category_obj->name));
            break;
        }
    }
}

$current_orderby_get = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'relevance';
$orderby_options_from_user = array(
    'date_desc' => __('Latests', 'hacklabr'),
    'date_asc'  => __('Oldestes', 'hacklabr'),
    'title_asc' => __('Alphabetical', 'hacklabr'),
);

$current_orderby_button_label = __('ORDER BY', 'hacklabr');
if (isset($orderby_options_from_user[$current_orderby_get])) {
    $current_orderby_button_label = esc_html(strtoupper($orderby_options_from_user[$current_orderby_get]));
} elseif ($current_orderby_get === 'relevance' && array_key_exists('orderby', $_GET)) {
    $current_orderby_button_label = __('ORDER BY', 'hacklabr');
}

$current_order_val = 'DESC';
if (isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC'])) {
    $current_order_val = strtoupper($_GET['order']);
} elseif (strpos($current_orderby_get, '_asc') !== false) {
    $current_order_val = 'ASC';
} elseif (strpos($current_orderby_get, '_desc') !== false) {
    $current_order_val = 'DESC';
} elseif ($current_orderby_get === 'relevance' && !empty(get_search_query())) {
    $current_order_val = 'DESC';
}

?>
<div class="search-filters">
    <form method="get" action="" class="search-filters__form">
        <?php
        foreach ($_GET as $key => $value) {
            if (!in_array($key, array('s', 'filter_by_taxonomy', 'orderby', 'order'))) {
                if (is_array($value)) {
                    foreach ($value as $v_item) {
                        if (is_scalar($v_item)) {
                            echo '<input type="hidden" name="' . esc_attr($key) . '[]" value="' . esc_attr($v_item) . '">';
                        }
                    }
                } elseif (is_scalar($value)) {
                    echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
                }
            }
        }
        ?>

        <div class="filter-control search-filters__filter-by custom-dropdown-wrapper filter-by-taxonomy-wrapper">
            <button type="button" class="custom-dropdown-trigger" data-placeholder="<?php echo esc_attr(__('FILTER BY', 'hacklabr')); ?>" aria-haspopup="listbox" aria-expanded="false">
                <span class="custom-dropdown-icon filter-icon"></span>
                <span class="custom-dropdown-label"><?php echo esc_html(strtoupper($current_filter_button_label)); ?></span>
                <span class="custom-dropdown-arrow"></span>
            </button>
            <ul class="custom-dropdown-options" role="listbox" style="display: none;">
                <?php
                $label_todos = __('ALL', 'hacklabr');
                echo '<li role="option" data-value="" data-label="' . esc_attr(strtoupper($label_todos)) . '" class="' . ($current_filter_val === '' ? 'selected' : '') . '">' . esc_html(strtoupper($label_todos)) . '</li>';

                foreach ($all_categories as $category) {
                    $option_value = 'category:' . esc_attr($category->slug);
                    $option_label = esc_html(strtoupper($category->name));
                    echo '<li role="option" data-value="' . $option_value . '" data-label="' . $option_label . '" class="' . ($current_filter_val === $option_value ? 'selected' : '') . '">' . $option_label . '</li>';
                }
                ?>
            </ul>
            <input type="hidden" name="filter_by_taxonomy" id="hidden_filter_by_taxonomy" value="<?php echo esc_attr($current_filter_val); ?>">
        </div>

        <div class="filter-control search-filters__organize-by custom-dropdown-wrapper organize-by-wrapper">
             <button type="button" class="custom-dropdown-trigger" data-placeholder="<?php echo esc_attr(__('ORDER BY', 'hacklabr')); ?>" aria-haspopup="listbox" aria-expanded="false">
                <span class="custom-dropdown-icon organize-icon"></span>
                <span class="custom-dropdown-label"><?php echo esc_html(strtoupper($current_orderby_button_label)); ?></span>
                <span class="custom-dropdown-arrow"></span>
            </button>
            <ul class="custom-dropdown-options" role="listbox" style="display: none;">
                <?php
                 $label_relevance = __('ORDER BY', 'hacklabr');
                 echo '<li role="option" data-value="relevance" data-label="' . esc_attr(strtoupper($label_relevance)) . '" class="' . ($current_orderby_get === 'relevance' ? 'selected' : '') . '">' . esc_html(strtoupper($label_relevance)) . '</li>';

                foreach ($orderby_options_from_user as $value => $label) {
                    $option_label = esc_html(strtoupper($label));
                    echo '<li role="option" data-value="' . esc_attr($value) . '" data-label="' . $option_label . '" class="' . ($current_orderby_get === $value ? 'selected' : '') . '">' . $option_label . '</li>';
                }
                ?>
            </ul>
            <input type="hidden" name="orderby" id="hidden_orderby" value="<?php echo esc_attr($current_orderby_get); ?>">
            <input type="hidden" name="order" id="hidden_order_input" value="<?php echo esc_attr($current_order_val); ?>">
        </div>
        <noscript><button type="submit" class="button">Aplicar Filtros</button></noscript>
    </form>
</div>
