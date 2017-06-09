<?php
/*
 * ImagePress Module: Collections
 */

function addCollection() {
	global $wpdb;

	$collection_author_ID = intval($_POST['collection_author_id']);
	$collection_title = sanitize_text_field($_POST['collection_title']);
	$collection_title_slug = sanitize_title($_POST['collection_title']);
	$collection_status = intval($_POST['collection_status']);

	$wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collections (collection_title, collection_title_slug, collection_status, collection_author_ID) VALUES ('%s', '%s', %d, %d)", $collection_title, $collection_title_slug, $collection_status, $collection_author_ID));
	die();
}
function editCollectionTitle() {
	global $wpdb;

	$collection_ID = intval($_POST['collection_id']);
	$collection_title = sanitize_text_field($_POST['collection_title']);

	$wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_title = '%s' WHERE collection_ID = %d", $collection_title, $collection_ID));
	die();
}
function editCollectionStatus() {
	global $wpdb;

	$collection_ID = intval($_POST['collection_id']);
	$collection_status = intval($_POST['collection_status']);

	$wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_status = '%s' WHERE collection_ID = %d", $collection_status, $collection_ID));
	die();
}
function deleteCollection() {
	global $wpdb;

	$collection_ID = intval($_POST['collection_id']);

	$wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collections WHERE collection_ID = %d", $collection_ID));
	die();
}
function deleteCollectionImage() {
	global $wpdb;

	$image_ID = intval($_POST['image_id']);

	$wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = %d", $image_ID));
	die();
}

add_action('wp_ajax_addCollection', 'addCollection');
add_action('wp_ajax_editCollectionTitle', 'editCollectionTitle');
add_action('wp_ajax_editCollectionStatus', 'editCollectionStatus');
add_action('wp_ajax_deleteCollection', 'deleteCollection');
add_action('wp_ajax_deleteCollectionImage', 'deleteCollectionImage');

add_action('wp_ajax_ip_collection_display', 'ip_collection_display');
add_action('wp_ajax_ip_collections_display', 'ip_collections_display');

function ip_collection_display() {
	$collection_ID = intval($_POST['collection_id']);

	$result = do_shortcode('[imagepress-show collection="1" collection_id="' . $collection_ID . '"]');

	echo $result;

	die();
}

function ip_collections_display() {
	global $wpdb;

	$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

	echo '<div class="the">';
	foreach($result as $collection) {
		echo '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">';
			$postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "' LIMIT 4", ARRAY_A);
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);
			echo '<div class="ip_collection_box">';
				foreach($postslist as $collectable) {
					echo get_the_post_thumbnail($collectable['image_ID'], 'imagepress_ls_std');
				}
			echo '</div>';

			echo '<div class="collection_details">';
    			echo '<h3 class="collection-title" data-collection-id="' . $collection['collection_ID'] . '">' . $collection['collection_title'] . '</h3>';
                echo '<select class="collection-status cs' . $collection['collection_ID'] . '" data-collection-id="' . $collection['collection_ID'] . '">';
                    $selected = ($collection['collection_status'] == 0) ? 'selected' : '';
                    echo '<option value="1" ' . $selected . '>Public</option>';
                    echo '<option value="0" ' . $selected . '>Private</option>';
                echo '</select>';

                echo '<div><small><i class="fa fa-eye"></i> ' . $collection['collection_views'] . ' &nbsp; <i class="fa fa-file"></i> ' . count($postslistcount) . '</small></div>';
            echo '</div>';
            echo '<a href="#" class="editCollection button noir-secondary" data-collection-id="' . $collection['collection_ID'] . '"><i class="fa fa-pencil"></i></a>';
            echo '<a href="#" class="deleteCollection button noir-secondary" data-collection-id="' . $collection['collection_ID'] . '"><i class="fa fa-times"></i></a>';
		echo '</div>';
	}
	echo '</div><div style="clear:both;"></div>';

	die();
}
function ip_collections_display_public($author_ID) {
	global $wpdb;

	$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 AND collection_author_ID = '" . $author_ID . "'", ARRAY_A);


	$out = '<div class="the">';
	foreach($result as $collection) {
		$out .= '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">';
			$postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . $author_ID . "' LIMIT 4", ARRAY_A);
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . $author_ID . "'", ARRAY_A);
			$out .= '<div class="ip_collection_box">';
				foreach($postslist as $collectable) {
					$out .= get_the_post_thumbnail($collectable['image_ID'], 'imagepress_ls_std');
				}
			$out .= '</div>';

			$out .= '<div class="collection_details">';
    			$out .= '<h3><a href="' . home_url() . '/collections/' . $collection['collection_ID'] . '/">' . $collection['collection_title'] . '</a></h3>';
                $out .= '<div><small><i class="fa fa-eye"></i> ' . $collection['collection_views'] . ' &nbsp; <i class="fa fa-file"></i> ' . count($postslistcount) . '</small></div>';
            $out .= '</div>';
		$out .= '</div>';
	}
	$out .= '</div><div style="clear:both;"></div>';

	return $out;
}



