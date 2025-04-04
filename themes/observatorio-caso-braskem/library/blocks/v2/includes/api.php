<?php

namespace hacklabr\v2;

use phpDocumentor\Reflection\Types\Null_;

/**
 * Registers REST API endpoints.
 *
 * Use the callback functions in the same order as the endpoints you created
 */
function register_endpoints() {
    register_rest_route(
        'hacklabr/v1',
        '/posttypes',
        [
            'methods'  => 'GET',
            'callback' => 'hacklabr\\v2\\get_public_post_types',
            'permission_callback' => '__return_true'
        ]
    );

    register_rest_route(
        'hacklabr/v1',
        '/taxonomias/(?P<post_type>[a-zA-Z0-9_-]+)',
        [
            'methods'  => 'GET',
            'callback' => 'hacklabr\\v2\\get_taxonomies_by_post_type',
            'args'     => [
                'post_type' => [
                    'required' => true,
                    'validate_callback' => function( $param, $request, $key ) {
                        return post_type_exists( $param );
                    }
                ],
            ],
            'permission_callback' => '__return_true'
        ]
    );

    register_rest_route(
        'hacklabr/v1',
        '/posts/(?P<post_type>[a-zA-Z0-9_-]+)',
        [
            'methods'  => 'GET',
            'callback' => 'hacklabr\\v2\\get_posts_by_taxonomy_term',
            'args'     => [
                'post_type' => [
                    'required' => true,
                    'validate_callback' => function( $param, $request, $key ) {
                        return post_type_exists( sanitize_text_field( $param ) );
                    }
                ],
                'taxonomy' => [
                    'required' => false,
                    'validate_callback' => function( $param, $request, $key ) {
                        return taxonomy_exists( sanitize_text_field( $param ) );
                    }
                ],
                'terms' => [
                    'required' => false,
                    'validate_callback' => function( $param, $request, $key ) {
                        $terms = explode( ',', sanitize_text_field( $request['terms'] ) );
                        foreach ( $terms as $term ) {
                            if ( is_numeric( $term ) ) {
                                $term = intval( $term );
                            }

                            if ( ! term_exists( $term, $request['taxonomy'] ) ) {
                                return false;
                            }
                        }
                        return true;
                    }
                ],
                'max_posts' => [
                    'required' => false,
                    'default' => 10,
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_numeric( $param ) && $param > 0;
                    }
                ],
                'per_page' => [
                    'required' => false,
                    'default' => 10,
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_numeric( $param ) && $param > 0;
                    }
                ],
                'page' => [
                    'required' => false,
                    'default' => 1,
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_numeric( $param ) && $param > 0;
                    }
                ],
                'post_not_in' => [
                    'required' => false,
                    'validate_callback' => function( $param, $request, $key ) {
                        $post_not_in = explode( ',', sanitize_text_field( $request['post_not_in'] ) );
                        foreach ( $post_not_in as $post_id ) {
                            return is_numeric( $post_id );
                        }

                        return true;
                    }
                ],
                'post_parent' => [
                    'required' => false,
                    'validate_callback' => function( $param, $request, $key ) {
                        return is_numeric( $param );
                    }
                ],
                'no_taxonomy' => [
                    'required' => false,
                    'validate_callback' => function( $param, $request, $key ) {
                        return taxonomy_exists( sanitize_text_field( $param ) );
                    }
                ],
                'no_post_type' => [
                    'required' => false,
                    'validate_callback' => function( $param, $request, $key ) {
                        return post_type_exists( sanitize_text_field( $param ) );
                    }
                ],
                'no_query_terms' => [
                    'required' => false,
                    'validate_callback' => function( $param, $request, $key ) {
                        $terms = explode( ',', sanitize_text_field( $request['no_query_terms'] ) );
                        foreach ( $terms as $term ) {

                            if ( is_numeric( $term ) ) {
                                $term = intval( $term );
                            }

                            if ( ! term_exists( $term, $request['no_taxonomy'] ) ) {
                                return false;
                            }
                        }
                        return true;
                    }
                ],
                'only_columnist' => [
                    'required' => false,
                    'validate_callback' => '__return_true'
                ]
            ],
            'permission_callback' => '__return_true'
        ]
    );

    register_rest_route(
        'hacklabr/v1',
        '/coauthors',
        [
            'methods'  => 'GET',
            'callback' => 'hacklabr\\v2\\get_coauthors_callback',
            'permission_callback' => '__return_true'
        ]
    );

    register_rest_route(
        'hacklabr/v1',
        '/flickr_albums',
        [
            'methods' => 'GET',
            'callback' => 'hacklabr\\v2\\flickr_album_rest_callback',
            'args' => [
                'api_key' => [
                    'required' => true,
                ],
                'page' => [
                    'default' => 1,
                ],
                'user_id' => [
                    'required' => true,
                ],
            ],
            'permission_callback' => function () {
                return current_user_can( 'edit_posts' );
            },
        ]
    );

    register_rest_route(
        'hacklabr/v1',
        '/flickr_page',
        [
            'methods' => 'GET',
            'callback' => 'hacklabr\\v2\\flickr_page_rest_callback',
            'args' => [
                'api_key' => [
                    'required' => true,
                ],
                'data_id' => [
                    'required' => true,
                ],
                'page' => [
                    'required' => true,
                ],
                'type' => [
                    'required' => true,
                ],
            ],
            'permission_callback' => '__return_true',
        ]
    );
}

