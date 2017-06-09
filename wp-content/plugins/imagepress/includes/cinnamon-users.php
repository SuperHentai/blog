<?php
/* CINNAMON FUNCTIONS */

function cinnamon_count_user_posts_by_type($userid, $post_type = 'post') {
	global $wpdb;

    $ip_slug = get_option('ip_slug');

    $where = get_posts_by_author_sql($ip_slug, true, $userid);
	$count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts $where");

  	return apply_filters('get_usernumposts', $count, $userid);
}

function cinnamon_last_login($login) {
    $user = get_user_by('login', $login);
    update_user_meta($user->ID, 'cinnamon_action_time', current_time('mysql'));
}

function get_cinnamon_login($user_id) {
	$last_login = get_user_meta($user_id, 'cinnamon_action_time', true);
	$date_format = get_option('date_format') . ' ' . get_option('time_format');
	$the_last_login = mysql2date($date_format, $last_login, false);
	return $the_last_login;
}

function update_cinnamon_action_time() {
    $user = wp_get_current_user();
    update_user_meta($user->ID, 'cinnamon_action_time', current_time('mysql'));
}

function cinnamon_PostViews($id, $count = true) {
    $axCount = get_user_meta($id, 'ax_post_views', true);
    if($axCount == '')
        $axcount = 0;

	if($count == true) {
		$axCount++;
		update_user_meta($id, 'ax_post_views', $axCount);
	}

    return $axCount;
}

function cinnamon_author_base() {
    global $wp_rewrite;

    $cinnamon_author_slug = get_option('cinnamon_author_slug');
    $author_slug = $cinnamon_author_slug; // change slug name
    $wp_rewrite->author_base = $author_slug;
}

function cinnamon_get_related_author_posts($author) {
    global $post;

    $ip_slug = get_option('ip_slug');
    $authors_posts = get_posts(array(
        'author' => $author,
        'posts_per_page' => 9,
        'post_type' => $ip_slug
    ));

    $output = '';
    if($authors_posts) {
        $output .= '
        <div class="cinnamon-grid"><ul>';
            foreach($authors_posts as $authors_post) {
                $output .= '<li><a href="' . get_permalink($authors_post->ID) . '">' . get_the_post_thumbnail($authors_post->ID, 'thumbnail') . '</a></li>';
            }
        $output .= '</ul></div>';
    }

    return $output;
}

function cinnamon_extra_contact_info($contactmethods) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);

    $contactmethods['facebook'] = 'Facebook';
    $contactmethods['twitter'] = 'Twitter';
    $contactmethods['googleplus'] = 'Google+';
    $contactmethods['behance'] = 'Behance';

    return $contactmethods;
}

function cinnamon_get_portfolio_posts($author, $count, $size = 'thumbnail') {
    global $post;

    $ip_slug = get_option('ip_slug');
    $authors_posts = get_posts(array(
        'author' => $author,
        'post_type' => $ip_slug,
        'posts_per_page' => $count,
        'meta_key' => 'imagepress_sticky',
        'meta_value' => 1,
    ));

    $output = '';
    if($authors_posts) {
        $output .= '<div id="cinnamon-index"><a href="#"><i class="fa fa-th-large"></i> ' . get_option('cinnamon_label_index') . '</a></div>
        <div id="cinnamon-feature"></div>
        <div class="cinnamon-grid-blank">';
            foreach($authors_posts as $authors_post) {
                $post_thumbnail_id = get_post_thumbnail_id($authors_post->ID);
                $post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
                $output .= '<a href="#" rel="' . $post_thumbnail_url . '">' . get_the_post_thumbnail($authors_post->ID, $size) . '</a>';
            }
        $output .= '</div>';
    }

    return $output;
}



function user_query_count_post_type($args) {
    $args->query_from = str_replace("post_type = 'post' AND", "post_type IN ('" . get_option('ip_slug') . "') AND ", $args->query_from);
}


