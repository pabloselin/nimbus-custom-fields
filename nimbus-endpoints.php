<?php 

function nimbus_artists($randomize = false) {
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

	$order = $randomize ? 'rand' : 'meta_value';

	$args 		= array(
					'post_type'		=> 'artistas',
					'numberposts'	=> -1,
					'orderby'		=> $order,
					'meta_key'		=> 'nimbusapellido_paterno',
					'order'			=> 'ASC'
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

function nimbus_artistslider() {
	$artists = nimbus_artists(true);
	$works = [];
	if($artists) {
		foreach($artists as $artist) {
			$works[] = $artist['works'][0];
		}	
	}

	return $works;
}

function nimbus_videos() {
	$args 		= array(
					'post_type'		=> 'videos',
					'numberposts'	=> -1,
					'orderby'		=> 'meta_value_num',
					'meta_key'		=> 'nimbusnumero_de_capitulo',
					'order'			=> 'ASC'
					);
	$videos 	= get_posts($args);

	if($videos) {
		foreach($videos as $video) {

			$videos_objects[] = nimbus_populatevideo($video->ID, $video->post_name);
		}

		return $videos_objects;
	} else {
		return 'False';
	}
}

function nimbus_populatevideo($videoid, $slug) {
	$videopost = get_post($videoid);
	$videoobj = array(
					'name' 		=> $videopost->post_title,
					'id' 		=> $videoid,
					'slug'		=> $slug,
					'video_url' 			=> get_post_meta($videopost->ID, 'nimbusurl_video', true),
					'video_id'				=> youtube_id_from_url(urldecode(rawurldecode(get_post_meta($videopost->ID, 'nimbusurl_video', true)))),
					'chapter_number'		=> get_post_meta($videopost->ID, 'nimbusnumero_de_capitulo', true),
					'chapter_series_number'	=> get_post_meta($videopost->ID, 'nimbusnumero_de_la_serie', true),
					'chapter_content'		=> apply_filters('the_content', $videopost->post_content),
					'duracion'				=> get_post_meta($videopost->ID, 'nimbusduracion', true),
					'subtitulos'			=> get_post_meta($videopost->ID, 'nimbussubtitulos', true),
					'audio'					=> get_post_meta($videopost->ID, 'nimbusaudio', true)
				);

	return $videoobj;
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
						'instagram_b'		=> get_post_meta($artistid, 'nimbusinstagram_b', true),
						'webs'				=> get_post_meta($artistid, 'nimbusweb', true),
						'additionalinfo'	=> get_post_meta($artistid, 'nimbusinfo_adicional', true),
						'works'				=> nimbus_artistworks($artistid),
						'videos'			=> nimbus_videoartist($artistid),
						'disciplines'		=> nimbus_get_plainterms_structured($artistid, 'disciplina'),
						'territories'		=> nimbus_get_plainterms_item($artistid, 'territorio')
						);	

		return $artistobj;			
}

function nimbus_artistworks($artistid) {
	$works = rwmb_meta('nimbusobra', array('medium'), $artistid);
	$arrworks = [];
	$artist = get_post($artistid);
	foreach($works as $work) {
		//extrametadata
		$workobj = (object) [
			'artist' 	=> $artist->post_title,
			'slug'		=> $artist->post_name,
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
		'meta_key'		=> 'nimbusartista_asociado',
		'meta_value'	=> $artistid
	);

	$videos = get_posts($args);
	if($videos) {
		foreach($videos as $video) {
			$videos_objects[] = array(
				'video_url' 			=> get_post_meta($video->ID, 'nimbusurl_video', true),
				'video_id'				=> youtube_id_from_url(urldecode(rawurldecode(get_post_meta($video->ID, 'nimbusurl_video', true)))),
				'chapter_number'		=> get_post_meta($video->ID, 'nimbusnumero_de_capitulo', true),
				'chapter_series-number'	=> get_post_meta($video->ID, 'nimbusnumero_de_la_serie', true),
				'chapter_content'		=> apply_filters('the_content', $video->post_content),
				'chapter_title'			=> $video->post_title,
				'slug'					=> $video->post_name
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

function nimbus_get_video_single( $request ) {
	$slug = $request['slug'];

	if($slug) {
			$args = array(
			'post_type' 	=> 'videos',
			'name' 			=> $slug,
			'numberposts'	=> 1
		);

		$video = get_posts($args);

		if($video) {
			return nimbus_populatevideo($video[0]->ID, $video[0]->post_name);
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

    register_rest_route('nimbus/v1/', '/videos/', array(
            'methods' => 'GET',
            'callback' => 'nimbus_videos'
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

        register_rest_route('nimbus/v1/', '/videosingle/', array(
            'methods' 	=> 'GET',
            'callback' 	=> 'nimbus_get_video_single',
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

  /*
    * get youtube video ID from URL
    *
    * @param string $url
    * @return string Youtube video id or FALSE if none found. 
    */
    function youtube_id_from_url($url) {
            $pattern = 
                '%^# Match any youtube URL
                (?:https?://)?  # Optional scheme. Either http or https
                (?:www\.)?      # Optional www subdomain
                (?:             # Group host alternatives
                  youtu\.be/    # Either youtu.be,
                | youtube\.com  # or youtube.com
                  (?:           # Group path alternatives
                    /embed/     # Either /embed/
                  | /v/         # or /v/
                  | /watch\?v=  # or /watch\?v=
                  )             # End path alternatives.
                )               # End host alternatives.
                ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
                $%x'
                ;
            $result = preg_match($pattern, $url, $matches);
            if ($result) {
                return $matches[1];
            }
            return false;
        }