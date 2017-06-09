<?php
/*
Plugin Name: ImagePress (share GFXFree.Net)
Plugin URI: http://getbutterfly.com/wordpress-plugins/imagepress/
Description: Create a user-powered image gallery or an image upload site, using nothing but WordPress custom posts. Moderate image submissions and integrate the plugin into any theme.
Version: 5.4
License: GPLv3
Author: Ciprian Popescu
Author URI: http://getbutterfly.com/

Copyright 2013, 2014, 2015 Ciprian Popescu (email: getbutterfly@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

define('IP_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('IP_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('IP_PLUGIN_VERSION', '5.4');

// plugin localization
load_plugin_textdomain('imagepress', false, dirname(plugin_basename(__FILE__)) . '/languages/');

$ip_upload_size = get_option('ip_upload_size');
$ip_slug = get_option('ip_slug');
if(empty($ip_slug))
	add_option('ip_slug', 'image');

define('MAX_UPLOAD_SIZE', ($ip_upload_size * 1024));

include IP_PLUGIN_PATH . '/includes/functions.php';
include IP_PLUGIN_PATH . '/includes/page-settings.php';
include IP_PLUGIN_PATH . '/includes/cinnamon-users.php';

// user modules
include IP_PLUGIN_PATH . '/modules/mod-awards.php';
include IP_PLUGIN_PATH . '/modules/mod-user-following.php';
include IP_PLUGIN_PATH . '/modules/mod-trending.php';
include IP_PLUGIN_PATH . '/modules/mod-likes.php';
include IP_PLUGIN_PATH . '/modules/mod-notifications.php';

if(get_option('ip_mod_collections') == 1) {
	include IP_PLUGIN_PATH . '/modules/mod-collections.php';
}
//

// user classes
if(get_option('cinnamon_mod_login') == 1) {
	include IP_PLUGIN_PATH . '/classes/class-frontend.php';
}
//

add_action('init', 'imagepress_registration');

add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');

add_action('admin_menu', 'imagepress_menu'); // settings menu
add_action('admin_menu', 'imagepress_menu_bubble');

add_filter('transition_post_status', 'notify_status', 10, 3); // email notifications
add_filter('widget_text', 'do_shortcode');

function imagepress_menu() {
    add_submenu_page('edit.php?post_type=' . get_option('ip_slug'), 'ImagePress Settings', 'ImagePress Settings', 'manage_options', 'imagepress_admin_page', 'imagepress_admin_page');
}

add_shortcode('imagepress-add', 'imagepress_add');
add_shortcode('imagepress-show', 'imagepress_show');
add_shortcode('imagepress-search', 'imagepress_search');
add_shortcode('imagepress-top', 'imagepress_top');

add_shortcode('imagepress', 'imagepress_widget');

add_image_size('imagepress_sq_sm', 175, 175, true);
add_image_size('imagepress_pt_sm', 175, 250, true);
add_image_size('imagepress_ls_sm', 250, 175, true);

add_image_size('imagepress_sq_std', 250, 250, true);
add_image_size('imagepress_pt_std', 250, 375, true);
add_image_size('imagepress_ls_std', 375, 250, true);

// show admin bar only for admins
if(get_option('cinnamon_hide_admin') == 1) {
	add_action('after_setup_theme', 'cinnamon_remove_admin_bar');
	function cinnamon_remove_admin_bar() {
		if(!current_user_can('administrator') && !is_admin()) {
			show_admin_bar(false);
		}
	}
}
//

/* CINNAMON ACTIONS */
add_action('init', 'update_cinnamon_action_time');
add_action('init', 'cinnamon_author_base');
add_action('wp_login', 'cinnamon_last_login');

add_action('personal_options_update', 'save_cinnamon_profile_fields');
add_action('edit_user_profile_update', 'save_cinnamon_profile_fields');

/* CINNAMON SHORTCODES */
add_shortcode('cinnamon-card', 'cinnamon_card');
add_shortcode('cinnamon-profile', 'cinnamon_profile');
add_shortcode('cinnamon-profile-blank', 'cinnamon_profile_blank');
add_shortcode('cinnamon-profile-edit', 'cinnamon_profile_edit');
add_shortcode('cinnamon-awards', 'cinnamon_awards');

/* CINNAMON FILTERS */
add_filter('get_avatar', 'hub_gravatar_filter', 10, 5);
add_filter('user_contactmethods', 'cinnamon_extra_contact_info');

/* CINNAMON CHECKS */
$user_ID = get_current_user_id();
update_user_meta($user_ID, 'cinnamon_action_time', current_time('mysql'));








// custom thumbnail column
$ip_column_slug = get_option('ip_slug');

add_filter('manage_edit-' . $ip_column_slug . '_columns', 'ip_columns_filter', 10, 1);
function ip_columns_filter($columns) {
	$column_thumbnail = array('thumbnail' => 'Thumbnail');
	$columns = array_slice($columns, 0, 1, true) + $column_thumbnail + array_slice($columns, 1, NULL, true);
	return $columns;
}
add_action('manage_posts_custom_column', 'ip_column_action', 10, 1);
function ip_column_action($column) {
	global $post;
	switch($column) {
		case 'thumbnail':
			echo get_the_post_thumbnail($post->ID, 'thumbnail');
		break;
	}
}
//

