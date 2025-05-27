<?php
/**
 * Template para exibir o formulário de busca personalizado.
 */

$unique_input_id = 'search-field-' . esc_attr( uniqid() );

$form_id = 'main-search-page-form';
?>
<form role="search" method="get" id="<?php echo esc_attr( $form_id ); ?>" class="search-form"  action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="<?php echo $unique_input_id; ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'hacklabr' ); ?></label>
    <input
        type="search"
        id="<?php echo $unique_input_id; ?>"
        class="search-field"
        placeholder="<?php esc_attr_e( 'Search term...', 'hacklabr' ); ?>"
        value="<?php echo get_search_query(); ?>"
        name="s"
    />
</form>