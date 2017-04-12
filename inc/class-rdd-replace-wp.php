<?php

/**
* Remote Dev Database replace standard WP functions with API data
*
* @author 	Tyler Bailey
* @version 1.0
* @package remote-dev-database
* @subpackage remote-dev-database/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RDD_Replace_WP')) :

	class RDD_Replace_WP extends RDD_Retrieve_Data {

		/**
		* Executed on class istantiation.
		*
		* @since    1.0.0
		*/
		public function __construct() {
			parent::__construct();

			add_action( 'posts_request', array( $this, 'rdd_strip_query' ), 10, 2);
		}

		/**
		* Takes variables from the initial query and wipes the query clean
		*
		* @since    1.0.0
		*/
		public function rdd_strip_query($sql, &$query) {
			// If this is not the main query OR the DB connection isn't set
			if(is_admin())
				return $sql;

			// Set the query variables to return NOTHING
			$query->query_vars['no_found_rows'] = true;
			$query->query_vars['cache_results'] = false;


		}
	}

	new RDD_Replace_WP();

endif;
