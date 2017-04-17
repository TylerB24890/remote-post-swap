<?php

/**
* Remote Post Swap Post Media Adjustments
*
* Changes the post featured image out with the returned featured image from the API
* Provides a function for ensuring the proper URLs for embedded images
*
* @author 	Tyler Bailey
* @version 0.7.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

namespace RPS;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS\RPS_Post_Media')) :

	class RPS_Post_Media {

		/**
		* Executed on class istantiation.
		*
		* @since    0.5.0
		*/
		public function __construct() {
			if(RPS_Base::rps_return_option('rps_post_media'))
			add_filter('post_thumbnail_html', array($this, 'rps_swap_post_thumbnail'), 99, 5);
		}

		/**
		* Swap the post thumbnail with the remote thumbnail
		*
		* @param  $html - string - HTML markup generated by WP
		* @param  $post_id - int - ID of the post to modify
		* @param  $post_thumbnail_id - int - ID of the post thumbnail we are changing
		* @param  $size - string - Size of photo to grab from the remote site
		* @param  $attr - array - Array of thumbnail attributes
		* @return  $html - string - Modified HTML markup with new image from API
		* @since    0.5.0
		*/
		public function rps_swap_post_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr) {

			$rps_pid = RPS_Base::rps_get_post_meta($post_id);
			$rpsp_img_url = false;

			if($size == 'post-thumbnail')
			$size = 'thumbnail';

			if($rps_pid) {
				$rpsp_obj = new RPS_Retrieve_Data;
				$rpsp = $rpsp_obj->rps_get_posts($rps_pid);
				$rpsp_img_id = $rpsp->featured_media;
				$rpsp_img = $rpsp_obj->rps_get_media($rpsp_img_id);

				if(isset($rpsp_img->media_details))
				$rpsp_img_url = $rpsp_img->media_details->sizes->$size->source_url;
			}

			if($rpsp_img_url)
		    $html = '<img src="' . $rpsp_img_url . '" />';

		    return $html;
		}


		/**
		* Fix embedded images from API that have relative URLs.
		*
		* This will add an absolute URL from the RPS option page.
		*
		* @param  $content - string - Full markup/content of post
		* @return  $content - string - Full markup/content of post with edited URLs
		* @since    0.5.0
		*/
		public static function rps_adjust_media_urls($content) {
			// Find all 'src' tags
			$content = preg_replace_callback('~((src)\s*=\s*[\"\'])([^\"\']+)~i', function($x) {
				$url = $x[3];

				// If src attribute does NOT have 'http', add the entered site URL
				if(strpos($url, 'http') === false)
				$url = RPS_Base::rps_return_option('rps_url') . $url;

				return $x[1] . $url;
			}, $content);

			return $content;
		}
	}

	new RPS_Post_Media();

endif;
