<?php

	namespace ChefRelated\Admin;

	use \Cuisine\Wrappers\Field;
	use \Cuisine\Wrappers\SettingsPage;
	use \ChefRelated\Front\Settings;
	use \ChefRelated\Wrappers\StaticInstance;

	class SettingsPageBuilder extends StaticInstance{

		/**
		 * Init admin events & vars
		 */
		function __construct(){

			$this->settingsPage();

		}


		/**
		 * The settingspage used by this plugin
		 * 
		 * @return void
		 */
		private function settingsPage(){


			$fields = $this->getSettingsFields();
			$options = array(
				'parent'		=> 'edit.php',
				'menu_title'	=> __( 'Gerelateerde posts', 'chefrelated' )
			);
	
	
			SettingsPage::make(
	
				__( 'Instellingen voor gerelateerde posts', 'chefrelated' ), 
				'chef-related-settings', 
				$options
	
			)->set( $fields );

		}



		/**
		 * Return all fields for the Mailchimp settings page:
		 * 
		 * @return array
		 */
		private function getSettingsFields(){

			$options = array(
				'parent'		=> 'page',
				'menu_title'	=> 'Related posts'
			);

			$fields = array(

				Field::checkbox( 
					'autoSupplementRelated', 
					'Vul gerelateerd automatisch aan',
					array(
						'defaultValue' => Settings::get( 'autoSupplementRelated')
					)
				),

				Field::number(
					'numberOfPosts',
					'Maximaal aantal posts',
					array(
						'defaultValue' => Settings::get( 'numberOfPosts')
					)
				),

				Field::checkbox( 
					'onlyIfNoRelatedFound', 
					'Alleen aanvullen als er geen gerelateerde posts zijn',
					array(
						'defaultValue' => Settings::get( 'onlyIfNoRelatedFound')
					)
				),
				Field::checkbox( 
					'autoRelateBidirectional', 
					'Relateer automatisch twee richtingen',
					array(
						'defaultValue' => Settings::get( 'autoRelateBidirectional')
					)
				)
			);

			return $fields;

		}

	}

	if( is_admin() )
		\ChefRelated\Admin\SettingsPageBuilder::getInstance();