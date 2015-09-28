<?php
namespace ChefRelated\Front;

use Cuisine\Wrappers\Template;
use Cuisine\Utilities\Sort;
use Cuisine\Utilities\Url;
use WP_Query;

/**
 * The Walker class fetches the related posts and gets the templates
 * 
 * @package ChefRelated\Front
 */
class Walker {


	var $postId;

	var $query;

	/**
	 * Set the postId for this Walker
	 */
	function __construct(){

		global $post;

		if( !isset( $post ) )
			return false;


		$this->postId = $post->ID;
		$this->query = $this->getQuery();

		if( !$this->query )
			return false;

	}


	/**
	 * Walk through all related items, and get there templates:
	 * 
	 * @return string (html)
	 */
	public function walk(){

		$default = self::defaultTemplate();
		$theme = 'blocks/';



		ob_start();
		
		if( $this->query && $this->query->have_posts() ){

			while( $this->query->have_posts() ){

				$this->query->the_post();
				$post_type = get_post_type();

				Template::element( $theme.$post_type, $default )->display();

			}
		}


		return ob_get_clean();

	}


	/**
	 * Locate the default template for related posts
	 * 
	 * @return string ( path )
	 */
	private function defaultTemplate(){

		$path = Url::path( 'plugin', 'chef-related/Assets/template.php', false );
		return $path; 

	}



	/**
	 * Returns the related posts, if they are set
	 * 
	 * @return mixed ( array or bool )
	 */
	public function getRelated(){


		$_related = get_post_meta( $this->postId, 'related', true );

		//no related items set:
		if( !$_related )
			return false;


		return $_related;
	}


	/**
	 * Returns the query for this related block
	 * 
	 * @return WP_Query
	 */
	private function getQuery(){

		$_related = self::getRelated();
		
		if( !$_related )
			return false;


		//related items are set, we can go on:
		$_related_ids = array_keys( Sort::pluck( $_related, 'id' ) );

		$args = array(

			'post__in' => $_related_ids,
			'posts_per_page' => count( $_related_ids )
		);

		return new WP_Query( $args );

	}



}