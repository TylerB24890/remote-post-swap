<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://tylerb.me
 * @since      1.0.0
 * @package    rdd
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
} else {
	delete_post_meta_by_key( 'post_size_bytes' );
	delete_post_meta_by_key( 'post_size_formatted' );
}
