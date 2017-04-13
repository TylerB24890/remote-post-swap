<?php

/**
* Remote Post Swap Administration
*
* @author 	Tyler Bailey
* @version 0.5.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc/admin
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS_Admin')) :

	class RPS_Admin extends RPS_Base {

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

			parent::__construct();

			add_action( 'admin_menu', array( $this, 'rps_admin_menu_init' ) );
			add_action( 'admin_init', array( $this, 'rps_settings_init' ) );

			add_action( 'wp_ajax_rps_delete_meta', array($this, 'rps_flush_meta') );
			add_action( 'wp_ajax_nopriv_rps_delete_meta', array($this, 'rps_flush_meta' ));
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
		* Registers and adds settings to admin page
		*
		* @return	null
		* @since    0.5.0
		*/
		public function rps_settings_init() {
			register_setting(
				'rps-settings', // Option group
				'rps-db-url', // Option name
				array( $this, 'rps_validate_options' ) // Sanitize
			);

			add_settings_section(
				'rps-settings-section', // ID
				'', // Title
				array( $this, 'rps_field_description' ), // Callback
				'rps-settings-admin' // Page
			);

			add_settings_field(
				'rps_toggle',
				__('Connect to remote database:', RPS_SLUG),
				array( $this, 'rps_toggle_input' ),
				'rps-settings-admin',
				'rps-settings-section'
			);

			add_settings_field(
				'rps_url',
				__('Website URL:', RPS_SLUG),
				array( $this, 'rps_url_input' ),
				'rps-settings-admin',
				'rps-settings-section'
			);
		}

		/**
		* Validate the options for saving into the database
		*
		* @param  	$input - array - array of submitted form data
		* @return	$new_input - array - validated/formatted form data
		* @since    0.5.0
		*/
		public function rps_validate_options($input) {
			$new_input = array();

			if(isset($input['rps_toggle'])) {
				$new_input['rps_toggle'] = ($input['rps_toggle'] == '1' ? true : false);
			}

			if(isset($input['rps_url'])) {
				$this->rps_flush_meta();
				$new_input['rps_url'] = esc_url_raw($input['rps_url']);
			}

			return $new_input;
		}

		/**
		* Renders help text for the RPS URL field
		*
		* @return	string
		* @since    0.5.0
		*/
		public function rps_field_description() {
			echo "<h3>" . __('Configure your Remote Database Connection below:', RPS_SLUG) . "</h3>";
			$this->rps_render_connection_notice();
		}

		/**
		* Renders the RPS Toggle Checkbox to turn on & off the remote db connection
		*
		* @return	string
		* @since    0.5.0
		*/
		public function rps_toggle_input() {
			echo '<label><input type="checkbox" id="rps_toggle" name="rps-db-url[rps_toggle]" value="1" ' . ($this->rps_return_toggle() ? 'checked="checked"' : '') . '/> Activate remote database connection</label>';
		}

		/**
		* Renders the RPS URL input field & populates it with saved data
		*
		* @return 	string
		* @since    0.5.0
		*/
		public function rps_url_input() {
			printf(
				'<input type="text" id="rps_url" name="rps-db-url[rps_url]" value="%s" style="width: 300px; height: 35px;" placeholder="http://yourwebsite.com"/>',
				( $this->rps_return_url() ? esc_url( $this->rps_return_url() ) : '' )
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
		* Renders the admin notices to indicate the db connection status
		*
		* @return	string
		* @since    0.5.0
		*/
		private function rps_render_connection_notice() {
			if($this->rps_check_connection()) {
				$class = "notice-success";
				$msg = __('Remote Database Connection is active', RPS_SLUG);
			} elseif($this->rps_return_toggle() && !$this->rps_return_url()) {
				$class = 'notice-error';
				$msg = __('Your database connection is turned on but you have not provided a valid URL to connect to.', RPS_SLUG);
			} else {
				$class = 'notice-warning';
				$msg = __('Remote Database Connection is not active', RPS_SLUG);
			}

			echo '<div class="notice is-dismissible ' . $class . '" style="padding: 15px; margin-top: 30px; margin-left: 0;">' . $msg . '</div>';
		}

		/**
		* AJAX Function to delete all RPS post meta
		*
		* @return	null
		* @since    0.5.0
		*/
		public function rps_flush_meta() {
			delete_post_meta_by_key( $this->rps_meta );
		}
	}

	new RPS_Admin();

endif;
