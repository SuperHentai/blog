<?php
function imagepress_registration() {
    $ip_slug = get_option('ip_slug');

	$image_type_labels = array(
		'name' 					=> _x('Images', 'post type general name'),
		'singular_name' 		=> _x('Image', 'post type singular name'),
		'add_new' 				=> _x('Add New Image', 'image'),
		'add_new_item' 			=> __('Add New Image'),
		'edit_item' 			=> __('Edit Image'),
		'new_item' 				=> __('Add New Image'),
		'all_items' 			=> __('View Images'),
		'view_item' 			=> __('View Image'),
		'search_items' 			=> __('Search Images'),
		'not_found' 			=> __('No images found'),
		'not_found_in_trash' 	=> __('No images found in trash'), 
		'parent_item_colon' 	=> '',
		'menu_name' 			=> __('ImagePress', 'imagepress')
	);

	$image_type_args = array(
		'labels' 				=> $image_type_labels,
		'public' 				=> true,
		'query_var' 			=> true,
		'rewrite' 				=> true,
		'capability_type' 		=> 'post',
		'has_archive' 			=> true,
		'hierarchical' 			=> false,
		'map_meta_cap' 			=> true,
		'menu_position' 		=> null,
		'supports' 				=> array('title', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
		'menu_icon' 			=> 'dashicons-format-gallery',
	);

	register_post_type($ip_slug, $image_type_args);

	$image_category_labels = array(
		'name' 					=> _x('Image Categories', 'taxonomy general name'),
		'singular_name' 		=> _x('Image', 'taxonomy singular name'),
		'search_items' 			=> __('Search Image Categories'),
		'all_items' 			=> __('All Image Categories'),
		'parent_item' 			=> __('Parent Image Category'),
		'parent_item_colon' 	=> __('Parent Image Category:'),
		'edit_item' 			=> __('Edit Image Category'), 
		'update_item' 			=> __('Update Image Category'),
		'add_new_item' 			=> __('Add New Image Category'),
		'new_item_name' 		=> __('New Image Name'),
		'menu_name' 			=> __('Image Categories'),
	);

	$image_category_args = array(
		'hierarchical' 			=> true,
		'labels' 				=> $image_category_labels,
		'show_ui' 				=> true,
		'query_var' 			=> true,
		'rewrite' 				=> array('slug' => 'user_image_category'),
	);

	register_taxonomy('imagepress_image_category', array($ip_slug), $image_category_args);

    // image tags
    $labels = array(
		'name'                       => _x('Image Tags', 'Taxonomy General Name', 'imagepress'),
		'singular_name'              => _x('Image Tag', 'Taxonomy Singular Name', 'imagepress'),
		'menu_name'                  => __('Image Tags', 'imagepress'),
		'all_items'                  => __('All Tags', 'imagepress'),
		'parent_item'                => __('Parent Tag', 'imagepress'),
		'parent_item_colon'          => __('Parent Tag:', 'imagepress'),
		'new_item_name'              => __('New Tag Name', 'imagepress'),
		'add_new_item'               => __('Add New Tag', 'imagepress'),
		'edit_item'                  => __('Edit Tag', 'imagepress'),
		'update_item'                => __('Update Tag', 'imagepress'),
		'separate_items_with_commas' => __('Separate tags with commas', 'imagepress'),
		'search_items'               => __('Search Tags', 'imagepress'),
		'add_or_remove_items'        => __('Add or remove tags', 'imagepress'),
		'choose_from_most_used'      => __('Choose from the most used tags', 'imagepress'),
		'not_found'                  => __('Not Found', 'imagepress'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);

	register_taxonomy('imagepress_image_tag', array($ip_slug), $args);

    // image keywords
    $labels = array(
		'name'                       => _x('Image Keywords', 'Taxonomy General Name', 'imagepress'),
		'singular_name'              => _x('Image Keyword', 'Taxonomy Singular Name', 'imagepress'),
		'menu_name'                  => __('Image Keywords', 'imagepress'),
		'all_items'                  => __('All Keywords', 'imagepress'),
		'parent_item'                => __('Parent Keyword', 'imagepress'),
		'parent_item_colon'          => __('Parent Keyword:', 'imagepress'),
		'new_item_name'              => __('New Keyword Name', 'imagepress'),
		'add_new_item'               => __('Add New Keyword', 'imagepress'),
		'edit_item'                  => __('Edit Keyword', 'imagepress'),
		'update_item'                => __('Update Keyword', 'imagepress'),
		'separate_items_with_commas' => __('Separate keywords with commas', 'imagepress'),
		'search_items'               => __('Search Keywords', 'imagepress'),
		'add_or_remove_items'        => __('Add or remove keywords', 'imagepress'),
		'choose_from_most_used'      => __('Choose from the most used keywords', 'imagepress'),
		'not_found'                  => __('Not Found', 'imagepress'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);

    register_taxonomy('imagepress_image_keyword', array($ip_slug), $args);
}



function ip_getPostViews($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count == '') {
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return '0';
	}
	return $count;
}
function ip_setPostViews($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count == '') {
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, 0);
	}
    else {
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}



// front-end image editor
function wp_get_object_terms_exclude_filter($terms, $object_ids, $taxonomies, $args) {
    if(isset($args['exclude']) && $args['fields'] == 'all') {
        foreach($terms as $key => $term) {
            foreach($args['exclude'] as $exclude_term) {
                if($term->term_id == $exclude_term) {
                    unset($terms[$key]);
                }
            }
        }
    }
    $terms = array_values($terms);
    return $terms;
}
add_filter('wp_get_object_terms', 'wp_get_object_terms_exclude_filter', 10, 4);

// frontend image editor
function ip_editor() {
    global $post, $current_user;

    get_currentuserinfo();

    // check if user is author // show author tools
    if($post->post_author == $current_user->ID) { ?>
        <section>
            <nav>
                <ul>
                    <li><a href="#" class="ip-editor-display"><i class="fa fa-wrench"></i> <?php _e('Author tools', 'imagepress'); ?></a></li>
                </ul>
            </nav>
        </section>
        <?php
        $edit_id = get_the_ID();

        if(isset($_GET['d'])) {
            $post_id = $_GET['d'];
            wp_delete_post($post_id);
            echo '<script>window.location.href="' . home_url() . '?deleted"</script>';
        }
        if('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['post_id']) && !empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['postcontent'])) {
            $post_id = $_POST['post_id'];
            $post_type = get_post_type($post_id);
            $capability = ('page' == $post_type) ? 'edit_page' : 'edit_post';
            if(current_user_can($capability, $post_id) && wp_verify_nonce($_POST['update_post_nonce'], 'update_post_'. $post_id)) {
                $post = array(
                    'ID'             => esc_sql($post_id),
                    'post_content'   => (stripslashes($_POST['postcontent'])),
                    'post_title'     => esc_sql($_POST['post_title'])
                );
                wp_update_post($post);

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
                            $_FILES = array("imagepress_image_additional" => $file);
                            foreach($_FILES as $file => $array) {
                                imagepress_process_image('imagepress_image_additional', $post_id, '');
                            }
                        }
                    }
                }
                // end multiple images

				$thumbnail_ID = $_POST['thumbnail_id'];
				update_post_meta($post_id, '_thumbnail_id', $thumbnail_ID);

                wp_set_object_terms($post_id, (int)$_POST['imagepress_image_category'], 'imagepress_image_category');
                if(get_option('ip_allow_tags') == 1)
                    wp_set_object_terms($post_id, (int)$_POST['imagepress_image_tag'], 'imagepress_image_tag');

                if('' != get_option('ip_behance_label'))
                    update_post_meta((int)$post_id, 'imagepress_behance', (string)$_POST['imagepress_behance']);
                if('' != get_option('ip_purchase_label'))
                    update_post_meta((int)$post_id, 'imagepress_purchase', (string)$_POST['imagepress_purchase']);
                if('' != get_option('ip_video_label'))
                    update_post_meta((int)$post_id, 'imagepress_video', (string)$_POST['imagepress_video']);
                if('' != get_option('ip_sticky_label'))
                    update_post_meta((int)$post_id, 'imagepress_sticky', (string)$_POST['imagepress_sticky']);
                if('' != get_option('ip_print_label'))
                    update_post_meta((int)$post_id, 'imagepress_print', (string)$_POST['imagepress_print']);

                echo '<script>window.location.href="' . $_SERVER['REQUEST_URI'] . '"</script>';
            }
            else {
                wp_die("You can't do that");
            }
        }
        ?>
        <div id="info" class="ip-editor">
            <form id="post" class="post-edit front-end-form imagepress-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="<?php echo $edit_id; ?>">
                <input type="hidden" name="thumbnail_id" value="<?php echo get_post_thumbnail_id(); ?>">
                <?php wp_nonce_field('update_post_' . $edit_id, 'update_post_nonce'); ?>

                <p><input type="text" id="post_title" name="post_title" value="<?php echo get_the_title($edit_id); ?>"></p>
                <p><textarea name="postcontent" rows="3"><?php echo strip_tags(get_post_field('post_content', $edit_id)); ?></textarea></p>
                <hr>
                <?php if('' != get_option('ip_behance_label')) { ?>
                    <p><input type="url" name="imagepress_behance" value="<?php echo get_post_meta($edit_id, 'imagepress_behance', true); ?>" placeholder="<?php echo get_option('ip_behance_label'); ?>"></p>
                <?php } ?>
                <?php if('' != get_option('ip_purchase_label')) { ?>
                    <p><input type="url" name="imagepress_purchase" value="<?php echo get_post_meta($edit_id, 'imagepress_purchase', true); ?>" placeholder="<?php echo get_option('ip_purchase_label'); ?>"></p>
                <?php } ?>
                <?php if('' != get_option('ip_video_label')) { ?>
                    <p><input type="url" name="imagepress_video" value="<?php echo get_post_meta($edit_id, 'imagepress_video', true); ?>" placeholder="<?php echo get_option('ip_video_label'); ?>"></p>
                <?php } ?>
                <hr>

                <?php if('' != get_option('ip_sticky_label')) { ?>
                    <p><input type="checkbox" id="imagepress_sticky" name="imagepress_sticky" value="1"<?php if(get_post_meta($edit_id, 'imagepress_sticky', true) == 1) echo ' checked'; ?>> <label for="imagepress_sticky"><?php echo get_option('ip_sticky_label'); ?></label></p>
                <?php } ?>
                <?php if('' != get_option('ip_print_label')) { ?>
                    <p><input type="checkbox" id="imagepress_print" name="imagepress_print" value="1"<?php if(get_post_meta($edit_id, 'imagepress_print', true) == 1) echo ' checked'; ?>> <label for="imagepress_print"><?php echo get_option('ip_print_label'); ?></label></p>
                <?php } ?>

                <?php $ip_category = wp_get_object_terms($edit_id, 'imagepress_image_category', array('exclude' => array(4))); ?>
                <?php if(get_option('ip_allow_tags') == 1) $ip_tag = wp_get_post_terms($edit_id, 'imagepress_image_tag'); ?>

                <p>
                    <?php echo imagepress_get_image_categories_dropdown('imagepress_image_category', $ip_category[0]->term_id); ?> 
                    <?php if(get_option('ip_allow_tags') == 1) echo imagepress_get_image_tags_dropdown('imagepress_image_tag', $ip_tag[0]->term_id); ?> 
                </p>

                <?php if(1 == get_option('ip_upload_secondary')) { ?>
                    <hr>
                    <p>
                        <?php _e('Select', 'imagepress'); ?> <i class="fa fa-check-circle"></i> <?php _e('main image or', 'imagepress'); ?> <i class="fa fa-times-circle"></i> <?php _e('delete additional images', 'imagepress'); ?>
                        <br><small><?php _e('Main image will appear first in single image listing and as a thumbnail in gallery view', 'imagepress'); ?></small>
                    </p>
                    <?php
                    $thumbnail_ID = get_post_thumbnail_id();
                    $images = get_children(array('post_parent' => $edit_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));

                    //if($images && count($images) > 1) {
                        echo '<div>';
                        foreach($images as $attachment_id => $image) {
                            $small_array = image_downsize($image->ID, 'thumbnail');
                            $big_array = image_downsize($image->ID, 'full');

                            if($image->ID == $thumbnail_ID)
                                echo '<div class="ip-additional-active">';
                            if($image->ID != $thumbnail_ID)
                                echo '<div class="ip-additional">';
                                echo '<div class="ip-toolbar">';
                                    echo '<a href="#" data-id="' . $image->ID . '" data-nonce="' . wp_create_nonce('my_delete_post_nonce') . '" class="delete-post ip-action-icon ip-floatright"><i class="fa fa-times-circle"></i></a>';
                                    echo '<a href="#" data-pid="' . $edit_id . '" data-id="' . $image->ID . '" data-nonce="' . wp_create_nonce('my_featured_post_nonce') . '" class="featured-post ip-action-icon ip-floatleft"><i class="fa fa-check-circle"></i></a>';
                                echo '</div>';
                            echo '<img src="' . $small_array[0] . '" alt=""></div>';
                        }
                        echo '</div>';
                    //}
                    ?>

                    <p><label for="imagepress_image_additional"><i class="fa fa-cloud-upload"></i> <?php _e('Add more images', 'imagepress'); ?> (<?php echo MAX_UPLOAD_SIZE/1024; ?>KB <?php _e('maximum', 'imagepress'); ?>)...</label><br><input type="file" accept="image/*" capture="camera" name="imagepress_image_additional[]" id="imagepress_image_additional" multiple></p>
                <?php } ?>

                <hr>
                <p>
                    <input type="submit" id="submit" value="<?php _e('Update image', 'imagepress'); ?>">
                    <a href="?d=<?php echo get_the_ID(); ?>" class="ask button ip-floatright"><i class="fa fa-trash-o"></i></a>
                </p>
            </form>
        </div>
        <?php wp_reset_query(); ?>
    <?php }
}

