<?php
$show_author    = ! empty( $args['attributes']['showAuthor'] );
$show_excerpt   = ! empty( $args['attributes']['showExcerpt'] );
$show_taxonomy  = ! empty( $args['attributes']['showTaxonomy'] ) ? $args['attributes']['showTaxonomy'] : false;
$show_avatar    = ! empty( $args['attributes']['showAvatar'] );
$show_thumbnail = ! empty( $args['attributes']['showThumbnail'] );
$block_model    = ! empty( $args['attributes']['blockModel'] ) ? $args['attributes']['blockModel'] : 'posts';
$counter_posts  = ! empty( $args['attributes']['counter_posts'] ) ? $args['attributes']['counter_posts'] : 1;
$show_date      = isset( $args['attributes']['showDate'] ) ? (bool) $args['attributes']['showDate'] : false;

$post_id = ! empty( $args['post']->ID ) ? (int) $args['post']->ID : get_the_ID();
$title   = get_the_title( $post_id );
?>
<div class="grid-item">
	<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
		<div class="post">
			<?php if ( 'numbered' === $block_model ) : ?>
				<div class="post-number">
					<span class="number"><?php echo (int) $counter_posts; ?></span><span class="point">.</span>
				</div>
			<?php elseif ( $show_thumbnail ) : ?>

				<div class="post-thumbnail">
					<?php
					$thumbnail_url = '';
					$thumbnail_alt = '';

					// Se for avatar via coauthors
					if ( $show_avatar && function_exists( 'get_coauthors' ) ) {
						$coauthors = get_coauthors( $post_id );

						if ( ! empty( $coauthors[0] ) ) {
							$author_id = is_object( $coauthors[0] ) && isset( $coauthors[0]->ID ) ? (int) $coauthors[0]->ID : 0;

							if ( $author_id ) {
								$thumbnail_id  = get_post_thumbnail_id( $author_id );
								$thumbnail_url = get_the_post_thumbnail_url( $author_id, 'medium' );

								if ( $thumbnail_id ) {
									$thumbnail_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
								}
							}
						}
					} else {
						// Thumbnail do post
						$thumbnail_id  = get_post_thumbnail_id( $post_id );
						$thumbnail_url = get_the_post_thumbnail_url( $post_id, 'medium' );

						if ( $thumbnail_id ) {
							$thumbnail_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
						}
					}

					// Fallback de imagem default
					if ( empty( $thumbnail_url ) ) {
						$thumbnail_url = get_stylesheet_directory_uri() . '/assets/images/default-image.png';
					}

					// Fallback do alt
					if ( empty( $thumbnail_alt ) ) {
						$thumbnail_alt = sprintf(
							/* translators: %s: post title */
							__( 'Imagem ilustrativa para %s', 'hacklabr' ),
							$title
						);
					}
					?>
					<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $thumbnail_alt ); ?>">
				</div>

			<?php endif; ?>

			<div class="post-content">
				<h2 class="post-title"><?php echo apply_filters( 'the_title', $args['post']->post_title ); ?></h2>

				<?php if ( $show_excerpt && has_excerpt( $post_id ) ) : ?>
					<div class="post-excerpt">
						<?php echo get_the_excerpt( $post_id ); ?>
					</div>
				<?php endif; ?>

				<div class="post-meta">
					<div class="post-meta--date">
						<?php if ( $show_date ) : ?>
							<span class="date"><?php echo esc_html( hacklabr\v2\get_the_time_ago( 'd \d\e F \d\e Y' ) ); ?></span>
						<?php endif; ?>

						<?php if ( $show_taxonomy ) : ?>
							<?php $get_html_terms = get_html_terms( $post_id, $show_taxonomy, false, true, 1 ); ?>

							<?php if ( $get_html_terms ) : ?>
								<span class="post-meta--terms">
									<span class="prefix"><?php _e( 'in', 'hacklabr' ); ?></span><?php echo $get_html_terms; ?>
								</span>
							<?php endif; ?>
						<?php endif; ?>
					</div>

					<?php if ( $show_author && $author = get_the_author() ) : ?>
						<div class="post-author">
							<span><?php _e( 'by', 'hacklabr' ); ?></span>
							<?php echo esc_html( $author ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</a>
</div>
