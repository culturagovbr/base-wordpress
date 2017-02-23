jQuery( document ).ready(function( $ ) {

	$( '#selected-skin' ).change( function(){
		$( '#selected-skin-form' ).submit();
	});

	$( '#dxef-style-switcher-close' ).on( 'click', function(){

		if ( $( '#dxef-style-switcher' ).hasClass( 'closed' ) ) {
			$( '#dxef-style-switcher' ).animate({
				left: '0',
				right: '+=180px',
				}, 1000, function() {
					$( this ).removeClass( 'closed' );
				}
			 );
		} else {
			$( '#dxef-style-switcher' ).animate({
				left: '-=180px',
				}, 1000, function() {
					$( this ).addClass( 'closed' );
				}
		 	);
		}
	});
});