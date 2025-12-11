<?php

/**
 * Get the search string
 */
$search_query = get_search_query( false );

if ( ! empty( $search_query ) ) {
    $title = 'You searched for: <span class="highlighted">' . esc_attr( $search_query  ) . '</span>';
} else {
    $title = 'Search';
} ?>

<header class="search__header">
    <div class="container">
        <h1 class="sr-only search__title">
            <?php echo apply_filters( 'the_title' , $title ); ?>
        </h1>
    </div>
</header><!-- /.c-title.title-search -->