add_action( 'rest_api_init', 'hacklabr\\v2\\register_endpoints' );

function get_public_post_types( $request ) {
    $args = [
        'public' => true,
        'publicly_queryable' => true
    ];

    $post_types_objects = get_post_types( $args, 'objects' );

    unset( $post_types_objects['attachment'] );
    unset( $post_types_objects['header-footer'] );

    $post_types = [];
    $post_types[] = __( 'Select an option', 'hacklabr' );

    foreach ( $post_types_objects as $post_type ) {
        $post_types[$post_type->name] = $post_type->label;
    }

    $post_types = apply_filters( 'hacklabr/helpers/post_types', $post_types );

    return new \WP_REST_Response( $post_types, 200 );
}

function get_taxonomies_by_post_type( $request ) {
    $post_type  = sanitize_text_field( $request['post_type'] );
    $taxonomies = get_object_taxonomies( $post_type, 'objects' );
    $response   = [];

    $remove_taxonomies = ['post_format', 'author'];

    foreach ( $taxonomies as $taxonomy ) {
        if ( ! in_array( $taxonomy->name, $remove_taxonomies ) ) {
            $response[] = [
                'label' => $taxonomy->label,
                'value' => $taxonomy->name
            ];
        }
    }

    /**
     * Filters the list of taxonomies associated with a post type.
     *
     * @param array  $response   The list of taxonomies associated with the post type.
     * @param string $post_type  The post type for which the taxonomies are being retrieved.
     *
     * @return array The modified list of taxonomies.
     */
    $response = apply_filters( 'hacklabr/helpers/taxonomies', $response, $post_type );

    array_unshift( $response, [
        'label' => __( 'Select an option', 'hacklabr' ),
        'value' => ''
    ] );

    return new \WP_REST_Response( $response, 200 );
}

