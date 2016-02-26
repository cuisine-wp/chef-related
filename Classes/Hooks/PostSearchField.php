<?php

	namespace ChefRelated\Admin;

	use Cuisine\Fields\DefaultField;
	use Cuisine\Utilities\Session;
	use ChefRelated\Database\DB;
	use \ChefRelated\Front\Settings;

	class PostSearchField extends DefaultField{



		/**
		 * The current Post ID
		 * 
		 * @var integer
		 */
		var $post_id = 0;

		var $bidirectional = false;

		/**
		 * Method to override to define the input type
		 * that handles the value.
		 *
		 * @return void
		 */
		protected function fieldType(){
		    $this->type = 'postsearch';

		}


		/**
		* Define a core Field.
		*
		* @param array $properties The text field properties.
		*/
		public function __construct( $name, $label = '', $props = array() ){

			 $this->bidirectional = (Settings::get( 'autoRelateBidirectional' ) == 'true' );
			 $this->post_id = Session::postId();

			 parent::__construct($name, $label, $props );
			 
		}

		/**
		 * Build the html
		 *
		 * @return String;
		 */
		public function build(){

		    $posts = $this->getValue();
		    
		    $html = '<div class="post-search-field" data-highest-id="'.$this->getHighestItemId().'" data-post_id="'.$this->post_id.'">';

		    $html .= '<div class="not-selected-wrapper">';
		    	$html .= '<div class="search-bar">';
		    		$html .= '<input type="text" placeholder="Zoeken..." id="search-posts">';
		    	$html .= '</div>';

		    	$html .= '<div class="not-selected">';
		    	$html .= '<h3>'.__( 'Niet geselecteerd', 'chefrelated').'</h3>';

		    	$html .= '<span class="spinner"></span>';

		    	$html .= '<ul class="not-selected-items records">';


		    	$html .= '</ul></div>';

		    $html .= '</div>';
		    $html .= '<div class="is-selected">';
		    	$html .= '<h3>'.__( 'Geselecteerd', 'chefrelated').'</h3>';
		    	$html .= '<ul class="selected-items records">';
		    	$i = 0;

		    	if( !empty( $posts ) ){

		    		$shown = array();

		    		foreach( $posts as $p ){
		    			
		    			if( get_post_status( $p['id'] ) == 'publish' && !in_array( $p['id'], $shown ) ){
		    				$html .= $this->makeItem( $p );
		    				$shown[] = $p['id'];
		    			}
	
		    		}
		    	}

		    	$html .= '</ul>';

		    $html .= '</div><div class="clear"></div>';
		    $html .= '</div>';

		    return $html;

		}

		/**
		 * Get a single post-block
		 * 
		 * @return String
		 */
		public function makeItem( $item ){

			$prefix = '<input type="hidden" class="multi" name="';
			$prefix .= $this->name.'['.$item['id'].']';

			$html = '';
			$html .= '<li data-id="'.$item['id'].'">';
				$html .= '<b>'.str_replace( "\\",'', $item['title'] ).'</b>';
				$html .= '<span class="type">'.$item['type'].'</span>';

				$html .= $prefix.'[id]" value="'.$item['id'].'" disabled>';
				$html .= $prefix.'[title]" value="'.str_replace( "\\",'', $item['title'] ).'" disabled>';
				$html .= $prefix.'[type]" value="'.$item['type'].'" disabled>';
				$html .= $prefix.'[position]" value="'.$item['position'].'" id="position" disabled>';

			$html .= '</li>';
			
			return $html;
		}



		/**
		 * Return the template, for Javascript
		 * 
		 * @return String
		 */
		public function renderTemplate(){

		    //make a clonable item, for javascript:
		    $html = '<script type="text/template" id="post_search_template">';
		        $html .= $this->makeItem( array( 
		            'id' => '<%= item_id %>',
		            'title' => '<%= title %>', 
		            'type' => '<%= type %>',
		            'position' => '<%= position %>',
		        ) );
		    $html .= '</script>';

		    return $html;
		}



		/**
		 * Get the highest item ID available
		 * 
		 * @return int
		 */
		private function getHighestItemId(){

		    $posts = $this->getValue();
		    return count( $posts );

		}



		/**
	     * Get the value of this field:
	     * 
	     * @return String
	     */
	    public function getValue(){

	        $value = $val = false;

            $values = DB::get( $this->post_id, $this->bidirectional );
            //$value = get_post_meta( $post->ID, $this->name, true );
            
            if( isset( $values ) && count( $values ) > 0 ) {
            	$value = array();
            	foreach ($values as $relatedPost) {
            		if ( $this->bidirectional ) {
            			if ( $relatedPost->related_post_id == $this->post_id )
							array_push($value, unserialize($relatedPost->post_data));
            		
            		}
            		if ( $relatedPost->post_id == $this->post_id )
            			array_push($value, unserialize($relatedPost->related_post_data));	

            	}
            }


	        if( $value && !$val )
	            $val = $value;

	        if( $this->properties['defaultValue'] && !$val )
	            $val = $this->getDefault();

	        return $val;
	    }



	}