function ip_manage_users_custom_column($output = '', $column_name, $user_id) {
	global $wpdb;

	if($column_name !== 'post_type_count')
		return;

	$where = get_posts_by_author_sql(get_option('ip_slug'), true, $user_id);
	$result = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts $where");

	return '<a href="' . admin_url("edit.php?post_type=" . get_option('ip_slug') . "&author=$user_id") . '">' . $result . '</a>';
}
add_filter('manage_users_custom_column', 'ip_manage_users_custom_column', 10, 3);

function ip_manage_users_columns($columns) {
	$columns['post_type_count'] = __('Images', 'imagepress');

	return $columns;
}
add_filter('manage_users_columns', 'ip_manage_users_columns');

// Disqus disable
function imagepress_block_disqus($file) {
	$ip_slug = get_option('ip_slug');

	if($ip_slug == get_post_type())
		remove_filter('comments_template', 'dsq_comments_template');
	return $file;
}
if(get_option('ip_disqus') == 0) {
	add_filter('comments_template', 'imagepress_block_disqus', 1);
}
//

function imagepress_add($atts, $content = null) {
	extract(shortcode_atts(array(
		'category' => ''
	), $atts));

	global $current_user;
	$out = '';

	if(isset($_POST['imagepress_upload_image_form_submitted']) && wp_verify_nonce($_POST['imagepress_upload_image_form_submitted'], 'imagepress_upload_image_form')) {
		$result = imagepress_parse_file_errors($_FILES['imagepress_image_file']);

		if(get_option('ip_moderate') == 0)
			$ip_status = 'pending';
		if(get_option('ip_moderate') == 1)
			$ip_status = 'publish';

		if($result['error']) {
			$out .= '<p class="message noir-danger">' . __('ERROR: ', 'imagepress') . $result['error'] . '</p>';
		}
		else {
			if(get_option('ip_createusers') == 1) {
	            // create new user
	            $user_id = username_exists($_POST['imagepress_author']);
	            if(!$user_id and email_exists($_POST['imagepress_email']) == false) {
	                $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
	                $user_id = wp_create_user($_POST['imagepress_author'], $random_password, $_POST['imagepress_email']);
	            } else {
	                $random_password = __('User already exists. Password inherited.');
	            }

				$ip_image_author = $user_id;
			}
			if(get_option('ip_createusers') == 0) {
				$ip_image_author = $current_user->ID;
			}
            $user_image_data = array(
				'post_title' => $_POST['imagepress_image_caption'],
				'post_content' => $_POST['imagepress_image_description'],
				'post_status' => $ip_status,
				'post_author' => $ip_image_author,
				'post_type' => get_option('ip_slug')
			);

			// send notification email to administrator
			$ip_notification_email = get_option('ip_notification_email');
			$ip_notification_subject = __('New image uploaded!', 'imagepress') . ' | ' . get_bloginfo('name');
			$ip_notification_message = __('New image uploaded!', 'imagepress') . ' | ' . get_bloginfo('name');

			if($post_id = wp_insert_post($user_image_data)) {
				imagepress_process_image('imagepress_image_file', $post_id, $result['caption']);

                // multiple images
                if(1 == get_option('ip_upload_secondary')) {
					$files = $_FILES['imagepress_image_additional'];
					if($files) { 
						foreach($files['name'] as $key => $value) {
							if($files['name'][$key]) {
								$file = array(
									'name' => $files['name'][$key],
									'type' => $files['type'][$key],
									'tmp_name' => $files['tmp_name'][$key],
									'error' => $files['error'][$key],
									'size' => $files['size'][$key]
								);
							}
							$_FILES = array("attachment" => $file);
							foreach($_FILES as $file => $array) {
								$attach_id = media_handle_upload($file, $post_id);
								if($attach_id < 0) { $post_error = true; }
							}
						}
					}
				}
				// end multiple images

				wp_set_object_terms($post_id, (int)$_POST['imagepress_image_category'], 'imagepress_image_category');
				wp_set_object_terms($post_id, (int)$_POST['imagepress_image_tag'], 'imagepress_image_tag');

                $keywords = explode(',', $_POST['imagepress_image_keywords']);
                wp_set_post_terms($post_id, $keywords, 'imagepress_image_keyword', false);

                if(isset($_POST['imagepress_behance']))
                    add_post_meta($post_id, 'imagepress_behance', $_POST['imagepress_behance'], true);
                else
                    add_post_meta($post_id, 'imagepress_behance', '', true);

                if(isset($_POST['imagepress_purchase']))
                    add_post_meta($post_id, 'imagepress_purchase', $_POST['imagepress_purchase'], true);
                else
                    add_post_meta($post_id, 'imagepress_purchase', '', true);

                if(isset($_POST['imagepress_video']))
                    add_post_meta($post_id, 'imagepress_video', $_POST['imagepress_video'], true);
                else
                    add_post_meta($post_id, 'imagepress_video', '', true);

                if(isset($_POST['imagepress_sticky']))
                    add_post_meta($post_id, 'imagepress_sticky', 1, true);
                else
                    add_post_meta($post_id, 'imagepress_sticky', 0, true);

                if(isset($_POST['imagepress_print']))
                    add_post_meta($post_id, 'imagepress_print', 1, true);
                else
                    add_post_meta($post_id, 'imagepress_print', 0, true);

				imagepress_post_add_custom($post_id, $ip_image_author);

                $headers[] = "MIME-Version: 1.0\r\n";
                $headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";
				wp_mail($ip_notification_email, $ip_notification_subject, $ip_notification_message, $headers);
			}

            $out .= '<p class="message noir-success">' . get_option('ip_upload_success_title') . '</p>';
            $out .= '<p class="message noir-default"><a href="' . get_permalink($post_id) . '">' . get_option('ip_upload_success') . '</a></p>';
		}
	}  

	if(get_option('ip_registration') == 0 && !is_user_logged_in()) {
		$out .= '<p>' . __('You need to be logged in to upload an image.', 'imagepress') . '</p>';
	}
	if((get_option('ip_registration') == 0 && is_user_logged_in()) || get_option('ip_registration') == 1) {
		if(isset($_POST['imagepress_image_caption']) && isset($_POST['imagepress_image_category']))
			$out .= imagepress_get_upload_image_form($imagepress_image_caption = $_POST['imagepress_image_caption'], $imagepress_image_category = $_POST['imagepress_image_category'], $imagepress_image_description = $_POST['imagepress_image_description'], $category);
		else
			$out .= imagepress_get_upload_image_form($imagepress_image_caption = '', $imagepress_image_category = '', $imagepress_image_description = '', $category);
	}

	return $out;
}

