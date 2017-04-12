<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @author 	Tyler Bailey
 * @version 1.0.0
 * @package remote-dev-database
 * @subpackage remote-dev-database/inc
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RDD_Activator')) :

	class RDD_Activator {

		/**
		 * Fired upon plugin activation
		 *
		 * Checks system requirements
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			self::rdd_system_requirements_met();
		}

		/**
		 * Checks if the system requirements are met
		 *
		 * @since	1.0.0
		 * @return 	bool True if system requirements are met, die() message if not
		 */
		private static function rdd_system_requirements_met() {
			global $wp_version;

			if ( version_compare( PHP_VERSION, RDD_REQUIRED_PHP_VERSION, '<' ) ) {
				wp_die(__("PHP 5.3 is required to run this plugin.", RDD_SLUG), __('Incompatible PHP Version', RDD_SLUG));
			}
			if ( version_compare( $wp_version, RDD_REQUIRED_WP_VERSION, '<' ) ) {
				wp_die(__("You must be running at least WordPress 3.5 for this plugin to function properly.", RDD_SLUG), __('Incompatible WordPress Version.', RDD_SLUG));
			}

			return true;
		}
	}

endif;
