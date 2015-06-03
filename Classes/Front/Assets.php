<?php

	namespace Crouton\Front;

	use Cuisine\Utilities\Url;
	use Cuisine\Wrappers\Scripts;
	use Cuisine\Wrappers\Sass;
	use Crouton\Wrappers\StaticInstance;

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

			add_action( 'init', function(){

				//scripts:
				$url = Url::plugin( 'crouton', true ).'Assets/js/';
				//Scripts::register( 'crouton-script', $url.'Frontend.js', false );

				//sass:
				$url = 'crouton/Assets/sass/';
				//Sass::register( 'template', $url.'_template.scss', false );
			
			});
		}



	}

	if( !is_admin() )
		\Crouton\Front\Assets::getInstance();