function imagepress_resize_default_images($data) {
	$ip_width = get_option('ip_max_width');
	$ip_quality = get_option('ip_max_quality');

	// Return an implementation that extends WP_Image_Editor
	$arguments = array(
		'mime_type' => 'image/jpeg',
		'methods' => array(
			'resize',
			'save'
		)
	);
	$image = wp_get_image_editor($data['file'], $arguments);
	if(is_wp_error($image)) {
		return false;
	}
	$image->set_quality($ip_quality);
	// max full size width: 960, unlimited height, no cropping
	$image->resize($ip_width, 99999, false);
	$image->save($data['file']);

	return $data;
}

if(get_option('ip_resize') == 1)
	add_action('wp_handle_upload', 'imagepress_resize_default_images');

function imagepress_process_image($file, $post_id, $caption, $feature = 1) {
	require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
	require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
	require_once(ABSPATH . 'wp-admin' . '/includes/media.php');

	$attachment_id = media_handle_upload($file, $post_id);
	if($feature == 1)
		update_post_meta($post_id, '_thumbnail_id', $attachment_id);

	$attachment_data = array(
		'ID' => $attachment_id,
		'post_excerpt' => $caption
	);
	wp_update_post($attachment_data);

	return $attachment_id;
}

function imagepress_parse_file_errors($file = '') {
	$result = array();
	$result['error'] = 0;

	if($file['error']) {
		$result['error'] = __('No file uploaded or upload error!', 'imagepress');
		return $result;
	}

	$image_data = getimagesize($file['tmp_name']);

	if(($file['size'] > MAX_UPLOAD_SIZE)) {
		$result['error'] = __('Invalid size! Your image must not exceed ', 'imagepress') . MAX_UPLOAD_SIZE . '.';
	}

	return $result;
}

/*
 * 1. removed username and email from upload form
 * 2. 
 */
