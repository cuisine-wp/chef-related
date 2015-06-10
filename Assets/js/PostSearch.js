
var PostSearch = Backbone.View.extend({

	id: '',
	highestId: '',
	posts: {},
	filtered: {},
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

		self.setEvents();
		self.setItemPositions();
	



	},



	setEvents: function(){

		var self = this;

		jQuery('.records').sortable({
			connectWith: '.records',
			stop: function(e, ui){
				
				setItemPositions();

			}
		}).disableSelection();


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


	renderList: function(){

		var self = this;
		var html = jQuery('#post_search_template').html();
		var template = '';

		jQuery('.not-selected-items').html('');

		for( var i = 0; i < self.filtered.length; i++ ){

			var item = self.filtered[ i ];
			var datas = {

				item_id: item.ID,
				title: item.post_title,
				type: item.post_type,
				position: i

			}

			template += _.template( html, datas );
		}

		jQuery('.not-selected-items').append( template );	

		return false;

	},


	addItem: function(){


	},

	removeItem: function(){


	},


	cleanField: function(){

		jQuery('.not-selected-items').html( '' );

	},

	searchItems: function( e ){

		var self = this;
		e.preventDefault;

		//if( e.keyCode == '13' ){

			var val = jQuery( e.target ).val().toLowerCase();

			//look through the results and return matches:
			var results = _.filter( self.posts, function( item ){

				return ( item.post_title.toLowerCase().indexOf( val ) > -1 );

			});
		
			self.filtered = results;
			self.renderList();
		//}

		return false;
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
				
			self.posts = JSON.parse( response );
			self.filtered = self.posts;
			self.renderList();
			return response;	
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
