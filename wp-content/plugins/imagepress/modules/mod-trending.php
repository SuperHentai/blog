<?php

/*
 * Main random function [imagepress_random]
 *
 */
add_shortcode('imagepress-wall', 'imagepress_wall');

function imagepress_wall($atts, $content = null) {
	extract(shortcode_atts(array(
		'category'    => '',
        'limit'       => 48,
		'size'        => 'medium',
        'type'        => 'latest'
	), $atts));

    $ip_unique_id = uniqid();

    // main images query
	$out = '';

    // all filters should be applied here
    if($type == 'trending') {
        $args = array(
            'post_type' 				=> get_option('ip_slug'),
            'posts_per_page' 			=> $limit,
            'orderby' 					=> 'date',
            'order' 					=> 'DESC',

            'meta_key' => 'votes_count',
            'meta_query' => array(
                array(
                    'key' => 'votes_count',
                    'value' => 50,
                    'type' => 'numeric',
                    'compare' => '>='
                )
            ),
            'meta_query' => array(
                array(
                    'key' => 'post_views_count',
                    'value' => 100,
                    'type' => 'numeric',
                    'compare' => '>='
                )
            ),

            'date_query' => array(
                array(
                    'column' => 'post_date_gmt',
                    'after'  => '90 days ago',
                )
            ),

            'cache_results' => false,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'no_found_rows' => true,
        );
    }
    if($type == 'latest') {
        $args = array(
            'post_type' 				=> get_option('ip_slug'),
            'posts_per_page' 			=> $limit,
            'orderby' 					=> 'date',
            'order' 					=> 'DESC',

            'cache_results' => false,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'no_found_rows' => true,
        );
    }
    $posts = get_posts($args);
    //

	if($posts) {
		$out .= '<style>
		#contain { width: 100%; margin: auto; }
		.box { background: rgb(0, 0, 0); width: 100px; height: 100px; }
		.size2-2 {
	width: 40px;
	height: 40px;
}

.size11 {
	height: 80px;
	width: 80px;
}

.size12 {
	width: 80px;
	height: 160px;
}

.size21 {
	width: 160px;
	height: 80px;
}

.size22 {
	height: 160px;
	width: 160px;
}

.size13 {
	width: 80px;
	height: 240px;
}

.size31 {
	width: 240px;
	height: 80px;
}

.size23 {
	width: 160px;
	height: 240px;
}

.size24 {
	width: 160px;
	height: 320px;
}

.size32 {
	width: 240px;
	height: 160px;
}

.size33 {
	width: 240px;
	height: 240px;
}

.size34 {
	width: 240px;
	height: 320px;
}

.size43 {
	width: 320px;
	height: 240px;
}

.size35 {
	width: 240px;
	height: 400px;
}

.size53 {
	width: 400px;
	height: 240px;
}

.size36 {
	width: 240px;
	height: 480px;
}
		</style>
		<script src="http://vnjs.net/www/project/freewall/freewall.js"></script>
		<script>
		jQuery(function() {
      var wall = new freewall("#contain");
	  wall.reset({
				animate: true,
				cellW: "auto",
				cellH: "auto",
				gutterX: 4,
				gutterY: 4,
				onResize: function() {
					wall.fitWidth();
				}
			});
      wall.fitWidth();
    });
	</script>';
        $out .= '<div id="contain">';

        foreach($posts as $user_image) {
            setup_postdata($user_image);

			$post_thumbnail_id = get_post_thumbnail_id($user_image->ID);   

			// get attachment source
			$image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');

			if(get_option('ip_click_behaviour') == 'media')
				$ip_image_link = $image_attributes[0];
			if(get_option('ip_click_behaviour') == 'custom')
				$ip_image_link = get_permalink($user_image->ID);

            if(!empty($size))
                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, $size); 
            else
                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, get_option('ip_image_size')); 

            $arrX = array("size2-2", "size11", "size21", "size32", "size12", "size22", "size23", "size13", "size31", "size24", "size33", "size34", "size35", "size43", "size53", "size36");
            $randIndex = array_rand($arrX);
            $ccc = $arrX[$randIndex];
            $out .= '<div class="box ' . $ccc . '"><a href="' . $ip_image_link . '" style="background: url(' . $image_attributes[0] . ') no-repeat center center;"></a></div>';
		}

		$out .= '</div>';

		return $out;
	} else {
		$out .= __('No images found!', 'imagepress');
		return $out;
	}

	return $out;
}
?>
