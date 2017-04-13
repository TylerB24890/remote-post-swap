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
* @package            rps
*
* @wordpress-plugin
* Plugin Name:        Remote Post Swap
* Plugin URI:        	http://tylerb.me/plugins/remote-post-swap.zip
* Description:       	Provides an easy way to switch between a remote database and a local database for development purposes
* Version:           	 1.0.0
* Author:            	 Tyler Bailey
* Author URI:          http://tylerb.me
* License:           	 GPL-2.0+
* License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       rps
*/



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die("Sneaky sneaky...");
}

// Define constants
define('RPS_VERSION', '1.0.0');
define('RPS_SLUG', 'rps');
define('RPS_GLOBAL_DIR', plugin_dir_path( __FILE__ ));
define('RPS_GLOBAL_URL', plugin_dir_url( __FILE__ ));
define('RPS_REQUIRED_PHP_VERSION', '5.3');
define('RPS_REQUIRED_WP_VERSION',  '4.7');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rps-activator.php
 */
function activate_rps() {
	require_once RPS_GLOBAL_DIR . 'inc/class-rps-activator.php';
	RPS_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rps-deactivator.php
 */
function deactivate_rps() {
	require_once RPS_GLOBAL_DIR . 'inc/class-rps-deactivator.php';
	RPS_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_rps' );
register_deactivation_hook( __FILE__, 'deactivate_rps' );


/**
 * The core plugin classes that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require RPS_GLOBAL_DIR .  'inc/class-rps.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
if(!function_exists('rps_init')) {
	function rps_init() {
		new RPS();
	}
}
add_action('init', 'rps_init');
