<?php

/**
* Remote Post Swap Plugin Bootstrap File
*
* @author 	Tyler Bailey
* @version 0.8.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

namespace RPS;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS\RPS')) :

	class RPS {

		/**
		* Plugin initialization functions
		*
		* @since    0.5.0
		*/
		public function __construct() {
			$this->set_locale();
			$this->rps_init();
		}

		/**
		* Initialize the RPS_Replace_WP object to get the plugin running.
		*
		* If user is admin, initialize the RPS_Admin object
		*
		* @return	null
		* @since	0.7.0
		*/
		private function rps_init() {
			new RPS_Replace_WP;

			if(is_admin())
				new \RPS\Admin\RPS_Admin;
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
