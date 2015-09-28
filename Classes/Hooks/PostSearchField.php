<?php

	namespace ChefRelated\Admin;

	use Cuisine\Fields\DefaultField;

	class PostSearchField extends DefaultField{



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
		 * Build the html
		 *
		 * @return String;
		 */
		public function build(){

		    $posts = $this->getValue();

		    $html = '<div class="post-search-field" data-highest-id="'.$this->getHighestItemId().'">';

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
		    	foreach( $posts as $p ){

		    		$html .= $this->makeItem( $p, $i );

		    		$i++;
		    	}}

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
				$html .= '<b>'.$item['title'].'</b>';
				$html .= '<span class="type">'.$item['type'].'</span>';

				$html .= $prefix.'[id]" value="'.$item['id'].'" disabled>';
				$html .= $prefix.'[title]" value="'.$item['title'].'" disabled>';
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



	}
