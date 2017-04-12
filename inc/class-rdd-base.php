<?php

/**
* Remote Dev Database Base Class
*
* @author 	Tyler Bailey
* @version 1.0
* @package remote-dev-database
* @subpackage remote-dev-database/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RDD_Base')) :

	class RDD_Base {

		/**
		 * Holds the user entered options
		 */
		protected $options;

		/**
		* Executed on class istantiation.
		*
		* @since    1.0.0
		*/
		public function __construct() {

			$this->options = get_option('rdd-db-url');
		}

		/**
		* Check if the remote database connection is active
		*
		* @since    1.0.0
		*/
		public function rdd_check_connection_options() {
			if(isset($this->options['rdd_toggle']) && $this->options['rdd_toggle'] === true && isset($this->options['rdd_url']) && strlen($this->options['rdd_url']) > 1) {
				return true;
			}

			return false;
		}

	}

	new RDD_Base();

endif;