// ip_editor() related actions
add_action('wp_ajax_my_delete_post', 'my_delete_post');
function my_delete_post() {
    $permission = check_ajax_referer('my_delete_post_nonce', 'nonce', false);
    if($permission == false) {
        echo 'error';
    }
    else {
        wp_delete_post($_REQUEST['id']);
        echo 'success';
    }
    die();
}
add_action('wp_ajax_my_featured_post', 'my_featured_post');
function my_featured_post() {
    $permission = check_ajax_referer('my_featured_post_nonce', 'nonce', false);
    if($permission == false) {
        echo 'error';
    }
    else {
        update_post_meta($_REQUEST['pid'], '_thumbnail_id', $_REQUEST['id']);
        //wp_delete_post($_REQUEST['id']);
        echo 'success';
    }
    die();
}



// main ImagePress image function
function ip_main($i) {
    // show image editor
    ip_editor();

	$post_thumbnail_id = get_post_thumbnail_id($i);
    $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');
    $post_thumbnail_url = $image_attributes[0];

    if(get_option('ip_disqus') == 1)
        $ip_disqus = '#disqus_thread';
    else
        $ip_disqus = '';

	if(get_option('ip_comments') == 1)
        $ip_comments = '<em> | </em><a href="' . get_permalink($i) . $ip_disqus . '"><i class="fa fa-comments"></i> ' . get_comments_number($i) . '</a> ';
    if(get_option('ip_comments') == 0)
        $ip_comments = '';
    ?>

    <div class="imagepress-container">
        <?php the_post_thumbnail('full'); ?>
        <?php ip_setPostViews($i); ?>
    </div>
    <?php imagepress_get_images($i); ?>

    <?php
    $imagepress_video = get_post_meta($i, 'imagepress_video', true);
    if(!empty($imagepress_video)) {
        echo '<br>';
        $embed_code = wp_oembed_get($imagepress_video);
        echo $embed_code;
        echo '<br>';
    }
    ?>

    <section role="navigation">
        <?php previous_post_link('%link', '<i class="fa fa-fw fa-chevron-left"></i> Previous'); ?>
        <?php next_post_link('%link', 'Next <i class="fa fa-fw fa-chevron-right"></i>'); ?>
    </section>

    <div class="social-hub">
        <i class="fa fa-share-square-o"></i> <?php _e('Share via', 'imagepress'); ?><br>
        <a style="background-color: #3B5998;" href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 'facebook-share-dialog', 'width=626,height=436'); return false;"><i class="fa fa-facebook-square"></i></a>
        <a style="background-color: #00ACED;" href="https://twitter.com/share" target="_blank" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="twitter-share" data-via="getButterfly" data-related="getButterfly" data-count="none" data-hashtags="cghubs"><i class="fa fa-twitter-square"></i></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <a style="background-color: #d23e30;" href="#" onclick="javascript:window.open('https://plus.google.com/share?url='+encodeURIComponent(location.href),'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus-square"></i></a>
        <div class="clearfix"></div>
    </div>

    <h1 class="ip-title">
        <?php
        if(has_term('featured', 'imagepress_image_category'))
            echo '<span class="hint hint--right" data-hint="' . get_option('cms_featured_tooltip') . '"><i class="fa fa-star"></i></span> ';

        echo get_the_title($i);

        if(get_option('ip_allow_tags') == 1) {
            $terms = get_the_terms($i, 'imagepress_image_tag');

            if($terms && !is_wp_error($terms)) :
                $term_links = array();
                foreach($terms as $term) {
                    $term_links[] = $term->name;
                }
                $tags = join(', ', $term_links);
                echo '<br><small><b><i class="fa fa-info-circle"></i> ' . __('Status', 'imagepress') . ':</b> ' . $tags . '</small>';
            endif;
        }
        ?>
    </h1>

    <div class="ip-bar">
        <div class="right">
            <?php if(get_post_meta($i, 'imagepress_print', true) == 1) { ?>
                <span class="hint hint--left" data-hint="<?php echo get_option('cms_available_for_print'); ?>"><i class="fa fa-fw fa-print"></i></span>
            <?php } ?>
            <a href="<?php echo $post_thumbnail_url; ?>"><i class="fa fa-fw fa-arrows-alt"></i></a>
        </div>

        <?php echo getPostLikeLink($i); ?><em> | </em><i class="fa fa-eye"></i> <?php echo ip_getPostViews($i); ?><?php echo $ip_comments; ?>
    </div>

    <p>
        <div style="float: left; margin: 0 8px 0 0;">
            <?php echo get_avatar($post->post_author, 40); ?>
        </div>
        <?php
        if(get_the_author_meta('user_title', $post->post_author) == 'Verified')
            $verified = ' <span class="teal hint hint--right" data-hint="' . get_option('cms_verified_profile') . '"><i class="fa fa-check-square"></i></span>';
        else
            $verified = '';
        ?>
        <?php _e('by', 'imagepress'); ?> <b><?php the_author_posts_link(); ?></b> <?php echo $verified; ?>
        <br><small>Uploaded <time title="<?php the_time(get_option('date_format')); ?>"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></time> in <?php echo get_the_term_list(get_the_ID(), 'imagepress_image_category', '', ', ', ''); ?></small>
    </p>

    <section>
        <?php echo wpautop(make_clickable(get_the_content())); ?>
    </section>
    <?php
}

