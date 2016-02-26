<?php

	namespace ChefRelated\Front;

	class Settings{


		/**
		 * Get a setting
		 * 
		 * @param  string $key
		 * @return mixed
		 */
		public static function get( $key ){

			$settings = self::findSettings();

			if( isset( $settings[ $key ] ) )
				return $settings[ $key ];

			return false;

		}


		/**
		 * Get the settings
		 * 
		 * @return array
		 */
		private static function findSettings(){

			$defaults = array(
						
				'autoSupplementRelated'		=> 'true',
				'numberOfPosts'				=> 3,
				'onlyIfNoRelatedFound'		=> 'false',
				'autoRelateBidirectional' 	=> 'false'
		
			);


			return get_option( 'chef-related-settings', $defaults );

		}


		/**
		 * Return all post types that can relate to eachother
		 * 
		 * @return array
		 */
		public static function relatedPostTypes(){
			return apply_filters( 'chef_related_post_types', array( 'post' ) );
		}
		

		/**
		 * Get the settings as a string (for logging purposses)
		 * 
		 * @return string
		 */
		public static function toString( ){

			$settingsString = '';

			$settings = self::findSettings();

			foreach ( $settings as $key => $value ) {
				$settingsString .= $key . ' : ' . $value . ', ';
			}

			$settingsString = rtrim( $settingsString, ', ' );

			return $settingsString;

		}

	}