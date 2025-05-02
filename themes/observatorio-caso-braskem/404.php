<?php get_header(); ?>

<div class="error-404">
    <div class="error-404__header container">
        <h1 class="error-404__title"><?php _e('404 :(', 'hacklabr') ?></h1>
    </div>

    <div class="error-404__content container container--wide">
        <h2 class="error-404__subtitle">
            <span class="error-404__subtitle-exclamation">
                <?php _e('Oops', 'hacklabr') ?>
                <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/exclamation-point.png" alt="exclamation-point" class="exclamation-icon">
            </span>
            <?php _e('Não encontramos a página que você tentou acessar!', 'hacklabr') ?>
        </h2>

        <p class="error-404__text"><?php _e('Não conseguimos encontrar o que você está procurando, digite o que você precisa na barra de pesquisa ali em cima.', 'hacklabr') ?></p>

        <a href="<?php echo home_url(); ?>" class="error-404__button"><?php _e('VOLTAR PARA HOME', 'hacklabr') ?></a>
    </div>
</div>

<?php get_footer(); ?>
