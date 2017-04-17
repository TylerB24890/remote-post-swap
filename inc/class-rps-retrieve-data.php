<?php

/**
* Remote Post Swap retrieve data from API endpoints
*
* @author 	Tyler Bailey
* @version 0.8.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

namespace RPS;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS_Retrieve_Data')) :

	class RPS_Retrieve_Data {

		/**
		* The URL to grab data from -- entered from plugin options page
		*
		* @var $rps_base_url
		* @since 0.5.0
		*/
		protected $rps_base_url;

		/**
		* Executed on class istantiation.
		*
		* @since    0.5.0
		*/
		public function __construct() {
			if( ! RPS_Base::rps_check_connection() )
			return false;

			// Base URL from user entered options
			$this->rps_base_url = RPS_Base::rps_return_option('url') . 'wp-json/wp/v2/';
		}

		/**
		* Retrieves the API data from the target site
		*
		* @param  $id - int - the ID of the data to retrieve from the API
		* @param  $type - string - type of data to retrieve from the API
		* @param  $filters - array - Array of API filters
		* @return  $api_return - array - Array of post data returned from API
		* @since    0.8.0
		*/
		public function rps_get_api_data($id = '', $type = 'posts', $filters = array()) {

			$resp = false;

			$id = (strlen($id) > 0 ? '/' . $id : $id);
			$filter_str = $this->rps_process_filters($filters);

			$url = $this->rps_base_url . $type . $id . $filter_str;

			$resp = wp_remote_get($url);

			if(is_wp_error( $resp )) {
				return false;
			}

			$api_return = json_decode( wp_remote_retrieve_body( $resp ));

			if(empty($api_return) || isset($api_return->data->status) && $api_return->data->status === 404)
			return false;

			return $api_return;
		}

		/**
		* Processes the API filter arguments passed as an array to retrieve data
		*
		* @param  $filters - array - Array of API filters
		* @since    0.8.0
		*/
		private function rps_process_filters($filters) {

			$fc = 0;
			$filter_str = '';

			if(is_array($filters)) {
				foreach($filters as $key => $filter) {
					if(is_array($filter)) {
						$filter = implode(",", $filter);
					}
					$filter_str .= ($fc === 0 ? '?' : '&') . $key . '=' . $filter;
					$fc++;
				}
			}

			return $filter_str;
		}
	}
	
endif;
