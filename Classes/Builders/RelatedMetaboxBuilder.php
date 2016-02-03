<?php
namespace ChefRelated\Builders;

use Cuisine\Builders\MetaboxBuilder;
use Cuisine\Utilities\Session;
use Cuisine\Utilities\User;
use ChefRelated\Database\DB;
use ChefRelated\Front\Settings;
use Cuisine\Utilities\Logger;


class RelatedMetaboxBuilder Extends MetaboxBuilder{

	/**
	 * The wrapper install method. Save container values.
	 *
	 * @param int $postId The post ID value.
	 * @return void
	 */
	public function save( $postId ){

		// do not save this field on WordPress autosave
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	    // check the nonce
	    $nonceName = (isset($_POST[Session::nonceName])) ? $_POST[Session::nonceName] : Session::nonceName;
	    if (!wp_verify_nonce($nonceName, Session::nonceAction)) return;

	    // Check user capability.
	    if ( $this->check && $this->data['postType'] === $_POST['post_type'] ){

	        if ( !$this->user->can( $this->capability ) ) {

	        	Logger::message('User had no rights to update/save this post');
	        	return;

	        }

	    }
	    
	    // check if $_POST isset else log an error
	    if( isset($_POST) ) {

	    	// check wheter the relationship is bi-directional
	    	$bidirectional = (Settings::get( 'autoRelateBidirectional' ) == 'true' );

	    	// set the array for the current post (for serializing to DB)
			$postArray = array(
				'id' => $postId,
				'title' => ( isset($_POST['post_title']) ) ? $_POST['post_title'] : -1,
				'type' => ( isset($_POST['post_type']) ) ? $_POST['post_type'] : -1,
				'position' => 1
			);

			// remove all current relations of this post
	    	DB::delete( $postId, $bidirectional );

			// check if a related post is set
	    	if( $_POST['related'] ) {

	    		// set related posts array
	    		$relatedPosts = $_POST['related'];

	    		// loop all related posts
	    		foreach ( $relatedPosts as $relatedPost ) {

	    			// get postID of related post
					$relatedPostID = ( isset($relatedPost['id']) ) ? $relatedPost['id'] : -1;

					// set the array for the related post (for serializing to DB)
					$relationArray = array(
						'id' => $relatedPostID,
						'title' => ( isset($relatedPost['title']) ) ? $relatedPost['title'] : 'title',
						'type' => ( isset($relatedPost['type']) ) ? $relatedPost['type'] : 'post',
						'position' => ( isset($relatedPost['position']) ) ? $relatedPost['position'] : 1
					);

					// check if the ID of the related post is set correct
					if ( $relatedPostID > 0 ) {

						// insert the current relations of this post
						DB::insert( $postId, serialize( $postArray ), $relatedPostID, serialize( $relationArray ) );

					} else {
						Logger::error('No ID of the related post found');
						return;

					}
	    		}
	    	} else {

	    		return;

	    	}

	    } else {

	    	Logger::error('No POST variables found');
	    	return;

	    }

	}


}