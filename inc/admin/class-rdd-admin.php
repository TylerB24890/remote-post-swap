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

	class RDD_Admin {

		/**
		* Executed on class istantiation.
		*
		* @since    1.0.0
		*/
		public function __construct() {
			if(!is_admin())
				exit("You must be an administrator.");

			add_action( 'admin_menu', array($this, 'rdd_add_admin_menu') );
			add_action( 'admin_init', array($this, 'rdd_settings_init') );
    	}

		public function rdd_add_admin_menu(  ) {
			add_options_page( 'Remote Dev Database', 'Remote Dev Database', 'manage_options', 'remote_dev_database', 'rdd_options_page' );
		}


		public function rdd_settings_init(  ) {

			register_setting( 'rdd-settings', 'rdd_settings' );

			add_settings_section(
				'rdd_settings_section',
				__( 'Your section description', 'rdd' ),
				'rdd_settings_section_callback',
				'rdd-settings'
			);

			add_settings_field(
				'rdd_text_field_0',
				__( 'Settings field description', 'rdd' ),
				'rdd_text_field_0_render',
				'rdd-settings',
				'rdd_settings_section'
			);
		}

		public function rdd_text_field_0_render(  ) {

			$options = get_option( 'rdd_settings' );
			?>
			<input type='text' name='rdd_settings[rdd_text_field_0]' value='<?php echo $options['rdd_text_field_0']; ?>'>
			<?php
		}

		public function rdd_settings_section_callback(  ) {

			echo __( 'This section description', 'rdd' );
		}

		public function rdd_options_page(  ) {
			include_once(RDD_GLOBAL_DIR . 'inc/admin/partials/settings-page.php');
		}

	}

	new RDD_Admin();

endif;