/* CINNAMON CARD SHORTCODE */
function cinnamon_card($atts, $content = null) {
	extract(shortcode_atts(array(
        'author' => '',
        'count' => 99999,
        'perpage' => 99999,
        'sort' => 0
    ), $atts));

    global $post;

    $ip_slug = get_option('ip_slug');

    if(empty($author))
        $author = get_current_user_id();

    $display = '';

	add_action('pre_user_query', 'user_query_count_post_type', 1);
	$hub_users = get_users(array('number' => $count, 'order' => 'DESC', 'orderby' => 'post_count'));

	$display .= '<div id="cinnamon-cards">';

	$display .= '<div class="cinnamon-sortable">
		<div class="innersort">
			<h4>' . __('Sort', 'imagepress') . '</h4>
			<span class="sort" data-sort="name"><i class="fa fa-circle fa-fw"></i> ' . __('A-Z', 'imagepress') . '</span>
			<span class="sort initial" data-sort="uploads"><i class="fa fa-circle fa-fw"></i> ' . __('Most uploads', 'imagepress') . '</span>
			<span class="sort" data-sort="followers"><i class="fa fa-circle fa-fw"></i> ' . __('Most followers', 'imagepress') . '</span>
		</div>
		<div class="innersort">
			<h4>' . get_option('ip_author_find_title') . '</h4>
			<input type="text" class="search" placeholder="' . get_option('ip_author_find_placeholder') . '">
		</div>
		<div style="clear: both;"></div>
	</div>';

	$display .= '<ul class="list">';

    foreach($hub_users as $user) {
        $author = $user->ID;
        $hub_user_info = get_userdata($author);
		$hub_location = get_the_author_meta('hub_location', $author);

        if($hub_user_info->first_name != '' && !empty($hub_location) && cinnamon_count_user_posts_by_type($author, $ip_slug) > 0) {
            $display .= '<li class="cinnamon-card">';
                $authors_posts = get_posts(array(
                    'author' => $author,
                    'posts_per_page' => get_option('ip_cards_per_author'),
                    'post_type' => $ip_slug
                ));

				if($authors_posts) {
                    $display .= '<div class="mosaicflow">';
                        foreach($authors_posts as $authors_post) {
                            $display .= '<div><a href="' . get_permalink($authors_post->ID) . '">' . get_the_post_thumbnail($authors_post->ID, get_option('ip_cards_image_size')) . '</a></div>';
                        }
                    $display .= '</div>';
                }

				$display .= '<div class="avatar-holder"><a href="' . get_author_posts_url($author) . '">' . get_avatar($author, 104) . '</a></div>';

                if(get_the_author_meta('user_title', $author) == 'Verified')
                    $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fa fa-check-square"></i></span>';
                else
                    $verified = '';
                $display .= '<h3><a href="' . get_author_posts_url($author) . '" class="name">' . $hub_user_info->first_name . ' ' . $hub_user_info->last_name . '</a>' . $verified . '</h3>';
				if(!empty($hub_location))
					$display .= '<div class="location-holder"><small><i class="fa fa-map-marker teal"></i> <span class="location">' . get_the_author_meta('hub_location', $author) . '</span></small></div>';

				$display .= '<div class="cinnamon-stats">
					<div class="cinnamon-meta"><span class="views">' . kformat(cinnamon_PostViews($author, false)) . '</span><br><small>' . __('views', 'imagepress') . '</small></div>
					<div class="cinnamon-meta"><span class="followers">' . kformat(pwuf_get_follower_count($author)) . '</span><br><small>' . __('followers', 'imagepress') . '</small></div>
					<div class="cinnamon-meta"><span class="uploads">' . kformat(cinnamon_count_user_posts_by_type($author, $ip_slug)) . '</span><br><small>' . __('uploads', 'imagepress') . '</small></div>
				</div>';
            $display .= '</li>';
        }
    }
    $display .= '</ul><ul class="pagination"></ul></div>';
    $display .= '<div style="clear: both;"></div>';

    return $display;
}

/* CINNAMON PROFILE (BLANK) SHORTCODE */
function cinnamon_profile_blank($atts, $content = null) {
	extract(shortcode_atts(array('author' => ''), $atts));

    $author = get_user_by('slug', get_query_var('author_name'));
    $author = $author->ID;

    $author_rewrite = get_user_by('slug', get_query_var('author_name'));
    $author_rewrite = $author_rewrite->user_login;

    if(empty($author))
        $author = get_current_user_id();

    $hub_user_info = get_userdata($author);
    $ip_slug = get_option('ip_slug');

    $hub_googleplus = ''; $hub_facebook = ''; $hub_twitter = ''; $hub_behance = '';
    if($hub_user_info->googleplus != '')
        $hub_googleplus = '<a href="' . $hub_user_info->googleplus . '" target="_blank"><i class="fa fa-google-plus-square"></i></a>';
    if($hub_user_info->facebook != '')
        $hub_facebook = '<a href="' . $hub_user_info->facebook . '" target="_blank"><i class="fa fa-facebook-square"></i></a>';
    if($hub_user_info->twitter != '')
        $hub_twitter = '<a href="https://twitter.com/' . $hub_user_info->twitter . '" target="_blank"><i class="fa fa-twitter-square"></i></a>';
    if($hub_user_info->behance != '')
        $hub_behance = '<a href="https://www.behance.net/' . $hub_user_info->behance . '" target="_blank"><i class="fa fa-behance-square"></i></a>';

    $hub_email = '<a href="mailto:' . get_the_author_meta('email', $author) . '" target="_blank"><i class="fa fa-envelope-square"></i></a>';

	$display = '';

	// themes // 1.0
	$theme = get_user_meta($author, 'cinnamon_portfolio_theme', true);
	if(empty($theme)) {
		$theme = 'default';
		update_user_meta($author, 'cinnamon_portfolio_theme', $theme);
	}

	if($theme == 'default') {
		$display .= '<style>.cornholio { max-width: 930px; margin: 0 auto; } .cornholio .c-main { text-align: center; font-size: 32px; font-weight: 300; } .cornholio .c-description { text-align: center; font-size: 14px; font-weight: 300; } .cornholio .c-social { text-align: center; font-size: 24px; } .cornholio .c-footer { text-align: center; font-size: 12px; }</style>';
		$display .= '<style>html, body { background-color: ' . get_user_meta($author, 'hub_portfolio_bg', true) . '; color: ' . get_user_meta($author, 'hub_portfolio_text', true) . '; } a, a:hover { color: ' . get_user_meta($author, 'hub_portfolio_link', true) . '; } ul#tab li.active a { border-bottom: 1px solid ' . get_user_meta($author, 'hub_portfolio_link', true) . '; }</style>';
		$cinnamon_size = 'thumbnail';
	}
	if($theme == 'sidebar-left') {
		$display .= '<style>.cornholio { max-width: 100%; margin: 0 auto; } .cornholio .c-main { text-align: center; font-size: 32px; font-weight: 300; } .cornholio .c-description { display: none; text-align: center; font-size: 14px; font-weight: 300; } .cornholio .c-social { text-align: center; font-size: 24px; margin: 16px 0; } .cornholio .c-footer { text-align: center; font-size: 12px; } .cornholio-top { width: 20%; float: left; padding-top: 64px; } .cornholio-bottom { width: 80%; float: right; border-left: 1px solid ' . get_user_meta($author, 'hub_portfolio_link', true) . '; } .cinnamon-grid-blank img { width: 248px; height: auto; } .about { padding: 90px 64px 256px 64px; } hr { border-top: 1px solid ' . get_user_meta($author, 'hub_portfolio_link', true) . '; opacity: 0.25; } #tab { box-shadow: none; text-align: left; } ul#tab li.active a { font-weight: 700; } #tab li { display: block; } #tab li a { margin: 0; }</style>';
		$display .= '<style>html, body { background-color: ' . get_user_meta($author, 'hub_portfolio_bg', true) . '; color: ' . get_user_meta($author, 'hub_portfolio_text', true) . '; } a, a:hover { color: ' . get_user_meta($author, 'hub_portfolio_link', true) . '; } ul#tab li.active a { border-bottom: 1px solid ' . get_user_meta($author, 'hub_portfolio_link', true) . '; }</style>';
		$cinnamon_size = 'imagepress_sq_std';
	}
	// end themes

	$display .= '<script>jQuery(document).ready(function(){ jQuery("' . get_option('cinnamon_hide') . '").hide(); });</script>
	<div class="cornholio">
		<div class="cornholio-top">
			<div class="c-main">' . $hub_user_info->first_name . ' ' . $hub_user_info->last_name . '</div>
			<div class="c-description"> ' . get_the_author_meta('hub_field', $author) . '<br><small>' . get_the_author_meta('hub_location', $author) . '</small></div>
			<div class="c-social">' . $hub_facebook . ' ' . $hub_twitter . ' ' . $hub_googleplus . ' ' . $hub_behance . ' ' . $hub_email;
				if($hub_user_info->user_url != '')
					$display .= ' <a href="' . $hub_user_info->user_url . '" rel="external" target="_blank"><i class="fa fa-link"></i></a>';
			$display .= '</div>

			<ul id="tab">
				<li><a href="#" class="c-index">' . get_option('cinnamon_label_portfolio') . '</a></li>
				<li><a href="#">' . get_option('cinnamon_label_about') . '</a></li>
			</ul>
		</div>';

        $display .= '<div class="cornholio-bottom">
			<div class="ip_clear"></div>
			<div class="tab_icerik">';
				$display .= cinnamon_get_portfolio_posts($author, 12, $cinnamon_size);
			$display .= '</div>
			<div class="tab_icerik about">
				<h3>' . get_option('cinnamon_label_about') . '</h3>';
				$display .= make_clickable(wpautop($hub_user_info->description));
			$display .= '</div>
		</div>';

			$display .= '<div class="ip_clear"></div><hr><div class="c-footer">&copy; ' . $hub_user_info->first_name . ' ' . $hub_user_info->last_name . ' ' . date('Y') . '</div>';
			$display .= '<div class="c-footer">Portfolio provided by ' . get_option('cinnamon_profile_title') . '</div>';
    $display .= '</div>';

    return $display;
}

