<?php

/**
* Remote Post Swap replace standard WP functions with API data
*
* @author 	Tyler Bailey
* @version 0.7.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

namespace RPS;
use \WP_Query;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('RPS\RPS_Replace_WP')) :

	class RPS_Replace_WP extends RPS_Retrieve_Data {

		/**
		* Executed on class istantiation.
		*
		* @since    0.5.0
		*/
		public function __construct() {
			parent::__construct();

			if(RPS_Base::rps_check_connection()) {
				add_filter( 'the_posts', array($this, 'rps_swap_post_data'), 10, 3 );

				new RPS_Post_Media();
			}
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

			if(is_single() && RPS_Base::rps_get_post_meta($posts[0]->ID)) {
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
			$rps_id = RPS_Base::rps_get_post_meta($post->ID);

			$rpsp = $this->rps_get_posts($rps_id);

			$post->post_content = RPS_Post_Media::rps_adjust_media_urls($rpsp->content->rendered);
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
			// Array of already saved post IDs from the API
			$rps_retrieve = array();

			foreach($posts as $post) {
				$rps_id = RPS_Base::rps_get_post_meta($post->ID);

				if($rps_id) {
					$rps_retrieve[] = $rps_id;
				}
			}

			// Number of items in the rps_retrieve array
			$rps_count = count($rps_retrieve);

			// Number of posts we need to query from the API
			// Total number of posts from wp_query - number of posts in rps array
			$num_posts = ($post_count - $rps_count);

			// Default argument for API request
			$rps_args = array(
				'per_page' => $post_count
			);

			if($num_posts > 0) {
				if($rps_count > 0) {
					$rps_args = array(
						'per_page' => $num_posts,
						'exclude' => $rps_retrieve
					);

					$rps_saved = $this->rps_get_posts(null, array('include' => $rps_retrieve));
				} else {
					$rps_args = array(
						'per_page' => $num_posts,
					);
				}
			} else {
				$rps_args = array(
					'include' => $rps_retrieve
				);
			}

			// Get the posts from the API
			$rpsp = $this->rps_get_posts(null, $rps_args);

			// If we got new posts + retrieved saved posts, merge the array
			if(isset($rps_saved) && !empty($rps_saved))
				$rpsp = array_merge($rps_saved, $rpsp);

			// If we have any new posts at all
			if($rpsp && !empty($rpsp)) {
				// Set the array for the API posts
				$rps_posts = array();

				// Set the "New Post Counter" variable
				$npc = 0;

				// Loop through returned API posts and assign new array data
				foreach($rpsp as $rps_post) {

					$rps_posts[$npc]['post_id'] = $rps_post->id;
					$rps_posts[$npc]['post_title'] = $rps_post->title->rendered;
					$rps_posts[$npc]['post_excerpt'] = $rps_post->excerpt->rendered;
					$rps_posts[$npc]['post_content'] = RPS_Post_Media::rps_adjust_media_urls($rps_post->content->rendered);
					$rps_posts[$npc]['post_date'] = $rps_post->date;

					$npc++;
				}

				// Set the "Original Post Counter" variable
				$opc = 0;
				foreach($posts as $post) {

					// Loop through original post object and replace data with returned API data
					if(RPS_Base::rps_return_option('post_title'))
					$post->post_title = $rps_posts[$opc]['post_title'];

					if(RPS_Base::rps_return_option('post_content'))
					$post->post_content = $rps_posts[$opc]['post_content'];

					if(RPS_Base::rps_return_option('post_date'))
					$post->post_date = $rps_posts[$opc]['post_date'];

					if(RPS_Base::rps_return_option('post_excerpt'))
					$post->post_excerpt = $rps_posts[$opc]['post_excerpt'];

					if($this->rps_ensure_unqiue_meta($rps_posts[$opc]['post_id']))
					update_post_meta($post->ID, RPS_Base::$rps_meta, $rps_posts[$opc]['post_id']);

					$opc++;
				}
			}

			return $posts;
		}


		/**
		* Ensure the remote API post has NOT been used on another post (unique meta values)
		*
		* @param  $rps_id - int - ID of remote post
		* @return  bool
		* @since    0.5.0
		*/
		private function rps_ensure_unqiue_meta($rps_id) {

			$rps_id = (int) $rps_id;

			$args = array(
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' => RPS_Base::$rps_meta,
						'value' => $rps_id
					)
				),
				'fields' => 'ids'
			);

			$rpsq = new WP_Query( $args );

			$rpsq_arr = $rpsq->posts;

			if(!empty($rpsq_arr)) {
				return false;
			}

			wp_reset_query();

			return true;
		}
	}

	new RPS_Replace_WP();

endif;
