<?php

	namespace ChefRelated\Admin;

	use \Cuisine\Wrappers\PostType;
	use \ChefRelated\Wrappers\AjaxInstance;
	use \WP_Query;

	class Ajax extends AjaxInstance{

		/**
		 * Init admin ajax events:
		 */
		function __construct(){

			$this->listen();

		}

		/**
		 * All backend-ajax events for this plugin
		 * 
		 * @return string, echoed
		 */
		private function listen(){


			add_action( 'wp_ajax_fetchPostList', function(){

				//query caching
				global $postList;

				if( !isset( $postList ) ){
					
					$post_types = PostType::get();
					$query = new WP_Query( array( 'post_types' => $post_types, 'posts_per_page' => 	-1 ) );
	
					$GLOBALS['postList'] = $query->posts;
					$return = $query->posts;				
				
				}else{
					$return = $postList;
				
				}


				//return the post-list
				echo json_encode( $return );
				die();
			});

		}
	}


	if( is_admin() )
		\ChefRelated\Admin\Ajax::getInstance();
