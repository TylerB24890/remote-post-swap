<?php

/**
* Remote Post Swap retrieve data from API endpoints
*
* @author 	Tyler Bailey
* @version 1.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS_Retrieve_Data')) :

	class RPS_Retrieve_Data extends RPS_Base {

		/**
		 * The URL to grab data from -- entered from plugin options page
		 */
		protected $rps_base_url;

		/**
		 * The API endpoint for posts
		 */
		protected $rps_posts;

		/**
		 * The API endpoint for users
		 */
		protected $rps_users;

		/**
		* Executed on class istantiation.
		*
		* @since    1.0.0
		*/
		public function __construct() {
			parent::__construct();

			if(!$this->rps_check_connection())
				return false;

			// Base URL from user entered options
			$this->rps_base_url = $this->rps_return_url();

			// Endpoint URLs
			$this->rps_posts = $this->rps_base_url . 'wp-json/wp/v2/posts';
			$this->rps_users = $this->rps_base_url . 'wp-json/wp/v2/users/';
		}

		/**
		* Retrieves the posts from the target site API
		*
		* @param  $id - int - the ID of the post to retrieve from the API
		* @param  $filters - array - Array of API filters
		* @since    1.0.0
		*/
		public function rps_get_posts($id, $filters = array()) {

			if($id != NULL && $id != FALSE) {
				$resp = wp_remote_get($this->rps_posts . '/' . $id);
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

				$resp = wp_remote_get($this->rps_posts . $filter_str);
			} else {
				$resp = wp_remote_get($this->rps_posts);
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
		* @param  $id - int - the ID of the user to retrieve from the API
		* @since    1.0.0
		*/
		public function rps_get_users($id = NULL) {

			if($id !== NULL) {
				$resp = wp_remote_get($this->rps_users . $id);
			} else {
				$resp = wp_remote_get($this->rps_users);
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

	new RPS_Retrieve_Data();

endif;
