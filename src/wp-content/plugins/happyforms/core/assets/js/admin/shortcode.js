( function( window, views, $, settings ) {

	var happyforms = {
		template: wp.media.template( 'happyforms-shortcode' ),

		getTitle: function( id ) {
			var title = '';

			if ( settings.forms[id] ) {
				title = '"' + settings.forms[id].post_title + '" - ';
			}

			return title;
		},

		getId: function() {
			return this.shortcode.attrs.named.id;
		},

		initialize: function() {
			var id = this.getId();
			var title = this.getTitle( id );

			this.render( this.template( {
				id: id,
				title: title,
			} ) );
		},

		edit: function() {
			var id = this.getId();
			var returnUrl = encodeURIComponent( document.location.href );
			var link = settings.editLink.replace( 'ID', id ).replace( 'URL', returnUrl );

			document.location.href = link;
		},
	}

	views.register( 'happyforms', _.extend( {}, happyforms ) );

} )( window, window.wp.mce.views, window.jQuery, _happyFormsAdmin );