<?php
/**
* Fired during plugin deactivation.
*
* This class defines all code necessary to decativate the plugin
*
* @author 	Tyler Bailey
* @version 0.6.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS_Deactivator')) :

	class RPS_Deactivator {

		/**
		* Fired on plugin deactivation
		*
		* @since    0.5.0
		*/
		public static function deactivate() {
			// nothing here yet
		}
	}

endif;
