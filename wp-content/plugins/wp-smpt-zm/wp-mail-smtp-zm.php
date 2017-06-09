<?php

/**
 * Plugin Name: WP Mail SMPT ZM
 * Plugin URI: http://zanematthew.com/products/wp-mail-smtp-zm-gallery
 * Description: This plugin does the same thing as the popular WP Mail SMPT, but respects your Gmail passowrd!
 * Version: 1.0.0
 * Author: Zane Matthew Kolnik
 * Author URI: http://zanematthew.com
 * Author Email: support@zanematthew.com
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

define( 'WP_MAIL_SMTP_ZM_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_MAIL_SMTP_ZM_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_MAIL_SMTP_ZM_NAMESPACE', 'wp_mail_smtp_zm' );
define( 'WP_MAIL_SMTP_ZM_VERSION', '1.0.0' );
define( 'WP_MAIL_SMTP_ZM_PLUGIN_FILE', __FILE__ );

require WP_MAIL_SMTP_ZM_PATH . '/lib/lumber/lumber.php';
require WP_MAIL_SMTP_ZM_PATH . '/lib/quilt/quilt.php';
require WP_MAIL_SMTP_ZM_PATH . '/inc/settings.php';
require WP_MAIL_SMTP_ZM_PATH . '/inc/mail.php';


/**
 * Load the text domain and various items once plugins are loaded
 *
 * @since 1.0.0
 */
function wp_mail_smtp_zm_plugins_loaded(){

    load_plugin_textdomain( WP_MAIL_SMTP_ZM_NAMESPACE, false, plugin_basename(dirname(__FILE__)) . '/languages' );

}
add_action( 'plugins_loaded', 'wp_mail_smtp_zm_plugins_loaded' );


/**
 * Manging of version numbers when plugin is activated
 *
 * @since 1.0.0
 */
function wp_mail_smtp_zm_activation() {

    // Add Upgraded From Option
    $current_version = get_option( WP_MAIL_SMTP_ZM_NAMESPACE . '_version' );
    if ( $current_version ) {
        update_option( WP_MAIL_SMTP_ZM_NAMESPACE . '_version_upgraded_from', $current_version );
    }

    update_option( WP_MAIL_SMTP_ZM_NAMESPACE . '_version', WP_MAIL_SMTP_ZM_VERSION );

    // Bail if activating from network, or bulk
    if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
        return;
    }

    // Add the transient to redirect
    set_transient( '_' . WP_MAIL_SMTP_ZM_NAMESPACE . '_activation_redirect', true, 30 );
}
register_activation_hook( WP_MAIL_SMTP_ZM_PLUGIN_FILE, 'wp_mail_smtp_zm_activation' );


/**
 * Manging of version numbers when plugin is activated
 *
 * @since 1.0.0
 */
function wp_mail_smtp_zm_deactivate() {

    delete_option( WP_MAIL_SMTP_ZM_NAMESPACE . '_version', WP_MAIL_SMTP_ZM_VERSION );

}
register_deactivation_hook( WP_MAIL_SMTP_ZM_PLUGIN_FILE, 'wp_mail_smtp_zm_deactivate' );


/**
 * Init
 *
 * @since 1.0.0
 */
function wp_mail_smtp_zm_init(){

    global $wp_mail_smtp_settings_obj;
    $wp_mail_smtp_settings_obj = new Quilt(
        WP_MAIL_SMTP_ZM_NAMESPACE,
        wp_mail_smtp_zm_base_settings(),
        'plugin'
    );

    global $wp_mail_smtp_settings;
    $wp_mail_smtp_settings = $wp_mail_smtp_settings_obj->getSaneOptions();

}
add_action( 'init', 'wp_mail_smtp_zm_init' );