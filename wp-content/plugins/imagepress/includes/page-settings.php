<?php
function imagepress_admin_page() {
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><b>Image</b>Press Settings</h2>

		<?php
		$t = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard_tab';
		if(isset($_GET['tab']))
			$t = $_GET['tab'];

        $i = get_option('ip_slug');
		?>
		<h2 class="nav-tab-wrapper">
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=dashboard_tab" class="nav-tab <?php echo $t == 'dashboard_tab' ? 'nav-tab-active' : ''; ?>"><div class="dashicons dashicons-info"></div></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=install_tab" class="nav-tab <?php echo $t == 'install_tab' ? 'nav-tab-active' : ''; ?>">Installation</a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=settings_tab" class="nav-tab <?php echo $t == 'settings_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'imagepress'); ?></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=configurator_tab" class="nav-tab <?php echo $t == 'configurator_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Configurator', 'imagepress'); ?></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=label_tab" class="nav-tab <?php echo $t == 'label_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Labels', 'imagepress'); ?></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=notifications_tab" class="nav-tab <?php echo $t == 'notifications_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Notifications', 'imagepress'); ?></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=upload_tab" class="nav-tab <?php echo $t == 'upload_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Upload', 'imagepress'); ?></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=users_tab" class="nav-tab <?php echo $t == 'users_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Users', 'imagepress'); ?></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=email_tab" class="nav-tab <?php echo $t == 'email_tab' ? 'nav-tab-active' : ''; ?>"><div class="dashicons dashicons-email-alt"></div></a>
			<a href="edit.php?post_type=<?php echo $i; ?>&page=imagepress_admin_page&amp;tab=maintenance_tab" class="nav-tab <?php echo $t == 'maintenance_tab' ? 'nav-tab-active' : ''; ?>"><div class="dashicons dashicons-admin-generic"></div></a>
		</h2>

		<?php if($t == 'dashboard_tab') {
            // Get the WP built-in version
            $wp_jquery_ver = $GLOBALS['wp_scripts']->registered['jquery']->ver;

            echo '<div class="wrap">
				<h2><b>Image</b>Press</h2>

				<div id="poststuff" class="ui-sortable meta-box-sortables">
					<div class="postbox">
						<h3>Dashboard (Help and general usage)</h3>
						<div class="inside">
							<p>Thank you for using <b>Image</b>Press, a multi-purpose fully-featured and WordPress-integrated image gallery plugin.</p>
        					<p>
                                <small>You are using <b>Image</b>Press plugin version <strong>' . IP_PLUGIN_VERSION . '</strong>.</small><br>
                                <small>Dependencies: <a href="http://fontawesome.io/" rel="external">FontAwesome</a> 4.3.0 and jQuery ' . $wp_jquery_ver . '.</small>
                            </p>

							<h4>Help with shortcodes</h4>
							<p>
								Use the shortcode tag <code>[imagepress-add]</code> in any post or page to show the submission form.<br>
								Use the shortcode tag <code>[imagepress-add category="landscapes"]</code> in any post or page to show the submission form with a fixed (hidden) category. Use this option for category-based contests. Use the category <b>slug</b>.<br>
                                Use the shortcode tag <code>[imagepress-add exclude="1,2,3"]</code> in any post or page to exclude certain categories from image submission.<br>
								Use the shortcode tag <code>[imagepress-search]</code> in any post or page to show the search form.<br>
								<br>
								Use the shortcode tag <code>[imagepress-show]</code> in any post or page to display all images.<br>
								Use the shortcode tag <code>[imagepress-show category="landscapes"]</code> in any post or page to display all images in a specific category. Use the category <b>slug</b>.<br>
								Use the shortcode tag <code>[imagepress-show sort="none"]</code> in any post or page to hide the category sorter/selector.<br>
								Use the shortcode tag <code>[imagepress-show author="yes"]</code> in any post or page to show the author sorter/selector.<br>

								Use the shortcode tag <code>[imagepress-show count="4"]</code> in any post or page to display a specific number of images.<br>
								Use the shortcode tag <code>[imagepress-show user="7"]</code> in any post or page to filter images by user ID.<br>
								<br>
								Use the shortcode tag <code>[imagepress type="top" mode="views" count="1"]</code> in any post or page to display the most viewed image.<br>
								Use the shortcode tag <code>[imagepress type="top" mode="likes" count="1"]</code> in any post or page to display the most voted image.<br>
								<br>
                                Use the shortcode tag <code>[imagepress mode="views"]</code> in a text widget to display most viewed images.<br>
                                Use the shortcode tag <code>[imagepress mode="likes"]</code> in a text widget to display most liked/voted images.<br>
                                Use the shortcode tag <code>[imagepress mode="likes/views" <b>count="10"</b>]</code> to adjust the number of displayed images.<br>
                                <br>
                                Use the shortcode tag <code>[imagepress-show sort="category" author="yes"]</code> to display category sort dropdown.<br>

								<br>
                                Use the shortcode tag <code>[notifications]</code> in any post or page to display the notifications (this feature is in beta status).<br>
							</p>

							<h4>Help with styling</h4>
							<p>Place a stylesheet file - <code>imagepress.css</code> - inside your active theme and add your own rules to override layout styling.</p>
							<p>See <code>/documentation/single-image.php</code> for a sample single image template. Match it with your <code>/single.php</code> template structure and drop it in your active theme.</p>

                            <h4>Profile Usage</h4>
                            <p>In order to view the user profile or portfolio, you need to create (or edit) the <code>author.php</code> file in your theme folder (<code>wp-content/themes/your-theme/author.php</code>) and add the following code:</p>

                            <p><textarea class="large-text code" rows="6">
&lt;?php
// BEGIN AUTHOR CODE // 5.1.2
if(function_exists("ip_author")) {
	ip_author();
}
// END IMAGEPRESS CODE
?&gt;
                            </textarea></p>
                            <p>
                                If you want to show the profile on a custom page, such as <b>My Profile</b> or <b>View My Portfolio</b>, use the <code>[cinnamon-profile]</code> shortcode.<br>
                                If you want to show a certain user profile on a page, use the <code>[cinnamon-profile author=17]</code> shortcode, where <b>17</b> is the user ID.
                            </p>
                            <p>In order for the above to work, you need to edit your .htaccess file and add these lines at the end:</p>
                            <p><textarea class="large-text code" rows="6">
# BEGIN Cinnamon Author Rewrite
RewriteCond %{HTTP_HOST} !^www\.domain.com
RewriteCond %{HTTP_HOST} ([^.]+)\.domain.com
RewriteRule ^(.*)$ ?author_name=%1
# END Cinnamon Author Rewrite
                            </textarea></p>
                            <p>Requirements for portfolio subdomains (jack.domain.com) include active permalinks, wildcard subdomain support (contact your hosting server for more information) and FTP access to your template files.</p>
						</div>
					</div>

                    <div class="postbox">
                        <div class="inside">
                            <p>For support, feature requests and bug reporting, please visit the <a href="//getbutterfly.com/" rel="external">official website</a>.</p>
                            <p>&copy;' . date('Y') . ' <a href="//getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <a href="//getbutterfly.com/forums/" rel="external">Support forums</a> &middot; <a href="//getbutterfly.com/trac/" rel="external">Trac</a> &middot; <small>Code wrangling since 2005</small></p>
                        </div>
                    </div>
				</div>
			</div>';
		} ?>
		<?php if($t == 'install_tab') { ?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Installation', 'imagepress'); ?></h3>
					<div class="inside">
                        <p>Check the installation steps below and make the required changes.</p>
                        <h2>Basic Installation</h2>
                        <?php
                        $slug = get_option('ip_slug');
                        $author_slug = get_option('cinnamon_author_slug');
                        $single_template = 'single-' . $slug . '.php';
                        $author_template = 'author.php';

                        if($slug == '')
                            echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> Your image slug is not set. Go to <b>Configurator</b> section and set it.</p>';
                        if($slug != '')
                            echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> Your image slug is <code>' . $slug . '</code>. If you changed it recently, visit your <b>Permalinks</b> section and resave the changes.</p>';

                        if($author_slug == '')
                            echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> Your author slug is not set. Go to <b>Users</b> section and set it.</p>';
                        if($author_slug != '')
                            echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> Your author slug is <code>' . $author_slug . '</code>. If you changed it recently, visit your <b>Permalinks</b> section and resave the changes.</p>';

                        if('' != locate_template($single_template))
                            echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> Your image template is available.</p>';

                        if('' == locate_template($single_template)) {
                            echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> Your image template is not available. Duplicate your <code>single.php</code> template file inside your theme folder, rename it as <code>' . $single_template . '</code> and replace the <code>the_content()</code> section with the code from the sample template file inside the /documentation/ folder.</p>';
                        }

                        if('' != locate_template($author_template))
                            echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> Your author template is available.</p>';
                        if('' == locate_template($author_template)) {
                            echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> Your author template is not available. Create a template file called <code>' . $author_template . '</code> inside your theme folder and replace the <code>the_content()</code> section with the code from the sample template file inside the /documentation/ folder.</p>';
                        }
						if(file_exists(get_template_directory() . '/imagepress.css')) {
							echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> An <code>imagepress.css</code> stylesheet was found inside your theme folder. Happy styling!</p>';
						} else {
							echo '<p><div class="dashicons dashicons-no"></div> <b>Note:</b> Place a stylesheet file - <code>imagepress.css</code> - inside your active theme and add your own rules to override layout styling.</p>';
						}
						if(get_option('default_role') == 'author') {
							echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> New user default role is <code>author</code>. Subscribers and contributors are not able to edit their uploaded images.</p>';
						} else {
							echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> New user default role should be <code>author</code> in order to allow for front-end image editing. Subscribers and contributors are not able to edit their uploaded images. <a href="' . admin_url('options-general.php') . '">Change it</a>.</p>';
						}
                        ?>
                        <h2>Advanced Installation (optional)</h2>
                        <p>The steps below require modification of .php and .htaccess files inside your web site root.</p>
                        <p>In order to enable the portfolio (hub) feature of ImagePress, check the <b>Dashboard</b> section and copy the required code inside your <code>author.php</code> template file and modify the <code>.htaccess</code> file.</p>
                        <?php
                        if(get_option('cinnamon_mod_hub') == 0)
                            echo '<p><div class="dashicons dashicons-no"></div> <b>Note:</b> Your portfolio (hub) is disabled. Go to <b>Users</b> section and enable it.</p>';
                        if(get_option('cinnamon_mod_hub') == 1)
                            echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> Your portfolio (hub) is enabled. Go to <b>Users</b> section and configure it. Also, make sure you made the correct changes to your <code>author.php</code> template file and your <code>.htaccess</code> file.</p>';
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
		<?php if($t == 'maintenance_tab') { ?>
			<?php
			if(isset($_POST['isResetSubmit'])) {
                global $wpdb;
				$ip_vote_meta = get_option('ip_vote_meta');

				$wpdb->query("UPDATE " . $wpdb->prefix . "postmeta SET meta_value = '0' WHERE meta_key = '" . $ip_vote_meta . "'");
                echo '<div class="updated notice is-dismissible"><p>Action completed successfully!</p></div>';
			}
			if(isset($_POST['isPrintSubmit'])) {
				$args = array(
					'post_type' => 'poster',
					'posts_per_page' => -1
				);

				$post_query = new WP_Query($args);
				if($post_query->have_posts()) {
					while($post_query->have_posts()) {
						$post_query->the_post();

						//
						$this_id = get_the_ID();

						$hardcoded_print = wp_get_object_terms($this_id, 'imagepress_image_property');
						if($hardcoded_print) {
							if($hardcoded_print[0]->slug == 'available-for-print') {
								add_post_meta($this_id, 'imagepress_print', 1, true);
							}
							else {
								add_post_meta($this_id, 'imagepress_print', 0, true);
							}
						}
						//
					}
				}
			}
			if(isset($_POST['isUpgrade'])) {
				delete_option('ip_presstrends');
				delete_option('ip_default_category');
				delete_option('ip_default_category_show');
				delete_option('ip_author_filter');
				delete_option('ip_box_styling');
				delete_option('ip_box_hover');
				delete_option('gs_title_colour');
				delete_option('gs_text_colour');
				delete_option('gs_cpt');
				delete_option('ip_module_masonry');
				delete_option('ip_width');
				delete_option('ip_content_optional');
				delete_option('ip_url_optional');
				delete_option('ip_module_flip');
				delete_option('cinnamon_post_type');

				delete_option('cinnamon_colour');
				delete_option('cinnamon_colour_step');
				delete_option('cinnamon_hover_colour');
				delete_option('act_settings');
				delete_option('cinnamon_awards_more');
				delete_option('cinnamon_mod_activity');
				delete_option('cinnamon_style_pure');

				delete_option('ip_timebeforerevote');
			    delete_option('ip_module_slider');
				delete_option('ip_lightbox');

				delete_option('gs_category', 0);
				delete_option('gs_slides', 5);
				delete_option('gs_width', '100%');
				delete_option('gs_autoplay', 0);
				delete_option('gs_secondary_background', '#dd9933');
				delete_option('gs_secondary_border', '#000000');
				delete_option('gs_secondary_border_type', 'solid');
				delete_option('gs_easing_style', 'easeOutQuint');
				delete_option('gs_additional_levels', 1);

				delete_option('cinnamon_text_colour');
				delete_option('cinnamon_background_colour');
				delete_option('ip_box_background');
				delete_option('ip_text_colour');
				delete_option('ip_cookie_expiration');
				delete_option('cinnamon_show_progress');

				delete_metadata('user', 0, 'hub_gender', '', true);

				wp_clear_scheduled_hook('act_cron_daily');

				global $wp_taxonomies;
				$taxonomy = 'imagepress_image_property';
				if(taxonomy_exists($taxonomy))
					unset($wp_taxonomies[$taxonomy]);
			}
			if(isset($_POST['isOptimizeImages'])) {
				$attachments = new WP_query(array(
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'posts_per_page' => '-1'
				));
				if($attachments->posts){
					$ip_width = get_option('ip_max_width');
					$ip_quality = get_option('ip_max_quality');

					/* Files required to update image information. */
					require_once(ABSPATH.'wp-admin/includes/image.php');
					require_once(ABSPATH.'wp-admin/includes/file.php');
					require_once(ABSPATH.'wp-admin/includes/media.php');
					/* Resize all attachments. */
					foreach($attachments->posts as $post) {
						/* Attachment file must be an image. */
						if(stripos($post->post_mime_type, 'image') === false){
							continue;
						}
						$image = wp_get_image_editor(get_attached_file($post->ID));
						if(is_wp_error($image)) {
							continue;
						}
						$image->set_quality($ip_quality);
						$image->resize($ip_width, 99999, false);
						$image->save(get_attached_file($post->ID));
						wp_update_attachment_metadata($post->ID, wp_generate_attachment_metadata($post->ID, get_attached_file($post->ID)));
					}
				}
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Maintenance', 'imagepress'); ?></h3>
					<div class="inside">
                        <p>All actions available on this page are irreversible. Please be cautious.</p>
						<form method="post" action="">
							<p>
								<input type="submit" name="isResetSubmit" value="Reset all likes" class="button-primary">
                                <br><small>This option resets <b>all</b> image likes to <b>0</b></small>
							</p>
							<p>
								<input type="submit" name="isPrintSubmit" value="Migrate print features to version 5" class="button-primary">
                                <br><small>This option migrates the print features from taxonomy to custom meta (available from version 5.0)</small>
							</p>
							<p>
								<input type="submit" name="isUpgrade" value="Remove pre-5 settings" class="button-primary">
                                <br><small>This option removes all pre-5 settings</small>
							</p>
							<p>
								<input type="submit" name="isOptimizeImages" value="Resize existing images" class="button-primary">
                                <br><small>Resize all existing images to a maximum width of <?php echo get_option('ip_max_width'); ?>px with <?php echo get_option('ip_max_quality'); ?> quality.</small>
							</p>
                        </form>
                    </div>
                </div>
            </div>
		<?php } ?>
		<?php if($t == 'configurator_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_slug',                $_POST['ip_slug']);
				update_option('ip_image_size',          $_POST['ip_image_size']);
				update_option('ip_title_optional',      $_POST['ip_title_optional']);
				update_option('ip_meta_optional',       $_POST['ip_meta_optional']);
				update_option('ip_views_optional',      $_POST['ip_views_optional']);
				update_option('ip_comments',            $_POST['ip_comments']);
				update_option('ip_likes_optional',      $_POST['ip_likes_optional']);
				update_option('ip_author_optional',     $_POST['ip_author_optional']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Configurator', 'imagepress'); ?></h3>
					<div class="inside">
                        <p>The <b>Configurator</b> allows you to select which information will be visible on the image box.</p>
						<form method="post" action="">
                            <p>
                                <input name="ip_slug" id="slug" type="text" class="regular-text" placeholder="Image slug" value="<?php echo get_option('ip_slug'); ?>" required> <label for="ip_slug"><b>Image</b> slug</label>
                                <br><small>Use an appropriate slug for your image (e.g. <b>image</b> in <code>domain.com/<b>image</b>/myimage</code>)</small>
                                <br><small>Tip: use a singular term, one word only (examples: image, poster, illustration)</small>
                            </p>
                            <p>
                                <select name="ip_image_size" id="ip_image_size">
                                    <optgroup label="WordPress (Default)">
                                        <option value="thumbnail"<?php if(get_option('ip_image_size') == 'thumbnail') echo ' selected'; ?>>Thumbnail</option>
                                    </optgroup>

                                    <optgroup label="Small">
                                        <option value="imagepress_sq_sm"<?php if(get_option('ip_image_size') == 'imagepress_sq_sm') echo ' selected'; ?>>Small (Square) (ImagePress)</option>
                                        <option value="imagepress_pt_sm"<?php if(get_option('ip_image_size') == 'imagepress_pt_sm') echo ' selected'; ?>>Small (Portrait) (ImagePress)</option>
                                        <option value="imagepress_ls_sm"<?php if(get_option('ip_image_size') == 'imagepress_ls_sm') echo ' selected'; ?>>Small (Landscape) (ImagePress)</option>
                                    </optgroup>

                                    <optgroup label="Standard">
                                        <option value="imagepress_sq_std"<?php if(get_option('ip_image_size') == 'imagepress_sq_std') echo ' selected'; ?>>Standard (Square) (ImagePress)</option>
                                        <option value="imagepress_pt_std"<?php if(get_option('ip_image_size') == 'imagepress_pt_std') echo ' selected'; ?>>Standard (Portrait) (ImagePress)</option>
                                        <option value="imagepress_ls_std"<?php if(get_option('ip_image_size') == 'imagepress_ls_std') echo ' selected'; ?>>Standard (Landscape) (ImagePress)</option>
                                    </optgroup>

                                    <optgroup label="ImagePress (Custom)">
                                        <option value="imagepress_thumbnail_wide"<?php if(get_option('ip_image_size') == 'imagepress_thumbnail_wide') echo ' selected'; ?>>Wide thumbnail - 300x150 (ImagePress)</option>
                                    </optgroup>
                                </select> <label for="ip_image_size"><b>Image box</b> thumbnail size</label>
                                <br><small>Use <b>thumbnail</b>, adjust the column size to match your thumbnail size and hide the description in order to have uniform sizes</small>
                            </p>
							<p>
								<select name="ip_title_optional" id="ip_title_optional">
									<option value="0"<?php if(get_option('ip_title_optional') == 0) echo ' selected'; ?>>Hide title</option>
									<option value="1"<?php if(get_option('ip_title_optional') == 1) echo ' selected'; ?>>Show title</option>
								</select>
								<label for="ip_title_optional"><b>Image box</b> title</label>
								<br><small>Show or hide the title of the image</small>
							</p>
							<p>
								<select name="ip_meta_optional" id="ip_meta_optional">
									<option value="0"<?php if(get_option('ip_meta_optional') == 0) echo ' selected'; ?>>Hide meta</option>
									<option value="1"<?php if(get_option('ip_meta_optional') == 1) echo ' selected'; ?>>Show meta</option>
								</select>
								<label for="ip_meta_optional"><b>Image box</b> meta</label>
								<br><small>Show or hide the meta information of the image (category/taxonomy)</small>
							</p>
							<p>
								<select name="ip_views_optional" id="ip_views_optional">
									<option value="0"<?php if(get_option('ip_views_optional') == 0) echo ' selected'; ?>>Hide views</option>
									<option value="1"<?php if(get_option('ip_views_optional') == 1) echo ' selected'; ?>>Show views</option>
								</select>
								<label for="ip_views_optional"><b>Image box</b> views</label>
								<br><small>Show or hide the number of image views</small>
							</p>
							<p>
								<select name="ip_likes_optional" id="ip_likes_optional">
									<option value="0"<?php if(get_option('ip_likes_optional') == 0) echo ' selected'; ?>>Hide likes</option>
									<option value="1"<?php if(get_option('ip_likes_optional') == 1) echo ' selected'; ?>>Show likes</option>
								</select>
								<label for="ip_likes_optional"><b>Image box</b> likes</label>
								<br><small>Show or hide the number of image likes</small>
							</p>
							<p>
								<select name="ip_comments" id="ip_comments">
									<option value="0"<?php if(get_option('ip_comments') == '0') echo ' selected'; ?>>Hide comments line</option>
									<option value="1"<?php if(get_option('ip_comments') == '1') echo ' selected'; ?>>Show comments line</option>
								</select>
								<label for="ip_comments"><b>Image box</b> comments line</label>
								<br><small>Show or hide the comments line in the image box (X comments)</small>
							</p>
							<p>
								<select name="ip_author_optional" id="ip_author_optional">
									<option value="0"<?php if(get_option('ip_author_optional') == 0) echo ' selected'; ?>>Hide author</option>
									<option value="1"<?php if(get_option('ip_author_optional') == 1) echo ' selected'; ?>>Show author</option>
								</select>
								<label for="ip_author_optional"><b>Image box</b> author</label>
								<br><small>Show or hide the author name and link</small>
							</p>
							<p>
								<input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary">
							</p>
                        </form>
                    </div>
                </div>
            </div>
		<?php } ?>
		<?php if($t == 'settings_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_ipp', $_POST['ip_ipp']);
				update_option('ip_padding', $_POST['ip_padding']);
				update_option('ip_upload_size', $_POST['ip_upload_size']);
				update_option('ip_moderate', $_POST['ip_moderate']);
				update_option('ip_registration', $_POST['ip_registration']);

				update_option('ip_click_behaviour', $_POST['ip_click_behaviour']);

				update_option('ip_order', $_POST['ip_order']);
				update_option('ip_orderby', $_POST['ip_orderby']);

				update_option('ip_createusers', $_POST['ip_createusers']);
				update_option('ip_cat_exclude', $_POST['ip_cat_exclude']);

                // modules
                update_option('cinnamon_mod_login', $_POST['cinnamon_mod_login']);
                update_option('ip_disqus', $_POST['ip_disqus']);
                update_option('ip_mod_collections', $_POST['ip_mod_collections']);

                update_option('ip_likes', $_POST['ip_likes']);
                update_option('ip_vote_meta', $_POST['ip_vote_meta']);
                update_option('ip_vote_like', stripslashes_deep($_POST['ip_vote_like']));
                update_option('ip_vote_unlike', stripslashes_deep($_POST['ip_vote_unlike']));
                update_option('ip_vote_nobody', stripslashes_deep($_POST['ip_vote_nobody']));
                update_option('ip_vote_who', stripslashes_deep($_POST['ip_vote_who']));
                update_option('ip_vote_who_singular', stripslashes_deep($_POST['ip_vote_who_singular']));
                update_option('ip_vote_who_plural', stripslashes_deep($_POST['ip_vote_who_plural']));
                update_option('ip_vote_who_link', stripslashes_deep($_POST['ip_vote_who_link']));
                update_option('ip_vote_login', stripslashes_deep($_POST['ip_vote_login']));

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Submission and Display Settings', 'imagepress'); ?></h3>
					<div class="inside">
						<form method="post" action="">
                            <fieldset>
                                <legend><b>Image</b>Press Modules</legend>
                                <p>
                                    <select name="ip_disqus" id="ip_disqus" style="width: 320px;">
                                        <option value="0"<?php if(get_option('ip_disqus') == 0) echo ' selected'; ?>>Disable Disqus integration</option>
                                        <option value="1"<?php if(get_option('ip_disqus') == 1) echo ' selected'; ?>>Enable Disqus integration</option>
                                    </select>
                                    <label for="ip_disqus"><b>Module:</b> Disqus integration</label>
                                </p>
                                <p>
                                    <select name="cinnamon_mod_login" id="cinnamon_mod_login" style="width: 320px;">
                                        <option value="1"<?php if(get_option('cinnamon_mod_login') == 1) echo ' selected'; ?>>Enable frontend login/registration module</option>
                                        <option value="0"<?php if(get_option('cinnamon_mod_login') == 0) echo ' selected'; ?>>Disable frontend login/registration module</option>
                                    </select> <label for="cinnamon_mod_login"><b>Module:</b> Frontend login</label>
                                    <br><small>Use the <code>[cinnamon-login]</code> shortcode to place a tabbed login/registration/password reset box anywhere on the site</small>
                                </p>
                                <p>
                                    <select name="ip_mod_collections" id="ip_mod_collections" style="width: 320px;">
                                        <option value="1"<?php if(get_option('ip_mod_collections') == 1) echo ' selected'; ?>>Enable collections module</option>
                                        <option value="0"<?php if(get_option('ip_mod_collections') == 0) echo ' selected'; ?>>Disable collections module</option>
                                    </select> <label for="ip_mod_collections"><b>Module:</b> Collections<sup>BETA</sup></label>
                                    <br><small>Note the updated single image template code for the collection button</small>
                                </p>
                            </fieldset>
                            <hr>
							<p>
								<select name="ip_click_behaviour" id="ip_click_behaviour">
									<option value="media"<?php if(get_option('ip_click_behaviour') == 'media') echo ' selected'; ?>>Open media (image) (recommended)</option>
									<option value="custom"<?php if(get_option('ip_click_behaviour') == 'custom') echo ' selected'; ?>>Open image page (requires custom post template)</option>
								</select>
								<label for="ip_click_behaviour"><b>Image</b> behaviour</label>
								<br><small>What to open when clicking on an image (single image or custom post template)</small>
							</p>
							<p>
								<select name="ip_registration" id="ip_registration">
									<option value="0"<?php if(get_option('ip_registration') == '0') echo ' selected'; ?>>Require user registration (recommended)</option>
									<option value="1"<?php if(get_option('ip_registration') == '1') echo ' selected'; ?>>Do not require user registration</option>
								</select>
								<label for="ip_registration"><b>User</b> registration</label>
								<br><small>Require users to be registered and logged in to upload images (recommended)</small>
							</p>
							<p>
								<select name="ip_moderate" id="ip_moderate">
									<option value="0"<?php if(get_option('ip_moderate') == '0') echo ' selected'; ?>>Moderate all images (recommended)</option>
									<option value="1"<?php if(get_option('ip_moderate') == '1') echo ' selected'; ?>>Do not moderate images</option>
								</select>
								<label for="ip_moderate"><b>Image</b> moderation</label>
								<br><small>Moderate all submitted images (recommended)</small>
							</p>
							<p>
								<select name="ip_createusers" id="ip_createusers">
									<option value="1"<?php if(get_option('ip_createusers') == '1') echo ' selected'; ?>>Create users on image submit (subscriber role)</option>
									<option value="0"<?php if(get_option('ip_createusers') == '0') echo ' selected'; ?>>Do not create users on image submit (default)</option>
								</select>
								<label for="ip_createusers">User creation</label>
								<br><small>Create a user (subscriber) when an image is submitted</small>
							</p>
							<p>
								<input type="text" name="ip_cat_exclude" id="ip_cat_exclude" value="<?php echo get_option('ip_cat_exclude'); ?>">
								<label for="ip_cat_exclude">Exclude categories</label>
								<br><small>Exclude these categories from the upload form (separate with comma)</small>
							</p>
							<p>
								<input type="number" name="ip_ipp" id="ip_ipp" min="1" max="9999" value="<?php echo get_option('ip_ipp'); ?>">
								<label for="ip_ipp"><b>Images</b> per page</label>
								<br><small>How many images per page do you want to display</small>
							</p>
							<p>
								<input type="number" name="ip_padding" id="ip_padding" min="0" max="9999" value="<?php echo get_option('ip_padding'); ?>">
								<label for="ip_padding"><b>Images</b> pading</label>
								<br><small>Gap between images (in pixels)</small>
							</p>
							<p>
								<input type="number" name="ip_upload_size" id="ip_upload_size" min="0" max="<?php echo (ini_get('upload_max_filesize') * 1024); ?>" step="1024" value="<?php echo get_option('ip_upload_size'); ?>">
								<label for="ip_upload_size"><b>Image</b> maximum upload size (in kilobytes)</label>
								<br><small>Try 2048 for most configurations (your server allows a maximum of <?php echo ini_get('upload_max_filesize'); ?>)</small>
							</p>

							<h3>Sorting and Ordering</h3>
							<p>
								<select name="ip_order" id="ip_order">
									<option value="ASC"<?php if(get_option('ip_order') == 'ASC') echo ' selected'; ?>>ASC</option>
									<option value="DESC"<?php if(get_option('ip_order') == 'DESC') echo ' selected'; ?>>DESC</option>
								</select>
								<label for="ip_order"><b>Image</b> ordering type</label>
								<br>
								<select name="ip_orderby" id="ip_orderby">
									<option value="none"<?php if(get_option('ip_orderby') == 'none') echo ' selected'; ?>>none</option>
									<option value="ID"<?php if(get_option('ip_orderby') == 'ID') echo ' selected'; ?>>ID</option>
									<option value="author"<?php if(get_option('ip_orderby') == 'author') echo ' selected'; ?>>author</option>
									<option value="title"<?php if(get_option('ip_orderby') == 'title') echo ' selected'; ?>>title</option>
									<option value="name"<?php if(get_option('ip_orderby') == 'name') echo ' selected'; ?>>name</option>
									<option value="date"<?php if(get_option('ip_orderby') == 'date') echo ' selected'; ?>>date</option>
									<option value="rand"<?php if(get_option('ip_orderby') == 'rand') echo ' selected'; ?>>rand</option>
									<option value="comment_count"<?php if(get_option('ip_orderby') == 'comment_count') echo ' selected'; ?>>comment_count</option>
								</select>
								<label for="ip_orderby"><b>Image</b> ordering mode</label>
							</p>

							<h3>Like/Unlike</h3>
							<p>
								<input type="text" name="ip_likes" id="ip_likes" value="<?php echo get_option('ip_likes'); ?>">
								<label for="ip_likes">General action name (plural)</label>
								<br><small>The name of the vote action ("like", "love", "appreciate", "vote"). Use plural.</small>
							</p>
							<p>
								<input type="text" name="ip_vote_meta" id="ip_vote_meta" value="<?php echo get_option('ip_vote_meta'); ?>">
								<label for="ip_vote_meta">Vote meta name</label>
								<br><small>The name of the vote meta field. Use this to migrate your old count.</small>
							</p>
							<p>
								<input type="text" name="ip_vote_like" id="ip_vote_like" value="<?php echo get_option('ip_vote_like'); ?>" placeholder="I like this image" class="regular-text">
								<label for="ip_vote_like">Vote "like" label</label>
								<br>
								<input type="text" name="ip_vote_unlike" id="ip_vote_unlike" value="<?php echo get_option('ip_vote_unlike'); ?>" placeholder="Oops! I don't like this" class="regular-text">
								<label for="ip_vote_unlike">Vote "unlike" label</label>
								<br>
								<input type="text" name="ip_vote_nobody" id="ip_vote_nobody" value="<?php echo get_option('ip_vote_nobody'); ?>" placeholder="Nobody likes this yet" class="regular-text">
								<label for="ip_vote_nobody">"No likes" label</label>
								<br>
								<input type="text" name="ip_vote_who" id="ip_vote_who" value="<?php echo get_option('ip_vote_who'); ?>" placeholder="Users that like this image:" class="regular-text">
								<label for="ip_vote_who">"Who" label</label>
							</p>
							<p>
								<input type="text" name="ip_vote_who_singular" id="ip_vote_who_singular" value="<?php echo get_option('ip_vote_who_singular'); ?>" placeholder="user likes this" class="regular-text">
								<label for="ip_vote_who_singular">Singular "who" label</label>
								<br>
								<input type="text" name="ip_vote_who_plural" id="ip_vote_who_plural" value="<?php echo get_option('ip_vote_who_plural'); ?>" placeholder="users like this" class="regular-text">
								<label for="ip_vote_who_plural">Plural "who" label</label>
								<br>
								<input type="text" name="ip_vote_who_link" id="ip_vote_who_link" value="<?php echo get_option('ip_vote_who_link'); ?>" placeholder="who?" class="regular-text">
								<label for="ip_vote_who_link">"Who" link label</label>
								<br>
								<input type="text" name="ip_vote_login" id="ip_vote_login" value="<?php echo get_option('ip_vote_login'); ?>" placeholder="You need to be logged in to like this" class="regular-text">
								<label for="ip_vote_login">"Logged in" notice</label>
								<br>
							</p>

							<p>
								<input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary">
							</p>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if($t == 'email_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_notification_email', $_POST['ip_notification_email']);
				update_option('approvednotification', $_POST['approvednotification']);
				update_option('declinednotification', $_POST['declinednotification']);
				update_option('ip_override_email_notification', $_POST['ip_override_email_notification']);
				update_option('ip_et_login', $_POST['ip_et_login']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Email Settings', 'imagepress'); ?></h3>
					<div class="inside">
						<form method="post" action="">
							<p>
								<input type="text" name="ip_notification_email" id="ip_notification_email" value="<?php echo get_option('ip_notification_email'); ?>" class="regular-text">
								<label for="ip_notification_email">Administrator email (used for new image notification)</label>
								<br><small>The administrator will receive an email notification each time a new image is uploaded</small>
								<br><small>Separate multiple addresses with comma</small>
							</p>
							<p>
								<input type="checkbox" id="approvednotification" name="approvednotification" value="yes" <?php if(get_option('approvednotification') == 'yes') echo 'checked'; ?>> <label for="approvednotification">Notify author when image is approved</label>
								<br>
								<input type="checkbox" id="declinednotification" name="declinednotification" value="yes" <?php if(get_option('declinednotification') == 'yes') echo 'checked'; ?>> <label for="declinednotification">Notify author when image is rejected</label>
							</p>
                            <p>
                                <select name="ip_override_email_notification" id="ip_override_email_notification">
                                    <option value="1"<?php if(get_option('ip_override_email_notification') == 1) echo ' selected'; ?>>Override WordPress email notification</option>
                                    <option value="0"<?php if(get_option('ip_override_email_notification') == 0) echo ' selected'; ?>>Do not override WordPress email notification</option>
                                </select>
                                <br><small>Override the default WordPress email notification. This will hide the default login/registration links and will redirect the user to the correct ImagePress links. Deactivate if using other member plugins or if you notice any conflict.</small>
                            </p>

							<h2>User Email Settings</h2>
							<p>
								<input type="url" name="ip_et_login" id="ip_et_login" value="<?php echo get_option('ip_et_login'); ?>" class="regular-text">
								<label for="ip_et_login">Login URL</label>
								<br><small>Use this option to define a different login URL than <code>wp-login.php</code> (optional)</small>
							</p>

							<p>
								<input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary">
							</p>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if($t == 'users_tab') { ?>
            <?php
            if(isset($_POST['cinnamon_submit'])) {
                update_option('cinnamon_author_slug', $_POST['cinnamon_author_slug']);
                update_option('cinnamon_profile_title', $_POST['cinnamon_profile_title']);
                update_option('cinnamon_label_index', $_POST['cinnamon_label_index']);
                update_option('cinnamon_label_portfolio', $_POST['cinnamon_label_portfolio']);
                update_option('cinnamon_label_about', $_POST['cinnamon_label_about']);
                update_option('cinnamon_label_hub', $_POST['cinnamon_label_hub']);
                update_option('cinnamon_hide', $_POST['cinnamon_hide']);
                update_option('cinnamon_image_size', $_POST['cinnamon_image_size']);
                update_option('ip_cards_per_author', $_POST['ip_cards_per_author']);
                update_option('ip_cards_image_size', $_POST['ip_cards_image_size']);
                update_option('cinnamon_edit_label', $_POST['cinnamon_edit_label']);

                update_option('cinnamon_show_online', $_POST['cinnamon_show_online']);
                update_option('cinnamon_show_uploads', $_POST['cinnamon_show_uploads']);
                update_option('cinnamon_show_awards', $_POST['cinnamon_show_awards']);
                update_option('cinnamon_show_posts', $_POST['cinnamon_show_posts']);
                update_option('cinnamon_show_comments', $_POST['cinnamon_show_comments']);
                update_option('cinnamon_show_map', $_POST['cinnamon_show_map']);
                update_option('cinnamon_show_followers', $_POST['cinnamon_show_followers']);
                update_option('cinnamon_show_following', $_POST['cinnamon_show_following']);

                update_option('cinnamon_hide_admin', $_POST['cinnamon_hide_admin']);

                update_option('cinnamon_account_page', $_POST['cinnamon_account_page']);
                update_option('cinnamon_edit_page', $_POST['cinnamon_edit_page']);

                update_option('cinnamon_mod_hub', $_POST['cinnamon_mod_hub']);

				update_option('cinnamon_pt_account', $_POST['cinnamon_pt_account']);
				update_option('cinnamon_pt_social', $_POST['cinnamon_pt_social']);
				update_option('cinnamon_pt_author', $_POST['cinnamon_pt_author']);
				update_option('cinnamon_pt_profile', $_POST['cinnamon_pt_profile']);
				update_option('cinnamon_pt_portfolio', $_POST['cinnamon_pt_portfolio']);

				update_option('cinnamon_show_likes', $_POST['cinnamon_show_likes']);
                update_option('cinnamon_show_activity', $_POST['cinnamon_show_activity']);

                echo '<div class="updated notice is-dismissible"><p><strong>Settings saved.</strong></p></div>';
            }
            ?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><div class="dashicons dashicons-groups"></div> <?php _e('Users Settings', 'imagepress'); ?></h3>
					<div class="inside">
						<form method="post" action="">
                            <p>
                                <input type="text" name="cinnamon_author_slug" id="cinnamon_author_slug" value="<?php echo get_option('cinnamon_author_slug'); ?>" class="text"> <label for="cinnamon_author_slug">Author profile slug (default is <b>author</b> - use <b>author</b>, <b>profile</b> or <b>hub</b>)</label>
                            </p>
                            <p>
                                <input type="text" name="cinnamon_profile_title" id="cinnamon_profile_title" value="<?php echo get_option('cinnamon_profile_title'); ?>" class="text"> <label for="cinnamon_profile_title">Site name (this will appear in the profile section and in the "portfolio powered by" footer)</label>
                            </p>
                            <p>
                                <input type="number" name="ip_cards_per_author" id="ip_cards_per_author" value="<?php echo get_option('ip_cards_per_author'); ?>" min="0" max="32"> <label for="ip_cards_per_author">Number of images for each author (inside author cards)</label>
                            </p>
                            <p>
                                <select name="ip_cards_image_size" id="ip_cards_image_size">
                                    <optgroup label="WordPress (Default)">
                                        <option value="thumbnail"<?php if(get_option('ip_cards_image_size') == 'thumbnail') echo ' selected'; ?>>Thumbnail</option>
                                    </optgroup>

                                    <optgroup label="Small">
                                        <option value="imagepress_sq_sm"<?php if(get_option('ip_cards_image_size') == 'imagepress_sq_sm') echo ' selected'; ?>>Small (Square) (ImagePress)</option>
                                        <option value="imagepress_pt_sm"<?php if(get_option('ip_cards_image_size') == 'imagepress_pt_sm') echo ' selected'; ?>>Small (Portrait) (ImagePress)</option>
                                        <option value="imagepress_ls_sm"<?php if(get_option('ip_cards_image_size') == 'imagepress_ls_sm') echo ' selected'; ?>>Small (Landscape) (ImagePress)</option>
                                    </optgroup>

                                    <optgroup label="Standard">
                                        <option value="imagepress_sq_std"<?php if(get_option('ip_cards_image_size') == 'imagepress_sq_std') echo ' selected'; ?>>Standard (Square) (ImagePress)</option>
                                        <option value="imagepress_pt_std"<?php if(get_option('ip_cards_image_size') == 'imagepress_pt_std') echo ' selected'; ?>>Standard (Portrait) (ImagePress)</option>
                                        <option value="imagepress_ls_std"<?php if(get_option('ip_cards_image_size') == 'imagepress_ls_std') echo ' selected'; ?>>Standard (Landscape) (ImagePress)</option>
                                    </optgroup>

                                    <optgroup label="ImagePress (Custom)">
                                        <option value="imagepress_thumbnail_wide"<?php if(get_option('ip_cards_image_size') == 'imagepress_thumbnail_wide') echo ' selected'; ?>>Wide thumbnail - 300x150 (ImagePress)</option>
                                    </optgroup>
                                </select> <label for="ip_cards_image_size">Size of images fir each author (inside author cards)</label>
                                <br><small>Use <b>thumbnail</b>, adjust the column size to match your thumbnail size and hide the description in order to have uniform sizes</small>
                            </p>
                            <p>
                                <input type="text" name="cinnamon_edit_label" id="cinnamon_edit_label" value="<?php echo get_option('cinnamon_edit_label'); ?>" class="text"> <label for="cinnamon_edit_label">Author profile edit label (try <b>Edit profile</b>)</label>
                            </p>
                            <p>
                                <input type="url" name="cinnamon_account_page" id="cinnamon_account_page" value="<?php echo get_option('cinnamon_account_page'); ?>" class="regular-text" placeholder="http://"> <label for="cinnamon_account_page">Author account login page</label>
                            </p>
                            <p>
                                <input type="url" name="cinnamon_edit_page" id="cinnamon_edit_page" value="<?php echo get_option('cinnamon_edit_page'); ?>" class="regular-text" placeholder="http://"> <label for="cinnamon_edit_page">Author profile edit page URL</label>
                                <br><small>Create a new page and add the <code>[cinnamon-profile-edit]</code> shortcode. This shortcode will display all user fields.</small>
                            </p>
							<p>
								<!-- pt = profile tab -->
								<input type="text" name="cinnamon_pt_account" value="<?php echo get_option('cinnamon_pt_account'); ?>" size="16" placeholder="Account details"> 
								<input type="text" name="cinnamon_pt_social" value="<?php echo get_option('cinnamon_pt_social'); ?>" size="16" placeholder="Social details"> 
								<input type="text" name="cinnamon_pt_author" value="<?php echo get_option('cinnamon_pt_author'); ?>" size="16" placeholder="Author details"> 
								<input type="text" name="cinnamon_pt_profile" value="<?php echo get_option('cinnamon_pt_profile'); ?>" size="16" placeholder="Profile details"> 
								<input type="text" name="cinnamon_pt_portfolio" value="<?php echo get_option('cinnamon_pt_portfolio'); ?>" size="16" placeholder="Portfolio editor"> 
								<label>Profile edit tab labels</label>
							</p>
                            <p>
                                <div class="dashicons dashicons-awards"></div> <a href="<?php echo admin_url('edit-tags.php?taxonomy=award'); ?>" class="button button-secondary">Add/Edit Awards</a>
                                <br><small>Create a new page and add the <code>[cinnamon-awards]</code> shortcode. This shortcode will list all available awards and their description.</small>
                            </p>

                            <hr>
                            <h2>User Modules</h2>
                            <p>
                                <select name="cinnamon_mod_hub" id="cinnamon_mod_hub">
                                    <option value="1"<?php if(get_option('cinnamon_mod_hub') == 1) echo ' selected'; ?>>Enable hub</option>
                                    <option value="0"<?php if(get_option('cinnamon_mod_hub') == 0) echo ' selected'; ?>>Disable hub</option>
                                </select> <label for="cinnamon_mod_hub">Enable a subdomain address for users (e.g. jack.yourdomain.com)</label>
                                <br><small>Use with caution. See below.</small>
                            </p>

                            <?php if(get_option('cinnamon_mod_hub') == 0) echo '<div style="opacity: 0.5;">'; ?>
                            <hr>
                            <h2>Hub Options <sup>(experimental, developers only)</sup></h2>
                            <p>
                                <input type="text" name="cinnamon_label_index" id="cinnamon_label_index" value="<?php echo get_option('cinnamon_label_index'); ?>" class="text"> <label for="cinnamon_label_index">Hub index icon label (try <b>View all</b> or <b>Back to index view</b>)</label>
                            </p>
                            <p>
                                <input type="text" name="cinnamon_label_hub" id="cinnamon_label_hub" value="<?php echo get_option('cinnamon_label_hub'); ?>" class="text"> <label for="cinnamon_label_hub">Hub view button label (try <b>View Portfolio</b>)</label>
                            </p>
                            <p>
                                <input type="number" min="90" max="320" name="cinnamon_image_size" id="cinnamon_image_size" value="<?php echo get_option('cinnamon_image_size'); ?>"> <label for="cinnamon_image_size">Profile image size</label>
                                <br><small>Default is <b>150</b>px. Leave blank for default WordPress size.</small>
                            </p>
                            <p>
                                <label>Hub tabs</label><br>
                                <input type="text" name="cinnamon_label_portfolio" id="cinnamon_label_portfolio" value="<?php echo get_option('cinnamon_label_portfolio'); ?>" class="text" placeholder="My Portfolio (tab title)"> <small>Try <b>My Portfolio</b> or <b>My Images</b></small><br>
                                <input type="text" name="cinnamon_label_about" id="cinnamon_label_about" value="<?php echo get_option('cinnamon_label_about'); ?>" class="text" placeholder="About (tab title)"> <small>Try <b>About</b></small>
                            </p>
                            <p>
                                <input type="text" name="cinnamon_hide" id="cinnamon_hide" value="<?php echo get_option('cinnamon_hide'); ?>" class="regular-text"> <label for="cinnamon_hide">CSS selectors to hide when viewing the hub</label>
                                <br><small>Try <b>header, nav, footer</b> or <b>.sidebar</b> or <b>#main-menu</b>.</small>
                                <br><small>If your hub page flashes for a brief moment, consider moving the selectors in your <code>style.css</code> file (e.g. <code>header, nav, footer, .sidebar, #main-menu { display: none; }</code>.</small>
                            </p>
                            <?php if(get_option('cinnamon_mod_hub') == 0) echo '</div>'; ?>

                            <hr>
                            <p>
                                <select name="cinnamon_show_uploads" id="cinnamon_show_uploads">
                                    <option value="1"<?php if(get_option('cinnamon_show_uploads') == 1) echo ' selected'; ?>>Show latest ImagePress uploads</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_uploads') == 0) echo ' selected'; ?>>Hide latest ImagePress uploads</option>
                                </select>
                            </p>
                            <p>
                                <select name="cinnamon_show_online" id="cinnamon_show_online">
                                    <option value="1"<?php if(get_option('cinnamon_show_online') == 1) echo ' selected'; ?>>Show online and join details</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_online') == 0) echo ' selected'; ?>>Hide online and join details</option>
                                </select> 
                                <select name="cinnamon_show_posts" id="cinnamon_show_posts">
                                    <option value="1"<?php if(get_option('cinnamon_show_posts') == 1) echo ' selected'; ?>>Show latest posts</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_posts') == 0) echo ' selected'; ?>>Hide latest posts</option>
                                </select> 
                                <select name="cinnamon_show_awards" id="cinnamon_show_awards">
                                    <option value="1"<?php if(get_option('cinnamon_show_awards') == 1) echo ' selected'; ?>>Show awards</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_awards') == 0) echo ' selected'; ?>>Hide awards</option>
                                </select>
                            </p>
                            <p>
                                <select name="cinnamon_show_comments" id="cinnamon_show_comments">
                                    <option value="1"<?php if(get_option('cinnamon_show_comments') == 1) echo ' selected'; ?>>Show latest comments and replies</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_comments') == 0) echo ' selected'; ?>>Hide latest comments and replies</option>
                                </select> 
                                <select name="cinnamon_show_map" id="cinnamon_show_map">
                                    <option value="1"<?php if(get_option('cinnamon_show_map') == 1) echo ' selected'; ?>>Show map</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_map') == 0) echo ' selected'; ?>>Hide map</option>
                                </select>
                            </p>
                            <p>
                                <select name="cinnamon_hide_admin" id="cinnamon_hide_admin">
                                    <option value="1"<?php if(get_option('cinnamon_hide_admin') == 1) echo ' selected'; ?>>Hide admin bar for non-admin users</option>
                                    <option value="0"<?php if(get_option('cinnamon_hide_admin') == 0) echo ' selected'; ?>>Show admin bar for non-admin users</option>
                                </select>
                            </p>
                            <hr>
                            <p>
                                <select name="cinnamon_show_followers" id="cinnamon_show_followers">
                                    <option value="1"<?php if(get_option('cinnamon_show_followers') == 1) echo ' selected'; ?>>Show followers</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_followers') == 0) echo ' selected'; ?>>Hide followers</option>
                                </select> 
                                <select name="cinnamon_show_following" id="cinnamon_show_following">
                                    <option value="1"<?php if(get_option('cinnamon_show_following') == 1) echo ' selected'; ?>>Show following</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_following') == 0) echo ' selected'; ?>>Hide following</option>
                                </select> <label>Followers behaviour</label>
                            </p>
                            <hr>
                            <p>
                                <select name="cinnamon_show_likes" id="cinnamon_show_likes">
                                    <option value="1"<?php if(get_option('cinnamon_show_likes') == 1) echo ' selected'; ?>>Show likes</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_likes') == 0) echo ' selected'; ?>>Hide likes</option>
                                </select> 
                                <select name="cinnamon_show_activity" id="cinnamon_show_activity">
                                    <option value="1"<?php if(get_option('cinnamon_show_activity') == 1) echo ' selected'; ?>>Show activity tab</option>
                                    <option value="0"<?php if(get_option('cinnamon_show_activity') == 0) echo ' selected'; ?>>Hide activity tab</option>
                                </select> 
                            </p>

                            <p>
                                <input name="cinnamon_submit" type="submit" class="button-primary" value="Save Changes">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
		<?php } ?>
		<?php if($t == 'label_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_caption_label', $_POST['ip_caption_label']);
				update_option('ip_category_label', $_POST['ip_category_label']);
				update_option('ip_tag_label', $_POST['ip_tag_label']);
				update_option('ip_description_label', $_POST['ip_description_label']);
				update_option('ip_upload_label', $_POST['ip_upload_label']);
				update_option('ip_keywords_label', $_POST['ip_keywords_label']);
				update_option('ip_image_label', $_POST['ip_image_label']);
				update_option('ip_behance_label', $_POST['ip_behance_label']);
				update_option('ip_video_label', $_POST['ip_video_label']);
				update_option('ip_purchase_label', $_POST['ip_purchase_label']);
				update_option('ip_sticky_label', $_POST['ip_sticky_label']);
				update_option('ip_print_label', $_POST['ip_print_label']);

				update_option('ip_author_find_title', $_POST['ip_author_find_title']);
				update_option('ip_author_find_placeholder', $_POST['ip_author_find_placeholder']);
				update_option('ip_image_find_title', $_POST['ip_image_find_title']);
				update_option('ip_image_find_placeholder', $_POST['ip_image_find_placeholder']);

				update_option('ip_notifications_mark', $_POST['ip_notifications_mark']);
				update_option('ip_notifications_all', $_POST['ip_notifications_all']);

                update_option('cms_title', $_POST['cms_title']);
                update_option('cms_featured_tooltip', $_POST['cms_featured_tooltip']);
                update_option('cms_verified_profile', $_POST['cms_verified_profile']);
                update_option('cms_available_for_print', $_POST['cms_available_for_print']);

				update_option('ip_upload_success_title', $_POST['ip_upload_success_title']);
				update_option('ip_upload_success', $_POST['ip_upload_success']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Label Settings', 'imagepress'); ?></h3>
					<div class="inside">
						<form method="post" action="">
							<p>
								<input type="text" name="ip_caption_label" id="ip_caption_label" value="<?php echo get_option('ip_caption_label'); ?>" class="regular-text">
								<label for="ip_caption_label">Image caption label</label>
							</p>
							<p>
								<input type="text" name="ip_category_label" id="ip_category_label" value="<?php echo get_option('ip_category_label'); ?>" class="regular-text">
								<label for="ip_category_label">Image category label (dropdown)</label>
							</p>
							<p>
								<input type="text" name="ip_tag_label" id="ip_tag_label" value="<?php echo get_option('ip_tag_label'); ?>" class="regular-text">
								<label for="ip_tag_label">Image tag label (dropdown)</label>
							</p>
							<p>
								<input type="text" name="ip_description_label" id="ip_description_label" value="<?php echo get_option('ip_description_label'); ?>" class="regular-text">
								<label for="ip_description_label">Image description label (textarea)</label>
								<br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_keywords_label" id="ip_keywords_label" value="<?php echo get_option('ip_keywords_label'); ?>" class="regular-text">
								<label for="ip_keywords_label">Image keywords label</label>
                                <br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_upload_label" id="ip_upload_label" value="<?php echo get_option('ip_upload_label'); ?>" class="regular-text">
								<label for="ip_upload_label">Image upload button label (button)</label>
							</p>
							<p>
								<input type="text" name="ip_image_label" id="ip_image_label" value="<?php echo get_option('ip_image_label'); ?>" class="regular-text">
								<label for="ip_image_label">Image upload selection label (link)</label>
							</p>
							<p>
								<input type="text" name="ip_behance_label" id="ip_behance_label" value="<?php echo get_option('ip_behance_label'); ?>" class="regular-text">
								<label for="ip_behance_label">Image Behance link label (button)</label>
                                <br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_video_label" id="ip_video_label" value="<?php echo get_option('ip_video_label'); ?>" class="regular-text">
								<label for="ip_video_label">Image video link (Youtube/Vimeo)</label>
                                <br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_purchase_label" id="ip_purchase_label" value="<?php echo get_option('ip_purchase_label'); ?>" class="regular-text">
								<label for="ip_purchase_label">Image purchase link label (button)</label>
                                <br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_sticky_label" id="ip_sticky_label" value="<?php echo get_option('ip_sticky_label'); ?>" class="regular-text">
								<label for="ip_sticky_label">Sticky image label (checkbox)</label>
                                <br><small>Leave blank to disable</small>
							</p>
							<p>
								<input type="text" name="ip_print_label" id="ip_print_label" value="<?php echo get_option('ip_print_label'); ?>" class="regular-text">
								<label for="ip_print_label">"Available for print" label (checkbox)</label>
                                <br><small>Leave blank to disable</small>
							</p>

                            <hr>
                            <h2>Author/Image Cards</h2>
							<p>
								<input type="text" name="ip_author_find_title" id="ip_author_find_title" value="<?php echo get_option('ip_author_find_title'); ?>" class="regular-text">
								<label for="ip_author_find_title">Author name/location sorter title</label>
								<br>
								<input type="text" name="ip_author_find_placeholder" id="ip_author_find_placeholder" value="<?php echo get_option('ip_author_find_placeholder'); ?>" class="regular-text">
								<label for="ip_author_find_placeholder">Author name/location sorter placeholder</label>
							</p>
							<p>
								<input type="text" name="ip_image_find_title" id="ip_image_find_title" value="<?php echo get_option('ip_image_find_title'); ?>" class="regular-text">
								<label for="ip_image_find_title">Image author/title/category sorter title</label>
								<br>
								<input type="text" name="ip_image_find_placeholder" id="ip_image_find_placeholder" value="<?php echo get_option('ip_image_find_placeholder'); ?>" class="regular-text">
								<label for="ip_image_find_placeholder">Image author/title/category sorter placeholder</label>
							</p>

                            <hr>
                            <h2>Notifications</h2>
							<p>
								<input type="text" name="ip_notifications_mark" id="ip_notifications_mark" value="<?php echo get_option('ip_notifications_mark'); ?>" class="regular-text">
								<label for="ip_notifications_mark">"Mark all as read" label</label>
								<br>
								<input type="text" name="ip_notifications_all" id="ip_notifications_all" value="<?php echo get_option('ip_notifications_all'); ?>" class="regular-text">
								<label for="ip_notifications_all">"View all notifications" label</label>
							</p>

                            <hr>
                            <h2>Tooltips</h2>
                            <p>
                                <input type="text" name="cms_title" id="cms_title" value="<?php echo get_option('cms_title'); ?>" class="regular-text"> <label for="cms_title">Site Title</label>
                                <br><small>This text will appear in various places all over the site.</small>
                            </p>
                            <p>
                                <input type="text" name="cms_featured_tooltip" id="cms_featured_tooltip" value="<?php echo get_option('cms_featured_tooltip'); ?>" class="regular-text"> <label for="cms_featured_tooltip">Featured item tooltip</label>
                                <br><small>This text will appear when the "featured" icon is hovered.</small>
                            </p>
                            <p>
                                <input type="text" name="cms_verified_profile" id="cms_verified_profile" value="<?php echo get_option('cms_verified_profile'); ?>" class="regular-text"> <label for="cms_verified_profile">Verified profile tooltip</label>
                                <br><small>This text will appear when the "verified" icon is hovered.</small>
                            </p>
                            <p>
                                <input type="text" name="cms_available_for_print" id="cms_available_for_print" value="<?php echo get_option('cms_available_for_print'); ?>" class="regular-text"> <label for="cms_available_for_print">Print availability tooltip</label>
                                <br><small>This text will appear when the "print" icon is hovered.</small>
                            </p>

                            <hr>
                            <h2>Image Upload</h2>
                            <p>
                                <input type="text" name="ip_upload_success_title" id="ip_upload_success_title" value="<?php echo get_option('ip_upload_success_title'); ?>" class="regular-text"> <label for="ip_upload_success_title">Upload success title</label>
                                <br><small>This text will appear when the image upload is successful.</small>
                                <br><small>Leave blank to disable</small>
                            </p>
                            <p>
                                <input type="text" name="ip_upload_success" id="ip_upload_success" value="<?php echo get_option('ip_upload_success'); ?>" class="regular-text"> <label for="ip_upload_success">Upload success</label>
                                <br><small>This text will appear when the image upload is successful.</small>
                                <br><small>Leave blank to disable</small>
                            </p>

							<p>
								<input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary">
							</p>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if($t == 'upload_tab') { ?>
			<?php
			if(isset($_POST['isGSSubmit'])) {
				update_option('ip_upload_secondary', $_POST['ip_upload_secondary']);
				update_option('ip_allow_tags', $_POST['ip_allow_tags']);
				update_option('ip_require_description', $_POST['ip_require_description']);

				update_option('ip_resize', $_POST['ip_resize']);
				update_option('ip_max_width', $_POST['ip_max_width']);
				update_option('ip_max_quality', $_POST['ip_max_quality']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			?>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Upload Settings', 'imagepress'); ?></h3>
					<div class="inside">
						<form method="post" action="">
							<p>
                                <select name="ip_resize" id="ip_resize">
                                    <option value="1"<?php if(get_option('ip_resize') == 1) echo ' selected'; ?>>Enable image resizing</option>
                                    <option value="0"<?php if(get_option('ip_resize') == 0) echo ' selected'; ?>>Disable image resizing</option>
                                </select>
                                <br><small>Enable or disable image resizing.</small>
                            </p>
                            <p>
                                <input name="ip_max_width" id="ip_max_width" type="number" value="<?php echo get_option('ip_max_width')?>" min="1">
								<label for="ip_max_width">Maximum image width</label>
                                <br><small>Set maximum image width (will be resized if larger).</small>
                            </p>
                            <p>
                                <input name="ip_max_quality" id="ip_max_quality" type="number" value="<?php echo get_option('ip_max_quality')?>" min="0" max="100">
								<label for="ip_max_quality">Resized image quality</label>
                                <br><small>Set image quality when resizing image.</small>
                            </p>

							<hr>

							<p>
                                <select name="ip_upload_secondary" id="ip_upload_secondary">
                                    <option value="1"<?php if(get_option('ip_upload_secondary') == 1) echo ' selected'; ?>>Enable secondary upload button</option>
                                    <option value="0"<?php if(get_option('ip_upload_secondary') == 0) echo ' selected'; ?>>Disable secondary upload button</option>
                                </select>
                                <br><small>Enable or disable additional images (variants, progress shots, making of, etc.) upload.</small>
                            </p>
                            <p>
                                <select name="ip_allow_tags" id="ip_allow_tags">
                                    <option value="1"<?php if(get_option('ip_allow_tags') == 1) echo ' selected'; ?>>Enable tags</option>
                                    <option value="0"<?php if(get_option('ip_allow_tags') == 0) echo ' selected'; ?>>Disable tags</option>
                                </select>
                                <br><small>Enable or disable image tags dropdown.</small>
                            </p>
                            <p>
                                <select name="ip_require_description" id="ip_require_description">
                                    <option value="1"<?php if(get_option('ip_require_description') == 1) echo ' selected'; ?>>Require description</option>
                                    <option value="0"<?php if(get_option('ip_require_description') == 0) echo ' selected'; ?>>Do not require description</option>
                                </select>
                                <br><small>Enable or disable image tags dropdown.</small>
                            </p>

							<p>
								<input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary">
							</p>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if($t == 'notifications_tab') { ?>
			<?php
			if(isset($_POST['notification_update'])) {
				update_option('notification_limit', $_POST['notification_limit']);
				update_option('notification_thumbnail_custom', $_POST['notification_thumbnail_custom']);

				echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
			}
			if(isset($_POST['notification_add'])) {
				global $wpdb;

				$notification_type_custom = $_POST['notification_type_custom'];
				$notification_icon_custom = $_POST['notification_icon_custom'];
				$notification_link_custom = $_POST['notification_link_custom'];
				$notification_user = $_POST['notification_user'];
				$when = date('Y-m-d H:i:s');

				if(!empty($notification_link_custom))
					$notification_type = '<a href="' . $notification_link_custom . '">' . $notification_type_custom . '</a>';
				else
					$notification_type = '' . $notification_type_custom . '';

				$sql = "INSERT INTO " . $wpdb->prefix . "notifications (`userID`, `postID`, `actionType`, `actionIcon`, `actionTime`) VALUES (0, '$notification_user', '$notification_type', '$notification_icon_custom', '$when')";
				$wpdb->query($sql);

				echo '<div class="updated notice is-dismissible"><p>Notification added successfully!</p></div>';
			}
			?>
            <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
			<script>
			jQuery(document).ready(function($) {
				$('.ajax_trash').click(function(e){
					var data = {
						action: 'ajax_trash_action',
						odvm_post: $(this).attr('data-post'),
					};

					$.post(ajaxurl, data, function(response) {
						alert('' + response);
					});
					fade_vote = $(this).attr('data-post');
					$('#notification-' + fade_vote).fadeOut('slow', function(){});
					e.preventDefault();
				});
			});
			</script>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Notifications Settings', 'imagepress'); ?></h3>
					<div class="inside">
						<form method="post">
							<div class="error"><p>The notifications module is in beta mode. Some features may not work as expected.</p></div>
							<h2><i class="fa fa-fw fa-wrench"></i> Notification options</h2>
							<p>
								<input type="number" name="notification_limit" id="notification_limit" min="0" max="65536" value="<?php echo get_option('notification_limit'); ?>"> 
								<label for="notification_limit">Notification limit</label>
								<br><small>How many notifications to show when using the [notifications] shortcode.</small>
							</p>
							<p>
								<input type="number" name="notification_thumbnail_custom" id="notification_thumbnail_custom" min="0" value="<?php echo get_option('notification_thumbnail_custom'); ?>"> 
								<label for="notification_thumbnail_custom">Custom notification thumbnail ID (scaled to 48x48px)</label>
								<br><small>Use this attachment ID for custom notifications (usually your logo or a custom square image).</small>
								<br><small>Check your media library for the correct ID.</small>
							</p>
							<p>
								<input type="submit" name="notification_update" value="Save Changes" class="button button-primary">
							</p>
						</form>

						<hr>

						<form method="post">
							<h2><i class="fa fa-fw fa-bell"></i> Add new notification</h2  >
							<p>
								<input type="text" name="notification_icon_custom" id="notification_icon_custom" class="regular-text" placeholder="fa-bicycle"> 
								<label for="notification_icon_custom">Notification icon (FontAwesome)</label>
								<br>
								<input type="text" name="notification_type_custom" id="notification_type_custom" class="regular-text"> 
								<label for="notification_type_custom">Notification type (custom)</label>
								<br><small>This is the notification body text (e.g. <em>Check out this great new feature!</em> or <em>You have been verified!</em>).</small>
							</p>
							<p>
								<input type="url" name="notification_link_custom" id="notification_link_custom" class="regular-text" placeholder="http://"> 
								<label for="notification_link_custom">Notification link (custom)</label>
								<br><small>This is the URL link the text above will point to.</small>
							</p>
							<p>
								<?php
								$args = array(
									'name' => 'notification_user',
									'show_option_none' => 'Show to this user only (optional, leave blank to show to all users)...'
								);
								wp_dropdown_users($args); ?>
							</p>
							<p>
								<input type="submit" name="notification_add" value="Add custom notification" class="button button-secondary">
							</p>
						</form>

						<hr>
						<h2>All notifications</h2>
						<?php
						global $wpdb;

						$limit = get_option('notification_limit');

						$sql = "SELECT * FROM " . $wpdb->prefix . "notifications ORDER BY ID DESC LIMIT " . $limit . "";
						$results = $wpdb->get_results($sql);
						foreach($results as $result) {
							?>
							<div id="notification-<?php echo $result->ID; ?>">
								<a href="#" class="ajax_trash" data-post="<?php echo $result->ID; ?>"><i class="fa fa-fw fa-trash"></i></a>&nbsp;
								<?php
								$display = '';
								$id = $result->ID;
								$action = $result->actionType;
								$nickname = get_the_author_meta('nickname', $result->userID);
								$time = human_time_diff(strtotime($result->actionTime), current_time('timestamp')) . ' ago';

								if($result->status == 0)
									$status = '<i class="fa fa-fw fa-circle"></i>&nbsp;&nbsp;&nbsp;&nbsp;';
								if($result->status == 1)
									$status = '<i class="fa fa-fw fa-check-circle"></i>&nbsp;&nbsp;&nbsp;&nbsp;';

								$display .= $status;

								$display .= ' [' . $result->ID . '] ';

								if($action == 'loved')
									$display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-heart"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $action . ' a poster <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';

								else if($action == 'added')
									$display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-arrow-circle-up"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $action . ' <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';

								else if($action == 'followed')
									$display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-plus-circle"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $result->actionType . ' you <time>' . $time . '</time>';

								else if($action == 'commented on')
									$display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-comment"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> ' . $action . ' a poster <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';

								else if($action == 'replied to a comment on') {
									$comment_id = get_comment($result->postID);
									$comment_post_ID = $comment_id->comment_post_ID;
									$b = $comment_id->user_id;

									$display .= '' . get_avatar($result->userID, 16) . ' <i class="fa fa-fw fa-comment"></i> <a href="' . get_author_posts_url($result->userID) . '">' . $nickname . '</a> replied to a comment on <a href="' . get_permalink($comment_post_ID) . '">' . get_the_title($comment_post_ID) . '</a> <time>' . $time . '</time>';
								}

								else if($action == 'featured')
									$display .= '' . get_the_post_thumbnail($result->postID, array(16,16)) . ' <i class="fa fa-fw fa-star"></i> <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> poster was ' . $action . ' <time>' . $time . '</time>';

								// custom
								else if(0 == $result->postID || '-1' == $result->postID) {
									$attachment_id = get_option('notification_thumbnail_custom');
									$image_attributes = wp_get_attachment_image_src($attachment_id, array(16,16));

									$display .= '<img src="' .  $image_attributes[0] . '" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '"> <i class="fa fa-fw ' . $result->actionIcon . '"></i> ' . $result->actionType . ' <time>' . $time . '</time>';
								}
								else {}

								echo $display;
								?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
    </div>	
	<?php
}
?>
