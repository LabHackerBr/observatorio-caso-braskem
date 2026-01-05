<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
	<meta charset="<?php bloginfo('charset');?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head()?>
	<title><?= is_front_page() ? get_bloginfo('name') : wp_title()?></title>
	<link rel="icon" href="<?= get_site_icon_url() ?>" />
    <link rel="preconnect" href="https://www.google.com">
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <!-- <link rel="preconnect" href="https://cdn.userway.org"> -->
    <link rel="preconnect" href="https://www.gstatic.com">

    <script src="https://www.gstatic.com/recaptcha/releases/-PgDGgfmUF0ySmnjMTJjzqak/recaptcha__pt_br.js" defer></script>

	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js" defer></script>
	<!-- <script src="https://cdn.userway.org/widget.js" defer></script> -->
    <script src="https://cdn.jsdelivr.net/gh/spbgovbr-vlibras/vlibras-portal@dev/app/vlibras-plugin.js" defer></script>

</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div class="pre-header">
        <a class="sr-only" href="#app"><?= __('Skip to main content') ?></a>
        <div class="container container--wide">
            <div class="pre-header__content">
                <div class="pre-header__language-selector">
                    <div class="wpml-language-switcher">
                        <?php do_action('wpml_add_language_selector');?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <header
        x-data="{
            menuOpen: false,
            searchOpen: false,
            lsOpen: false,
            focusSearch() {
                const el =
                    document.querySelector('#s') ||
                    document.querySelector('#search') ||
                    document.querySelector('.search-form input[type=search]') ||
                    document.querySelector('.search-form input[name=s]');
                if (el) el.focus();
            }
        }"
        x-init="
            $watch('searchOpen', (isOpen) => { if (isOpen) $nextTick(() => focusSearch()) });
        "
        @keydown.escape.window="
            if (searchOpen) { searchOpen = false; $nextTick(() => $refs.toggleSearch && $refs.toggleSearch.focus()); }
            if (lsOpen) { lsOpen = false; $nextTick(() => $refs.toggleLang && $refs.toggleLang.focus()); }
        "
        class="main-header main-header-lateral"
        :class="{
            'main-header-lateral--menu-open': menuOpen,
            'main-header-lateral--search-open': searchOpen,
            'main-header-lateral--ls-open': lsOpen
        }"
    >
        <div class="container container--wide">
			<div class="main-header-lateral__content">
                <button type="button" class="main-header__toggle-menu main-header-lateral__toggle-menu" aria-label="<?= __('Toggle menu visibility closed', 'hacklabr') ?>" @click="menuOpen = !menuOpen">
                    <svg class="hamburger" :class="{ 'hamburger--open': menuOpen }" role="img" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <title>Exibir menu</title>
                        <rect width="16" height="2" x="0" y="2"/>
                        <rect width="16" height="2" x="0" y="8"/>
                        <rect width="16" height="2" x="0" y="14"/>
                    </svg>
                </button>

				<div class="main-header-lateral__logo">
                    <?php if ( has_custom_logo() ): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <a href="<?= home_url() ?>" alt="logo braskem">
                            <img src="<?= get_template_directory_uri() ?>/assets/images/logo.svg" width="200" alt="logo braskem" fetchpriority="high">
                        </a>
                    <?php endif; ?>
				</div>

                <div class="main-header-lateral__social">
                    <h1 class="main-header-lateral__social-title"><?= _e( 'Follow us on our social media', 'hacklabr' ) ?></span>
                    <?= the_social_networks_menu(false); ?>
                </div>

                <div class="main-header-lateral__search">
                    <?php get_search_form(); ?>

                    <button
                        type="button"
                        class="main-header__toggle-search main-header-lateral__toggle-search"
                        x-ref="toggleSearch"
                        aria-label="<?= esc_attr__( 'Toggle search form visibility closed', 'hacklabr' ) ?>"
                        :aria-expanded="searchOpen ? 'true' : 'false'"
                        aria-controls="header-search-form"
                        @click="
                            searchOpen = !searchOpen;
                            if (!searchOpen) $nextTick(() => $refs.toggleSearch && $refs.toggleSearch.focus());
                        "
                        @keydown.enter.prevent="
                            searchOpen = !searchOpen;
                            if (!searchOpen) $nextTick(() => $refs.toggleSearch && $refs.toggleSearch.focus());
                        "
                        @keydown.space.prevent="
                            searchOpen = !searchOpen;
                            if (!searchOpen) $nextTick(() => $refs.toggleSearch && $refs.toggleSearch.focus());
                        "
                    >
                        <img src="<?= get_template_directory_uri() ?>/assets/images/search-icon.svg" width="20" alt="ícone de busca">
                    </button>

                    <button
                        type="button"
                        class="main-header__toggle-language main-header-lateral__toggle-language"
                        x-ref="toggleLang"
                        aria-label="<?= esc_attr__( 'Toggle language selector visibility', 'hacklabr' ) ?>"
                        :aria-expanded="lsOpen ? 'true' : 'false'"
                        aria-controls="header-language-selector"
                        @click="
                            lsOpen = !lsOpen;
                            if (!lsOpen) $nextTick(() => $refs.toggleLang && $refs.toggleLang.focus());
                        "
                        @keydown.enter.prevent="
                            lsOpen = !lsOpen;
                            if (!lsOpen) $nextTick(() => $refs.toggleLang && $refs.toggleLang.focus());
                        "
                        @keydown.space.prevent="
                            lsOpen = !lsOpen;
                            if (!lsOpen) $nextTick(() => $refs.toggleLang && $refs.toggleLang.focus());
                        "
                    >
                        <img src="<?= get_template_directory_uri() ?>/assets/images/web-icon.svg" width="20" alt="ícone de web">
                    </button>
                </div>
			</div>

            <div class="main-header-lateral__desktop-content">
                <?= wp_nav_menu(['theme_location' => 'main-menu', 'container' => 'nav', 'menu_class' => 'menu', 'container_class' => 'main-header-lateral__menu-desktop']) ?>
            </div>
            <?php do_action( 'hacklabr/header/menus-end' ); ?>

        </div>

        <div class="main-header-lateral__scroll-content">
            <div class="main-header-lateral__mobile-content">
                <?= wp_nav_menu(['theme_location' => 'main-menu', 'container' => 'nav', 'menu_class' => 'menu', 'container_class' => 'main-header-lateral__menu-mobile']) ?>
            </div>

            <div class="main-header-lateral__language-selector" id="header-language-selector">
                <div class="wpml-language-switcher">
                    <?php do_action('wpml_add_language_selector');?>
                </div>
            </div>
        </div>

	</header>

	<div id="app">
