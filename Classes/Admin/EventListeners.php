<?php

	namespace ChefRelated\Admin;

	use \Cuisine\Utilities\Url;
	use \Cuisine\Wrappers\Field;
	use \Cuisine\Wrappers\SettingsPage;
	use \ChefRelated\Wrappers\StaticInstance;

	class EventListeners extends StaticInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->listen();
			
			$this->settingsPage();

		}

		/**
		 * Listen for admin events
		 * 
		 * @return void
		 */
		private function listen(){


			add_action( 'init', function(){
				
				//register the custom field type "Post searcher"
				add_filter( 'cuisine_field_types', function( $arr ){

					$arr['postsearch'] = array(

						'name'		=> 'Post Zoeker',
						'class'		=> 'ChefRelated\\Admin\\PostSearchField'
					);

					return $arr;

				});

			});

		}

		/**
		 * The settingspage used by this plugin
		 * 
		 * @return void
		 */
		private function settingsPage(){
			
			$options = array(
				'parent'		=> 'page',
				'menu_title'	=> 'Related posts'
			);

			$fields = array(

				Field::checkbox( 
					'auto_fill_related', 
					'Vul gerelateerd automatisch',
					array(
						'defaultValue' => 'true'
					)
				),

				Field::number(
					'number_of_posts',
					'Maximaal aantal posts',
					array(
						'defaultValue' => 3
					)
				),

				Field::checkbox( 
					'only_if_no_related', 
					'Alleen als er geen gerelateerde zijn',
					array(
						'defaultValue' => 'false'
					)
				)
			);

			SettingsPage::make(

				'Related posts', 
				'related-posts-settings', 
				$options

			)->set( $fields );

		}

		/**
		 * Get all default settings in an array
		 * 
		 * @return array
		 *
		public function getDefaultSettings(){

			return array(
						
				'only_if_no_related'	=> 'false',
				'auto_fill_related'		=> 'true',
				'number_of_posts'		=> 3,
				'post_categories'		=> 'all'
			
			);

		}
		*/

	}

	if( is_admin() )
		\ChefRelated\Admin\EventListeners::getInstance();