function get_posts_by_taxonomy_term( $request ) {
    $compare        = ! empty( $request['compare'] ) ? sanitize_text_field( $request['compare'] ) : 'OR';
    $post_type      = sanitize_text_field( $request['post_type'] );
    $taxonomy       = sanitize_text_field( $request['taxonomy'] );
    $terms          = explode( ',', sanitize_text_field( $request['terms'] ) );
    $max_posts      = ! empty( $request['max_posts']) ? intval( $request['max_posts'] ) : 10;
    $per_page       = ! empty( $request['per_page'] ) ? intval( $request['per_page'] ) : 10;
    $page           = ! empty( $request['page'] ) ? intval( $request['page'] ) : 1;
    $post__not_in   = explode( ',', sanitize_text_field( $request['post_not_in'] ) );
    $show_children  = ! empty( intval( $request['post_parent'] ) );
    $only_columnist = ! empty( $request['only_columnist'] ) ? true : false;

    // Exclude posts
    $no_compare     = ! empty( $request['no_compare'] ) ? sanitize_text_field( $request['no_compare'] ) : 'OR';
    $no_post_type   = ! empty( $request['no_post_type'] ) ? $request['no_post_type'] : '';
    $no_taxonomy    = ! empty( $request['no_taxonomy'] ) ? $request['no_taxonomy'] : '';
    $no_query_terms = explode( ',', sanitize_text_field( $request['no_query_terms'] ) );

    $no_post__not_in = [];

    if ( $no_post_type ) {
        $no_args = [
            'post_type'      => $no_post_type,
            'posts_per_page' => -1,
            'fields'         => 'ids'
        ];

        if ( $no_taxonomy && $no_query_terms ) {
            $no_args['tax_query'] = ['relation' => $no_compare];

            foreach ( $no_query_terms as $no_term ) {
                $no_args['tax_query'][] = [
                    'taxonomy' => $no_taxonomy,
                    'field'    => 'term_id',
                    'terms'    => [$no_term]
                ];
            }

            $no_post__not_in = get_posts( $no_args );
        }
    }

    $no_found_rows = $page === 1 ? false : true;

    $args = [
        'post_type'           => $post_type,
        'posts_per_page'      => $per_page,
        'paged'               => $page,
        'no_found_rows'       => false,
        'ignore_sticky_posts' => true
    ];

    $args['post__not_in'] = array_merge(
        $no_post__not_in,
        $post__not_in
    );

    if ( ! $show_children ) {
        $args['post_parent'] = 0;
    }

    $terms_filter = array_filter( $terms, function( $item ) {
        return trim( $item ) !== "";
    } );

    if ( $taxonomy && $terms_filter ) {
        $args['tax_query'] = ['relation' => $compare];

        foreach ( $terms_filter as $term ) {
            $field = is_numeric( $term ) ? 'term_id' : 'slug';

            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => $field,
                'terms'    => [$term]
            ];
        }
    }

    if( $only_columnist ) {
        $args['meta_query'] = [
            [
                'key' => 'colunista',
                'value' => '1',
                'compare' => '='
            ]
        ];
    }

    $query = new \WP_Query( $args );
    $data = [];

    foreach ( $query->posts as $post ) {

        $thumbnail = '';

        if ( $post_type === 'opiniao' && \function_exists( 'get_coauthors' ) ) {
            $get_coauthors = \get_coauthors( $post->ID );

            foreach ( $get_coauthors as $coauthor ) {
                if ( get_post_meta( $coauthor->ID, 'colunista', true ) ) {
                    $thumbnail = get_the_post_thumbnail_url( $coauthor->ID );

                    if ( $thumbnail ) {
                        break;
                    }
                }

                if ( has_post_thumbnail( $coauthor->ID ) ) {
                    $thumbnail = get_the_post_thumbnail_url( $coauthor->ID );
                }
            }
        } else {
            $thumbnail = has_post_thumbnail( $post ) ? get_the_post_thumbnail_url( $post ) : get_stylesheet_directory_uri() . '/assets/images/default-image.png';
        }

        if ( ! $thumbnail ) {
            $thumbnail = get_stylesheet_directory_uri() . '/assets/images/default-avatar.png';
        }

        if( $post_type == 'guest-author' ) {
            $data[] = [
                'ID'        => $post->ID,
                'link'      => get_author_posts_url( $post->id, str_ireplace( 'cap-', '', $post->post_name ) ),
                'thumbnail' => has_post_thumbnail( $post ) ? get_the_post_thumbnail_url( $post ) : get_stylesheet_directory_uri() . '/assets/images/default-avatar.png',
                'title'     => $post->post_title,
            ];
        } else {
            $data[] = [
                'ID'        => $post->ID,
                'author'    => get_list_coauthors( $post->ID ),
                'date'      => date_i18n( 'd \d\e F \d\e Y', strtotime( $post->post_date ) ),
                'excerpt'   => $post->post_excerpt,
                'link'      => get_the_permalink( $post ),
                'thumbnail' => $thumbnail,
                'title'     => $post->post_title,
            ];
        }
    }

    if ( empty( $data ) ) {
        return new \WP_Error( 'no_posts', 'Nenhum post encontrado com os critérios especificados', ['status' => 404] );
    }

    return new \WP_REST_Response( [
        'posts'      => $data,
        'totalPages' => ceil( min( $max_posts, $query->found_posts ) / $per_page ),
    ], 200 );
}

