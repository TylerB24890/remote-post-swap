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
* @since             	 0.5.0
* @package            rps
*
* @wordpress-plugin
* Plugin Name:        Remote Post Swap
* Plugin URI:        	http://tylerb.me/plugins/remote-post-swap.zip
* Description:       	Swap local development post data out with live/remote post data on the fly
* Version:           	 0.7.0
* Author:            	 Tyler Bailey
* Author URI:          http://tylerb.me
* License:           	 GPL-2.0+
* License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       rps
*/

namespace RPS;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die("Sneaky sneaky...");
}

// Define constants
define('RPS_VERSION', '0.5.0');
define('RPS_SLUG', 'rps');
define('RPS_GLOBAL_DIR', plugin_dir_path( __FILE__ ));
define('RPS_GLOBAL_URL', plugin_dir_url( __FILE__ ));
define('RPS_REQUIRED_PHP_VERSION', '5.3');
define('RPS_REQUIRED_WP_VERSION',  '4.7');

/**
* The autoloader class to autoload PHP classes using Namespaces.
*/
require_once RPS_GLOBAL_DIR .  'autoload.php';

/**
* The code that runs during plugin activation.
* This action is documented in inc/class-rps-activator.php
*/
function activate_rps() {
	require_once(RPS_GLOBAL_DIR . 'inc/class-rps-activator.php');
	RPS_Activator::rps_activate();
}
/**
* The code that runs during plugin deactivation.
* This action is documented in inc/class-rps-deactivator.php
*/
function deactivate_rps() {
	require_once(RPS_GLOBAL_DIR . 'inc/class-rps-deactivator.php');
	RPS_Deactivator::rps_deactivate();
}
register_activation_hook( __FILE__, 'RPS\activate_rps' );
register_deactivation_hook( __FILE__, 'RPS\deactivate_rps' );

/**
* Begins execution of the plugin.
*
* @since    0.5.0
*/
if( ! function_exists('RPS\rps_init') ) {
	function rps_init() {
			// Load the autoloader class
			$loader = new RPS_Psr4AutoloaderClass();

			// Register the autoloader
			$loader->rps_register();

			// Add the plugin namespaces
			$loader->rps_add_namespace('RPS', RPS_GLOBAL_DIR . 'inc');
			$loader->rps_add_namespace('RPS\Admin', RPS_GLOBAL_DIR . 'inc/admin');

			// Istantiate the plugin
			new RPS;
	}

	add_action('init', 'RPS\rps_init');
}