function imagepress_get_upload_image_form($imagepress_image_caption = '', $imagepress_image_category = 0, $imagepress_image_description = '', $imagepress_hardcoded_category) {
    global $current_user;
    get_currentuserinfo();
    // upload form // customize

	$out = '<div class="ip-uploader">';
		$out .= '<form id="imagepress_upload_image_form" method="post" action="" enctype="multipart/form-data" class="imagepress-form">';
			$out .= wp_nonce_field('imagepress_upload_image_form', 'imagepress_upload_image_form_submitted');
            // name and email
            $out .= '<input type="hidden" name="imagepress_author" value="' . $current_user->display_name . '">';
            $out .= '<input type="hidden" name="imagepress_email" value="' . $current_user->user_email . '">';

			$out .= '<h4>' . __('Main Details (required)', 'imagepress') . '</h4>';
			$out .= '<p><input type="text" id="imagepress_image_caption" name="imagepress_image_caption" placeholder="' . get_option('ip_caption_label') . '" required></p>';
			$ip_description_label = get_option('ip_description_label');
			if(!empty($ip_description_label)) {
				if(get_option('ip_require_description') == 1)
					$required = 'required';
				else
					$required = '';
				$out .= '<p><textarea id="imagepress_image_description" name="imagepress_image_description" placeholder="' . get_option('ip_description_label') . '" rows="6" ' . $required . '></textarea></p>';
			}

			$out .= '<p>';
				if('' != $imagepress_hardcoded_category) {
					$iphcc = get_term_by('slug', $imagepress_hardcoded_category, 'imagepress_image_category'); // ImagePress hard-coded category
					$out .= '<input type="hidden" id="imagepress_image_category" name="imagepress_image_category" value="' . $iphcc->term_id . '">';
				}
				else {
					$out .= imagepress_get_image_categories_dropdown('imagepress_image_category', '') . '';
				}

				if(get_option('ip_allow_tags') == 1)
					$out .= imagepress_get_image_tags_dropdown('imagepress_image_tag', '') . '';
			$out .= '</p>';

			// sticky image
			if('' != get_option('ip_sticky_label'))
				$out .= '<p><input type="checkbox" id="imagepress_sticky" name="imagepress_sticky" value="1"> <label for="imagepress_sticky">' . get_option('ip_sticky_label') . '</label></p>';

			// available for print
			if('' != get_option('ip_print_label'))
				$out .= '<p><input type="checkbox" id="imagepress_print" name="imagepress_print" value="1"> <label for="imagepress_print">' . get_option('ip_print_label') . '</label></p>';

			$out .= '<h4>' . __('Additional Details (optional)', 'imagepress') . '</h4>';
			if('' != get_option('ip_behance_label'))
				$out .= '<p><input type="url" id="imagepress_behance" name="imagepress_behance" placeholder="' . get_option('ip_behance_label') . '"></p>';
			if('' != get_option('ip_purchase_label'))
				$out .= '<p><input type="url" id="imagepress_purchase" name="imagepress_purchase" placeholder="' . get_option('ip_purchase_label') . '"></p>';
			if('' != get_option('ip_video_label'))
				$out .= '<p><input type="url" id="imagepress_video" name="imagepress_video" placeholder="' . get_option('ip_video_label') . '"></p>';
			if('' != get_option('ip_keywords_label'))
				$out .= '<p><input type="text" id="imagepress_image_keywords" name="imagepress_image_keywords" placeholder="' . get_option('ip_keywords_label') . '"></p>';

			$uploadsize = number_format((MAX_UPLOAD_SIZE/1024000), 0, '.', '');
			$datauploadsize = $uploadsize * 1024000;
			$ip_width = get_option('ip_max_width');

			$out .= '<hr>';
			$out .= '<div id="imagepress-errors"></div>';
			$out .= '<p><label for="imagepress_image_file"><i class="fa fa-cloud-upload"></i> Select a file (' . $uploadsize . 'MB ' . __('maximum', 'imagepress') . ')...</label><br><input type="file" accept="image/*" data-max-size="' . $datauploadsize . '" data-max-width="' . $ip_width . '" name="imagepress_image_file" id="imagepress_image_file"></p>';
			$out .= '<hr>';

			if(1 == get_option('ip_upload_secondary'))
				$out .= '<p><label for="imagepress_image_additional"><i class="fa fa-cloud-upload"></i> Select file(s) (' . $uploadsize . 'MB ' . __('maximum', 'imagepress') . ')...</label><br><input type="file" accept="image/*" name="imagepress_image_additional[]" id="imagepress_image_additional" multiple><br><small>Additional images (variants, making of, progress shots)</small></p><hr>';

			$out .= '<p>';
				$out .= '<input type="submit" id="imagepress_submit" name="imagepress_submit" value="' . get_option('ip_upload_label') . '" class="button noir-secondary">';
				$out .= ' <span id="ipload"></span>';
			$out .= '</p>';
		$out .= '</form>';
	$out .= '</div>';

	return $out;
}






function imagepress_get_image_categories_dropdown($taxonomy, $selected) {
	return wp_dropdown_categories(array(
		'taxonomy' => $taxonomy,
		'name' => 'imagepress_image_category',
		'selected' => $selected,
        'exclude' => get_option('ip_cat_exclude'),
		'hide_empty' => 0,
		'echo' => 0,
		'show_option_all' => get_option('ip_category_label')
	));
}
function imagepress_get_image_tags_dropdown($taxonomy, $selected) {
	return wp_dropdown_categories(array(
		'taxonomy' => $taxonomy,
		'name' => 'imagepress_image_tag',
		'selected' => $selected,
		'hide_empty' => 0,
		'echo' => 0,
		'show_option_all' => get_option('ip_tag_label')
	));
}