/* CINNAMON PROFILE SHORTCODE */
function cinnamon_profile($atts, $content = null) {
	extract(shortcode_atts(array('author' => ''), $atts));

    $author = get_user_by('slug', get_query_var('author_name'));
    $author = $author->ID;

    $author_rewrite = get_user_by('slug', get_query_var('author_name'));
    $author_rewrite = $author_rewrite->user_login;

    $ip_slug = get_option('ip_slug');

    if(empty($author))
        $author = get_current_user_id();

    $hub_user_info = get_userdata($author);

    $display = '';

    $hub_googleplus = ''; $hub_facebook = ''; $hub_twitter = ''; $hub_behance = ''; $hub_user_url = '';
    if($hub_user_info->googleplus != '')
        $hub_googleplus = ' <a href="' . $hub_user_info->googleplus . '" target="_blank"><i class="fa fa-google-plus-square"></i></a>';
    if($hub_user_info->facebook != '')
        $hub_facebook = ' <a href="' . $hub_user_info->facebook . '" target="_blank"><i class="fa fa-facebook-square"></i></a>';
    if($hub_user_info->twitter != '')
        $hub_twitter = ' <a href="https://twitter.com/' . $hub_user_info->twitter . '" target="_blank"><i class="fa fa-twitter-square"></i></a>';
    if($hub_user_info->behance != '')
        $hub_behance = ' <a href="https://www.behance.net/' . $hub_user_info->behance . '" target="_blank"><i class="fa fa-behance-square"></i></a>';

    $hca = get_the_author_meta('hub_custom_cover', $author);
    $hca = wp_get_attachment_url($hca);
    if(!isset($hca) || empty($hca))
        $hca = IP_PLUGIN_URL . '/img/coverimage.png';

    $logged_in_user = wp_get_current_user();
    $display .= '<div class="profile-hub-container">';
        $hub_url = $hub_user_info->user_url;
        $hub_field = get_the_author_meta('hub_field', $author);
        $hub_software = get_the_author_meta('hub_software', $author);
        $hub_employer = get_the_author_meta('hub_employer', $author);
        $hub_location = get_the_author_meta('hub_location', $author);

        $display .= '<div class="cinnamon-cover" style="background: url(' . $hca . ') no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"><div class="cinnamon-opaque"></div>';

            if(is_user_logged_in() && $author_rewrite == $logged_in_user->user_login)
                $display .= '<div class="overlay"><a href="' . get_option('cinnamon_edit_page') . '" class="imagepress-button"><i class="fa fa-pencil-square-o"></i> ' . get_option('cinnamon_edit_label') . '</a></div>';

            $display .= '<div class="cinnamon-avatar"><div class="cinnamon-user">' . get_avatar($author, 120) . '';
                if(is_user_logged_in() && $author_rewrite != $logged_in_user->user_login)
                    $display .= '<div class="imagepress-follow">' . do_shortcode('[follow_links follow_id="' . $author . '"]') . '</div>';
				$display .= '</div>';

				if(get_the_author_meta('user_title', $author) == 'Verified')
                    $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fa fa-check-square"></i></span>';
                else
                    $verified = '';

                // get custom URL
                $hubprotocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
                $hubdomain = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']);
                $hubuser = get_user_by('id', $author);
                $hubuser = sanitize_title($hubuser->user_login);
                $hub_name = $hub_user_info->first_name . ' ' . $hub_user_info->last_name;
                if(empty($hub_user_info->first_name) && empty($hub_user_info->last_name))
                    $hub_name = $hubuser;

				if($hub_user_info->user_url != '')
					$hub_user_url = ' <a href="' . $hub_user_info->user_url . '" rel="external" target="_blank"><i class="fa fa-link"></i></a>';

                $display .= '<div>
					<div class="ph-nametag">' . $hub_name . $verified . '</div>
					<div class="ph-locationtag">';
						if(!empty($hub_location))
							$display .= '<br><b>Location</b> ' . $hub_location;
						if(!empty($hub_field))
							$display .= '<br><b>Field</b> ' . $hub_field;
						$display .= '<br><b>Connect</b> ' . $hub_facebook . $hub_twitter . $hub_googleplus . $hub_behance . $hub_user_url .
					'</div>
				</div>';
            $display .= '</div>';
        $display .= '</div>';

		$display .= '<div class="tab">
			<ul class="tabs active">';
				if(get_option('cinnamon_show_uploads') == 1)
					$display .= '<li class="current"><a href="#">' . __('Uploads', 'imagepress') . '</a></li>';

				$display .= '<li><a href="#">' . __('About', 'imagepress') . '</a></li>';

				if(get_option('cinnamon_show_followers') == 1)
					$display .= '<li><a href="#">' . __('Followers', 'imagepress') . '</a></li>';
				if(get_option('cinnamon_show_following') == 1)
					$display .= '<li><a href="#">' . __('Following', 'imagepress') . '</a></li>';
				if(get_option('cinnamon_show_likes') == 1)
					$display .= '<li><a href="#">' . __('Loved', 'imagepress') . ' ' . get_option('ip_slug') . 's</a></li>';
				if(get_option('cinnamon_show_awards') == 1)
					$display .= '<li><a href="#">' . __('Awards', 'imagepress') . '</a></li>';

				$display .= '<li><a href="#">' . __('Activity', 'imagepress') . '</a></li>';

				if(get_option('ip_mod_collections') == 1) {
					$display .= '<li><a href="#">' . __('Collections', 'imagepress') . '</a></li>';
				}

				$display .= '<li style="float: right;">';
					// Cinnamon Stats
					$display .= '<div class="cinnamon-stats">';
						$display .= '<div class="cinnamon-meta"><b>' . kformat(cinnamon_PostViews($author)) . '</b><br><small>' . __('views', 'imagepress') . '</small></div>';
						$display .= '<div class="cinnamon-meta"><b>' . kformat(pwuf_get_follower_count($author)) . '</b><br><small>' . __('followers', 'imagepress') . '</small></div>';
						$display .= '<div class="cinnamon-meta"><b>' . kformat(cinnamon_count_user_posts_by_type($author, $ip_slug)) . '</b><br><small>' . __('uploads', 'imagepress') . '</small></div>';

						$display .= '<div class="cinnamon-meta">';
							if(get_option('cinnamon_mod_hub') == 1)
                    			$display .= '<a href="' . $hubprotocol . $hubuser . '.' . $hubdomain . '" class="imagepress-button" target="_blank"><i class="fa fa-th"></i> ' . get_option('cinnamon_label_hub') . '</a>';
							if(get_the_author_meta('hub_status', $author) == 1)
								$display .= ' <a href="mailto:' . get_the_author_meta('email', $author) . '" class="imagepress-button"><i class="fa fa-envelope"></i></a>';
						$display .= '</div>';
					$display .= '</div>';
				$display .= '</li>';
			$display .= '</ul>
			<div class="tab_content">';
				if(get_option('cinnamon_show_uploads') == 1)
					$display .= '<div class="tabs_item" style="display: block;">' . do_shortcode('[imagepress-show user="' . $author . '" sort="no"]') . '</div>';

				$display .= '<div class="tabs_item" style="display: none;">';
					$display .= '<cite>';
						if(!empty($hub_user_info->description))
							$display .= make_clickable(wpautop($hub_user_info->description));
						$display .= '<br>';
						if(!empty($hub_software))
							$display .= '<p><b>' . __('Preferred Software', 'imagepress') . ':</b><br>' . $hub_software . '</p>';
						if(!empty($hub_employer))
							$display .= '<p><b>' . __('Employer', 'imagepress') . ':</b><br>' . $hub_employer . '</p>';
					$display .= '</cite>';

					if(get_option('cinnamon_show_map') == 1 && get_the_author_meta('hub_location', $author) != '')
						$display .= '<p><img src="http://maps.googleapis.com/maps/api/staticmap?center=' . get_the_author_meta('hub_location', $author) . '&zoom=13&size=640x320&maptype=terrain&sensor=false"></p>';
				$display .= '</div>';

				if(get_option('cinnamon_show_followers') == 1) {
					$display .= '<div class="tabs_item" style="display: none;">';
						$arr = pwuf_get_followers($author);
						if($arr) {
							$display .= '<div class="cinnamon-followers">';
								foreach($arr as $value) {
									$user = get_user_by('id', $value);
									$display .= '<a href="' . get_author_posts_url($value) . '">' . get_avatar($value, 40) . '</a> ';
								}
								unset($value);
							$display .= '</div>';
						}
					$display .= '</div>';
				}

				if(get_option('cinnamon_show_following') == 1) {
					$display .= '<div class="tabs_item" style="display: none;">';
						$arr = pwuf_get_following($author);
						if($arr) {
							$display .= '<div class="cinnamon-followers">';
								foreach($arr as $value) {
									$user = get_user_by('id', $value);
									$display .= '<a href="' . get_author_posts_url($value) . '">' . get_avatar($value, 40) . '</a> ';
								}
								unset($value);
							$display .= '</div>';
						}
					$display .= '</div>';
				}

				if(get_option('cinnamon_show_likes') == 1) {
					$display .= '<div class="tabs_item" style="display: none;">';
						$display .= frontEndUserLikes($author);
					$display .= '</div>';
				}

				if(get_option('cinnamon_show_awards') == 1) {
					$display .= '<div class="tabs_item" style="display: none;">';
						$award_terms = wp_get_object_terms($author, 'award');
						if(!empty($award_terms)) {
							if(!is_wp_error($award_terms)) {
								foreach($award_terms as $term) {
									// get custom FontAwesome
									$t_ID = $term->term_id;
									$term_data = get_option("taxonomy_$t_ID");

									$display .= '<span class="cinnamon-award-list-item" title="' . $term->description . '">';
										if(isset($term_data['img']))
											$display .= '<i class="fa ' . $term_data['img'] . '"></i> ';
										else
											$display .= '<i class="fa fa-trophy"></i> ';
									$display .= $term->name . '</span>';
								}
							}
						}
					$display .= '</div>';
				}

				if(get_option('cinnamon_show_activity') == 1) {
					$display .= '<div class="tabs_item" style="display: none;">';
						if(get_option('cinnamon_show_comments') == 1) {
							$args = array(
								'user_id' => $author,
								'status' => 'approve',
								'number' => 10
							);
							$comments = get_comments($args);
							foreach($comments as $comment) :
								$display .= '<div class="ip-post"><time><small>' . $comment->comment_date . '</small></time><br><a href="' . get_permalink($comment->comment_post_ID) . '"><i class="fa fa-comments"></i> ' . $comment->comment_content . '</a></div>';
							endforeach;
						}
						if(get_option('cinnamon_show_posts') == 1) {
							$args = array(
								'author' => $author,
								'post_type' => 'post',
								'posts_per_page' => 10
							);
							$my_query = null;
							$my_query = new WP_Query($args);
							if($my_query->have_posts()) {
								while($my_query->have_posts()) : $my_query->the_post();
									$display .= '<div class="ip-post"><time><small>' . get_the_date() . '</small></time><br><a href="' . get_permalink() . '"><i class="fa fa-file-text"></i> ' . get_the_title() . '</a><p><small>' . get_the_excerpt() . '</small></p></div>';
								endwhile;
							}
							wp_reset_query();
						}
						if(get_option('cinnamon_show_online') == 1)
							$display .= '<p><small>' . __('Last seen on', 'imagepress') . ' ' . get_cinnamon_login($author) . ' (' . human_time_diff(strtotime(get_cinnamon_login($author))) . ' ' . __('ago', 'imagepress') . ')<br>' . __('Joined on', 'imagepress') . ' ' . $hub_user_info->user_registered . '</small></p>';
					$display .= '</div>';
				}

				if(get_option('ip_mod_collections') == 1) {
					$display .= '<div class="tabs_item" style="display: none;">';
						$display .= ip_collections_display_public($author);
					$display .= '</div>';
				}

			$display .= '</div>
		</div>';





        $display .= '<div style="clear: both;"></div>';


    $display .= '</div>';

    return $display;
}

