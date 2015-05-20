<?php

	namespace Crouton\Admin;

	use \Cuisine\Wrappers\Metabox;
	use \Cuisine\Wrappers\Field;

	class Metaboxes{

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

			$this->initBoxes();

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
		 * Creates the metaboxes for this plugin
		 * 
		 * @return void
		 */
		private function initBoxes(){

			$fields = $this->getFields();
			Metabox::make( 'A Metabox', 'post' )->set( $fields );

		}

		/**
		 * Gets the fields for our metabox
		 * 
		 * @return array
		 */
		private function getFields(){

			return array(
				Field::media( 
					'images', 
					'Afbeeldingen',
					array(
						'label' 				=> 'top',
						'defaultValue'			=> array(),
					)
				),
			);

		}
		


	}

	if( is_admin() )
		\Crouton\Admin\Metaboxes::getInstance();
