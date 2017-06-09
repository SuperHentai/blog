<?php
add_action('wp_ajax_nopriv_imagepress-like', 'imagepress_like');
add_action('wp_ajax_imagepress-like', 'imagepress_like');

function imagepress_like() {
    $nonce = $_POST['nonce'];

    if(isset($_POST['imagepress_like'])) {
        $post_id = $_POST['post_id']; // post id
		$ip_vote_meta = get_option('ip_vote_meta');
		$like_count = get_post_meta($post_id, $ip_vote_meta, true); // post like count

		if(function_exists('wp_cache_post_change')) { // invalidate WP Super Cache if exists
			$GLOBALS['super_cache_enabled'] = 1;
			wp_cache_post_change($post_id);
		}

		if(is_user_logged_in()) { // user is logged in
			$user_id = get_current_user_id(); // current user
			$meta_POSTS = get_user_option('_liked_posts', $user_id); // post ids from user meta
			$meta_USERS = get_post_meta($post_id, '_user_liked'); // user ids from post meta
			$liked_POSTS = NULL; // setup array variable
			$liked_USERS = NULL; // setup array variable

			if(count($meta_POSTS) != 0) { // meta exists, set up values
				$liked_POSTS = $meta_POSTS;
			}

			if(!is_array($liked_POSTS)) // make array just in case
				$liked_POSTS = array();

			if(count($meta_USERS) != 0) { // meta exists, set up values
				$liked_USERS = $meta_USERS[0];
			}

			if(!is_array($liked_USERS)) // make array just in case
				$liked_USERS = array();

			$liked_POSTS['post-' . $post_id] = $post_id; // add post id to user meta array
			$liked_USERS['user-' . $user_id] = $user_id; // add user id to post meta array
			$user_likes = count($liked_POSTS); // count user likes

			if(!AlreadyLiked($post_id)) { // like the post
				update_post_meta($post_id, '_user_liked', $liked_USERS); // add user ID to post meta
				update_post_meta($post_id, $ip_vote_meta, ++$like_count); // +1 count post meta
				update_user_option($user_id, '_liked_posts', $liked_POSTS); // add post ID to user meta
				update_user_option($user_id, '_user_like_count', $user_likes); // +1 count user meta
				echo $like_count; // update count on front end

                // notification
                global $wpdb;
                $act_time = current_time('mysql', true);
                $wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, actionType, actionTime) VALUES (null, $user_id, $post_id, 'loved', '$act_time')");
                //
			}
			else { // unlike the post
				$pid_key = array_search($post_id, $liked_POSTS); // find the key
				$uid_key = array_search($user_id, $liked_USERS); // find the key
				unset($liked_POSTS[$pid_key]); // remove from array
				unset($liked_USERS[$uid_key]); // remove from array
				$user_likes = count($liked_POSTS); // recount user likes
				update_post_meta($post_id, '_user_liked', $liked_USERS); // remove user ID from post meta
				update_post_meta($post_id, $ip_vote_meta, --$like_count); // -1 count post meta
				update_user_option($user_id, '_liked_posts', $liked_POSTS); // remove post ID from user meta			
				update_user_option($user_id, '_user_like_count', $user_likes); // -1 count user meta
				echo 'already' . $like_count; // update count on front end
			}
		}
		else { // user is not logged in (anonymous)
			$ip = $_SERVER['REMOTE_ADDR']; // user IP address
			$meta_IPS = get_post_meta($post_id, '_user_IP'); // stored IP addresses
			$liked_IPS = NULL; // set up array variable

			if(count($meta_IPS) != 0) { // meta exists, set up values
				$liked_IPS = $meta_IPS[0];
			}

			if(!is_array($liked_IPS)) // make array just in case
				$liked_IPS = array();

			if(!in_array($ip, $liked_IPS)) // if IP not in array
				$liked_IPS['ip-' . $ip] = $ip; // add IP to array

			if(!AlreadyLiked($post_id)) { // like the post
				update_post_meta($post_id, '_user_IP', $liked_IPS); // add user IP to post meta
				update_post_meta($post_id, $ip_vote_meta, ++$like_count); // +1 count post meta
				echo $like_count; // update count on front end
			}
			else { // unlike the post
				$ip_key = array_search( $ip, $liked_IPS ); // find the key
				unset($liked_IPS[$ip_key]); // remove from array
				update_post_meta($post_id, '_user_IP', $liked_IPS); // remove user IP from post meta
				update_post_meta($post_id, $ip_vote_meta, --$like_count); // -1 count post meta
				echo "already".$like_count; // update count on front end
			}
		}
	}
	exit;
}

/**
 * Test if user already liked post
 */
