<?php

	namespace Crouton\Admin;

	use \stdClass;
	use \Crouton\Wrappers\AjaxInstance;

	class Ajax extends AjaxInstance{

		/**
		 * Init admin events
		 */
		function __construct(){

			$this->listen();

		}

		/**
		 * All ajax events for sections on the backend
		 * 
		 * @return string, echoed
		 */
		private function listen(){


			//boilerplate:
			add_action( 'wp_ajax_actionName', function(){

				$this->setPostGlobal();


				die();

			});
		}
	}


	if( is_admin() )
		\Crouton\Admin\Ajax::getInstance();
