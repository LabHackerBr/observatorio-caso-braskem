<?php $show_taxonomy = isset( $args['attributes']['showTaxonomy'] ) ? $args['attributes']['showTaxonomy'] : false; ?>
>
<?php $show_excerpt = isset( $args['attributes']['showExcerpt'] ) ? $args['attributes']['showExcerpt'] : false; ?>

<a href="<?php echo get_permalink();?>">
    <div class="post">
        <div class="post-thumbnail">
            <div class="post-thumbnail--image">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php echo get_the_post_thumbnail( $args['post']->ID, 'medium' ); ?>
                <?php else : ?>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/default-image.png" alt="" height="600" width="800">
                <?php endif; ?>
            </div>
        </div>
        <div class="post-content">
            <h2 class="post-title"><?php echo apply_filters( 'the_title', $args['post']->post_title ); ?></h2>

            <?php if ($show_excerpt) :?>
                <span class="post-excerpt">
                    <?php _e(get_the_excerpt())?>
                </span>
            <?php endif?>

            <div class="post-meta">
                <span class="post-meta--date"><?php echo hacklabr\v2\get_the_time_ago(); ?></span>

                <?php if ( $show_taxonomy ) : ?>
                    <?php $get_html_terms = get_html_terms( $args['post']->ID, $args['attributes']['showTaxonomy'], false, true, 1 ); ?>
                    <?php if ( $get_html_terms ) : ?>
                        <span class="post-meta--terms">
                            <span><?php _e( 'in', 'hacklabr' ); ?></span>
                            <?php echo $get_html_terms; ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</a>
