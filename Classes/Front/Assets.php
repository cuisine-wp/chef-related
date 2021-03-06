<?php

	namespace ChefRelated\Front;

	use Cuisine\Utilities\Url;
	use Cuisine\Wrappers\Script;
	use Cuisine\Wrappers\Sass;
	use ChefRelated\Wrappers\StaticInstance;

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

				//javascript files loaded in the frond-end:
			//	$url = Url::plugin( 'crouton', true ).'Assets/js/';

				// id - url (without .js) - autoload
			//	Script::register( 'crouton-script', $url.'Frontend', false );

				//sass files loaded in the front-end:
			//	$url = 'crouton/Assets/sass/';
				
				// id - url (without .scss ) - force-overwrite
			//	Sass::register( 'template', $url.'_template', false );
			
			});
		}



	}

	if( !is_admin() )
		\ChefRelated\Front\Assets::getInstance();
