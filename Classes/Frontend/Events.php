<?php

	namespace Crouton\Frontend;

	use \Cuisine\Utilities\Url;

	class Events{

		/**
		 * Frontend Events bootstrap instance.
		 *
		 * @var \Cuisine
		 */
		private static $instance = null;


		/**
		 * Init events & vars
		 */
		function __construct(){

			$this->enqueues();

		}

		/**
		 * Init the Frontend Event class
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

	\Crouton\Frontend\Events::getInstance();
