<?php

/**
* Remote Post Swap Administration
*
* Adds the menu pages and initializes the settings API
*
* @author 	Tyler Bailey
* @version 0.8.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc/admin
*/

namespace RPS\Admin;
use \RPS\RPS_Base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS_Admin')) :

	class RPS_Admin {

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

			add_action( 'admin_menu', array( $this, 'rps_admin_menu_init' ) );

			// Init the settings
			new \RPS\Admin\RPS_Settings;

			add_action( 'wp_ajax_rps_delete_meta', array(__CLASS__, 'rps_flush_meta') );
			add_action( 'wp_ajax_nopriv_rps_delete_meta', array(__CLASS__, 'rps_flush_meta' ));
		}

		/**
		* Creates the top-level admin menu page
		*
		* @return	null
		* @since    0.5.0
		*/
		public function rps_admin_menu_init() {

			// This page will be under "Settings"
			add_options_page(
				__('Remote Post Swap', RPS_SLUG),
				__('RPS Settings', RPS_SLUG),
				'manage_options',
				'rps-settings-admin',
				array( $this, 'rps_main_menu_page_render' )
			);
		}

		/**
		* Loads the landing page markup from admin partials
		*
		* @return	file
		* @since    0.5.0
		*/
		public function rps_main_menu_page_render() {
			include_once(RPS_GLOBAL_DIR . 'inc/admin/partials/settings-page.php');
		}

		/**
		* AJAX Function to delete all RPS post meta
		*
		* @return	null
		* @since    0.5.0
		*/
		public static function rps_flush_meta() {
			delete_post_meta_by_key( RPS_Base::$rps_meta );
		}

		/**
		* Renders the admin notices to indicate the db connection status
		*
		* @return	string
		* @since    0.5.0
		*/
		public static function rps_render_connection_notice() {
			if(RPS_Base::rps_check_connection()) {
				$class = "notice-success";
				$msg = __('Remote Database Connection is active', RPS_SLUG);
			} elseif(RPS_Base::rps_return_option('rps_toggle') && ! RPS_Base::rps_return_option('rps_url')) {
				$class = 'notice-error';
				$msg = __('Your database connection is turned on but you have not provided a valid URL to connect to.', RPS_SLUG);
			} else {
				$class = 'notice-warning';
				$msg = __('Remote Database Connection is not active', RPS_SLUG);
			}

			echo '<div class="notice is-dismissible ' . $class . '" style="padding: 15px; margin-top: 30px; margin-left: 0;">' . $msg . '</div>';
		}
	}

endif;
