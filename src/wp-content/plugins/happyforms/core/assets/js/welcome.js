( function( $ ) {
	$( document ).ready( function() {
		$successTemplate = $( '#happyforms-tracking-success' );
		$errorTemplate = $( '#happyforms-tracking-error' );
		$content = $( '.happyforms-welcome-panel .welcome-panel-content' );
		$footer = $( '.welcome-panel-footer' );
		$proceedLink = $( '#happyforms-tracking-proceed' );
		$skipLink = $( '#happyforms-tracking-skip' );
		$email = $( 'input[type="email"]' );

		$email.focus();

		$( '#happyforms-tracking' ).submit( function( e ) {
			e.preventDefault();

			var $this = $( this );

			$.post(
				$this.attr( 'action' ),
				$this.serialize(),

				function( data ) {
					if ( 400 === data.Status ) {
						$content.html( $errorTemplate.html() );
					} else {
						$content.html( $successTemplate.html() );
						$footer.hide();

						$.post( ajaxurl, {
							action: 'happyforms_update_tracking',
							status: 3,
							email: $email.val(),
						} );
					}
			} );
		} );

		function proceed( e ) {
			e.preventDefault();

			url = $( this ).attr( 'href' );

			$.post( ajaxurl, {
				action: 'happyforms_update_tracking',
				status: 4,
			}, function() {
				window.location.href = url;
			} );
		}

		$proceedLink.click( proceed );
		$skipLink.click( proceed );
		$email.focus();

	} );
} )( jQuery );