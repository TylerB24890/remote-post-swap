<?php

/**
* Remote Post Swap Base Class
*
* @author 	Tyler Bailey
* @version 1.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS_Base')) :

	class RPS_Base {

		/**
		 * Holds the user entered options
		 */
		private $options;

		/**
		* Executed on class istantiation.
		*
		* @since    1.0.0
		*/
		public function __construct() {
			$this->options = get_option('rps-db-url');
		}

		/**
		* Check if the all required variables are set to make the remote DB connection
		*
		* @since    1.0.0
		*/
		public function rps_check_connection() {
			if($this->rps_return_toggle() && $this->rps_return_url()) {
				return true;
			}

			return false;
		}

		/**
		* Check if the user has the remote database connection turned on
		*
		* @since    1.0.0
		*/
		public function rps_return_toggle() {
			if(isset($this->options['rps_toggle']) && $this->options['rps_toggle'] === true)
				return true;

			return false;
		}

		/**
		* Check if the user has entered a remote database connection URL
		*
		* @since    1.0.0
		*/
		public function rps_return_url() {
			if(isset($this->options['rps_url']) && strlen($this->options['rps_url']) > 1)
				return $this->rps_fix_url($this->options['rps_url']);

			return false;
		}

		/**
		 * Make sure the user entered URL as a slash added to the end of it
		 * @param  string - $url - URL to modify
		 * @return  string - modified URL
		 */
		private function rps_fix_url($url) {
			$furl = str_replace('\\', '/', trim($url));
			return ( substr($furl, -1) != '/' ) ? $furl .= '/' : $furl;
		}
	}

	new RPS_Base();

endif;
