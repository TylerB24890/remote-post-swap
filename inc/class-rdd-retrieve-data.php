<?php

/**
* Remote Dev Database retrieve data from API endpoints
*
* @author 	Tyler Bailey
* @version 1.0
* @package remote-dev-database
* @subpackage remote-dev-database/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RDD_Retrieve_Data')) :

	class RDD_Retrieve_Data extends RDD_Base {

		/**
		 * The URL to grab data from -- entered from plugin options page
		 */
		protected $rdd_base_url;

		/**
		 * The API endpoint for posts
		 */
		protected $rdd_posts;

		/**
		 * The API endpoint for users
		 */
		protected $rdd_users;

		/**
		* Executed on class istantiation.
		*
		* @since    1.0.0
		*/
		public function __construct() {
			parent::__construct();

			if(!$this->rdd_check_connection())
				return false;

			// Base URL from user entered options
			$this->rdd_base_url = $this->rdd_return_url();

			// Endpoint URLs
			$this->rdd_posts = $this->rdd_base_url . 'wp-json/wp/v2/posts';
			$this->rdd_users = $this->rdd_base_url . 'wp-json/wp/v2/users/';
		}

		/**
		* Retrieves the posts from the target site API
		*
		* @param  int - $id - the ID of the post to retrieve from the API
		* @since    1.0.0
		*/
		public function rdd_get_posts($id, $filters = array()) {

			if($id != NULL) {
				$resp = wp_remote_get($this->rdd_posts . '/' . $id);
			} elseif(!empty($filters)) {

				$fc = 0;
				$filter_str = '';

				foreach($filters as $key => $filter) {
					if(is_array($filter)) {
						$filter = implode(",", $filter);
					}
					$filter_str .= ($fc === 0 ? '?' : '&') . $key . '=' . $filter;
					$fc++;
				}

				$resp = wp_remote_get($this->rdd_posts . $filter_str);
			} else {
				$resp = wp_remote_get($this->rdd_posts);
			}

			if(is_wp_error( $resp )) {
				echo $resp->get_error_message();
				return false;
			}

			$posts = json_decode( wp_remote_retrieve_body( $resp ));

			if(empty($posts))
				return array();


			return $posts;
		}

		/**
		* Retrieves the users from the target site API
		*
		* @param  int - $id - the ID of the user to retrieve from the API
		* @since    1.0.0
		*/
		public function rdd_get_users($id = NULL) {

			if($id !== NULL) {
				$resp = wp_remote_get($this->rdd_users . $id);
			} else {
				$resp = wp_remote_get($this->rdd_users);
			}

			if(is_wp_error( $resp )) {
				echo $resp->get_error_message();
				return false;
			}

			$users = json_decode( wp_remote_retrieve_body( $resp ));

			if(empty($users))
				return array();

			return $users;
		}
	}

	new RDD_Retrieve_Data();

endif;
