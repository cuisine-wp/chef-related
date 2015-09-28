<?php
/**
 * All template tags for Chef Sections
 * @package ChefRelated
 */

use ChefRelated\Wrappers\Walker;

/**
 * Echoes the related templates
 * @return void
 */
function the_related(){	

	echo Walker::walk();

}


/**
 * Gets all html for the related section
 * 
 * @return ChefRelated\Front\Walker ( html )
 */
function get_related(){

	return Walker::walk();

}

/**
 * Does this item have related items?
 * 
 * @return ChefRelated\Front\Walker
 */
function has_related(){

	return Walker::getRelated();
	
}


/**
 * Get a section from an external post
 * 
 * @param  int $post_id    
 * @param  int $section_id 
 * @return string (html)
 */
function get_related_items(){

	return Walker::getRelated();

}
