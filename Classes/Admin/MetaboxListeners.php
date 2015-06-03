<?php

	namespace Crouton\Admin;

	use \Cuisine\Wrappers\Metabox;
	use \Cuisine\Wrappers\Field;
	use \Crouton\Wrappers\StaticInstance;

	class MetaboxListeners extends StaticInstance{


		/**
		 * Init admin metaboxes
		 */
		function __construct(){

			$this->metaboxes();

		}


		/**
		 * Creates the metaboxes for this plugin
		 * 
		 * @return void
		 */
		private function metaboxes(){

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
					'images', 					//id
					'Afbeeldingen',				//Label
					array(
						'label' 				=> 'top',		//display Label
						'defaultValue'			=> array()
					)
				)
				
			);

		}
		


	}

	if( is_admin() )
		\Crouton\Admin\MetaboxListeners::getInstance();
