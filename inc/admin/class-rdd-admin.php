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
		* Constructs parent object
		* Adds menu pages on class load
		*
		* @since    1.0.0
		*/
		public function __construct() {

			if(!is_admin())
				exit("You must be an administrator.");

			parent::__construct();

			add_action( 'admin_menu', array( $this, 'rdd_admin_menu_init' ) );
		}

		/**
		* Creates the top-level admin menu page
		*
		* @since    1.0.0
		*/
		public function rdd_admin_menu_init() {

		}

		/**
		* Loads the landing page markup from admin partials
		*
		* @since    1.0.0
		*/
		public function rdd_main_menu_page_render() {
			include_once(RDD_GLOBAL_DIR . 'inc/admin/partials/settings-page.php');
		}

	}

	new RDD_Admin();

endif;
