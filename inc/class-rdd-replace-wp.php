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

			add_filter( 'the_posts', array($this, 'rdd_replace_post_data'), 10, 3 );
		}

		/**
		* Executed just after the posts are selected from wp_query.
		*
		* @since    1.0.0
		*/
		public function rdd_replace_post_data($posts, $query = false) {
			if(is_admin() || !is_main_query())
				return $posts;

			if(is_single()) {
				$post = $posts[0];
				$rdd_id = get_transient('rdd_post_match_' . $post->ID);

				if($rdd_id && strlen($rdd_id) > 0) {
					$rddp = $this->rdd_get_posts($rdd_id);

					$post->post_content = $rddp->content->rendered;
					$post->post_title = $rddp->title->rendered;
					$post->post_date = $rddp->date;

					$posts[0] = $post;

					return $posts;
				}
			}

			// Get the total post count queried
			$post_count = count($posts);
			$new_post_ids = array();
			$og_post_ids = array();

			foreach($posts as $post) {
				$rdd_id = get_transient('rdd_post_match_' . $post->ID);

				if($rdd_id || strlen($rdd_id) > 0) {

					$new_post_ids[] = $rdd_id;

					$rddp = $this->rdd_get_posts($rdd_id);

					$post->post_content = $rddp->content->rendered;
					$post->post_title = $rddp->title->rendered;
					$post->post_date = $rddp->date;

				} else {
					// Get posts from the API endpoint
					if(!empty($new_post_ids)) {
						$rddp = $this->rdd_get_posts(null, array('per_page' => $post_count, 'exclude' => $new_post_ids));
					} else {
						$rddp = $this->rdd_get_posts(null, array('per_page' => $post_count));
					}

					// Set the array for the API posts
					$rdd_posts = array();

					// Set the new post count variable for the array
					// Loop through returned API posts and assign new array data
					$npc = 0;
					foreach($rddp as $rdd_post) {
						$npc++;
						$rdd_posts[$npc]['post_id'] = $rdd_post->id;
						$rdd_posts[$npc]['post_content'] = $rdd_post->content->rendered;
						$rdd_posts[$npc]['post_title'] = $rdd_post->title->rendered;
						$rdd_posts[$npc]['post_date'] = $rdd_post->date;
						$rdd_posts[$npc]['post_excerpt'] = $rdd_post->excerpt->rendered;

						$new_post_ids[] = $rdd_posts[$npc]['post_id'];
					}

					// Reset the new post count variable for the second array
					// Loop through original post object and replace data with returned API data
					$npc = 0;
					foreach($posts as $post) {
						$npc++;
						$post->post_title = $rdd_posts[$npc]['post_title'];
						$post->post_content = $rdd_posts[$npc]['post_content'];
						$post->post_date = $rdd_posts[$npc]['post_date'];
						$post->post_excerpt = $rdd_posts[$npc]['post_excerpt'];

						set_transient( 'rdd_post_match_' . $post->ID, $rdd_posts[$npc]['post_id'], 100 * DAY_IN_SECONDS );
					}
				}
			}

			return $posts;
		}
	}

	new RDD_Replace_WP();

endif;
