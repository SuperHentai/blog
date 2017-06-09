<?php
/**
 * Plugin Name: Social Login for Wordpress
 * Plugin URI: http://www.loginradius.com
 * Description: Let your users log in, comment and share via their social accounts with Facebook, Google, Amazon, Twitter, LinkedIn, Vkontakte, QQ and over 25 more!
 * Version: 7.2
 * Author: LoginRadius Team
 * Author URI: http://www.loginradius.com
 * Text Domain: loginradius-for-wordpress
 * License: GPL2+
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

define( 'LR_PLUGIN_VERSION', '7.2' );
define( 'LR_MIN_WP_VERSION', '3.5' );

// Type of Plugin ADV, SL, SS
define( 'LR_PLUGIN_PKG', 'SL' );

define( 'LR_ROOT_DIR', plugin_dir_path(__FILE__) );
define( 'LR_ROOT_URL', plugin_dir_url(__FILE__) );
define( 'LR_ROOT_SETTING_LINK', plugin_basename(__FILE__) );

// Initialize Modules in specific order
include_once LR_ROOT_DIR . 'module-loader.php';
new LR_Modules_Loader();
