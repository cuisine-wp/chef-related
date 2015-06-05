
var PostSearch = Backbone.View.extend({

	name: '',
	posts: {},
	events: {

	},


	initialize: function(){


	},


	add: function(){


	},

	remove: function(){


	},

	sort: function(){

	},

	search: function(){



	},


	fetchPosts: function(){

		var data = {
			'action' 		: 'fetchPostList'
		};

		jQuery.post( ajaxurl, data, function( response ){

			console.log( response );

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

		if( query === false ){
		
			var ps = new PostSearch( { el: obj } );
			query = ps.fetchPosts();

		}else{

			ps = new PostSearch( { el: obj, posts: query } );
		}

	});
}