function imagepress_activate() {
	add_option('ip_ipp', 40);
	add_option('ip_padding', 1);
	add_option('ip_slug', 'image'); // UPDATE cms_posts SET post_type = REPLACE(post_type, 'user_images', 'image');

	add_option('ip_upload_size', 2048);
	add_option('ip_moderate', 0);
	add_option('ip_registration', 1);

	add_option('ip_order', 'DESC');
	add_option('ip_orderby', 'date');

	add_option('ip_click_behaviour', 'media'); // media, custom

	add_option('approvednotification', 'yes');
	add_option('declinednotification', 'yes');

	add_option('ip_caption_label', 'Image Caption');
	add_option('ip_category_label', 'Image Category');
	add_option('ip_tag_label', 'Image Tag');
	add_option('ip_image_label', 'Select Image');
	add_option('ip_description_label', 'Image Description');
	add_option('ip_upload_label', 'Upload');
	add_option('ip_keywords_label', 'Image Keywords (optional, separate with comma, backspace or x to remove)');
	add_option('ip_behance_label', 'Behance link for this project (optional)');
	add_option('ip_video_label', 'Youtube/Vimeo link');
	add_option('ip_purchase_label', 'Purchase link for this project (optional)');
	add_option('ip_sticky_label', 'Sticky (display this image with higher priority)');
	add_option('ip_print_label', 'Available for print');

	add_option('ip_createusers', 0);

    // configurator options
    add_option('ip_image_size', 'large');
    add_option('ip_title_optional', 1);
    add_option('ip_meta_optional', 1);
    add_option('ip_views_optional', 1);
    add_option('ip_likes_optional', 1);
    add_option('ip_comments', 1);
    add_option('ip_author_optional', 1);

    add_option('ip_cat_exclude', '');

    // users
    add_option('cinnamon_author_slug', 'author'); // try 'profile'
    add_option('cinnamon_profile_title', 'Cinnamon Profile');
    add_option('cinnamon_label_index', 'View all');
    add_option('cinnamon_label_portfolio', 'My Hub');
    add_option('cinnamon_label_about', 'About/Biography');
    add_option('cinnamon_label_hub', 'My Hub');
    add_option('cinnamon_hide', '');
    add_option('cinnamon_image_size', 150);

    add_option('cinnamon_show_online', 1);
    add_option('cinnamon_show_uploads', 0);
    add_option('cinnamon_show_awards', 0);
    add_option('cinnamon_show_posts', 1);
    add_option('cinnamon_show_comments', 1);
    add_option('cinnamon_show_map', 0);
    add_option('cinnamon_show_followers', 0);
    add_option('cinnamon_show_following', 0);
    add_option('ip_cards_per_author', 9);
    add_option('ip_cards_image_size', 'thumbnail');
    add_option('cinnamon_edit_label', 'Edit profile');

    add_option('cinnamon_hide_admin', 0);

    add_option('cinnamon_edit_page', '');

    add_option('cinnamon_mod_login', 0);
    add_option('cinnamon_mod_hub', 0);

    add_option('cms_title', 'Your Site Title');
    add_option('cms_featured_tooltip', 'Staff Favourite');
    add_option('cms_verified_profile', 'Verified Profile');
    add_option('cms_available_for_print', 'Print available, contact artist for more info');

    add_option('cinnamon_account_page', 'http://yourdomain.com/login/');

    //
    add_option('ip_upload_secondary', 0);
    add_option('ip_allow_tags', 0);
    add_option('ip_require_description', 0);
    add_option('ip_override_email_notification', 1);
    add_option('ip_disqus', 0);

	add_option('ip_mod_collections', 0);

    //
    add_option('cinnamon_show_likes', 1);
    add_option('cinnamon_show_activity', 1);

	add_option('ip_resize', 0);
	add_option('ip_max_width', 1920);
	add_option('ip_max_quality', 100);

	add_option('ip_vote_like', "I like this image");
	add_option('ip_vote_unlike', "Oops! I don't like this");
	add_option('ip_vote_nobody', "Nobody likes this yet");
	add_option('ip_vote_who', "Users that like this image:");
	add_option('ip_vote_who_singular', "user likes this");
	add_option('ip_vote_who_plural', "users like this");
	add_option('ip_vote_who_link', "who?");

    add_option('ip_likes', 'likes');
    add_option('ip_vote_meta', '_like_count');
    add_option('ip_vote_login', 'You need to be logged in to like this');

	add_option('notification_limit', 50);
	add_option('notification_thumbnail_custom', 0);

	add_option('ip_author_find_title', 'Find by name or location');
	add_option('ip_author_find_placeholder', 'Search by name or location...');
	add_option('ip_image_find_title', 'Find by author or title');
	add_option('ip_image_find_placeholder', 'Search by author or title...');

	add_option('ip_notifications_mark', 'Mark all as read');
	add_option('ip_notifications_all', 'View all notifications');

	add_option('cinnamon_pt_account', 'Account details');
	add_option('cinnamon_pt_social', 'Social details');
	add_option('cinnamon_pt_author', 'Author details');
	add_option('cinnamon_pt_profile', 'Profile details');
	add_option('cinnamon_pt_portfolio', 'Portfolio editor');

	add_option('ip_upload_succsss_title', 'Image uploaded!');
	add_option('ip_upload_success', 'Click here to view your image.');

	global $wpdb;

	// notifications table
	$table_name = $wpdb->prefix . 'notifications';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `userID` int(11) NOT NULL,
            `postID` int(11) NOT NULL,
            `actionType` text COLLATE utf8_unicode_ci NOT NULL,
            `actionIcon` text COLLATE utf8_unicode_ci NOT NULL,
            `actionTime` datetime NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT '0',

            PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	// collections table
	$table_name = $wpdb->prefix . 'ip_collections';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`collection_ID` int(11) NOT NULL,
			`collection_title` text COLLATE utf8_unicode_ci NOT NULL,
			`collection_title_slug` text COLLATE utf8_unicode_ci NOT NULL,
			`collection_status` tinyint(4) NOT NULL DEFAULT '1',
			`collection_views` int(11) NOT NULL,
			`collection_author_ID` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ip_collections` ADD PRIMARY KEY (`collection_ID`);");
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ip_collections` MODIFY `collection_ID` int(11) NOT NULL AUTO_INCREMENT;");
	}
	$table_name = $wpdb->prefix . 'ip_collectionmeta';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
			`image_meta_ID` int(11) NOT NULL,
			`image_ID` int(11) NOT NULL,
			`image_collection_ID` int(11) NOT NULL,
			`image_collection_author_ID` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ip_collectionmeta` ADD UNIQUE KEY `image_meta_ID` (`image_meta_ID`);");
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ip_collectionmeta` MODIFY `image_meta_ID` int(11) NOT NULL AUTO_INCREMENT;");
	}
}

function imagepress_deactivate() {
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'imagepress_activate');
register_deactivation_hook(__FILE__, 'imagepress_deactivate');
//register_uninstall_hook( __FILE__, 'imagepress_uninstall');

// enqueue scripts and styles // deactivated for now
/**
add_action('admin_enqueue_scripts', 'ip_enqueue_color_picker');
function ip_enqueue_color_picker($hook_suffix) {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('ip.functions', plugins_url('js/functions.js', __FILE__), array('wp-color-picker'), false, true);
}
/**/


