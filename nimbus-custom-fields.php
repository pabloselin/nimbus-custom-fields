<?php 

add_filter( 'rwmb_meta_boxes', 'nimbus_register_meta_boxes' );

function nimbus_register_meta_boxes( $meta_boxes ) {
    $prefix = 'nimbus';

    $meta_boxes[] = [
        'title'    => esc_html__( 'Información artista', 'nimbus' ),
        'id'       => 'infoartista',
        'context'  => 'normal',
        'post_types'    => ['artistas'],
        'autosave' => true,
        'fields'   => [
           
            [
                'type' => 'text',
                'name' => esc_html__( 'Nombre', 'nimbus' ),
                'id'   => $prefix . 'nombre',
            ],
            [
                'type' => 'text',
                'name' => esc_html__( 'Apellido paterno', 'nimbus' ),
                'id'   => $prefix . 'apellido_paterno',
            ],
            [
                'type' => 'text',
                'name' => esc_html__( 'Apellido materno', 'nimbus' ),
                'id'   => $prefix . 'apellido_materno',
            ],
            [
                'type' => 'text',
                'name' => esc_html__( 'Nombre artístico', 'nimbus' ),
                'id'   => $prefix . 'nombre_artistico',
            ],
             [
                'type' => 'email',
                'name' => esc_html__( 'Email', 'nimbus' ),
                'id'   => $prefix . 'email',
            ],
             [
                'type' => 'text',
                'name' => esc_html__( 'Teléfono', 'nimbus' ),
                'id'   => $prefix . 'fono',
            ],
               [
                'type' => 'text',
                'name' => esc_html__( 'Facebook', 'nimbus' ),
                'id'   => $prefix . 'facebook',
            ],
            [
                'type' => 'text',
                'name' => esc_html__( 'Instagram', 'nimbus' ),
                'id'   => $prefix . 'instagram',
            ],
            [
                'type' => 'text',
                'name' => esc_html__( 'Instagram (cuenta adicional)', 'nimbus' ),
                'id'   => $prefix . 'instagram_b',
            ],
            [
                'type'    => 'fieldset_text',
                'name'    => esc_html__( 'Web', 'nimbus' ),
                'id'      => $prefix . 'web',
                'desc'    => esc_html__( 'Webs del artista', 'nimbus' ),
                'clone' => true,
                'options' => [
                    'linkinfo' => 'URL',
                ],
            ],
            [
                'type'    => 'fieldset_text',
                'name'    => esc_html__( 'Info adicional', 'nimbus' ),
                'id'      => $prefix . 'info_adicional',
                'desc'    => esc_html__( 'Links - videos con texto explicativo', 'nimbus' ),
                'clone' => true,
                'options' => [
                    'descinfo' => 'Descripción',
                    'linkinfo' => 'URL',
                ],
            ],
             
            [
                'type'      => 'image_advanced',
                'name'      => esc_html__('Obras', 'nimbus'),
                'id'        => $prefix . 'obra',
                'desc'      => esc_html__('Imagen e información de cada obra')
            ]
        ],
    ];

    return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'nimbus_videos_register_meta_boxes' );

function nimbus_videos_register_meta_boxes( $meta_boxes ) {
    $prefix = 'nimbus';

    $meta_boxes[] = [
        'title'      => esc_html__( 'Datos video', 'nimbus' ),
        'id'         => 'datos_video',
        'post_types' => ['videos'],
        'context'    => 'normal',
        'fields'     => [
            [
                'type' => 'oembed',
                'name' => esc_html__( 'URL Video', 'nimbus' ),
                'id'   => $prefix . 'url_video',
                'desc' => esc_html__( 'Url video Youtube', 'nimbus' ),
            ],
            [
                'type' => 'number',
                'name' => esc_html__( 'Número de capítulo', 'nimbus' ),
                'id'   => $prefix . 'numero_de_capitulo',
            ],
            [
                'type' => 'number',
                'name' => esc_html__( 'Número de la serie / temporada', 'nimbus' ),
                'id'   => $prefix . 'numero_de_la_serie',
            ],
            [
                'type'       => 'post',
                'name'       => esc_html__( 'Artista asociado', 'nimbus' ),
                'id'         => $prefix . 'artista_asociado',
                'post_type'  => 'artistas',
                'field_type' => 'select_advanced',
            ],
        ],
    ];

    return $meta_boxes;
}

