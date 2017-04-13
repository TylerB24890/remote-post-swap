<?php
/**
* Fired when the plugin is uninstalled.
*
* @link       http://tylerb.me
* @since      0.5.0
* @package    rps
*/

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
} else {
	delete_post_meta_by_key( 'rps_post_id' );
}