function AlreadyLiked($post_id) { // test if user liked before
	if(is_user_logged_in()) { // user is logged in
		$user_id = get_current_user_id(); // current user
		$meta_USERS = get_post_meta($post_id, '_user_liked'); // user ids from post meta
		$liked_USERS = ''; // set up array variable

		if(count($meta_USERS) != 0) { // meta exists, set up values
			$liked_USERS = $meta_USERS[0];
		}

		if(!is_array($liked_USERS)) // make array just in case
			$liked_USERS = array();

		if(in_array($user_id, $liked_USERS)) { // true if user ID in array
			return true;
		}
		return false;
	}
	else { // user is anonymous, use IP address for voting
		$meta_IPS = get_post_meta($post_id, '_user_IP'); // get previously voted IP address
		$ip = $_SERVER['REMOTE_ADDR']; // retrieve current user IP
		$liked_IPS = ''; // set up array variable

		if(count($meta_IPS) != 0) { // meta exists, set up values
			$liked_IPS = $meta_IPS[0];
		}

		if(!is_array($liked_IPS)) // make array just in case
			$liked_IPS = array();

		if(in_array($ip, $liked_IPS)) { // true if IP in array
			return true;
		}
		return false;
	}
}

/**
 * Front end button
 */
function getPostLikeLink($post_id) {
	$ip_vote_meta = get_option('ip_vote_meta');
	$ip_vote_like = get_option('ip_vote_like');
	$ip_vote_unlike = get_option('ip_vote_unlike');
	$ip_vote_login = get_option('ip_vote_login');

	if(is_user_logged_in()) {
		$like_count = get_post_meta($post_id, $ip_vote_meta, true); // get post likes
		if(AlreadyLiked($post_id)) {
			$class = esc_attr(' liked');
			$like = '<i class="fa fa-fw fa-heart-o"></i> ' . $ip_vote_unlike;
		}
		else {
			$class = esc_attr('');
			$like = '<i class="fa fa-fw fa-heart"></i> ' . $ip_vote_like;
		}
		$output = '<a href="#" class="imagepress-like' . $class . '" data-post_id="' . $post_id . '">' . $like . '</a>';
	}
	else {
		$output .= $ip_vote_login;
    }
    return $output;
}

/**
 * If the user is logged in, output a list of posts that the user likes
 */
function frontEndUserLikes($author) {
    $like_list = '<div class="cinnamon-likes">';
    $user_likes = get_user_option('_liked_posts', $author);
    if(!empty($user_likes) && count($user_likes) > 0)
        $the_likes = $user_likes;
    else
        $the_likes = '';

    if(!is_array($the_likes))
        $the_likes = array();
    $the_likes = array_reverse($the_likes);
    $count = count($the_likes);
    if($count > 0) {
        $limited_likes = array_slice($the_likes, 0, 12); // this will limit the number of posts returned to 12
        foreach($limited_likes as $the_like) {
            $like_list .= '<a href="' . esc_url(get_permalink($the_like)) . '"><!--' . get_the_title( $the_like ) . '-->' . get_the_post_thumbnail($the_like, 'thumbnail') . '</a>';
        }
    }
    $like_list .= '</div>';
    return $like_list;
}



function imagepress_get_like_users($id) {
    $meta_USERS = get_post_meta($id, '_user_liked');
    $totalUsers = array_sum(array_map('count', $meta_USERS));
    echo '<div style="position: relative;">';
        if($totalUsers == 0)
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center;"><span class="imagepress-update-count">' . get_option('ip_vote_nobody') . '</div>';
        if($totalUsers == 1)
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center;"><span class="imagepress-update-count">' . $totalUsers . '</span> ' . get_option('ip_vote_who_singular') . ' <span class="teal">(' . get_option('ip_vote_who_link') . ')</span></div>';
        if($totalUsers > 1)
            echo '<div class="slide" style="cursor: pointer; font-size: 80%; text-align: center;"><span class="imagepress-update-count">' . $totalUsers . '</span> ' . get_option('ip_vote_who_plural') . ' <span class="teal">(' . get_option('ip_vote_who_link') . ')</span></div>';
        echo '<div class="view" style="position: absolute; background-color: #2c3e50; padding: 16px; max-height: 300px; overflow: auto; z-index: 100;">';
            if($totalUsers == 0) {
                echo '<div>' . get_option('ip_vote_nobody') . '</div>';
            }
            else {
                echo '<div><small>' . get_option('ip_vote_who') . '<br></small></div>';
                foreach($meta_USERS as $users) {
                    foreach($users as $user) {
                        echo '<small><i class="fa fa-user"></i></small> <a href="' . get_author_posts_url($user) . '">' . get_the_author_meta('nickname', $user) . '</a><br>';
                    }
                }
            }
        echo '</div>';
    echo '</div>';
}

function imagepress_get_like_count($id) {
    $meta_USERS = get_post_meta($id, '_user_liked');
    $totalUsers = array_sum(array_map('count', $meta_USERS));

    return $totalUsers;
}
?>
