<?php

	namespace Crouton\Frontend;

	use \Cuisine\Utilities\Url;
	use \Crouton\Wrappers\StaticInstance;

	class EventListeners extends StaticInstance{



		/**
		 * Init events & vars
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

			add_action( 'wp_header', function(){

				$url = Url::plugin( 'crouton', true ).'Assets';

				wp_enqueue_script( 
					'test_script', 
					$url.'/js/Frontend.js'
				);

			});
		}



	}

	\Crouton\Frontend\EventListeners::getInstance();
