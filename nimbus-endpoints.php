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

			$artists_objects[] = array(
						'name' 				=> get_post_meta($artist->ID, 'nimbusnombre', true),
						'lastname'			=> get_post_meta($artist->ID, 'nimbusapellido_paterno', true),
						'second-lastname'	=> get_post_meta($artist->ID, 'nimbusapellido_materno', true),
						'artist-name'		=> get_post_meta($artist->ID, 'nimbusnombre_artistico', true),
						'email'				=> get_post_meta($artist->ID, 'nimbusemail', true),
						'phone'				=> get_post_meta($artist->ID, 'nimbusfono', true),
						'facebook'			=> get_post_meta($artist->ID, 'nimbusfacebook', true),
						'instagram'			=> get_post_meta($artist->ID, 'nimbusinstagram', true),
						'webs'				=> get_post_meta($artist->ID, 'nimbusweb', true),
						'additional-info'	=> get_post_meta($artist->ID, 'nimbusinfo_adicional'),
						'works'				=> rwmb_meta('nimbusobra', array('medium'), $artist->ID),
						'videos'			=> nimbus_videoartist($artistid)
						);
		}

		return $artists_objects;
	} else {
		return 'False';
	}

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

add_action( 'rest_api_init', 'nimbus_artists_endpoint');

function nimbus_artists_endpoint() {
    register_rest_route('nimbus/v1/', '/artists/', array(
            'methods' => 'GET',
            'callback' => 'nimbus_artists'
            )
        );

}