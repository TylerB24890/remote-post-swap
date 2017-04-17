<?php

/**
* Remote Post Swap Settings Configuration
*
* Registers the plugin settings with the WP Settings API
*
* @author 	Tyler Bailey
* @version 0.7.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc/admin
*/

namespace RPS\Admin;
use \RPS\RPS_Base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS\Admin\RPS_Settings')) :

	class RPS_Settings {

		/**
		* Executed on class istantiation.
		*
		* Constructs parent object
		* Adds menu pages on class load
		*
		* @since    0.5.0
		*/
		public function __construct() {

			if(!is_admin())
			exit(__("You must be an administrator.", RPS_SLUG));

			add_action( 'admin_init', array($this, 'rps_settings_init'));
		}

		/**
		* Registers and adds settings to admin page
		*
		* @return	null
		* @since    0.5.0
		*/
		public function rps_settings_init() {
			// Register setting with WP API
			register_setting(
				'rps-settings', // Option group
				'rps-connection-settings', // Option name
				array( $this, 'rps_validate_options' ) // Sanitize
			);

			// Register the connection section
			add_settings_section(
				'rps-settings-section', // ID
				'', // Title
				'\RPS\Admin\RPS_Settings_Display::rps_field_description', // Callback
				'rps-settings-admin' // Page
			);

			// Register the post fields section
			add_settings_section(
				'rps-post-fields', // ID
				'', // Title
				'\RPS\Admin\RPS_Settings_Display::rps_posts_fields_description',
				'rps-settings-admin' // Page
			);

			self::rps_add_connection_fields();
			self::rps_add_field_toggle_fields();
		}

		/**
		* Adds the RPS Connection Fields
		*
		* @return	null
		* @since    0.7.0
		*/
		private static function rps_add_connection_fields() {
			add_settings_field(
				'rps_toggle',
				__('Connect to remote database:', RPS_SLUG),
				'\RPS\Admin\RPS_Settings_Display::rps_toggle_input',
				'rps-settings-admin',
				'rps-settings-section'
			);

			add_settings_field(
				'rps_url',
				__('Website URL:', RPS_SLUG),
				'\RPS\Admin\RPS_Settings_Display::rps_url_input',
				'rps-settings-admin',
				'rps-settings-section'
			);
		}

		/**
		* Adds the RPS Post Fields Fields
		*
		* Allows the user to choose which fields to swap out with remote data
		*
		* @return	null
		* @since    0.7.0
		*/
		private static function rps_add_field_toggle_fields() {
			// Register the post title swap field
			add_settings_field(
				'rps_post_title',
				__('Post Title:', RPS_SLUG),
				'\RPS\Admin\RPS_Settings_Display::rps_title_toggle_input',
				'rps-settings-admin',
				'rps-post-fields'
			);

			// Register the post excerpt swap field
			add_settings_field(
				'rps_post_excerpt',
				__('Post Excerpt:', RPS_SLUG),
				'\RPS\Admin\RPS_Settings_Display::rps_excerpt_toggle_input',
				'rps-settings-admin',
				'rps-post-fields'
			);

			// Register the post content swap field
			add_settings_field(
				'rps_post_content',
				__('Post Content:', RPS_SLUG),
				'\RPS\Admin\RPS_Settings_Display::rps_content_toggle_input',
				'rps-settings-admin',
				'rps-post-fields'
			);

			// Register the post media swap field
			add_settings_field(
				'rps_post_media',
				__('Post Media:', RPS_SLUG),
				'\RPS\Admin\RPS_Settings_Display::rps_media_toggle_input',
				'rps-settings-admin',
				'rps-post-fields'
			);

			// Register the post date swap field
			add_settings_field(
				'rps_post_date',
				__('Post Date:', RPS_SLUG),
				'\RPS\Admin\RPS_Settings_Display::rps_date_toggle_input',
				'rps-settings-admin',
				'rps-post-fields'
			);
		}

		/**
		* Validate the options for saving into the database
		*
		* @param  	$input - array - array of submitted form data
		* @return	$new_input - array - validated/formatted form data
		* @since    0.7.0
		*/
		public function rps_validate_options($input) {

			$new_input = array();

			foreach($input as $k => $v) {
				if($k === 'rps_url') {
					if(RPS_Base::rps_return_option($k) != $input[$k])
					RPS_Admin::rps_flush_meta();

					$new_input[$k] = esc_url_raw($input[$k]);
				} else {
					$new_input[$k] = ($input[$k] == '1' ? true : false);
				}
			}

			return $new_input;
		}
	}

endif;
