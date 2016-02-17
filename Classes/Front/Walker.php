<?php
namespace ChefRelated\Front;

use Cuisine\Wrappers\Template;
use Cuisine\Utilities\Sort;
use Cuisine\Utilities\Url;
use ChefRelated\Front\Settings;
use Cuisine\Utilities\Logger;
use ChefRelated\Database\DB;
use Exception;
use WP_Query;

/**
 * The Walker class fetches the related posts and gets the templates
 * 
 * @package ChefRelated\Front
 */
class Walker {


	var $postId;

	var $query;

	var $bidirectional;

	/**
	 * Set the postId for this Walker
	 */
	function __construct(){

		global $post;

		$this->bidirectional = (Settings::get( 'autoRelateBidirectional' ) == 'true' );

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

				// filter for setting custom templates for related content
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

		$_related = array();
		$relatedPosts = DB::get( $this->postId ,$this->bidirectional );
		
        foreach ($relatedPosts as $relatedPost) {
    		if ( $this->bidirectional ) {
    			if ( $relatedPost->related_post_id == $this->postId )
    				array_push($_related, unserialize($relatedPost->post_data));	
    		}
    		if ( $relatedPost->post_id == $this->postId )
    			array_push($_related, unserialize($relatedPost->related_post_data));	
    		
    	}
		//no related items set:
		if (!$_related)
			return false;

		return $_related;
	}



	/**
	 * Returns the query for this related block
	 * 
	 * @return WP_Query
	 */
	private function getQuery(){

		try {

			// log the settings
			Logger::message( Settings::toString() );

			// get the related posts
			$_related = self::getRelated();
			
			// if no related, check for supplement settings or return false
			if( !$_related ) {

				if ( Settings::get( 'autoSupplementRelated' ) == 'true' ) {
					$result = $this->getRelatedPosts( array( $this->postId ), Settings::get( 'numberOfPosts' ) );
					//cuisine_dump($result);
					return $result;

				} else {

					return false;

				}

			} else {

				// related items are set, we can go on:
				$_related_ids =  Sort::pluck( $_related, 'id' );


				// set query args
				$args = array(
		
					'post__in' => $_related_ids,
					'posts_per_page' => count( $_related_ids ),
		
				);
		
				// get the query result
				$result = new WP_Query( $args );
		
				// check if supplement is required and desired
				if ( ( count( $_related ) < Settings::get( 'numberOfPosts' ) )  && ( Settings::get( 'autoSupplementRelated' ) == 'true' ) && ( Settings::get( 'onlyIfNoRelatedFound' ) == 'false' ) ) {
					
					// set the posts which should be excluded
					$excluded_posts = array_merge( array( $this->postId ), $_related_ids );
					// get the supplement posts from the database and merge with the already aquired result
					$result = $this->getSupplementPosts( $result, $excluded_posts );

				}
		
				
				return $result;

			}
		} catch( Exception $ex) {
			Logger::error( $ex->getMessage() );
		}

	}



	/**
	 * @param  [array] Query result, passed so the supplement posts can be merged
	 * @param  [array] post_ids to exclude
	 * @return [array] Query results (merged)
	 */
	private function getSupplementPosts( $result, $excluded_posts ) {

		// check how many posts should be supplemented
		$numberOfSupplements = Settings::get( 'numberOfPosts' ) - $result->post_count;

		$supplementResults = $this->getRelatedPosts( $excluded_posts, $numberOfSupplements );

		// merge supplement
		if ( $supplementResults->have_posts() ) {
			// start putting the contents in the new object
			$result->posts = array_merge( $result->posts, $supplementResults->posts );
			// we also need to set post count correctly so as to enable the looping
			$result->post_count = count( $result->posts );
		}

		return $result;
	}

	

	/**
	 * Gets all related posts based on taxonomies and given params
	 * @param  [(int)array()] $excluded_posts post id's you want to exclude from collection
	 * @param  [int] $numberOfPosts  number of posts to collect
	 * @return [array] Query result
	 */
	private function getRelatedPosts( $excluded_posts, $numberOfPosts ) {

		// get the terms query
		$taxQuery = $this->getTaxQuery();
		if (!empty ( $taxQuery )) {
			// get posts with same category
			return new WP_Query( array( 
				'posts_per_page' => $numberOfPosts,
				'post__not_in'=> $excluded_posts,
				'tax_query' => $taxQuery
			));
		}else {
			// if no taxquery found, try to get posts of same posttype
			return new WP_Query( array( 
				'posts_per_page' => $numberOfPosts,
				'post__not_in'=> $excluded_posts,
				'post_type' => get_post_type ( $this->postId )
			));
		}
	}
	



	/**
	 * Gets the taonomy query based on tags, categories and taxonomies of the current post
	 * @return [array] the taxquery to add to WP_Query
	 */
	private function getTaxQuery() {

		// get the post taxonomies
		$taxonomies = get_post_taxonomies( $this->postId );

		// if no taxonomies found
		if( empty($taxonomies) ) {
			Logger::message( 'no taxonomies found for relating posts' );
			return array();

		} else {

			// get all the post terms with the used taxonomies
			$postTerms = wp_get_post_terms( $this->postId, $taxonomies );

			// if no postterms found
			if( empty($postTerms) ) {
				Logger::message( 'no terms found for relating posts' );
				return array();
			} else {
			
				// create the taxquery args
				$taxQueries = array( 'relation' => 'OR' );

				foreach ( $taxonomies as $taxonomy ) {
					
					// add termIds to array
					$termIds = array();
					foreach ( $postTerms as $term ) {
						
						if( $term->taxonomy == $taxonomy ) 
							array_push( $termIds, $term->term_id );

					}

					// add a new query arg array
					if( !empty( $termIds ) ) {
						$taxQuery = array(
							'taxonomy' => $taxonomy,
							'field' => 'id',
							'terms' => $termIds,
							'operator' => 'IN'
						);

						array_push( $taxQueries, $taxQuery );
					}

				}

				// return complete taxQuery
				return array( array( 
					'relation' => 'AND',
					$taxQueries
				));
			}
		}

	}

}