function ip_get_the_term_list( $id = 0, $taxonomy, $before = '', $sep = '', $after = '', $exclude = array() ) {
	$terms = get_the_terms( $id, $taxonomy );

	if ( is_wp_error( $terms ) )
		return $terms;

	if ( empty( $terms ) )
		return false;

	foreach ( $terms as $term ) {

		if(!in_array($term->term_id,$exclude)) {
			$link = get_term_link( $term, $taxonomy );
			if ( is_wp_error( $link ) )
				return $link;
			$term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
		}
	}

	$term_links = apply_filters( "term_links-$taxonomy", $term_links );

	return $before . join( $sep, $term_links ) . $after;
}

function imagepress_get_images($post_id) {
    global $post;

    $thumbnail_ID = get_post_thumbnail_id();
    $images = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));

    if($images && count($images) > 1) {
        echo '<div class="ip-more">';
            foreach($images as $attachment_id => $image) {
                if($image->ID != $thumbnail_ID) {
                    $big_array = image_downsize($image->ID, 'full');

                    echo '<img src="' . $big_array[0] . '" alt="">';
                }
            }
        echo '</div>';
    }
}

// override WordPress notification email
// redefine user notification function
if(get_option('ip_override_email_notification') == 1) {
    if(!function_exists('wp_new_user_notification')) {
        function wp_new_user_notification($user_id, $plaintext_pass = '') {
            $user = new WP_User($user_id);

            $user_login = stripslashes($user->user_login);
            $user_email = stripslashes($user->user_email);

            $message  = sprintf(__('New user registration on %s:'), get_option('blogname')) . "\r\n\r\n";
            $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
            $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

            @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);

            if(empty($plaintext_pass))
                return;

            $message  = __('Hi there,') . "\r\n\r\n";
            $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
            $message .= get_option('cinnamon_account_page') . "\r\n";
            $message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
            $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";
            $message .= __('--') . "\r\n";
            $message .= get_option('blogname') . "\r\n";

            wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);
        }
    }
}


