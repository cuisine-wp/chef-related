<?php

	namespace ChefRelated\Admin;

	use \Cuisine\Utilities\Url;
	use \Cuisine\Wrappers\Field;
	use \ChefRelated\Database\DB;
	use \ChefRelated\Wrappers\StaticInstance;

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

			add_action( 'init', function(){
				
				//register the custom field type "Post searcher"
				add_filter( 'cuisine_field_types', function( $arr ){

					$arr['postsearch'] = array(

						'name'		=> 'Post Zoeker',
						'class'		=> 'ChefRelated\\Admin\\PostSearchField'
					);

					return $arr;

				});

				//create table if table doesn't exists
				$tableExists =  get_option( 'chef_related_posts_table_installed', false );

				if( !$tableExists )
					DB::install();

			});

		}

	}

	if( is_admin() )
		\ChefRelated\Admin\EventListeners::getInstance();
