<?php

/**
* Remote Post Swap Base Class
*
* @author 	Tyler Bailey
* @version 0.5.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS_Base')) :

	class RPS_Base {

		/**
		* The RPS post meta key
		*
		* @var $rps_meta
		* @since 0.5.0
		*/
		public $rps_meta;

		/**
		* Holds the user entered options
		*
		* @var $options
		* @since 0.5.0
		*/
		private $options;

		/**
		* Executed on class istantiation.
		*
		* @since    0.5.0
		*/
		public function __construct() {
			$this->options = get_option('rps-db-url');

			$this->rps_meta = 'rps_post_id';
		}

		/**
		* Check if the all required variables are set to make the remote DB connection
		*
		* @return  	bool
		* @since    0.5.0
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
		* @return	bool
		* @since    0.5.0
		*/
		public function rps_return_toggle() {
			if(isset($this->options['rps_toggle']) && $this->options['rps_toggle'] === true)
			return true;

			return false;
		}

		/**
		* Check if the user has entered a remote database connection URL
		*
		* @return	string || bool
		* @since    0.5.0
		*/
		public function rps_return_url() {
			if(isset($this->options['rps_url']) && strlen($this->options['rps_url']) > 1)
			return $this->rps_fix_url($this->options['rps_url']);

			return false;
		}

		/**
		* Make sure the user entered URL as a slash added to the end of it
		*
		* @param  string - $url - URL to modify
		* @return  string - modified URL
		*/
		private function rps_fix_url($url) {
			$furl = str_replace('\\', '/', trim($url));
			return ( substr($furl, -1) != '/' ) ? $furl .= '/' : $furl;
		}

		/**
		* Check if post has a remote post ID attached
		*
		* @param  int - $pid - Post ID to retrieve meta for
		* @return  int || bool
		*/
		public function rps_get_post_meta($pid) {
			return get_post_meta($pid, $this->rps_meta, true);
		}
	}

	new RPS_Base();

endif;
