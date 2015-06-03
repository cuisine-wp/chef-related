<?php

    namespace Crouton\Wrappers;
    
    class StaticInstance {
    
        /**
         * Static bootstrapped instance.
         *
         * @var \Crouton\Wrappers\StaticInstance
         */
        public static $instance = null;
    
    
    
        /**
         * Init the Assets Class
         *
         * @return \Crouton\Admin\Assets
         */
        public static function getInstance(){
    
            return static::$instance = new static();
    
        }
    
    
    } 