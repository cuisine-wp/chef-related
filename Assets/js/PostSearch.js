
var PostSearch = Backbone.View.extend({

	id: '',
	highestId: '',
	posts: {},
	items: {},

	included: '',
	excluded: '',

	events: {

		'keyup #search-posts' : 'searchItems'

	},


	initialize: function(){

	
		var self = this;
		self.id = self.$el.data('id');
		self.highestId = parseInt( self.$el.data( 'highest-id' ) );
		
		self.included = self.$el.find( '.selected-items' );
		self.excluded = self.$el.find( '.not-selected' );

		self.fetchPosts();

		self.setItems();
		self.setEvents();
		self.setItemPositions();
	



	},


	/**
	 * Set the items object for this field:
	 *
	 * @return void
	 */
	setItems: function(){
		
		var self = this;
		self.items = self.$el.find( '.selected-items li' );
	
	},



	setEvents: function(){

		var self = this;

		jQuery( self.included ).sortable({

			update: function (event, ui) {

				self.setItems();
				self.setItemPositions();

			}
		});


	},


	/**
	 * Set item positions:
	 *
	 * @return void
	 */
	setItemPositions: function(){

		var self = this;

		for( var i = 0; i < self.items.length; i++ ){

			var item = jQuery( self.items[ i ] );
			//set the position:
			item.find( '#position' ).val( i );

		}

	},


	addItem: function(){


	},

	removeItem: function(){


	},

	searchItems: function( e ){

		var self = this;

		if( e.keyCode == '13' ){

			e.preventDefault();

			console.log( self.posts );



		}
	},


	/**
	 * Fetch the post for this search-form:
	 * @return {[type]} [description]
	 */
	fetchPosts: function(){

		var self = this;
		var data = {
			'action' 		: 'fetchPostList'
		};

		jQuery.post( ajaxurl, data, function( response ){
			return response;

			self.posts = JSON.parse( response );
			
		});

	}

});



jQuery( document ).ready( function( $ ){

	setPostSearch();

});


function setPostSearch(){

	var query = false;

	jQuery('.post-search-field').each( function( index, obj ){

		
		var ps = new PostSearch( { el: obj } );

	});
}
