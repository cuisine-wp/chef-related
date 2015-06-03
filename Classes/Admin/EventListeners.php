<?php

	namespace Crouton\Admin;

	use \Cuisine\Utilities\Url;
	use \Crouton\Wrappers\StaticInstance;

	class EventListeners extends StaticInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->listen();

		}

		/**
		 * Listen for admin events
		 * 
		 * @return void
		 */
		private function listen(){


			add_action( 'admin_init', function(){
				
				//do something

			});

		}



	}

	if( is_admin() )
		\Crouton\Admin\EventListeners::getInstance();
