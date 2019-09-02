/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and descriptions.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            $( '.site-description' ).text( to );
        } );
    } );

    wp.customize( 'blogdenomination', function( value ) {
        value.bind( function( to ) {
            $( '.denomination' ).text( to );
        } );
    } );

    wp.customize( 'color_palette', function( value ) {
        value.bind( function( to ) {
        	$('body').removeClass('yellow-theme blue-theme white-theme green-theme custom-theme');
        	$('body').addClass(to + '-theme');
        } );
    } );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description, .denomination' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description, .denomination' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description, .denomination' ).css( {
					'color': to
				} );
			}
		} );
	} );
} )( jQuery );
