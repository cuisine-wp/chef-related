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

				$block_template = apply_filters('chef_related_block_template', $theme.$post_type);

				Template::element( $block_template, $default )->display();

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

		// TODO: Change location of the default settings | get plugin settings
		$settings = get_option( 'related-posts-settings', array(
						
			'only_if_no_related'	=> 'false',
			'auto_fill_related'		=> 'true',
			'number_of_posts'		=> 3,
			'post_categories'		=> 'all'
			
		) );

		$_related = self::getRelated();

		// get the posts categories
		$postCategories = $this->getPostCategories();
		$numberOfPosts = $settings['number_of_posts'];
		
		if( !$_related ) {

			if ( $settings['auto_fill_related'] == 'true' ) {

				return $this->getCategoryRelatedPosts($numberOfPosts, $postCategories);

			} else {
				return false;
			}

		}

		//related items are set, we can go on:
		$_related_ids = array_keys( Sort::pluck( $_related, 'id' ) );

		$args = array(

			'post__in' => $_related_ids,
			'posts_per_page' => count( $_related_ids ),

		);

		$result = new WP_Query( $args );

		if ( count( $_related ) < $settings['number_of_posts']  && ($settings['only_if_no_related'] == 'false') && ($settings['auto_fill_related'] == 'true') ) {
	
			$numberOfSupplements = $settings['number_of_posts'] - count( $_related );
			$excluded_posts = array_merge( array( $this->postId ), $_related_ids );
			
			$result = $this->getSupplementPosts( $result, $postCategories, $numberOfSupplements, $excluded_posts );
		}

		return $result;

	}

	/**
	 * @param  [array] Query result
	 * @param  [string] categories 
	 * @param  [int] number of posts to collect
	 * @param  [array] post_ids to exclude
	 * @return [array] Query results (merged)
	 */
	private function getSupplementPosts( $result, $postCategories, $numberOfSupplements, $excluded_posts ) {

		$supplementResults = new WP_Query ( array (
				'category_name' => $postCategories,
				'posts_per_page' => $numberOfSupplements,
				'post__not_in'=> $excluded_posts
			));

		if ( $supplementResults ) {
			// start putting the contents in the new object
			$result->posts = array_merge( $result->posts, $supplementResults->posts );
			// we also need to set post count correctly so as to enable the looping
			$result->post_count = count( $result->posts );
		}

		return $result;
	}

	/**
	 * @param  [int] number of posts to collect
	 * @return [array] Query result
	 */
	private function getCategoryRelatedPosts($numberOfPosts, $postCategories) {
		return new WP_Query( array( 
				'category_name' => $postCategories,
				'posts_per_page' => $numberOfPosts,
				'post__not_in'=> array($this->postId)
			));
	}

	
	/**
	 * @return [string] post categories separated bij comma
	 */
	private function getPostCategories() {

		$post_categories = wp_get_post_categories( $this->postId );
		$categoryString = '';

		foreach($post_categories as $c){

			$cat = get_category( $c );
			$categoryString .= $cat->slug . ',';

		}

		$categoryString = rtrim($categoryString, ',');

		return $categoryString;
	}



}