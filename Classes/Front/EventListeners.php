<?php

	namespace Crouton\Frontend;

	use \Cuisine\Utilities\Url;
	use \Crouton\Wrappers\StaticInstance;

	class EventListeners extends StaticInstance{


		/**
		 * Init events & vars
		 */
		function __construct(){

			$this->listen();

		}


		/**
		 * Listen to front-end events
		 * 
		 * @return void
		 */
		private function listen(){

			add_action( 'init', function(){

				//do something

			});
		}



	}

	\Crouton\Frontend\EventListeners::getInstance();
