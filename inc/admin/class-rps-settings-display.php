<?php

/**
* Remote Post Swap Settings Display
*
* Contains callback functions for the WP Settings API to display the plugin option fields
*
* @author 	Tyler Bailey
* @version 0.8.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc/admin
*/

namespace RPS\Admin;
use \RPS\RPS_Base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS\Admin\RPS_Settings_Display')) :

	class RPS_Settings_Display {

		/**
		* Renders help text for the RPS URL field
		*
		* @return	string
		* @since    0.5.0
		*/
		public static function rps_field_description() {
			echo '<h3 style="margin-top: 30px;">' . __('Configure your Remote Database Connection below:', RPS_SLUG) . '</h3>';
			\RPS\Admin\RPS_Admin::rps_render_connection_notice();
		}

		/**
		* Renders help text for the RPS URL field
		*
		* @return	string
		* @since    0.5.0
		*/
		public static function rps_posts_fields_description() {
			echo "<h3>" . __('Configure which fields to replace:', RPS_SLUG) . "</h3>";
		}

		/**
		* Renders the RPS Toggle Checkbox to turn on & off the remote db connection
		*
		* @return	string
		* @since    0.5.0
		*/
		public static function rps_toggle_input() {
			echo '<label><input type="checkbox" id="rps_toggle" name="rps-connection-settings[rps_toggle]" value="1" ' . (RPS_Base::rps_return_option('rps_toggle') ? 'checked="checked"' : '') . '/> Activate remote database connection</label>';
		}

		/**
		* Renders the RPS URL input field & populates it with saved data
		*
		* @return 	string
		* @since    0.5.0
		*/
		public static function rps_url_input() {
			printf(
				'<input type="text" id="rps_url" name="rps-connection-settings[rps_url]" value="%s" style="width: 300px; height: 35px;" placeholder="http://yourwebsite.com"/>',
				( RPS_Base::rps_return_option('rps_url') ? esc_url( RPS_Base::rps_return_option('rps_url') ) : '' )
			);
		}

		/**
		* Renders the RPS Post Title Toggle
		*
		* @return	string
		* @since    0.7.0
		*/
		public static function rps_title_toggle_input() {
			echo '<label><input type="checkbox" id="rps_post_title" name="rps-connection-settings[rps_post_title]" value="1" ' . (RPS_Base::rps_return_option('rps_post_title') ? 'checked="checked"' : '') . '/> Swap Post Titles</label>';
		}

		/**
		* Renders the RPS Post Excerpt Toggle
		*
		* @return	string
		* @since    0.7.0
		*/
		public static function rps_excerpt_toggle_input() {
			echo '<label><input type="checkbox" id="rps_post_excerpt" name="rps-connection-settings[rps_post_excerpt]" value="1" ' . (RPS_Base::rps_return_option('rps_post_excerpt') ? 'checked="checked"' : '') . '/> Swap Post Excerpt</label>';
		}

		/**
		* Renders the RPS Post Content Toggle
		*
		* @return	string
		* @since    0.7.0
		*/
		public static function rps_content_toggle_input() {
			echo '<label><input type="checkbox" id="rps_post_title" name="rps-connection-settings[rps_post_content]" value="1" ' . (RPS_Base::rps_return_option('rps_post_content') ? 'checked="checked"' : '') . '/> Swap Post Content</label>';
		}

		/**
		* Renders the RPS Post Media Toggle
		*
		* @return	string
		* @since    0.7.0
		*/
		public static function rps_media_toggle_input() {
			echo '<label><input type="checkbox" id="rps_post_media" name="rps-connection-settings[rps_post_media]" value="1" ' . (RPS_Base::rps_return_option('rps_post_media') ? 'checked="checked"' : '') . '/> Swap Post Media (Featured Images)</label>';
		}

		/**
		* Renders the RPS Post Date Toggle
		*
		* @return	string
		* @since    0.7.0
		*/
		public static function rps_date_toggle_input() {
			echo '<label><input type="checkbox" id="rps_post_date" name="rps-connection-settings[rps_post_date]" value="1" ' . (RPS_Base::rps_return_option('rps_post_date') ? 'checked="checked"' : '') . '/> Swap Post Date</label>';
		}
	}

endif;
