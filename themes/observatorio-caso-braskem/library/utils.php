<?php
/**
 *
 * Remove recaptcha from tainacan
 *
 */
add_action( 'init', function() {
    wp_dequeue_script( 'tainacan-google-recaptcha-script' );
}, 150 );

/**
 * Print the excerpt with limit words
 */
function get_custom_excerpt( $post_id = '', $limit = 30 ) {

    if ( empty( $post_id ) ) {
        $post_id = get_the_ID();
    }

    // If exists excerpt metadata
    $excerpt = get_post_meta( $post_id, 'excerpt', true );

    if ( empty( $excerpt ) ) {
        $excerpt = get_the_excerpt( $post_id );
    }

    if ( empty( $excerpt ) ) {
        $excerpt = wp_trim_excerpt( '', $post_id );
    }

    $excerpt = wp_strip_all_tags( $excerpt );
    $excerpt = explode( ' ', $excerpt, $limit );

    if ( count( $excerpt ) >= $limit ) {
        array_pop( $excerpt );
        $excerpt = implode( ' ', $excerpt ) . ' ...';
    } else {
        $excerpt = implode( ' ', $excerpt );
    }

    return $excerpt;

}

/**
 * Rename the defaults taxonomies
 */
function rename_taxonomies() {

    // Tags -> Temas
    $post_tag_args = get_taxonomy( 'post_tag' );

    $post_tag_args->label = 'Temas';
    $post_tag_args->labels->name = 'Temas';
    $post_tag_args->labels->singular_name = 'Tema';
    $post_tag_args->labels->search_items = 'Pesquisar tema';
    $post_tag_args->labels->popular_items = 'Temas populares';
    $post_tag_args->labels->all_items = 'Todos temas';
    $post_tag_args->labels->parent_item = 'Tema superior';
    $post_tag_args->labels->edit_item = 'Editar tema';
    $post_tag_args->labels->view_item = 'Ver tema';
    $post_tag_args->labels->update_item = 'Atualizar tema';
    $post_tag_args->labels->add_new_item = 'Adicionar novo tema';
    $post_tag_args->labels->new_item_name = 'Nome do novo tema';
    $post_tag_args->labels->separate_items_with_commas = 'Separe os temas com vírgulas';
    $post_tag_args->labels->add_or_remove_items = 'Adicionar ou remover temas';
    $post_tag_args->labels->choose_from_most_used = 'Escolha entre os temas mais usados';
    $post_tag_args->labels->not_found = 'Nenhum tema encontrado';
    $post_tag_args->labels->no_terms = 'Nenhum tema';
    $post_tag_args->labels->items_list_navigation = 'Navegação da lista de temas';
    $post_tag_args->labels->items_list = 'Lista de temas';
    $post_tag_args->labels->most_used = 'Temas mais utilizados';
    $post_tag_args->labels->back_to_items = '&larr; Ir para os temas';
    $post_tag_args->labels->item_link = 'Link do tema';
    $post_tag_args->labels->item_link_description = 'Um link para o tema';
    $post_tag_args->labels->menu_name = 'Temas';
    $post_tag_args->hierarchical = true;

    $object_type = array_merge( $post_tag_args->object_type, ['page'] );
    $object_type = array_unique( $object_type );

    register_taxonomy( 'post_tag', $object_type, (array) $post_tag_args );

    // Category -> Projetos
    $category_args = get_taxonomy( 'category' );

    $category_args->label = 'Projetos';
    $category_args->labels->name = 'Projetos';
    $category_args->labels->singular_name = 'Projeto';
    $category_args->labels->search_items = 'Pesquisar Projeto';
    $category_args->labels->popular_items = 'Projetos populares';
    $category_args->labels->all_items = 'Todos Projetos';
    $category_args->labels->parent_item = 'Projeto superior';
    $category_args->labels->edit_item = 'Editar Projeto';
    $category_args->labels->view_item = 'Ver Projeto';
    $category_args->labels->update_item = 'Atualizar Projeto';
    $category_args->labels->add_new_item = 'Adicionar novo Projeto';
    $category_args->labels->new_item_name = 'Nome do novo Projeto';
    $category_args->labels->separate_items_with_commas = 'Separe os Projetos com vírgulas';
    $category_args->labels->add_or_remove_items = 'Adicionar ou remover Projetos';
    $category_args->labels->choose_from_most_used = 'Escolha entre os Projetos mais usados';
    $category_args->labels->not_found = 'Nenhum Projeto encontrado';
    $category_args->labels->no_terms = 'Nenhum Projeto';
    $category_args->labels->items_list_navigation = 'Navegação da lista de Projetos';
    $category_args->labels->items_list = 'Lista de Projetos';
    $category_args->labels->most_used = 'Projetos mais utilizados';
    $category_args->labels->back_to_items = '&larr; Ir para os Projetos';
    $category_args->labels->item_link = 'Link do Projeto';
    $category_args->labels->item_link_description = 'Um link para o Projeto';
    $category_args->labels->menu_name = 'Projetos';

    $object_type = array_merge( $category_args->object_type, ['page'] );
    $object_type = array_unique( $object_type );

    register_taxonomy( 'category', $object_type, (array) $category_args );

}
// Descomentar para renomear as taxonomias padrão do WP
// add_action( 'init', 'rename_taxonomies', 11 );

