<?php

/**
* Remote Post Swap replace standard WP functions with API data
*
* @author 	Tyler Bailey
* @version 0.5.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS_Replace_WP')) :

	class RPS_Replace_WP extends RPS_Retrieve_Data {


		/**
		* Executed on class istantiation.
		*
		* @since    0.5.0
		*/
		public function __construct() {
			parent::__construct();

			if($this->rps_check_connection())
			add_filter( 'the_posts', array($this, 'rps_swap_post_data'), 10, 3 );
			add_filter('post_thumbnail_html', array($this, 'rps_swap_post_thumbnail'), 99, 5);
		}

		/**
		* Executed just after the posts are selected from wp_query.
		* @param  $posts - object - WP Post Object
		* @param  $query - object - WP SQL Query Object
		* @return	$posts - object - WP Post Object
		* @since    0.5.0
		*/
		public function rps_swap_post_data($posts, $query = false) {

			if(is_admin())
			return $posts;

			if(is_single() && $this->rps_get_post_meta($posts[0]->ID)) {
				$posts = $this->rps_swap_single_post($posts[0]);
			} else {
				$posts = $this->rps_swap_loop_posts($posts);
			}

			return $posts;
		}

		/**
		* Swap a single posts content with it's API partner
		*
		* @param  $post - object - WP Post Object
		* @param  $rps_id - int - ID of the remote post to retrieve
		* @return  $posts - WP Post Object
		* @since    0.5.0
		*/
		private function rps_swap_single_post($post, $rps_id = NULL) {

			if($rps_id === NULL)
			$rps_id = $this->rps_get_post_meta($post->ID);

			$rpsp = $this->rps_get_posts($rps_id);

			$post->post_content = $this->rps_adjust_media_urls($rpsp->content->rendered);
			$post->post_title = $rpsp->title->rendered;
			$post->post_date = $rpsp->date;

			$posts[0] = $post;

			return $posts;
		}

		/**
		* Swap the post content within the loops
		*
		* @param  $posts - object - WP Posts Object
		* @return  $posts - WP Post Object
		* @since    0.5.0
		*/
		private function rps_swap_loop_posts($posts) {
			// Get the total post count queried
			$post_count = count($posts);
			$npc = 0;

			foreach($posts as $post) {
				$npc++;
				$rps_id = $this->rps_get_post_meta($post->ID);

				if($rps_id) {

					$post = $this->rps_swap_single_post($post, $rps_id);

				} else {

					$rpsp = $this->rps_get_posts(null, array('per_page' => $post_count));

					if($rpsp) {
						// Set the array for the API posts
						$rps_posts = array();

						// Set the new post count variable for the array
						// Loop through returned API posts and assign new array data
						foreach($rpsp as $rps_post) {
							if($this->rps_ensure_unqiue_meta($rps_post->id)) {
								$rps_posts[$npc]['post_id'] = $rps_post->id;
								$rps_posts[$npc]['post_content'] = $this->rps_adjust_media_urls($rps_post->content->rendered);
								$rps_posts[$npc]['post_title'] = $rps_post->title->rendered;
								$rps_posts[$npc]['post_date'] = $rps_post->date;
								$rps_posts[$npc]['post_excerpt'] = $rps_post->excerpt->rendered;
							}
						}

						// Reset the new post count variable for the second array
						// Loop through original post object and replace data with returned API data
						$post->post_title = $rps_posts[$npc]['post_title'];
						$post->post_content = $rps_posts[$npc]['post_content'];
						$post->post_date = $rps_posts[$npc]['post_date'];
						$post->post_excerpt = $rps_posts[$npc]['post_excerpt'];

						update_post_meta($post->ID, $this->rps_meta, $rps_posts[$npc]['post_id']);
					}
				}
			}

			return $posts;
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

			$rps_pid = $this->rps_get_post_meta($post_id);
			$rpsp_img_url = false;

			if($size == 'post-thumbnail')
			$size = 'thumbnail';

			if($rps_pid) {
				$rpsp = $this->rps_get_posts($rps_pid);
				$rpsp_img_id = $rpsp->featured_media;
				$rpsp_img = $this->rps_get_media($rpsp_img_id);

				if(isset($rpsp_img->media_details))
				$rpsp_img_url = $rpsp_img->media_details->sizes->$size->source_url;
			}

			if($rpsp_img_url)
		    $html = '<img src="' . $rpsp_img_url . '" />';

		    return $html;
		}

		/**
		* Ensure the remote API post has NOT been used on another post (unique meta values)
		*
		* @param  $rps_id - int - ID of remote post
		* @return  bool
		* @since    0.5.0
		*/
		private function rps_ensure_unqiue_meta($rps_id) {
			$args = array(
				'posts_per_page' => -1,
				'meta_key' => $this->rps_meta,
				'meta_value' => $rps_id
			);
			$rpsq = new WP_Query( $args );

			if($rpsq->have_posts()) {
				return false;
			}

			return true;
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
		private function rps_adjust_media_urls($content) {
			// Find all 'src' tags
			$content = preg_replace_callback('~((src)\s*=\s*[\"\'])([^\"\']+)~i', function($x) {
				$url = $x[3];

				// If src attribute does NOT have 'http', add the entered site URL
				if(strpos($url, 'http') === false)
				$url = $this->rps_return_url() . $url;

				return $x[1] . $url;
			}, $content);

			return $content;
		}
	}

	new RPS_Replace_WP();

endif;