function cinnamon_profile_edit($atts, $content = null) {
	extract(shortcode_atts(array('author' => ''), $atts));

    global $wpdb, $current_user;
    get_currentuserinfo();

    $error = array();    

    if('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'update-user') {
        if(!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
            if($_POST['pass1'] == $_POST['pass2'])
                wp_update_user(array('ID' => $current_user->ID, 'user_pass' => esc_attr($_POST['pass1'])));
            else
                $error[] = __('The passwords you entered do not match. Your password was not updated.', 'imagepress');
        }

        if(!empty($_POST['url']))
            wp_update_user(array('ID' => $current_user->ID, 'user_url' => esc_url($_POST['url'])));
        if(!empty($_POST['email'])) {
            if(!is_email(esc_attr($_POST['email'])))
                $error[] = __('The email you entered is not valid. Please try again.', 'imagepress');
            elseif(email_exists(esc_attr($_POST['email'])) != $current_user->ID)
                $error[] = __('This email is already used by another user. Try a different one.', 'imagepress');
            else {
                wp_update_user(array('ID' => $current_user->ID, 'user_email' => esc_attr($_POST['email'])));
            }
        }

        if(!empty($_POST['first-name']))
            update_user_meta($current_user->ID, 'first_name', esc_attr($_POST['first-name']));
        if(!empty($_POST['last-name']))
            update_user_meta($current_user->ID, 'last_name', esc_attr($_POST['last-name']));

        if(!empty($_POST['nickname'])) {
            update_user_meta($current_user->ID, 'nickname', esc_attr($_POST['nickname']));
            $wpdb->update($wpdb->users, array('display_name' => $_POST['nickname']), array('ID' => $current_user->ID), null, null);
        }

        if(!empty($_POST['description']))
            update_user_meta($current_user->ID, 'description', esc_attr($_POST['description']));

        if(!empty($_POST['facebook']))
            update_user_meta($current_user->ID, 'facebook', esc_attr($_POST['facebook']));
        if(!empty($_POST['twitter']))
            update_user_meta($current_user->ID, 'twitter', esc_attr($_POST['twitter']));
        if(!empty($_POST['googleplus']))
            update_user_meta($current_user->ID, 'googleplus', esc_attr($_POST['googleplus']));
        if(!empty($_POST['behance']))
            update_user_meta($current_user->ID, 'behance', esc_attr($_POST['behance']));

        // avatar and cover upload
        if($_FILES) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');

			foreach($_FILES as $file => $array) {
                if(!empty($_FILES[$file]['name'])) {
                    $file_id = media_handle_upload($file, 0);
                    if($file_id > 0) {
                        update_user_meta($current_user->ID, $file, $file_id);
                    }
                }
            }   
        }
        //

        if(count($error) == 0) {
            do_action('edit_user_profile_update', $current_user->ID);
            echo '<p class="message noir-success">' . __('Profile updated successfully!', 'imagepress') . '</p>';
        }
    }
    ?>

    <div id="post-<?php the_ID(); ?>">
        <div class="entry-content entry cinnamon">
            <?php if(!is_user_logged_in()) : ?>
                    <p class="warning"><?php _e('You must be logged in to edit your profile.', 'imagepress'); ?></p>
            <?php else : ?>
                <?php if(count($error) > 0) echo '<p class="error">' . implode('<br>', $error) . '</p>'; ?>

				<form method="post" id="adduser" action="<?php the_permalink(); ?>" enctype="multipart/form-data">
					<div class="tab">
						<ul class="tabs active">
							<li class="current"><a href="#"><?php echo get_option('cinnamon_pt_account'); ?></a></li>
							<li><a href="#"><?php echo get_option('cinnamon_pt_social'); ?></a></li>
							<li><a href="#"><?php echo get_option('cinnamon_pt_author'); ?></a></li>
							<li><a href="#"><?php echo get_option('cinnamon_pt_profile'); ?></a></li>
							<li><a href="#"><?php echo get_option('cinnamon_pt_portfolio'); ?></a></li>
							<?php if(get_option('ip_mod_collections') == 1) { ?>
								<li><a href="#" class="imagepress-collections">Collections</a></li>
							<?php } ?>
						</ul>
						<div class="tab_content">
							<div class="tabs_item" style="display: block;">
								<table class="form-table">
									<tr>
										<th><label for="first-name"><?php _e('First name', 'imagepress'); ?></label></th>
										<td><input name="first-name" type="text" id="first-name" value="<?php the_author_meta('first_name', $current_user->ID); ?>"></td>
									</tr>
									<tr>
										<th><label for="last-name"><?php _e('Last name', 'imagepress'); ?></label></th>
										<td><input name="last-name" type="text" id="last-name" value="<?php the_author_meta('last_name', $current_user->ID); ?>"></td>
									</tr>
									<tr>
										<th><label for="nickname"><?php _e('Nickname', 'imagepress'); ?></label></th>
										<td><input name="nickname" type="text" id="nickname" value="<?php the_author_meta('nickname', $current_user->ID); ?>"></td>
									</tr>
									<tr>
										<th><label for="email"><?php _e('E-mail *', 'imagepress'); ?></label></th>
										<td><input name="email" type="text" id="email" value="<?php the_author_meta('user_email', $current_user->ID); ?>"></td>
									</tr>
									<tr>
										<th><label for="url"><?php _e('Website', 'imagepress'); ?></label></th>
										<td><input name="url" type="text" id="url" value="<?php the_author_meta('user_url', $current_user->ID); ?>"></td>
									</tr>
									<tr>
										<th><label for="pass1"><?php _e('Password *', 'imagepress'); ?></label></th>
										<td><input name="pass1" type="password" id="pass1"></td>
									</tr>
									<tr>
										<th><label for="pass2"><?php _e('Repeat password *', 'imagepress'); ?></label></th>
										<td><input name="pass2" type="password" id="pass2"></td>
									</tr>
									<tr>
										<th><label for="description"><?php _e('Biographical information', 'imagepress'); ?></label></th>
										<td><textarea name="description" id="description" rows="4" style="width: 100%;"><?php the_author_meta('description', $current_user->ID); ?></textarea></td>
									</tr>
								</table>
							</div>
							<div class="tabs_item" style="display: none;">
								<table class="form-table">
									<tr>
										<th><label for="facebook"><i class="fa fa-facebook-square"></i> <?php _e('Facebook profile URL', 'imagepress'); ?></label></th>
										<td><input name="facebook" type="url" id="facebook" value="<?php the_author_meta('facebook', $current_user->ID); ?>"></td>
									</tr>
									<tr>
										<th><label for="twitter"><i class="fa fa-twitter-square"></i> <?php _e('Twitter username', 'imagepress'); ?></label></th>
										<td><input name="twitter" type="text" id="twitter" value="<?php the_author_meta('twitter', $current_user->ID); ?>"></td>
									</tr>
									<tr>
										<th><label for="googleplus"><i class="fa fa-google-plus-square"></i> <?php _e('Google+ profile URL', 'imagepress'); ?></label></th>
										<td><input name="googleplus" type="url" id="googleplus" value="<?php the_author_meta('googleplus', $current_user->ID); ?>"></td>
									</tr>
									<tr>
										<th><label for="behance"><i class="fa fa-behance-square"></i> <?php _e('Behance username', 'imagepress'); ?></label></th>
										<td><input name="behance" type="text" id="behance" value="<?php the_author_meta('behance', $current_user->ID); ?>"></td>
									</tr>
								</table>
							</div>
							<div class="tabs_item" style="display: none;">
								<table class="form-table">
									<tr>
										<th><label for="hub_location"><?php _e('Location', 'imagepress'); ?></label></th>
										<td>
											<input type="text" name="hub_location" id="hub_location" value="<?php echo esc_attr(get_the_author_meta('hub_location', $current_user->ID)); ?>" class="regular-text">
										</td>
									</tr>
									<tr>
										<th><label for="hub_employer"><?php _e('Employer', 'imagepress'); ?></label></th>
										<td>
											<input type="text" name="hub_employer" id="hub_employer" value="<?php echo esc_attr(get_the_author_meta('hub_employer', $current_user->ID)); ?>" class="regular-text">
										</td>
									</tr>
									<tr>
										<th><label for="hub_field"><?php _e('Occupational field', 'imagepress'); ?></label></th>
										<td>
											<input type="text" name="hub_field" id="hub_field" value="<?php echo esc_attr(get_the_author_meta('hub_field', $current_user->ID)); ?>" class="regular-text">
										</td>
									</tr>
									<tr>
										<th><label for="hub_status"><?php _e('Status', 'imagepress'); ?></label></th>
										<td>
											<select name="hub_status" id="hub_status">
												<option value="1"<?php if(get_the_author_meta('hub_status', $current_user->ID) == 1) echo ' selected'; ?>><?php _e('Available for hire', 'imagepress'); ?></option>
												<option value="0"<?php if(get_the_author_meta('hub_status', $current_user->ID) == 0) echo ' selected'; ?>><?php _e('Not available for hire', 'imagepress'); ?></option>
											</select>
											<br><small><?php _e('Being available for hire will show an additional email icon on your profile, emails will be sent to the email address you have registered with the site.', 'imagepress'); ?></small>
										</td>
									</tr>
									<tr>
										<th><label for="hub_software"><?php _e('Preferred software', 'imagepress'); ?></label></th>
										<td>
											<input type="text" name="hub_software" id="hub_software" value="<?php echo esc_attr(get_the_author_meta('hub_software', $current_user->ID)); ?>" class="regular-text">
											<br><small><?php _e('Preferred Software (e.g. Adobe Photoshop, Adobe Illustrator, Sketch, Autodesk 3ds Max, etc.)', 'imagepress'); ?></small>
										</td>
									</tr>
									<tr><td colspan="2"><hr></td></tr>
								</table>
							</div>
							<div class="tabs_item" style="display: none;">
								<table class="form-table">
									<?php if(!is_admin()) { ?>
										<tr>
											<th><?php _e('Cover/avatar preview', 'imagepress'); ?></th>
											<td>
												<?php
												$hcc = get_the_author_meta('hub_custom_cover', $current_user->ID);
												$hca = get_the_author_meta('hub_custom_avatar', $current_user->ID);
												$hcc = wp_get_attachment_url($hcc);
												$hca = wp_get_attachment_url($hca);
												?>
												<div class="cinnamon-cover-preview" style="background: url('<?php echo $hcc; ?>') no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"><img src="<?php echo $hca; ?>" alt=""></div>
											</td>
										</tr>
									<?php } ?>
									<tr>
										<th><label for="hub_custom_cover"><?php _e('Profile cover image', 'imagepress'); ?></label></th>
										<td>
											<input type="file" name="hub_custom_cover" id="hub_custom_cover" value="<?php echo get_the_author_meta('hub_custom_cover', $current_user->ID); ?>" class="regular-text">
										</td>
									</tr>
									<tr>
										<th><label for="hub_custom_avatar"><?php _e('Profile avatar image', 'imagepress'); ?></label></th>
										<td>
											<input type="file" name="hub_custom_avatar" id="hub_custom_avatar" value="<?php echo get_the_author_meta('hub_custom_avatar', $current_user->ID); ?>" class="regular-text">
											<br><small><?php _e('Recommended cover size is 1080x300.', 'imagepress'); ?></small>
											<br><small><?php _e('Recommended avatar size is 240x240. If there is no custom avatar, your Gravatar will be used.', 'imagepress'); ?></small>
										</td>
									</tr>
									<tr><td colspan="2"><hr></td></tr>
								</table>
							</div>
							<div class="tabs_item" style="display: none;">
								<?php
								// get custom URL
								$hub_user_info = get_userdata($current_user->ID);
								$hub_user_login = get_userdata($current_user->user_login);
								$hubprotocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
								$hubdomain = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']);
								$hubuser = get_user_by('id', $current_user->ID);
								$hubuser = sanitize_title($hubuser->user_login);
								//
								$hub_user_url = '';
								if($hub_user_info->user_url != '')
									$hub_user_url = 'Customize your <a href="' . $hubprotocol . $hubuser . '.' . $hubdomain . '" rel="external" target="_blank"><i class="fa fa-th"></i> portfolio</a> page.';
								?>
								<p><?php echo $hub_user_url; ?></p>
								<hr>
								<p>
									<?php $cinnamon_portfolio_theme = get_user_meta($current_user->ID, 'cinnamon_portfolio_theme', true); ?>
									<select name="cinnamon_portfolio_theme" id="cinnamon_portfolio_theme">
										<option value="default"<?php if($cinnamon_portfolio_theme == 'default') echo ' selected'; ?>><?php _e('Default theme', 'imagepress'); ?></option>
										<option value="sidebar-left"<?php if($cinnamon_portfolio_theme == 'sidebar-left') echo ' selected'; ?>><?php _e('Sidebar (left)', 'imagepress'); ?></option>
									</select> <label for="cinnamon_portfolio_theme"><?php _e('Portfolio theme', 'imagepress'); ?></label>
								</p>
								<p>
									<div style="background-color: <?php echo get_user_meta($current_user->ID, 'hub_portfolio_bg', true); ?>;" class="color_portfolio_bg ip-picker"><i class="fa fa-eyedropper"></i></div>
									<input type="text" id="hub_portfolio_bg" name="hub_portfolio_bg" value="<?php echo get_user_meta($current_user->ID, 'hub_portfolio_bg', true); ?>"> <label for="hub_portfolio_bg"><?php _e('Background colour', 'imagepress'); ?></label>
								</p>
								<p>
									<div style="background-color: <?php echo get_user_meta($current_user->ID, 'hub_portfolio_text', true); ?>;" class="color_portfolio_text ip-picker"><i class="fa fa-eyedropper"></i></div>
									<input type="text" id="hub_portfolio_text" name="hub_portfolio_text" value="<?php echo get_user_meta($current_user->ID, 'hub_portfolio_text', true); ?>"> <label for="hub_portfolio_text"><?php _e('Text colour', 'imagepress'); ?></label>
								</p>
								<p>
									<div style="background-color: <?php echo get_user_meta($current_user->ID, 'hub_portfolio_link', true); ?>;" class="color_portfolio_link ip-picker"><i class="fa fa-eyedropper"></i></div>
									<input type="text" id="hub_portfolio_link" name="hub_portfolio_link" value="<?php echo get_user_meta($current_user->ID, 'hub_portfolio_link', true); ?>"> <label for="hub_portfolio_link"><?php _e('Accent colour', 'imagepress'); ?></label>
								</p>
							</div>
							<?php if(get_option('ip_mod_collections') == 1) { ?>
							<div class="tabs_item" style="display: none;">
								<p>
									<a href="#" class="toggleModal button noir-secondary"><i class="fa fa-plus"></i> Create new collection</a>
									<span class="ip-loadingCollections"><i class="fa fa-cog fa-spin"></i> <?php echo __('Loading collections...', 'imagepress'); ?></span>
									<span class="ip-loadingCollectionImages"><i class="fa fa-cog fa-spin"></i> <?php echo __('Loading collection images...', 'imagepress'); ?></span>
									<a href="#" class="imagepress-collections imagepress-float-right button"><i class="fa fa-refresh"></i></a>
								</p>
								<div class="modal">
									<h2>Create new collection</h2>
									<a href="#" class="close toggleModal"><i class="fa fa-times"></i> Close</a>

									<input type="hidden" id="collection_author_id" name="collection_author_id" value="<?php echo $current_user->ID; ?>">
									<p><input type="text" id="collection_title" name="collection_title" placeholder="Collection title"></p>
									<p><label>Make this collection</label> <select id="collection_status"><option value="1">Public</option><option value="0">Private</option></select> <label><small><a href="#"><i class="fa fa-question-circle"></i> Read more</a></small></label></p>
									<p>
										<input type="submit" value="Create" class="addCollection">
										<label class="collection-progress"><i class="fa fa-cog fa-spin"></i></label>
										<label class="showme"> <i class="fa fa-check"></i> Collection created!</label>
									</p>
								</div>

								<div class="collections-display"></div>
							</div>
							<?php } ?>
						</div>
					</div>

                    <?php do_action('edit_user_profile', $current_user); ?>
                    <hr>
                    <table class="form-table">
                        <tr>
                            <td colspan="2">
                                <input name="updateuser" type="submit" class="button" id="updateuser" value="<?php _e('Update', 'imagepress'); ?>">
                                <?php wp_nonce_field('update-user'); ?>
                                <input name="action" type="hidden" id="action" value="update-user">
                                <i class="fa fa-share-square"></i> <a href="<?php echo get_author_posts_url($current_user->ID); ?>"><?php _e('View and share your profile', 'imagepress'); ?></a>
                            </td>
                        </tr>
                    </table>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <?php
}