function imagepress_enqueue_pointer_script_style($hook_suffix) {
    $enqueue_pointer_script_style = false;
    $dismissed_pointers = explode(',', get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
    if(!in_array('imagepress_settings_pointer', $dismissed_pointers)) {
        $enqueue_pointer_script_style = true;
        add_action('admin_print_footer_scripts', 'imagepress_pointer_print_scripts');
    }

    if($enqueue_pointer_script_style) {
        wp_enqueue_style('wp-pointer');
        wp_enqueue_script('wp-pointer');
    }
}
add_action('admin_enqueue_scripts', 'imagepress_enqueue_pointer_script_style');

function imagepress_pointer_print_scripts() {
    $pointer_content  = '<h3>Thank you for installing ImagePress!</h3>';
    $pointer_content .= '<p>Check the <b>Installation</b> section and follow all steps carefully.</p>';
    $pointer_content .= '<p>Make sure you resave your <b>Permalinks</b> settings after you set the image slug or the author profile slug.</p>';
    ?>
    <script type="text/javascript">
	//<![CDATA[
    jQuery(document).ready(function($) {
        $('#menu-posts-<?php echo get_option('ip_slug'); ?>').pointer({
			content:		'<?php echo $pointer_content; ?>',
			position:		{
								edge:	'left', // arrow direction
								align:	'center' // vertical alignment
							},
			pointerWidth:	350,
			close:			function() {
								$.post( ajaxurl, {
										pointer: 'imagepress_settings_pointer', // pointer ID
										action: 'dismiss-wp-pointer'
								});
							}
		}).pointer('open');
	});
	//]]>
	</script>
<?php }

function kformat($number) {
	$prefixes = 'KMGTPEZY';
	if($number >= 1000) {
		$log1000 = floor(log10($number)/3);
		return floor($number/pow(1000, $log1000)).$prefixes[$log1000-1];
	}
	return $number;
}

function ip_related($i) {
	global $post;
	$post_thumbnail_id = get_post_thumbnail_id($i);
	$author_id = $post->post_author;
	$filesize = filesize(get_attached_file($post_thumbnail_id)) / 1024;
	$filesize = number_format($filesize, 2, '.', ' ');
	$filesize .= ' KB';
	?>

	<p><?php previous_post_link('%link', '<i class="fa fa-fw fa-chevron-left"></i>'); ?> <?php next_post_link('%link', '<i class="fa fa-fw fa-chevron-right"></i>'); ?></p>

	<h3 class="widget-title"><i class="fa fa-file-text-o"></i> Image Details</h3>
	<div class="textwidget">
		<p><small>
			&copy;<?php echo date('Y'); ?> <?php the_author_posts_link(); ?> | <b>Image size:</b> <?php echo $filesize; ?> | <b>Date uploaded:</b> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?> (<?php the_time(get_option('date_format')); ?>) | <b>Category:</b> <?php echo ip_get_the_term_list($i, 'imagepress_image_category', '', ', ', '', array()); ?> | <b>Status:</b> <?php echo get_the_term_list($i, 'imagepress_image_tag', '', ', ', ''); ?>
			<?php if(get_post_meta($i, 'imagepress_print', true) == 1) { ?>
				 | <i class="fa fa-print"></i> Available for print
			<?php } ?>
			<br>
			<b><?php echo ip_getPostViews($i); ?></b> views, <b><?php echo get_comments_number($i); ?></b> comments, <b><?php echo imagepress_get_like_count($i); ?></b> likes
		</small></p>
	</div>
	<div class="textwidget">
		<?php
		$hub_user_info = get_userdata($author_id);

		if(get_post_meta($i, 'imagepress_behance', true) != '' || get_post_meta($i, 'imagepress_purchase', true) != '') {
			echo '<h3 class="widget-title"><i class="fa fa-external-link-square"></i> External Links</h3>';
			echo '<p>';
		}

		if(get_post_meta($i, 'imagepress_behance', true) != '')
			echo '<a href="' . get_post_meta($i, 'imagepress_behance', true) . '" target="_blank" rel="external"><i class="fa fa-behance-square"></i> View Behance Project</a>';
		if(get_post_meta($i, 'imagepress_purchase', true) != '')
			echo '<a href="' . get_post_meta($i, 'imagepress_purchase', true) . '" target="_blank" rel="external"><i class="fa fa-shopping-cart"></i> Purchase Print</a>';
		if(get_post_meta($i, 'imagepress_behance', true) != '' || get_post_meta($i, 'imagepress_purchase', true) != '')
			echo '</p>';
		?>
	</div>

    <hr>

	<div class="widget-container widget_text">
		<h3 class="widget-title"><i class="fa fa-tags"></i> Related Posters</h3>
		<div class="textwidget">
			<p><i class="fa fa-user"></i> More by the same author (<a href="<?php echo get_author_posts_url($post->post_author); ?>">view all</a>)</p>
			<?php echo cinnamon_get_related_author_posts($post->post_author); ?>
		</div>
	</div>
	<?php
}

function ip_author() {
	// check for external portfolio // if page call is made from subdomain (e.g. username.domain.ext), display external page
	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$parseUrl = parse_url($url);
	$ext_detect = trim($parseUrl['path']);
	if($ext_detect == '/') {
		echo '<div id="hub-loading"></div>';
		echo do_shortcode('[cinnamon-profile-blank]');
	}
	else {
		echo do_shortcode('[cinnamon-profile]');
	}
}
?>