function get_coauthors_callback( $request ) {

    if ( is_plugin_active( 'co-authors-plus/co-authors-plus.php' ) ) {
        global $coauthors_plus;
        $author_terms = get_terms( ['taxonomy' => $coauthors_plus->coauthor_taxonomy] );

        $data = [];

        foreach ( $author_terms as $author_term ) {
            $author_object = $coauthors_plus->get_coauthor_by( 'user_nicename', $author_term->slug );

            $data[] = [
                'id'   => $author_object->ID,
                'display_name' => $author_object->display_name,
                'user_nicename' => $author_object->user_nicename
            ];
        }

        array_unshift( $data, [
            'id'    => 0,
            'display_name' => __( 'Select an option', 'hacklabr' ),
            'user_nicename' => ''
        ] );

        $data = apply_filters( 'hacklabr/helpers/coauthors', $data );

        if ( ! empty( $data ) ) {
            return new \WP_REST_Response( $data, 200 );
        }

    }

    return new \WP_Error( 'no_posts', 'Nenhum coauthor encontrado', ['status' => 404] );
}

/**
 * Adds custom fields to a response post.
 */
function add_fields_to_post() {
    register_rest_field( 'post', 'main_category', [
        'get_callback' => function( $attr ) {
            $main_category = get_primary_term( $attr['id'], 'category' );

            if ( ! empty( $main_category ) ) {
                return [
                    'link'             => get_term_link( $main_category->term_id, 'category' ),
                    'name'             => $main_category->name,
                    'slug'             => $main_category->slug
                ];
            } else {
                return null;
            }
        }
    ] );
}

function flickr_album_rest_callback( \WP_REST_Request $request ) {
    require_once __DIR__ . '/includes/flickr.php';
    return flickr_get_albums( $request['api_key'], $request['user_id'], 10, $request['page'] );
}

function flickr_page_rest_callback( \WP_REST_Request $request ) {
    require_once __DIR__ . '/includes/flickr.php';

    $api_key = flickr_decrypt_key( $request['api_key'] );

    ob_start();

    if ( $request['type'] === 'albums' ) {
        $data = flickr_get_albums( $api_key, $request['data_id'], 9, intval( $request['page'] ) );

        if ( ! empty( $data ) ) {
            foreach( $data['data'] as $album ) {
                get_template_part( 'library/blocks/v2/src/flickr-gallery/template-parts/album', null, [ 'album' => $album ] );
            }
        }
    } else {
        $data = flickr_get_photos( $api_key, $request['type'], $request['data_id'], 9, intval( $request['page'] ) );

        if ( ! empty( $data ) ) {
            foreach( $data['data'] as $photo ) {
                get_template_part( 'library/blocks/v2/src/flickr-gallery/template-parts/photo', null, [ 'photo' => $photo ] );
            }
        }
    }

    $html = ob_get_clean();

    return new \WP_REST_Response( [ 'html' => $html ], 200 );
}

add_action( 'rest_api_init', 'hacklabr\\v2\\add_fields_to_post' );
