<?php
/**
* Fired during plugin deactivation.
*
* This class defines all code necessary to decativate the plugin
*
* @author 	Tyler Bailey
* @version 0.8.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

namespace RPS;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS\RPS_Deactivator')) :

	class RPS_Deactivator {

		/**
		* Fired on plugin deactivation
		*
		* @since    0.5.0
		*/
		public static function rps_deactivate() {
			// nothing here yet
		}
	}

endif;