// Page Slug Body Class
function add_slug_body_class( $classes ) {
    global $post;
    if ( isset( $post ) ) {
    $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/**
 * Return the structure HTML of the posts separetade by month
 *
 * @param array $args use params of the class WP_Query
 * @link https://developer.wordpress.org/reference/classes/wp_query/#parameters
 *
 * @return array months|slider
 */
function get_posts_by_month( $args = [] ) {

    $args['orderby'] = 'date';

    $items = new WP_Query( $args );

    if( $items->have_posts() ) :

        $month_titles   = [];
        $close_ul       = false;
        $content_slider = '';

        while( $items->have_posts() ) : $items->the_post();

            $month_full = [
                'Jan' => 'Janeiro',
                'Feb' => 'Fevereiro',
                'Mar' => 'Marco',
                'Apr' => 'Abril',
                'May' => 'Maio',
                'Jun' => 'Junho',
                'Jul' => 'Julho',
                'Aug' => 'Agosto',
                'Nov' => 'Novembro',
                'Sep' => 'Setembro',
                'Oct' => 'Outubro',
                'Dec' => 'Dezembro'
            ];

            $year = date( 'Y', strtotime( get_the_date( 'Y-m-d H:i:s' ) ) );
            $month = date( 'M', strtotime( get_the_date( 'Y-m-d H:i:s' ) ) );

            $month_title = $month_full[$month] . ' ' . $year;

            if ( ! in_array( $month_title, $month_titles ) ) :
                if ( $close_ul ) $content_slider .= '</ul>';
                $content_slider .= '<ul id="items-' . sanitize_title( $month_title ) . '" class="item-slider">';
                $month_titles[] = $month_title;
                $close_ul = true;
            endif;

            $thumbnail = ( has_post_thumbnail( get_the_ID() ) ) ? get_the_post_thumbnail( get_the_ID() ) : '<img alt="imagem com fundo padrão cinza" src="' . get_stylesheet_directory_uri() . '/assets/images/default-image.png">';

            $content_slider .= sprintf(
                '<li id="item-%1$s" class="item item-month-%2$s"><a href="%3$s"><div class="thumb">%4$s</div><div class="title"><h3>%5$s</h3></div></a></li>',
                get_the_ID(),
                $month_title,
                get_permalink( get_the_ID() ),
                $thumbnail,
                get_the_title( get_the_ID() )
            );

        endwhile;

        if ( $close_ul ) $content_slider .= '</ul>';
    endif;

    return [
        'months' => $month_titles,
        'slider' => $content_slider
    ];

}

function allow_svg_uploads( $file_types ){
	$file_types['svg'] = 'image/svg+xml';
	return $file_types;
}
add_filter( 'upload_mimes', 'allow_svg_uploads' );

function archive_filter_posts( $query ) {
    // Apply filter of the archives
    if ( $query->is_main_query() && ! is_admin() ) {

        $is_blog = false;
        $page_for_posts = get_option( 'page_for_posts' );

        if ( $query->is_home() && isset( $query->get_queried_object()->ID ) && $query->get_queried_object()->ID == $page_for_posts ) {
            $is_blog = true;
        }

        if ( is_archive() || $is_blog ) {
            if ( isset( $_GET['filter_term'] ) && 'all' !== $_GET['filter_term'] ) {
                $term = get_term_by_slug( $_GET['filter_term'] );

                if ( $term && ! is_wp_error( $term ) ) {
                    $tax_query = [
                        [
                            'field'    => 'slug',
                            'taxonomy' => $term->taxonomy,
                            'terms'    => [ $term->slug ]
                        ]
                    ];

                    $query->set( 'tax_query', $tax_query );
                }
            }
        }
    }
}
add_action( 'pre_get_posts', 'archive_filter_posts' );

function rename_post_object() {
    global $wp_post_types;
    $labels                     = &$wp_post_types['post']->labels;
    $labels->name               = __( 'News', 'hacklabr' );
    $labels->singular_name      = __( 'News', 'hacklabr' );
    $labels->add_new            = __( 'Add news', 'hacklabr' );
    $labels->add_new_item       = __( 'Add news', 'hacklabr' );
    $labels->edit_item          = __( 'Edit news', 'hacklabr' );
    $labels->new_item           = __( 'News', 'hacklabr' );
    $labels->view_item          = __( 'View news', 'hacklabr' );
    $labels->search_items       = __( 'Search news', 'hacklabr' );
    $labels->not_found          = __( 'No news found', 'hacklabr' );
    $labels->not_found_in_trash = __( 'No news found in Trash', 'hacklabr' );
    $labels->all_items          = __( 'All news', 'hacklabr' );
    $labels->menu_name          = __( 'News', 'hacklabr' );
    $labels->name_admin_bar     = __( 'News', 'hacklabr' );
}

add_action( 'init', 'rename_post_object' );

function override_storymap_template($template) {
	global $post;

	if (is_singular('storymap')) {
		return get_stylesheet_directory() . '/single-storymap.php';
	}

    if(is_singular('map')) {
        return get_stylesheet_directory() . '/single-map.php';
    }

	return $template;
}

add_filter('single_template', 'override_storymap_template', 20, 1);

function set_biblioteca_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'biblioteca' ) ) {
        $query->set( 'posts_per_page', 9 );
    }
}
add_action( 'pre_get_posts', 'set_biblioteca_posts_per_page' );