/* CINNAMON CUSTOM PROFILE FIELDS */
function save_cinnamon_profile_fields($user_id) {
	if(!current_user_can('edit_user', $user_id))
		return false;

	update_user_meta($user_id, 'hub_location', $_POST['hub_location']);
	update_user_meta($user_id, 'hub_employer', $_POST['hub_employer']);
	update_user_meta($user_id, 'hub_field', $_POST['hub_field']);
	update_user_meta($user_id, 'hub_status', $_POST['hub_status']);
	update_user_meta($user_id, 'hub_software', $_POST['hub_software']);

	update_user_meta($user_id, 'hub_portfolio_bg', $_POST['hub_portfolio_bg']);
	update_user_meta($user_id, 'hub_portfolio_text', $_POST['hub_portfolio_text']);
	update_user_meta($user_id, 'hub_portfolio_link', $_POST['hub_portfolio_link']);

	update_user_meta($user_id, 'cinnamon_portfolio_theme', $_POST['cinnamon_portfolio_theme']);

	// awards
    if(current_user_can('manage_options', $user_id)) {
		update_user_meta($user_id, 'user_title', $_POST['user_title']);

		$tax = get_taxonomy('award');
        $term = $_POST['award'];
        wp_set_object_terms($user_id, $term, 'award', false);
        clean_object_term_cache($user_id, 'award');
    }
}

