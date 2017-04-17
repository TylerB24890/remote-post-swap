<?php

/**
* Remote Post Swap Base Class
*
* @author 	Tyler Bailey
* @version 0.7.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

namespace RPS;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS\RPS_Base')) :

	class RPS_Base {

		/**
		* The RPS post meta key
		*
		* @var $rps_meta
		* @since 0.5.0
		*/
		public static $rps_meta;

		/**
		* Holds the user entered options
		*
		* @var $options
		* @since 0.5.0
		*/
		private static $options;

		/**
		* Executed on class istantiation.
		*
		* @since    0.5.0
		*/
		public function __construct() {
			self::$options = get_option('rps-db-url');

			self::$rps_meta = 'rps_post_id';
		}

		/**
		* Check if the all required variables are set to make the remote DB connection
		*
		* @return  	bool
		* @since    0.5.0
		*/
		public static function rps_check_connection() {
			if(self::rps_return_toggle() && self::rps_return_url()) {
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
		public static function rps_return_toggle() {
			if(isset(self::$options['rps_toggle']) && self::$options['rps_toggle'] === true)
			return true;

			return false;
		}

		/**
		* Check if the user has entered a remote database connection URL
		*
		* @return	string || bool
		* @since    0.5.0
		*/
		public static function rps_return_url() {
			if(isset(self::$options['rps_url']) && strlen(self::$options['rps_url']) > 1)
			return self::rps_fix_url(self::$options['rps_url']);

			return false;
		}

		/**
		* Make sure the user entered URL as a slash added to the end of it
		*
		* @param  string - $url - URL to modify
		* @return  string - modified URL
		*/
		private static function rps_fix_url($url) {
			$furl = str_replace('\\', '/', trim($url));
			return ( substr($furl, -1) != '/' ) ? $furl .= '/' : $furl;
		}

		/**
		* Check if post has a remote post ID attached
		*
		* @param  int - $pid - Post ID to retrieve meta for
		* @return  int || bool
		*/
		public static function rps_get_post_meta($pid) {
			return get_post_meta($pid, self::$rps_meta, true);
		}
	}

	new RPS_Base();

endif;
