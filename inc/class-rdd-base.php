<?php

/**
* Remote Dev Database Base Class
*
* @author 	Tyler Bailey
* @version 1.0
* @package remote-dev-database
* @subpackage remote-dev-database/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RDD_Base')) :

	class RDD_Base {

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
			$this->options = get_option('rdd-db-url');
		}

		/**
		* Check if the all required variables are set to make the remote DB connection
		*
		* @since    1.0.0
		*/
		public function rdd_check_connection() {
			if($this->rdd_return_toggle() && $this->rdd_return_url()) {
				return true;
			}

			return false;
		}

		/**
		* Check if the user has the remote database connection turned on
		*
		* @since    1.0.0
		*/
		public function rdd_return_toggle() {
			if(isset($this->options['rdd_toggle']) && $this->options['rdd_toggle'] === true)
				return true;

			return false;
		}

		/**
		* Check if the user has entered a remote database connection URL
		*
		* @since    1.0.0
		*/
		public function rdd_return_url() {
			if(isset($this->options['rdd_url']) && strlen($this->options['rdd_url']) > 1)
				return $this->rdd_fix_url($this->options['rdd_url']);

			return false;
		}

		/**
		 * Make sure the user entered URL as a slash added to the end of it
		 * @param  string - $url - URL to modify
		 * @return  string - modified URL
		 */
		private function rdd_fix_url($url) {
			$furl = str_replace('\\', '/', trim($url));
			return ( substr($furl, -1) != '/' ) ? $furl .= '/' : $furl;
		}
	}

	new RDD_Base();

endif;
