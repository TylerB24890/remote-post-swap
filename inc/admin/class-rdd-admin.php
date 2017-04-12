<?php

/**
* Remote Dev Database Administration
*
* @author 	Tyler Bailey
* @version 1.0
* @package remote-dev-database
* @subpackage remote-dev-database/inc/admin
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RDD_Admin')) :

	class RDD_Admin extends RDD_Base {

		/**
		* Executed on class istantiation.
		*
		* Constructs parent object
		* Adds menu pages on class load
		*
		* @since    1.0.0
		*/
		public function __construct() {

			if(!is_admin())
				exit(__("You must be an administrator.", RDD_SLUG));

			parent::__construct();

			add_action( 'admin_menu', array( $this, 'rdd_admin_menu_init' ) );
			add_action( 'admin_init', array( $this, 'rdd_settings_init' ) );
		}

		/**
		* Creates the top-level admin menu page
		*
		* @since    1.0.0
		*/
		public function rdd_admin_menu_init() {

			// This page will be under "Settings"
	        add_options_page(
	            __('Remote Dev Database', RDD_SLUG),
	            __('RDD Settings', RDD_SLUG),
	            'manage_options',
	            'rdd-settings-admin',
	            array( $this, 'rdd_main_menu_page_render' )
	        );
		}

		/**
		* Registers and adds settings to admin page
		*
		* @since    1.0.0
		*/
	    public function rdd_settings_init() {
	        register_setting(
	            'rdd-settings', // Option group
	            'rdd-db-url', // Option name
	            array( $this, 'rdd_validate_options' ) // Sanitize
	        );

	        add_settings_section(
	            'rdd-settings-section', // ID
	            '', // Title
	            array( $this, 'rdd_field_description' ), // Callback
	            'rdd-settings-admin' // Page
	        );

			add_settings_field(
	            'rdd_toggle',
	            __('Connect to remote database:', RDD_SLUG),
	            array( $this, 'rdd_toggle_input' ),
	            'rdd-settings-admin',
	            'rdd-settings-section'
	        );

	        add_settings_field(
	            'rdd_url',
	            __('Website URL:', RDD_SLUG),
	            array( $this, 'rdd_url_input' ),
	            'rdd-settings-admin',
	            'rdd-settings-section'
	        );
	    }

		/**
		* Validate the options for saving into the database
		*
		* @since    1.0.0
		*/
		public function rdd_validate_options($input) {
			$new_input = array();

			if(isset($input['rdd_toggle'])) {
				$new_input['rdd_toggle'] = ($input['rdd_toggle'] == '1' ? true : false);
			}

			if(isset($input['rdd_url'])) {
				$new_input['rdd_url'] = esc_url_raw($input['rdd_url']);
			}

			return $new_input;
		}

		/**
		* Renders help text for the RDD URL field
		*
		* @since    1.0.0
		*/
		public function rdd_field_description() {
			echo "<h3>" . __('Configure your Remote Database Connection below:', RDD_SLUG) . "</h3>";
			$this->rdd_render_connection_notice();
		}

		/**
		* Renders the RDD Toggle Checkbox to turn on & off the remote db connection
		*
		* @since    1.0.0
		*/
    	public function rdd_toggle_input() {
			echo '<label><input type="checkbox" id="rdd_toggle" name="rdd-db-url[rdd_toggle]" value="1" ' . ($this->rdd_return_toggle() ? 'checked="checked"' : '') . '/> Activate remote database connection</label>';
    	}

		/**
		* Renders the RDD URL input field & populates it with saved data
		*
		* @since    1.0.0
		*/
    	public function rdd_url_input() {
	        printf(
	            '<input type="text" id="rdd_url" name="rdd-db-url[rdd_url]" value="%s" style="width: 300px; height: 35px;" placeholder="http://yourwebsite.com"/>',
	            ( $this->rdd_return_url() ? esc_url( $this->rdd_return_url() ) : '' )
	        );
    	}

		/**
		* Loads the landing page markup from admin partials
		*
		* @since    1.0.0
		*/
		public function rdd_main_menu_page_render() {
			include_once(RDD_GLOBAL_DIR . 'inc/admin/partials/settings-page.php');
		}

		/**
		* Renders the admin notices to indicate the db connection status
		*
		* @since    1.0.0
		*/
		private function rdd_render_connection_notice() {
			if($this->rdd_check_connection()) {
				$class = "notice-success";
				$msg = __('Remote Database Connection is active', RDD_SLUG);
			} elseif($this->rdd_return_toggle() && !$this->rdd_return_url()) {
				$class = 'notice-error';
				$msg = __('Your database connection is turned on but you have not provided a valid URL to connect to.', RDD_SLUG);
			} else {
				$class = 'notice-warning';
				$msg = __('Remote Database Connection is not active', RDD_SLUG);
			}

			echo '<div class="notice is-dismissible ' . $class . '" style="padding: 15px; margin-top: 30px; margin-left: 0;">' . $msg . '</div>';
		}

	}

	new RDD_Admin();

endif;
