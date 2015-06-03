<?php

	namespace Crouton\Admin;

	use \Cuisine\Utilities\Url;
	use \Crouton\Wrappers\StaticInstance;

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


				$url = Url::plugin( 'crouton', true ).'Assets';

				//enqueue a script
				wp_enqueue_script( 'crouton_admin', $url.'/js/Admin.js' );

				//enqueue a stylesheet:
				wp_enqueue_style( 'crouton_style', $url, '/css/admin.css' );
				
			});
		}



	}

	if( is_admin() )
		\Crouton\Admin\Assets::getInstance();
