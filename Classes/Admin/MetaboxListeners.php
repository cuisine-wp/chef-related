<?php

	namespace ChefRelated\Admin;

	use \ChefRelated\Wrappers\RelatedMetabox;
	use \Cuisine\Wrappers\Field;
	use \ChefRelated\Wrappers\StaticInstance;

	class MetaboxListeners extends StaticInstance{


		/**
		 * Init admin metaboxes
		 */
		function __construct(){

			$this->metaboxes();

		}


		/**
		 * Creates the metabox for this plugin
		 * 
		 * @return void
		 */
		private function metaboxes(){

			add_action( 'admin_init', function(){

				$fields = $this->getFields();
				$postTypes = apply_filters( 'chef_related_post_types', array( 'post' ) );

				foreach( $postTypes as $pt ){

					RelatedMetabox::make( 'Gerelateerd', $pt )->set( $fields );

				}

			});
		}

		/**
		 * Gets the fields for our metabox
		 * 
		 * @return array
		 */
		private function getFields(){

			return array(

				Field::postsearch( 
					'related', 					
					__( 'Berichten', 'chef-related' ),
					array(
						'label' 				=> 'top',		//display Label
						'defaultValue'			=> array()
					)
				)
				
			);

		}
		


	}

	if( is_admin() )
		\ChefRelated\Admin\MetaboxListeners::getInstance();
