<?php

/**
* Remote Post Swap retrieve data from API endpoints
*
* @author 	Tyler Bailey
* @version 0.7.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

namespace RPS;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS\RPS_Retrieve_Data')) :

	class RPS_Retrieve_Data extends RPS_Base {

		/**
		* The URL to grab data from -- entered from plugin options page
		*
		* @var $rps_base_url
		* @since 0.5.0
		*/
		protected $rps_base_url;

		/**
		* The API endpoint for posts
		*
		* @var $rps_posts
		* @since 0.5.0
		*/
		protected $rps_posts;

		/**
		* The API endpoint for users
		*
		* @var $rps_users
		* @since 0.5.0
		*/
		protected $rps_users;

		/**
		* The API endpoint for media
		*
		* @var $rps_media
		* @since 0.5.0
		*/
		protected $rps_media;

		/**
		* Executed on class istantiation.
		*
		* @since    0.5.0
		*/
		public function __construct() {
			parent::__construct();

			if(!$this->rps_check_connection())
			return false;

			// Base URL from user entered options
			$this->rps_base_url = $this->rps_return_url();

			// Endpoint URLs
			$this->rps_posts = $this->rps_base_url . 'wp-json/wp/v2/posts';
			$this->rps_users = $this->rps_base_url . 'wp-json/wp/v2/users';
			$this->rps_media = $this->rps_base_url . 'wp-json/wp/v2/media';
		}

		/**
		* Retrieves the posts from the target site API
		*
		* @param  $id - int - the ID of the post to retrieve from the API
		* @param  $filters - array - Array of API filters
		* @return	$posts - array - Array of post data returned from API
		* @since    0.5.0
		*/
		protected function rps_get_posts($id, $filters = array()) {

			$posts = false;

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
				return false;
			}

			$posts = json_decode( wp_remote_retrieve_body( $resp ));

			if(empty($posts) || isset($posts->data->status) && $posts->data->status === 404)
			return false;

			return $posts;
		}

		/**
		* Retrieves the users from the target site API
		*
		* @param  $id - int - the ID of the user to retrieve from the API
		* @return	$users - array - array of user data returned from API
		* @since    0.5.0
		*/
		protected function rps_get_users($id = NULL) {

			$users = false;

			if($id !== NULL) {
				$resp = wp_remote_get($this->rps_users . $id);
			} else {
				$resp = wp_remote_get($this->rps_users);
			}

			if(is_wp_error( $resp )) {
				return false;
			}

			$users = json_decode( wp_remote_retrieve_body( $resp ));

			if(empty($users))
			return false;

			return $users;
		}

		/**
		* Retrieves featured image/media information from the API
		*
		* @param   $id - int - the ID of the media element to grab
		* @return	$media - array - array of media data returned from API
		* @since    0.5.0
		*/
		protected function rps_get_media($id) {

			$media = false;

			if($id !== NULL) {
				$resp = wp_remote_get($this->rps_media . '/' . $id);
			}

			if(is_wp_error($resp)) {
				return false;
			}

			$media = json_decode( wp_remote_retrieve_body( $resp ) );

			if(empty($media))
			return false;

			return $media;
		}
	}

	new RPS_Retrieve_Data();

endif;