add_action('wp_enqueue_scripts', 'ip_enqueue_scripts');
function ip_enqueue_scripts($hook_suffix) {
    wp_enqueue_style('fa', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

	wp_enqueue_style('ip-bootstrap', plugins_url('css/ip.bootstrap.css', __FILE__));

    if(file_exists(get_template_directory_uri() . '/imagepress.css')) {
		wp_enqueue_style('ip-override', get_template_directory_uri() . '/imagepress.css');
	}

	// https://github.com/javve/list.js
	wp_enqueue_script('list-core', plugins_url('js/list.js', __FILE__), array('jquery'), '1.0', true);
	wp_enqueue_script('list-pagination', plugins_url('js/list.pagination.js', __FILE__), array('jquery'), '1.0', true);

	// https://github.com/bgrins/spectrum
	wp_enqueue_script('spectrum', plugins_url('js/spectrum.js', __FILE__), array('jquery'), '1.6.1', true);
	wp_enqueue_style('spectrum', plugins_url('css/spectrum.css', __FILE__), '', '1.6.1');

	wp_enqueue_script('ipjs-main', plugins_url('js/jquery.main.js', __FILE__), array('jquery'));
	wp_localize_script('ipjs-main', 'ajax_var', array(
		'imagesperpage' 	=> get_option('ip_ipp'),
		'likelabel' 		=> get_option('ip_vote_like'),
		'unlikelabel' 		=> get_option('ip_vote_unlike'),
		'processing_error' 	=> __('There was a problem processing your request.', 'imagepress'),
		'login_required' 	=> __('Oops, you must be logged-in to follow users.', 'imagepress'),
		'logged_in' 		=> is_user_logged_in() ? 'true' : 'false',
		'ajaxurl' 			=> admin_url('admin-ajax.php'),
		'nonce' 			=> wp_create_nonce('ajax-nonce')
	));
}
// end

function imagepress_search($atts, $content = null) {
	extract(shortcode_atts(array(
		'type' => '',
	), $atts));

	$display = '<form role="search" method="get" action="' . home_url() . '" class="imagepress-form">
			<div>
				<input type="search" name="s" id="s" placeholder="' . __('Search images...', 'imagepress') . '"> 
				<input type="submit" id="searchsubmit" value="' . __('Search', 'imagepress') . '">
				<input type="hidden" name="post_type" value="' . get_option('ip_slug') . '">
			</div>
		</form>';

	return $display;
}

/*
 * Main shortcode function [imagepress_show]
 *
 */
function imagepress_show($atts, $content = null) {
	extract(shortcode_atts(array(
		'category' 		=> '',
		'count' 		=> 0,
		'limit' 		=> 999999,
		'user' 			=> 0,
		'size' 			=> '',
		'columns' 		=> '',
		'sort' 			=> 'no',
		'type' 			=> '', // 'random'
		'collection' 	=> '', // new parameter (will extract all images from a certain collection)
		'collection_id'	=> '' // new parameter (will extract all images from a certain collection)
	), $atts));

	global $wpdb, $current_user;
    $ip_unique_id = uniqid();

	if(empty($type))
		$ip_order = get_option('ip_orderby');
	else
		$ip_order = 'rand';

	if($count != 0)
		$ip_ipp = $count;
	else
		$ip_ipp = -1;

	if($user > 0)
		$author = $user;
	if(isset($_POST['user']))
		$author = $_POST['user'];

    // defaults
    $ip_order_asc_desc = get_option('ip_order');
	$ip_vote_meta = get_option('ip_vote_meta');

    // main images query
	$out = '';
	$cs = '';

	if(!empty($collection) && is_numeric($collection) && $collection > 0) {
		global $wp_query;
		$ip_parameters = $wp_query->query_vars;
		$collection_page = $ip_parameters['page'];

		$collectionables = $wpdb->get_results("SELECT image_ID, image_collection_ID FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection_page . "'", ARRAY_A);

		foreach($collectionables as $collectable) {
			$cs[] = $collectable['image_ID'];
			$cm = $collectable['image_collection_ID'];
		}
		$post_in = implode(', ', $cs);

		$wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_views = collection_views + 1 WHERE collection_ID = %d", $collection_page));

		$collection_row = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_ID = '" . $cm . "'", ARRAY_A);
		$out .= '<div class="ip-template-collection-meta">';
			$out .= '<h3>' . $collection_row['collection_title'] . '</h3>';
			$out .= '<div>' . $collection_row['collection_views'] . ' views | ' . count($collectionables) . ' images</div>';
		$out .= '</div>';
	}

	// all filters should be applied here
	$args = array(
		'imagepress_image_category' => $category,
		'post_type' 				=> get_option('ip_slug'),
		'posts_per_page' 			=> $limit,//$ip_ipp,
		'orderby' 					=> $ip_order,
		'order' 					=> $ip_order_asc_desc,
		'author' 					=> $author,
		'post__in' 					=> $cs,

		'cache_results' => false,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'no_found_rows' => true,
	);

	$posts = get_posts($args);
    //

	if($posts) {
		$out .= '<div class="ip_clear"></div>';

        if(!empty($columns) && is_numeric($columns)) {
            $custom_column_size = 99.96 / $columns;
            $out .= '<style>#ip_container_' . $ip_unique_id . ' .ip_box { width: ' . $custom_column_size . '%; height: ' . $custom_column_size . '%; }</style>';
        }
        $out .= '<style>.ip_box { padding: ' . get_option('ip_padding') . 'px; }</style>';

        $out .= '<div id="cinnamon-cards">';
		if($sort == 'yes') {
			$out .= '<div class="cinnamon-sortable">
				<div class="innersort">
					<h4>' . __('Sort', 'imagepress') . '</h4>
					<span class="sort initial" data-sort="imageviews"><i class="fa fa-circle fa-fw"></i> ' . __('Most views', 'imagepress') . '</span>
					<span class="sort" data-sort="imagecomments"><i class="fa fa-circle fa-fw"></i> ' . __('Most comments', 'imagepress') . '</span>
					<span class="sort" data-sort="imagelikes"><i class="fa fa-circle fa-fw"></i> ' . __('Most ', 'imagepress') . get_option('ip_likes') . '</span>
				</div>
				<div class="innersort">
					<h4>' . get_option('ip_image_find_title') . '</h4>
					<input type="text" class="search" placeholder="' . get_option('ip_image_find_placeholder') . '">
				</div>
				<div style="clear: both;"></div>
			</div>';
		}
		
		$out .= '<ul id="ip_container_' . $ip_unique_id . '" class="list" style="display: block;" data-imagepress-count="' . get_option('ip_ipp') . '" data-imagepress-id="' . $ip_unique_id . '">';

        // the configurator
        $ip_views_optional      = '';
        $ip_comments            = '';
        $ip_likes_optional      = '';
        $ip_author_optional     = '';
        $ip_meta_optional       = '';
        $ip_title_optional      = '';

        foreach($posts as $user_image) {
            setup_postdata($user_image);

			$user_info = get_userdata($user_image->post_author);
			$post_thumbnail_id = get_post_thumbnail_id($user_image->ID);   

			// get attachment source
			$image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');

			if(get_option('ip_click_behaviour') == 'media')
				$ip_image_link = $image_attributes[0];
			if(get_option('ip_click_behaviour') == 'custom')
				$ip_image_link = get_permalink($user_image->ID);

			// make all "brick" elements optional and active by default
			if(get_option('ip_title_optional') == 1)
				$ip_title_optional = '<a href="' . $ip_image_link . '"><b class="imagetitle">' . get_the_title($user_image->ID) . '</b></a>';
			if(get_option('ip_author_optional') == 1)
				$ip_author_optional = '<span class="teal name">By <a href="' . get_author_posts_url($user_info->ID) . '">' . $user_info->display_name . '</a></span>';

			if(get_option('ip_meta_optional') == 1)
                $ip_meta_optional = '<small class="imagecategory" data-tag="' . strip_tags(get_the_term_list($user_image->ID, 'imagepress_image_category', '', ', ', '')) . '">' . strip_tags(get_the_term_list($user_image->ID, 'imagepress_image_category', '', ', ', '')) . '</small>';

            if(get_option('ip_views_optional') == 1)
                $ip_views_optional = '<i class="fa fa-eye"></i> <span class="imageviews">' . ip_getPostViews($user_image->ID) . '</span> ';
			if(get_option('ip_comments') == 1)
				$ip_comments = '<i class="fa fa-comments"></i> <span class="imagecomments">' . get_comments_number($user_image->ID) . '</span> ';
			if(get_option('ip_likes_optional') == 1)
				$ip_likes_optional = '<i class="fa fa-heart"></i> <span class="imagelikes">' . imagepress_get_like_count($user_image->ID) . '</span> ';

            if(!empty($size)) {
                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, $size);
			}
            else {
				$size = get_option('ip_image_size');
				$size = (string)$size;
                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, $size);
			}

			$tag = wp_get_post_terms($user_image->ID, 'imagepress_image_tag');
			$tag = $tag[0]->slug;
			if($tag == 'work-in-progress')
				$tagged = '<i class="fa fa-lg fa-flask"></i>';
			else if($tag == 'request-critique')
				$tagged = '<i class="fa fa-lg fa-comments-o"></i>';
			else
				$tagged = '';

            $out .= '<div class="ip_box ip_box_' . $user_image->ID . '"><a href="' . $ip_image_link . '" class="ip_box_img"><img src="" data-src="' . $image_attributes[0] . '" alt="" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '"></a><div class="ip_box_top">' . $ip_title_optional . $ip_author_optional . $ip_meta_optional . '</div><div class="ip_box_bottom">' . $tagged . $ip_views_optional . $ip_comments . $ip_likes_optional . '</div>';

			if(!empty($collection) && is_numeric($collection) && $collection > 0) {
				$logged_in_user = wp_get_current_user();
				if($collection_row['collection_author_ID'] == $logged_in_user->ID) {
					$out .= '<div class="ip_box_bottom"><a href="#" class="deleteCollectionImage" data-image-id="' . $user_image->ID . '"><i class="fa fa-times"></i> Remove</a></div>';
				}
			}

			$out .= '</div>';
		}

		$out .= '</ul>';
		if($limit == 999999 or $count == 0)
			$out .= '<ul class="pagination"></ul>';
		$out .= '</div><div class="ip_clear"></div>';

		return $out;
	} else {
		$out .= __('No images found!', 'imagepress');
		return $out;
	}

	return $out;
}