function hub_gravatar_filter($avatar, $id_or_email, $size, $default, $alt) {
    $custom_avatar = get_the_author_meta('hub_custom_avatar', $id_or_email);
    $custom_avatar = wp_get_attachment_url($custom_avatar);

    $attachment_id = $custom_avatar;
    $image_attributes = wp_get_attachment_image_src($attachment_id, 'imagepress_sq_sm');
    if($image_attributes)
        $custom_avatar = $image_attributes[0];

    if($custom_avatar)
        $return = '<img src="' . $custom_avatar . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '" class="avatar">';
    elseif($avatar)
        $return = $avatar;
    else
        $return = '<img src="' . $default . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '" class="avatar">';

    return $return;
}

function cinnamon_awards() {
    $args = array(
        'hide_empty' => false,
        'pad_counts' => true
    );
    $terms = get_terms('award', $args);

    if(!empty($terms) && !is_wp_error($terms)) {
        foreach($terms as $term) {
            // get custom FontAwesome
            $t_ID = $term->term_id;
            $term_data = get_option("taxonomy_$t_ID");

            echo '<p><span class="cinnamon-award-list-item" title="' . $term->description . '">';
                if(isset($term_data['img']))
                    echo '<i class="fa ' . $term_data['img'] . '"></i> ';
                else
                    echo '<i class="fa fa-trophy"></i> ';
                echo $term->name . '</span> <span>' . $term->description . '<br><small>(' . $term->count . ' author(s) received this award)</small></span>';
            echo '</p>';
        }
    }
}
// line 903
?>