function set_author_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_author() ) {
        $query->set( 'posts_per_page', 4 );
    }
}
add_action( 'pre_get_posts', 'set_author_posts_per_page' );

add_action('pre_get_posts', function($query) {
    if (is_admin() || !$query->is_main_query()) return;

    if (is_post_type_archive('biblioteca')) {

        $meta_query = [];
        $tax_query  = [];

        // Filtro ano
        if (!empty($_GET['biblioteca_ano'])) {
            $meta_query[] = [
                'key'     => 'ano',
                'value'   => sanitize_text_field($_GET['biblioteca_ano']),
                'compare' => 'LIKE',
            ];
        }

        // Filtro co-author convidado
        if (!empty($_GET['biblioteca_coautor'])) {
            $ga = get_page_by_path(sanitize_text_field($_GET['biblioteca_coautor']), OBJECT, 'guest-author');
            if ($ga) {
                $tax_query[] = [
                    'taxonomy' => 'author', // taxonomia do Co-Authors Plus
                    'field'    => 'slug',
                    'terms'    => $ga->post_name,
                ];
            }
        }

        // Filtro mídia (Pods Relationship)
        if (!empty($_GET['biblioteca_midia'])) {
            $meta_query[] = [
                'key'     => 'tipo_de_midia',
                'value'   => sanitize_text_field($_GET['biblioteca_midia']),
                'compare' => 'LIKE',
            ];
        }

        // Filtro instância (Pods Relationship)
        if (!empty($_GET['biblioteca_instancia'])) {
            $meta_query[] = [
                'key'     => 'instancia',
                'value'   => sanitize_text_field($_GET['biblioteca_instancia']),
                'compare' => 'LIKE',
            ];
        }

        if ( ! empty($_GET['biblioteca_classe']) ) {
            $tax_query[] = [
                'taxonomy' => 'classe',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET['biblioteca_classe']),
            ];
        }

        // Novo filtro: relacionado_cpi
        if (isset($_GET['relacionado_cpi'])) {
            $relacionado = $_GET['relacionado_cpi'] === '1' ? 1 : 0;
            $meta_query = $query->get('meta_query') ?: [];
            $meta_query[] = [
                'key'     => 'relacionado_cpi',  // slug do campo PODS
                'value'   => $relacionado,
                'compare' => '=',
                'type'    => 'NUMERIC',
            ];
            $query->set('meta_query', $meta_query);
        }

        // aplica meta_query
        if (!empty($meta_query)) {
            $query->set('meta_query', $meta_query);
        }

        // aplica tax_query
        if (!empty($tax_query)) {
            $query->set('tax_query', array_merge(['relation' => 'AND'], $tax_query));
        }

        // pesquisa de texto
        if (!empty($_GET['biblioteca_search'])) {
            $query->set('s', sanitize_text_field($_GET['biblioteca_search']));
        }

        $query->set('post_type', 'biblioteca');
    }
});