// FRONT END BUTTON
function ip_frontend_add_collection($ip_id) {
	if(isset($_POST['collectme'])) {
		global $wpdb, $current_user;

		$ip_collections = intval($_POST['ip_collections']);

		$current_user = wp_get_current_user();
		$ip_collection_author_id = $current_user->ID;

		if(!empty($_POST['ip_collections_new'])) {
			$ip_collections_new = sanitize_text_field($_POST['ip_collections_new']);
			$ip_collection_status = intval($_POST['collection_status']);

			$wpdb->query("INSERT INTO " . $wpdb->prefix . "ip_collections (collection_title, collection_status, collection_author_ID) VALUES ('$ip_collections_new', $ip_collection_status, $ip_collection_author_id);");
			$wpdb->query("INSERT INTO " . $wpdb->prefix . "ip_collectionmeta (image_ID, image_collection_ID, image_collection_author_ID) VALUES ($ip_id, $wpdb->insert_id, $ip_collection_author_id);");
		} else {
			$wpdb->query("INSERT INTO " . $wpdb->prefix . "ip_collectionmeta (image_ID, image_collection_ID, image_collection_author_ID) VALUES ($ip_id, $ip_collections, $ip_collection_author_id);");
		}
	}
	?>
	<div class="textwidget">
		<a href="#" class="toggleFrontEndModal button noir-secondary"><i class="fa fa-folder"></i> Add to collection</a> <?php if(isset($_POST['collectme'])) { echo ' <i class="fa fa-check"></i>'; } ?>

		<div class="frontEndModal">
			<h2>Add to collection</h2>
			<a href="#" class="close toggleFrontEndModal"><i class="fa fa-times"></i> Close</a>

			<form method="post" class="imagepress-form">
				<input type="hidden" id="collection_author_id" name="collection_author_id" value="<?php echo $current_user->ID; ?>">

				<p>
					<?php
					global $wpdb;

					$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

					echo '<select name="ip_collections">
						<option value="">' . __('Choose a collection...', 'imagepress') . '</option>';
						foreach($result as $collection) {
							$disabled = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = '" . get_the_ID() . "' AND image_collection_ID = '" . $collection['collection_ID'] . "'", ARRAY_A);

							echo '<option value="' . $collection['collection_ID'] . '"';
							if(count($disabled) > 0)
								echo ' disabled';
							echo '>' . $collection['collection_title'];
							echo '</option>';
						}
					echo '</select>';
					?>
				</p>
				<p>or</p>
				<p><input type="text" name="ip_collections_new" placeholder="Create new collection..."></p>
				<p><label>Make this collection</label> <select id="collection_status"><option value="1">Public</option><option value="0">Private</option></select> <label><small><a href="#"><i class="fa fa-question-circle"></i> Read more</a></small></label></p>
				<p>
					<input type="submit" name="collectme" value="<?php echo __('Add', 'imagepress'); ?>">
					<label class="collection-progress"><i class="fa fa-cog fa-spin"></i></label>
					<label class="showme"> <i class="fa fa-check"></i> Collection created!</label>
				</p>
			</form>
		</div>
	</div>
	<?php
}
// END ALPHA
?>
