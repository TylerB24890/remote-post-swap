<?php

/**
 * Remote Dev Database Plugin Bootstrap File
 *
 * @author 	Tyler Bailey
 * @version 1.0
 * @package remote-dev-database
 * @subpackage remote-dev-database/inc
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RDD')) :

	class RDD {

		/**
		 * Plugin initialization functions
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->set_locale();
			$this->load_dependencies();
		}


		/**
		 * Loads all required plugin files and istantiates classes
		 *
		 * @since   1.0.0
		 */
		private function load_dependencies() {

			require_once(RDD_GLOBAL_DIR . 'inc/class-rdd-base.php');
			require_once(RDD_GLOBAL_DIR . 'inc/class-rdd-retrieve-data.php');
			require_once(RDD_GLOBAL_DIR . 'inc/class-rdd-replace-wp.php');

			if(is_admin())
				require_once(RDD_GLOBAL_DIR . 'inc/admin/class-rdd-admin.php');

		}

		/**
		 * Loads the plugin text-domain for internationalization
		 *
		 * @since   1.0.0
		 */
		private function set_locale() {
			load_plugin_textdomain( RDD_SLUG, false, RDD_GLOBAL_DIR . 'language' );
	    }

	}

endif;