//Limita titulos e resumos nos cards
function hacklabr_limit_text( $text, $limit = 200 ) {
    $text = wp_strip_all_tags( $text );
    if ( mb_strlen( $text ) > $limit ) {
        return mb_substr( $text, 0, $limit ) . '[...]';
    }
    return $text;
}

function hacklab_corrige_aria_cooltimeline( $content ) {
    if ( strpos( $content, 'cool-timeline' ) === false && strpos( $content, 'ctl-title' ) === false ) {
        return $content;
    }

    $pattern = '/(<div[^>]+class="[^"]*ctl-title[^"]*"[^>]*)(\saria-label="[^"]*")/i';
    $content = preg_replace( $pattern, '$1', $content );

    return $content;
}
add_filter( 'the_content', 'hacklab_corrige_aria_cooltimeline', 20 );

function hacklab_remove_redundant_link_titles( $content ) {
	if ( strpos( $content, '<a ' ) === false || strpos( $content, 'title=' ) === false ) {
		return $content;
	}

	libxml_use_internal_errors( true );

	$dom = new \DOMDocument();

	$html = '<?xml encoding="UTF-8"><div id="hacklab-wrap">' . $content . '</div>';

	if ( ! $dom->loadHTML( $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD ) ) {
		libxml_clear_errors();
		return $content;
	}

	$xpath = new \DOMXPath( $dom );
	$links = $xpath->query( '//*[@id="hacklab-wrap"]//a[@title]' );

	foreach ( $links as $link ) {
		$title = trim( $link->getAttribute( 'title' ) );
		$text  = trim( $link->textContent );

		if ( $title !== '' && $text !== '' && mb_strtolower( $title, 'UTF-8' ) === mb_strtolower( $text, 'UTF-8' ) ) {
			$link->removeAttribute( 'title' );
		}
	}

	$wrap = $dom->getElementById( 'hacklab-wrap' );
	if ( ! $wrap ) {
		libxml_clear_errors();
		return $content;
	}

	$out = '';
	foreach ( $wrap->childNodes as $child ) {
		$out .= $dom->saveHTML( $child );
	}

	libxml_clear_errors();

	return $out;
}
add_filter( 'the_content', 'hacklab_remove_redundant_link_titles', 25 );

function hacklab_buffer_start() {
    if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return;
    }

    ob_start( 'hacklab_fix_empty_links_in_output' );
}
add_action( 'template_redirect', 'hacklab_buffer_start' );

function hacklab_fix_empty_links_in_output( $html ) {
	if ( strpos( $html, '<a' ) === false ) {
		return $html;
	}

	$site_name = get_bloginfo( 'name' );

	return preg_replace_callback(
		'~<a\b([^>]*)>(.*?)</a>~is',
		function ( $m ) use ( $site_name ) {
			$attrs = $m[1];
			$inner = $m[2];

			if ( preg_match( '~\baria-label=|\baria-labelledby=~i', $attrs ) ) {
				return $m[0];
			}

			if ( preg_match( '~<\s*(svg|img|span|i|use|picture)\b~i', $inner ) ) {
				return $m[0];
			}

			$plain = trim( wp_strip_all_tags( $inner ) );
			if ( $plain !== '' ) {
				return $m[0];
			}

			if ( ! preg_match( '~\bhref=(["\'])(.*?)\1~is', $attrs, $hm ) ) {
				return $m[0];
			}

			$href = $hm[2];
			$label = '';

			if ( stripos( $href, 'instagram.com' ) !== false ) {
				$label = sprintf( 'Instagram de %s', $site_name );
			} elseif ( stripos( $href, 'facebook.com' ) !== false ) {
				$label = sprintf( 'Facebook de %s', $site_name );
			} elseif ( stripos( $href, 'twitter.com' ) !== false || stripos( $href, 'x.com' ) !== false ) {
				$label = sprintf( 'X (Twitter) de %s', $site_name );
			} elseif ( stripos( $href, 'youtube.com' ) !== false || stripos( $href, 'youtu.be' ) !== false ) {
				$label = sprintf( 'YouTube de %s', $site_name );
			} else {
				$label = __( 'Link', 'hacklabr' );
			}

			return '<a' . $attrs . ' aria-label="' . esc_attr( $label ) . '"></a>';
		},
		$html
	);
}

