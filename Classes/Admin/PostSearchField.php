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
		    $html = '<div class="post-search-field" data-name="'.$this->name.'">';

		    $html .= '<div class="not-selected-wrapper">';
		    	$html .= '<div class="search-bar">';
		    		$html .= '<input type="text" placeholder="Zoeken..." id="search-posts">';
		    	$html .= '</div>';

		    	$html .= '<div class="not-selected">';


		    	$html .= '</div>';

		    $html .= '</div>';
		    $html .= '<div class="selected-wrapper">';

		    	$html .= '<ul class="selected-items">';
		    	$i = 0;

		    	if( !empty( $posts ) ){
		    	foreach( $posts as $p ){

		    		$this->makeItem( $p, $i );

		    		$i++;
		    	}}

		    	$html .= '</ul>';

		    $html .= '</div>';
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
			$prefix .= $this->name.'['.$p['id'].']';

			$html .= '<li>';
				$html .= $p['title'];
				$html .= '<span class="type">'.$p['type'].'</span>';

				$html .= $prefix.'[id]" value="'.$p['id'].'">';
				$html .= $prefix.'[title]" value="'.$p['title'].'">';
				$html .= $prefix.'[type]" value="'.$p['type'].'">';
				$html .= $prefix.'[position]" value="'.$p['position'].'">';

			$html .= '</li>';
			
			return $html;
		}


	}
