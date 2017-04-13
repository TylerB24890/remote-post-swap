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
		}

		/**
		* Executed just after the posts are selected from wp_query.
		* @param  $posts - object - WP Post Object
		* @param  $query - object - WP SQL Query Object
		* @return	$posts - object - WP Post Object
		* @since    0.5.0
		*/
		public function rps_swap_post_data($posts, $query = false) {

			if(is_admin() || !is_main_query())
			return $posts;

			if(is_single()) {
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
			$rps_id = get_transient('rps_post_match_' . $post->ID);

			$rpsp = $this->rps_get_posts($rps_id);

			$post->post_content = $rpsp->content->rendered;
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
			$new_post_ids = array();
			$npc = 0;

			foreach($posts as $post) {
				$npc++;
				$rps_id = get_transient('rps_post_match_' . $post->ID);

				if($rps_id || strlen($rps_id) > 0) {

					$new_post_ids[] = $rps_id;

					$post = $this->rps_swap_single_post($post, $rps_id);

				} else {
					// Get posts from the API endpoint
					if(!empty($new_post_ids)) {
						$rpsp = $this->rps_get_posts(null, array('per_page' => $post_count, 'exclude' => $new_post_ids));
					} else {
						$rpsp = $this->rps_get_posts(null, array('per_page' => $post_count));
					}

					// Set the array for the API posts
					$rps_posts = array();

					// Set the new post count variable for the array
					// Loop through returned API posts and assign new array data
					foreach($rpsp as $rps_post) {
						$rps_posts[$npc]['post_id'] = $rps_post->id;
						$rps_posts[$npc]['post_content'] = $rps_post->content->rendered;
						$rps_posts[$npc]['post_title'] = $rps_post->title->rendered;
						$rps_posts[$npc]['post_date'] = $rps_post->date;
						$rps_posts[$npc]['post_excerpt'] = $rps_post->excerpt->rendered;

						$new_post_ids[] = $rps_posts[$npc]['post_id'];
					}

					// Reset the new post count variable for the second array
					// Loop through original post object and replace data with returned API data
					$post->post_title = $rps_posts[$npc]['post_title'];
					$post->post_content = $rps_posts[$npc]['post_content'];
					$post->post_date = $rps_posts[$npc]['post_date'];
					$post->post_excerpt = $rps_posts[$npc]['post_excerpt'];

					set_transient( 'rps_post_match_' . $post->ID, $rps_posts[$npc]['post_id'], 100 * DAY_IN_SECONDS );
				}
			}

			return $posts;
		}
	}

	new RPS_Replace_WP();

endif;
