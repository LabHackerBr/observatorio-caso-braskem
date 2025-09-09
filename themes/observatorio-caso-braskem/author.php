<?php
get_header();

$author = get_queried_object();
$author_id = $author->ID;

$author_display_name = $author->display_name ?? '';
$author_description = $author->description ?? '';

$avatar_data = get_avatar_data($author_id);
if ( $avatar_data && ! $avatar_data['found_avatar'] && has_post_thumbnail($author_id) ) {
    $avatar_html = get_the_post_thumbnail($author_id, [150, 150], ['class' => 'author__image']);
} elseif ( $avatar_data['found_avatar'] ) {
    $avatar_html = get_avatar($author_id, 150, '', 'Foto de perfil de ' . $author_display_name, ['class' => 'author__image']);
} else {
    $avatar_html = '<img src="' . get_stylesheet_directory_uri() . '/assets/images/avatar.png" alt="author-image" class="author__image">';
}

$social_links = [];
$social_network_keys = ['instagram', 'facebook', 'youtube', 'telegram', 'threads'];

foreach ($social_network_keys as $key) {
    $url = '';
    if ( isset($author->type) && $author->type === 'guest-author' ) {
        $url = get_post_meta($author_id, $key, true);
    }
    else {
        $url = get_the_author_meta($key, $author_id);
    }

    if ($url) {
        $social_links[$key] = $url;
    }
}
?>

<div class="author index-wrapper">
    <div class="author__background"></div>
    <div class="author__content-overlay container container--wide">
        <div class="row">
            <div class="author__title-container">
               <?php
                echo $avatar_html;
                ?>
                <h1 class="entry-title"><?php echo esc_html($author_display_name); ?></h1>
            </div>

            <div class="author__wrapper-content">
                <main class="author__content posts-grid__content">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/post-card', 'horizontal', ['hide_author' => true] ); ?>
                    <?php endwhile; ?>
                </main>

                <aside class="author__sidebar">
                    <?php if ($author_description) : ?>
                        <div class="author-bio-widget">
                            <div class="author-bio-widget__about">
                                <h3 class="widget-title"><?php _e('About the author', 'hacklabr') ?></h3>
                                <div class="author-bio-widget__content">
                                    <?php echo wpautop($author_description); ?>

                                    <?php
                                    $bio_extra_en = get_post_meta($author_id, 'biographical_information', true);
                                    $bio_extra_es = get_post_meta($author_id, 'informacion_biografica', true);

                                    if ($bio_extra_en) {
                                        echo '<div class="author-bio-widget__extra">';
                                        echo wpautop($bio_extra_en);
                                        echo '</div>';
                                    }

                                    if ($bio_extra_es) {
                                        echo '<div class="author-bio-widget__extra">';
                                        echo wpautop($bio_extra_es);
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <?php if (!empty($social_links)) : ?>
                                <div class="author-bio-widget__socials">
                                    <?php
                                    foreach ($social_links as $key => $url) {
                                        $icon_url = get_template_directory_uri() . '/assets/images/author-social-networks/' . $key . '.png';
                                        $name = ucfirst($key);
                                        echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
                                        echo '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr($name) . '">';
                                        echo '</a>';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
            <?php
            the_posts_pagination([
                'prev_text' => __( '<iconify-icon icon="iconamoon:arrow-left-2-bold"></iconify-icon>', 'hacklbr'),
                'next_text' => __( '<iconify-icon icon="iconamoon:arrow-right-2-bold"></iconify-icon>', 'hacklbr'),
            ]);
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
