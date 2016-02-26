<?php

	namespace ChefRelated\Admin;

	use \Cuisine\Wrappers\PostType;
	use \ChefRelated\Wrappers\AjaxInstance;
	use \ChefRelated\Front\Settings;
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

				$this->setPostGlobal();

				//query caching
				global $relatedPostList;
				global $post;

				if( !isset( $relatedPostList ) ){
					
					$post_types = Settings::relatedPostTypes();
					$query = new WP_Query( 
						array( 
							'post_type' => $post_types, 
							'posts_per_page' => -1, 
							'post__not_in'=> array( $post->ID ),
							'post_status' => 'publish'
						)
					);
	
					$GLOBALS['relatedPostList'] = $query->posts;
					$return = $query->posts;				
				
				}else{
					$return = $relatedPostList;
				
				}


				//return the post-list
				echo json_encode( $return );
				die();

			});

		}
	}


	if( is_admin() )
		\ChefRelated\Admin\Ajax::getInstance();
