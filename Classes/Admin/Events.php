<?php

	namespace Crouton\Admin;

	use \Cuisine\Utilities\Url;

	class Events{

		/**
		 * Bootstrap the Backend events instance .
		 *
		 * @var \Cuisine
		 */
		private static $instance = null;


		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->adminEnqueues();

		}

		/**
		 * Init the instance:
		 *
		 * @return \Crouton
		 */
		public static function getInstance(){

		    if ( is_null( static::$instance ) ){
		        static::$instance = new static();
		    }
		    return static::$instance;
		}


		/**
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function adminEnqueues(){

			
			add_action( 'admin_init', function(){
				
				global $pagenow;

				if( $pagenow == 'post.php' || $pagenow == 'post-new.php' || $pagenow == 'page.php' || $pagenow == 'page-new.php' ){
					wp_enqueue_media();
				}

			});

			add_action( 'admin_menu', function(){

				$url = Url::plugin( 'crouton', true ).'Assets';
				wp_enqueue_script( 
					'crouton_admin', 
					$url.'/js/Admin.js', 
				);

				
			});
		}



	}

	if( is_admin() )
		\Crouton\Admin\Events::getInstance();
