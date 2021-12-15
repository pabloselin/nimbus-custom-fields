<?php 

function nimbus_artists() {
	//endpoints here
	/* 
		artist object:
			name
			lastname
			second-lastname
			artist-name
			email
			phone
			facebook
			instagram
			web
			info-adicional
			obras	
	*/

	$args 		= array(
					'post_type'		=> 'artistas',
					'numberposts'	=> -1
					);
	$artists 	= get_posts($args);

	if($artists) {
		foreach($artists as $artist) {

			$artists_objects[] = nimbus_populateartist($artist->ID, $artist->post_name);
		}

		return $artists_objects;
	} else {
		return 'False';
	}

}

function nimbus_populateartist($artistid, $slug) {

		$artistobj = array(
						'name' 				=> get_post_meta($artistid, 'nimbusnombre', true),
						'id'				=> $artistid,
						'slug'				=> $slug,
						'lastname'			=> get_post_meta($artistid, 'nimbusapellido_paterno', true),
						'secondlastname'	=> get_post_meta($artistid, 'nimbusapellido_materno', true),
						'artistname'		=> get_post_meta($artistid, 'nimbusnombre_artistico', true),
						'email'				=> get_post_meta($artistid, 'nimbusemail', true),
						'phone'				=> get_post_meta($artistid, 'nimbusfono', true),
						'facebook'			=> get_post_meta($artistid, 'nimbusfacebook', true),
						'instagram'			=> get_post_meta($artistid, 'nimbusinstagram', true),
						'webs'				=> get_post_meta($artistid, 'nimbusweb', true),
						'additionalinfo'	=> get_post_meta($artistid, 'nimbusinfo_adicional', true),
						'works'				=> nimbus_artistworks($artistid),
						'videos'			=> nimbus_videoartist($artistid),
						'disciplines'		=> nimbus_get_plainterms_item($artistid, 'disciplina'),
						'territories'		=> nimbus_get_plainterms_item($artistid, 'territorio')	
						);	

		return $artistobj;			
}

function nimbus_artistworks($artistid) {
	$works = rwmb_meta('nimbusobra', array('medium'), $artistid);
	$arrworks = [];
	foreach($works as $work) {
		//extrametadata
		$workobj = (object) [
			'year' => get_post_meta($work["ID"], 'year_obra', true),
			'technique' => get_post_meta($work["ID"], 'tecnica_obra', true),
			'measures' => get_post_meta($work["ID"], 'medidas', true),
			'images' => $work
			];
		$arrworks[] = $workobj;
	}

	return $arrworks;
}

function nimbus_videoartist($artistid) {
	$args = array(
		'post_type'		=> 'videos',
		'numberposts'	=> -1,
		'meta_key'		=> 'nimbus_artista_asociado',
		'meta_value'	=> $artistid
	);

	$videos = get_posts($args);
	if($videos) {
		foreach($videos as $video) {
			$videos_objects[] = array(
				'video_url' 		=> get_post_meta($video->ID, 'nimbus_url_video', true),
				'chapter-number'	=> get_post_meta($video->ID, 'nimbus_numero_de_capitulo', true),
				'chapter-series-number'	=> get_post_meta($video->ID, 'nimbus_numero_de_la_serie', true),
			);
		}

		return $videos_objects;
	}
}

function nimbus_get_artist_single( $request ) {
	$slug = $request['slug'];

	if($slug) {
			$args = array(
			'post_type' 	=> 'artistas',
			'name' 			=> $slug,
			'numberposts'	=> 1
		);

		$artist = get_posts($args);

		if($artist) {
			return nimbus_populateartist($artist[0]->ID, $artist[0]->post_name);
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function nimbus_get_artist_tax( $request ) {
	$discipline 	= $request['disciplina'];
	$territory		= $request['territorio'];

	if($discipline) {
		$terms = $discipline;
		$taxonomy = 'disciplina';
	} elseif($territory) {
		$terms = $territory;
		$taxonomy = 'territorio';
	}

	$args = array(
			'post_type'		=> 'artistas',
			'tax_query'		=> array(
					array(
						'taxonomy'	=> $taxonomy,
						'field'		=> 'slug',
						'terms'		=> $terms
					)
				)
			);

	$artists = get_posts($args);
	if($artists) {
		$jsonartists = [];
		foreach($artists as $artist) {
			$jsonartists[] = nimbus_populateartist($artist->ID, $artist->post_name);
		}
		return $jsonartists;	
	} else {
		return false;
	}
	
}

function nimbus_search_artist( $request ) {
	$searchterm = $request['searchterm'];

	$args = array(
		'post_type'	=> 'artistas',
		's'			=> $searchterm
	);

	$search = get_posts($args);
	if($search) {
		foreach($search as $artist) {
			$jsonartists[] = nimbus_populateartist($artist->ID, $artist->post_name);
		}
		return $jsonartists;
	} else {
		return false;
	}
}

add_action( 'rest_api_init', 'nimbus_artists_routes');

function nimbus_artists_routes() {
    register_rest_route('nimbus/v1/', '/artists/', array(
            'methods' => 'GET',
            'callback' => 'nimbus_artists'
            )
        );

    register_rest_route('nimbus/v1/', '/artistsingle/', array(
            'methods' 	=> 'GET',
            'callback' 	=> 'nimbus_get_artist_single',
            'args'		=> array(
            	'slug' => array(
            			'validate_callback' => function($param, $request, $key) {
            				return sanitize_text_field( $param );
            			}
            		)
            )
        ));

    register_rest_route('nimbus/v1/', '/artistsearch/', array(
            'methods' 	=> 'GET',
            'callback' 	=> 'nimbus_search_artist',
            'args'		=> array(
            	'searchterm' => array(
            			'validate_callback' => function($param, $request, $key) {
            				return sanitize_text_field( $param );
            			}
            		)
            )
        ));

    register_rest_route('nimbus/v1/', '/artiststax/', array(
    		'methods'	=> 'GET',
    		'callback'	=> 'nimbus_get_artist_tax',
    		'args'		=> array(
			    			'disciplina' => array(
			            			'validate_callback' => function($param, $request, $key) {
			            				return sanitize_text_field( $param );
			            			}
			            	),
			    			'territorio' => array(
			            			'validate_callback' => function($param, $request, $key) {
			            				return sanitize_text_field( $param );
			            			}
			            	)
    					)
    	));
}
