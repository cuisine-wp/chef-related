<?php

	namespace ChefRelated\Admin;

	use \Cuisine\Utilities\Url;
	use \ChefRelated\Wrappers\StaticInstance;

	class Assets extends StaticInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->enqueues();

		}

		/**
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function enqueues(){


			add_action( 'admin_menu', function(){


				$url = Url::plugin( 'chef-related', true ).'Assets';

				//enqueue a script
				//wp_enqueue_script( 'chef_related', $url.'/js/Admin.js' );
				wp_enqueue_script( 'chef_related_post_search', $url.'/js/PostSearch.js' );

				//enqueue a stylesheet:
				wp_enqueue_style( 'related-style', $url, '/css/admin.css' );
				
			});
		}



	}

	if( is_admin() )
		\ChefRelated\Admin\Assets::getInstance();
