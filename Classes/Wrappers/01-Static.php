<?php

    namespace ChefRelated\Wrappers;
    
    class StaticInstance {
    
        /**
         * Static bootstrapped instance.
         *
         * @var \ChefRelated\Wrappers\StaticInstance
         */
        public static $instance = null;
    
    
    
        /**
         * Init the Assets Class
         *
         * @return \ChefRelated\Admin\Assets
         */
        public static function getInstance(){
    
            return static::$instance = new static();
    
        }
    
    
    } 