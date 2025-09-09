<?php
/**
 * The template for displaying all single posts
 */

get_header();
the_post();
$categories = get_the_category();
$excerpt = !empty( $post->post_excerpt ) ? wp_kses_post( $post->post_excerpt ) : '';
?>

<div class="single">
    <!-- <div class="single__background"></div> -->

    <div class="single__content-overlay">
        <header class="post-header container">
            <div class="post-header__featured-image">
                <?= get_the_post_thumbnail(null, 'post-thumbnail',['class'=>'post-header__featured-image']); ?>
            </div>

            <div class="post-header__tags">
            <?php
                    if( !empty($categories) ){
                        foreach($categories as $category){ ?>
                            <a class="tag tag--<?= $category->slug ?>" href="<?= get_term_link($category, 'category') ?>">
                                <?= $category->name ?>
                            </a>
                        <?php
                        }
                    }
                ?>
            </div>

            <h1 class="post-header__title"> <?php the_title(); ?> </h1>

            <?php if( $excerpt ) : ?>
                <p class="post-header__excerpt"><?= get_the_excerpt() ?></p>
            <?php endif; ?>

            <!-- <div class="post-header__meta container">
                <?php get_template_part('template-parts/share-links', null, ['link'=>get_the_permalink()]) ?>
            </div> -->

            <div class="post-header__author">
                <div class="post-header__published-container">
                    <?php
                    $coauthors = get_coauthors();
                    foreach ( $coauthors as $author ) :
                        $avatar = get_avatar_url( $author->ID, ['size' => 96] );
                        $name   = $author->display_name;
                        $link   = get_author_posts_url( $author->ID, $author->user_nicename );
                    ?>
                        <div class="post-header__published-container">
                            <img class="post-header__author-image" src="<?= esc_url($avatar) ?>" alt="<?= esc_attr($name) ?>"/>
                            <div class="post-header__author-published-date">
                                <p class="post-header__author-name">
                                    <?php _e('By ', 'hacklabr') ?>
                                    <a href="<?= esc_url($link) ?>"><?= esc_html($name) ?></a>
                                </p>
                                <p class="post-header__date"><?= get_the_date("j/m/Y")?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </header>

        <main class="post-content container">
            <?php the_content() ?>
        </main>

        <?php
            $coauthors = get_coauthors();
            if ( $coauthors ) : ?>
            <section class="post-authors container">
                <?php foreach ( $coauthors as $author ) :
                    $name  = $author->display_name;
                    $link  = get_author_posts_url( $author->ID, $author->user_nicename );
                ?>
                    <div class="post-author">
                        <div class="post-author__avatar">
                            <?php echo coauthors_get_avatar( $author, 96 ); ?>
                            <h3 class="post-author__name">
                                <a href="<?= esc_url($link) ?>"><?= esc_html($name) ?></a>
                            </h3>
                        </div>
                        <div class="post-author__info">

                            <?php if ( !empty($author->description) ) : ?>
                                <p class="post-author__bio">
                                    <?= esc_html( $author->description ); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

    </div>
</div>


<?php get_template_part('template-parts/content/related-posts') ?>

<?php get_footer();
