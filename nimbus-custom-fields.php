<?php
/**
 * Plugin Name:       Campos personalizados para Nimbus
 * Plugin URI:        https://apie.cl
 * Description:       Info de artista y otros campos.
 */

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
                'type' => 'divider',
            ],
            [
                'type' => 'heading',
                'name' => esc_html__( 'Obra 1', 'nimbus' ),
            ],
            [
                'type' => 'file_input',
                'name' => esc_html__( 'Imagen obra 1', 'nimbus' ),
                'id'   => $prefix . 'imagen_obra_1',
            ],
            [
                'type' => 'number',
                'name' => esc_html__( 'Año obra 1', 'nimbus' ),
                'id'   => $prefix . 'year_obra_1',
                'step' => 1,
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'heading',
                'name' => esc_html__( 'Obra 2', 'nimbus' ),
            ],
            [
                'type' => 'file_input',
                'name' => esc_html__( 'Imagen obra 2', 'nimbus' ),
                'id'   => $prefix . 'imagen_obra_2',
            ],
            [
                'type' => 'number',
                'name' => esc_html__( 'Año obra 2', 'nimbus' ),
                'id'   => $prefix . 'year_obra_2',
                'step' => 1,
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'heading',
                'name' => esc_html__( 'Obra 3', 'nimbus' ),
            ],
            [
                'type' => 'file_input',
                'name' => esc_html__( 'Imagen obra 3', 'nimbus' ),
                'id'   => $prefix . 'imagen_obra_3',
            ],
            [
                'type' => 'number',
                'name' => esc_html__( 'Año obra 3', 'nimbus' ),
                'id'   => $prefix . 'year_obra_3',
                'step' => 1,
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'heading',
                'name' => esc_html__( 'Obra 4', 'nimbus' ),
            ],
             [
                'type' => 'file_input',
                'name' => esc_html__( 'Imagen obra 4', 'nimbus' ),
                'id'   => $prefix . 'imagen_obra_4',
            ],
            [
                'type' => 'number',
                'name' => esc_html__( 'Año obra 4', 'nimbus' ),
                'id'   => $prefix . 'year_obra_4',
                'step' => 1,
            ],
            [
                'type' => 'divider',
            ],
            [
                'type' => 'heading',
                'name' => esc_html__( 'Obra 5', 'nimbus' ),
            ],
             [
                'type' => 'file_input',
                'name' => esc_html__( 'Imagen obra 5', 'nimbus' ),
                'id'   => $prefix . 'imagen_obra_5',
            ],
            [
                'type' => 'number',
                'name' => esc_html__( 'Año obra 5', 'nimbus' ),
                'id'   => $prefix . 'year_obra_5',
                'step' => 1,
            ],
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