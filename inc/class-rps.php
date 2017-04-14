<?php

/**
* Remote Post Swap Plugin Bootstrap File
*
* @author 	Tyler Bailey
* @version 0.6.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS')) :

	class RPS {

		/**
		* Plugin initialization functions
		*
		* @since    0.5.0
		*/
		public function __construct() {
			$this->set_locale();
			$this->load_dependencies();
		}


		/**
		* Loads all required plugin files and istantiates classes
		*
		* @return	null
		* @since   0.5.0
		*/
		private function load_dependencies() {

			require_once(RPS_GLOBAL_DIR . 'inc/class-rps-base.php');
			require_once(RPS_GLOBAL_DIR . 'inc/class-rps-retrieve-data.php');
			require_once(RPS_GLOBAL_DIR . 'inc/class-rps-replace-wp.php');

			if(is_admin())
			require_once(RPS_GLOBAL_DIR . 'inc/admin/class-rps-admin.php');

		}

		/**
		* Loads the plugin text-domain for internationalization
		*
		* @return	null
		* @since   0.5.0
		*/
		private function set_locale() {
			load_plugin_textdomain( RPS_SLUG, false, RPS_GLOBAL_DIR . 'language' );
		}

	}

endif;
