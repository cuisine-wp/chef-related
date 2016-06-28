
var PostSearch = Backbone.View.extend({

	id: '',
	highestId: '',
	posts: {},
	filtered: {},
	items: {},
	selectedItems: {},

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

		self.setSelected();

		self.setEvents();
		self.setItemPositions();
	



	},



	setEvents: function(){

		var self = this;

		jQuery('.records').sortable({
			connectWith: '.records',
			stop: function(e, ui){
				
				self.setItemPositions();

				var _item = jQuery( ui.item[0] );

				if( _item.parent().hasClass('selected-items') === true ){

					_item.find('input').prop( 'disabled', false );

				}else{
					_item.find('input').prop( 'disabled', true );
				}


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


			if( self.selectedItems.indexOf( item.ID ) === -1 ){
				var _temp = _.template( html );
				template += _temp( datas );
			}
		}

		jQuery( '.not-selected .spinner' ).remove();
		jQuery('.not-selected-items').append( template );	

		return false;

	},



	setSelected: function(){

		var self = this;
		self.selectedItems = new Array();

		jQuery( '.selected-items li').each( function(){

			jQuery( this ).find('input').prop( 'disabled', false );

			var _id = jQuery( this ).data( 'id' );
			self.selectedItems.push( _id );

		});

		self.fetchPosts();
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
			'action' 		: 'fetchPostList',
			'post_id'		: self.$el.data('post_id' )
		};

		console.log(data);

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

jQuery( document ).on( 'refreshFields', function(){
	setPostSearch();
});

function setPostSearch(){

	var query = false;

	jQuery('.post-search-field').each( function( index, obj ){
		
		var ps = new PostSearch( { el: obj } );

	});
}
