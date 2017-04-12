<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              	  http://tylerb.me
* @since             	 1.0.0
* @package            rdd
*
* @wordpress-plugin
* Plugin Name:        Remote Dev Database
* Plugin URI:        	http://tylerb.me/plugins/remote-dev-database.zip
* Description:       	Provides an easy way to switch between a remote database and a local database for development purposes
* Version:           	 1.0.0
* Author:            	 Tyler Bailey
* Author URI:          http://tylerb.me
* License:           	 GPL-2.0+
* License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       rdd
*/



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die("Sneaky sneaky...");
}

// Define constants
define('RDD_VERSION', '1.0.0');
define('RDD_SLUG', 'rdd');
define('RDD_GLOBAL_DIR', plugin_dir_path( __FILE__ ));
define('RDD_GLOBAL_URL', plugin_dir_url( __FILE__ ));
define('RDD_REQUIRED_PHP_VERSION', '5.3');
define('RDD_REQUIRED_WP_VERSION',  '3.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rdd-activator.php
 */
function activate_rdd() {
	require_once RDD_GLOBAL_DIR . 'inc/class-rdd-activator.php';
	RDD_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rdd-deactivator.php
 */
function deactivate_rdd() {
	require_once RDD_GLOBAL_DIR . 'inc/class-rdd-deactivator.php';
	RDD_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_rdd' );
register_deactivation_hook( __FILE__, 'deactivate_rdd' );


/**
 * The core plugin classes that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require RDD_GLOBAL_DIR .  'inc/class-rdd.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
if(!function_exists('rdd_init')) {
	function rdd_init() {
		new RDD();
	}
}
add_action('init', 'rdd_init');