add_filter( 'wpml_ls_html', function ( $html, $args = [] ) {

	if ( empty( $html ) || strpos( $html, 'wpml-ls' ) === false ) {
		return $html;
	}

	// Mapeamento de siglas para nomes (ajuste se você tiver outros)
	$language_map = [
		'PT' => 'Português',
		'EN' => 'Inglês',
		'ES' => 'Espanhol',
	];

	// Pega todos os <li ... wpml-ls-item ...>...</li> do switcher
	if ( ! preg_match_all( '~<li\b[^>]*\bwpml-ls-item\b[^>]*>.*?</li>~is', $html, $matches ) ) {
		return $html;
	}

	$items = $matches[0];
	$total = count( $items );

	// Se por algum motivo vier vazio, não mexe
	if ( $total < 1 ) {
		return $html;
	}

	foreach ( $items as $i => $item_html ) {
		$pos = $i + 1;

		// Detecta se esse <li> é o idioma atual
		$is_current = (bool) preg_match( '~\bwpml-ls-current-language\b~i', $item_html );

		// Extrai o texto do idioma (geralmente "PT", "EN", "ES") do span wpml-ls-native
		$short = '';
		if ( preg_match( '~<span\b[^>]*\bwpml-ls-native\b[^>]*>(.*?)</span>~is', $item_html, $m ) ) {
			$short = trim( wp_strip_all_tags( $m[1] ) );
		}

		$short_up = strtoupper( $short );
		$language = $language_map[ $short_up ] ?? ( $short !== '' ? $short : 'Idioma' );

		$aria_label = $is_current
			? sprintf( 'Idioma %s selecionado %d de %d', $language, $pos, $total )
			: sprintf( 'Mudar para idioma %s %d de %d', $language, $pos, $total );

		// Atualiza o <a ... class="... wpml-ls-link ..."> dentro desse <li>
		$item_html_new = preg_replace_callback(
			'~<a\b([^>]*\bclass=(["\'])[^\2]*\bwpml-ls-link\b[^\2]*\2[^>]*)>~is',
			function ( $a ) use ( $aria_label, $is_current ) {
				$attrs = $a[1];

				// Remove atributos antigos que causam redundância
				$attrs = preg_replace(
					[
						'~\s+\btitle=(["\']).*?\1~is',
						'~\s+\baria-label=(["\']).*?\1~is',
					],
					'',
					$attrs
				);

				// Marca o idioma atual
				if ( $is_current ) {
					$attrs = preg_replace( '~\s+\baria-current=(["\']).*?\1~is', '', $attrs );
					$attrs .= ' aria-current="true"';
				}

				// Adiciona aria-label novo
				$attrs .= ' aria-label="' . esc_attr( $aria_label ) . '"';

				return '<a' . $attrs . '>';
			},
			$item_html,
			1
		);

		// Substitui somente esse item no HTML final
		$html = str_replace( $item_html, $item_html_new, $html );
	}

	return $html;

}, 20, 2 );

add_action('template_redirect', function () {

    if (is_admin() || wp_doing_ajax()) return;

    ob_start(function ($html) {
        $locale = function_exists('determine_locale') ? determine_locale() : get_locale();
        $lang = strtolower(substr($locale, 0, 2));

        $labels = [
            'pt' => ['prev' => 'Voltar',    'next' => 'Avançar'],
            'en' => ['prev' => 'Previous',  'next' => 'Next'],
            'es' => ['prev' => 'Anterior',  'next' => 'Siguiente'],
        ];
        $i18n = $labels[$lang] ?? $labels['pt'];

        $to_button = function ($match, $ariaLabel) {
            $attrs = $match[1];

            $attrs = preg_replace('/\s+tabindex=("|\').*?\1/i', '', $attrs);

            $attrs = preg_replace('/\s+aria-label=("|\').*?\1/i', '', $attrs);

            return '<button type="button"' . $attrs . ' aria-label="' . esc_attr($ariaLabel) . '"></button>';
        };

        $html = preg_replace_callback(
            '/<div([^>]*\bclass=("|\')[^"\']*\bglider-prev\b[^"\']*\2[^>]*)>\s*<\/div>/i',
            fn($m) => $to_button($m, $i18n['prev']),
            $html
        );

        $html = preg_replace_callback(
            '/<div([^>]*\bclass=("|\')[^"\']*\bglider-next\b[^"\']*\2[^>]*)>\s*<\/div>/i',
            fn($m) => $to_button($m, $i18n['next']),
            $html
        );

        return $html;
    });
}, 0);