function imagepress_menu_bubble() {
	global $menu, $submenu;

	$args = array(
		'post_type' => get_option('ip_slug'),
		'post_status' => 'pending',
		'showposts' => -1,
	);
	$draft_ip_links = count(get_posts($args));

	if($draft_ip_links) {
		foreach($menu as $key => $value) {
			if($menu[$key][2] == 'edit.php?post_type=' . get_option('ip_slug')) {
				$menu[$key][0] .= ' <span class="update-plugins count-' . $draft_ip_links . '"><span class="plugin-count">' . $draft_ip_links . '</span></span>';
				return;
			}
		}
	}
	if($draft_ip_links) {
		foreach($submenu as $key => $value) {
			if($submenu[$key][2] == 'edit.php?post_type=' . get_option('ip_slug')) {
				$submenu[$key][0] .= ' <span class="update-plugins count-' . $draft_ip_links . '"><span class="plugin-count">' . $draft_ip_links . '</span></span>';
				return;
			}
		}
	}
}

function notify_status($new_status, $old_status, $post) {
	global $current_user;
	$contributor = get_userdata($post->post_author);

	$headers[] = "MIME-Version: 1.0\r\n";
	$headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";

	if($old_status != 'pending' && $new_status == 'pending') {
		$emails = get_option('ip_notification_email');
		if(strlen($emails)) {
			$subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" pending review';
			$message = "<p>A new post by {$contributor->display_name} is pending review.</p>";
			$message .= "<p>Author: {$contributor->user_login} <{$contributor->user_email}> (IP: {$_SERVER['REMOTE_ADDR']})</p>";
			$message .= "<p>Title: {$post->post_title}</p>";
			$category = get_the_category($post->ID);
			if(isset($category[0])) 
				$message .= "<p>Category: {$category[0]->name}</p>";
			wp_mail($emails, $subject, $message, $headers);
		}
	}
	elseif($old_status == 'pending' && $new_status == 'publish') {
		if(get_option('approvednotification') == 'yes') {
			$subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" approved';
			$message = "<p>{$contributor->display_name}, your post has been approved and published at " . get_permalink($post->ID) . ".</p>";
			wp_mail($contributor->user_email, $subject, $message, $headers);
		}
	}
	elseif($old_status == 'pending' && $new_status == 'draft' && $current_user->ID != $contributor->ID) {
		if(get_option('declinednotification') == 'yes') {
			$subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" declined';
			$message = "<p>{$contributor->display_name}, your post has not been approved.</p>";
			wp_mail($contributor->user_email, $subject, $message, $headers);
		}
	}
}

/*
 * Main shortcode function [imagepress]
 *
 */
function imagepress_widget($atts, $content = null) {
	extract(shortcode_atts(array(
        'type' => 'list', // list, top
		'mode' => 'views', // views, likes
        'count' => 5
	), $atts));

	$ip_vote_meta = get_option('ip_vote_meta');

    if($mode == 'likes')
        $imagepress_meta_key = $ip_vote_meta;
    if($mode == 'views')
        $imagepress_meta_key = 'post_views_count';

	if($type == 'top')
		$count = 1;

    $args = array(
        'post_type' 				=> get_option('ip_slug'),
        'posts_per_page' 			=> $count,
        'orderby' 					=> 'meta_value_num',
        'meta_key'                  => $imagepress_meta_key,
        'meta_query'                => array(
            array(
                'key'       => $imagepress_meta_key,
                'type'      => 'numeric'
            )
        ),
        'cache_results'             => false,
        'update_post_term_cache'    => false,
        'update_post_meta_cache'    => false,
        'no_found_rows'             => true,
    );

    $is = get_posts($args);

    if($is && ($type == 'list')) {
        $display = '<ul>';
            foreach($is as $i) {
                if($mode == 'likes')
                    $ip_link_value = getPostLikeLink($i->ID, false);
                if($mode == 'views')
                    $ip_link_value = ip_getPostViews($i->ID);

                $display .= '<li><a href="' . get_permalink($i->ID) . '">' . get_the_title($i->ID) . '</a> <small>(' . $ip_link_value . ')</small></li>';
            }
        $display .= '</ul>';
    }

    if(get_option('ip_disqus') == 1)
        $ip_disqus = '#disqus_thread';
    else
        $ip_disqus = '';

    if($is && ($type == 'top')) {
        foreach($is as $i) {
			if(get_option('ip_comments') == 1)
				$ip_comments = '<i class="fa fa-comments"></i> ' . get_comments_number($user_image->ID) . '';
			if(get_option('ip_comments') == 0)
				$ip_comments = '';

			$post_thumbnail_id = get_post_thumbnail_id($i->ID);   
			$image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');

			if(get_option('ip_click_behaviour') == 'media')
				$ip_image_link = $image_attributes[0];
			if(get_option('ip_click_behaviour') == 'custom')
				$ip_image_link = get_permalink($i->ID);

			$display .= '<div id="ip_container_2"><div class="ip_icon_hover">' . 
                    '<div><strong>' . get_the_title($i->ID) . '</strong></div>' . 
					'<div><small><i class="fa fa-eye"></i> ' . ip_getPostViews($i->ID) . ' ' . $ip_comments . ' <i class="fa fa-heart"></i> ' . imagepress_get_like_count($i->ID) . '</small></div>
				</div><a href="' . $ip_image_link . '" class="ip-link">' . wp_get_attachment_image($post_thumbnail_id, 'full') . '</a></div>';
		}
    }

    return $display;
}
?>