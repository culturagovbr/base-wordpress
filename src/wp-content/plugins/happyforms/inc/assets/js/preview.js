( function( $, _, Backbone, api, settings ) {

	HappyForms.parts = HappyForms.parts || {};

	var handlers = {};
	var $pencil, $form, $submit;

	handlers.getPart = function( id ) {
		var $part = $( '[data-happyforms-id="' + id + '"]' );

		return $part;
	}

	handlers.formTitleUpdate = function( title ) {
		$( '.happyforms-form__title' ).text( title );
	}

	handlers.formDomUpdate = function( e ) {
		var context = parent.happyForms.previewer;
		var callback = context[e.callback];
		var options = e.options || {}

		if ( callback ) {
			callback.call( context, $form, options, $ );
		}
	}

	handlers.formPartAdd = function( data ) {
		$part = $( data.html );

		if ( 'undefined' === typeof data.after ) {
			$submit.before( $part );
		} else if ( -1 === data.after ) {
			$( '.happyforms-part', $form ).first().before( $part );
		} else {
			var $previous = handlers.getPart( data.after );
			$previous.after( $part );
		}

		$part.prepend( $( $pencil ) );
		HappyForms.wrapPart( $part, $form );
	}

	handlers.formPartRefresh = function( e ) {
		var $part = handlers.getPart( e.id );
		var $next = $part.next();
		var $refreshedPart = $( e.html );

		$part.trigger( 'happyforms.detach' );
		$part.remove();
		$next.before( $refreshedPart );
		$refreshedPart.prepend( $( $pencil ) );
		HappyForms.wrapPart( $refreshedPart, $form );
		$refreshedPart.trigger( 'happyforms.attach' );
	}

	handlers.partialHtmlFetch = function( e ) {
		var $partial = $( e.selector );
		var context = parent.happyForms.previewer;
		var $refreshedPartial = $( e.html );
		var options = e.options || {};

		if ( options.pencil ) {
			$refreshedPartial.prepend( $( $pencil ) );
		}

		if ( $partial.length ) {
			$partial.replaceWith( $refreshedPartial );
		} else {
			$( options.after ).after( $refreshedPartial );
		}
	}

	handlers.partialDomUpdate = function( e ) {
		var $partial = $( e.partialSelector );
		var context = parent.happyForms.previewer;
		var callback = context[e.callback];
		var options = e.options || {};

		if ( callback ) {
			callback.call( context, e.partialSelector, e.id, $partial, options, $ );
		}
	}

	handlers.formPartDisable = function( e ) {
		var $part = handlers.getPart( e.id );

		$part.addClass( 'unloading' );
	}

	handlers.formPartRemove = function( id ) {
		var $part = handlers.getPart( id );
		$part.trigger( 'happyforms.detach' );
		$part.remove();
	}

	handlers.partDomUpdate = function( e ) {
		var $part = handlers.getPart( e.id );
		var context = parent.happyForms.previewer;
		var callback = context[e.callback];
		var options = e.options || {}

		if ( callback ) {
			callback.call( context, e.id, $part, options, $ );
		}
	}

	handlers.partialDisable = function( e ) {
		var $partial = $( '[data-partial-id='+ e.partial + ']' );

		$partial.addClass( 'unloading' );
	}

	handlers.partialRemove = function( e ) {
		var $partial = $( '[data-partial-id='+ e.partial + ']' );

		$partial.remove();
	}

	handlers.formPartsSort = function( ids ) {
		var $parts = $.map( ids, function( id ) {
			var $part = handlers.getPart( id );

			$part.trigger( 'happyforms.detach' );
			$part.detach();

			return $part;
		} );

		$.each( $parts, function( i, $part ) {
			$submit.before( $part );
			$part.trigger( 'happyforms.attach' );
		} );
	}

	handlers.subPartAdded = function( e ) {
		var $part = handlers.getPart( e.id );
		var callback = parent.happyForms.onSubPartAdded;
		var options = {};

		callback.call( parent.happyForms, e.id, $part, e.html, options );
	}

	handlers.cssVariableUpdate = function( e ) {
		var formID = parent.happyForms.form.get( 'ID' );
		var $parts = $( '[data-happyforms-type]', $form );
		var variable = {
			name: e.variable,
			value: e.value,
		};

		document.querySelector( '.happyforms-form' ).style.setProperty( e.variable, e.value );

		$.each( $parts, function( i, part ) {
			$( part ).trigger( 'happyforms.cssvar', variable );
		} );
	}

	handlers.formClassUpdate = function( e ) {
		var context = parent.happyForms.previewer;
		var callback = context[e.callback];
		var options = e.options || {}
		var $parts = $( '[data-happyforms-type]', $form );

		if ( callback ) {
			callback.call( context, e.attribute, $formContainer, options );
		}
	}

	handlers.formClassUpdated = function( e ) {
		var $parts = $( '[data-happyforms-type]', $form );

		$.each( $parts, function( i, part ) {
			$( part ).trigger( 'happyforms.formclass', $form.attr( 'class' ) );
		} );
	}

	handlers.pencilPartClick = function( e ) {
		e.preventDefault();

		var id = $( e.target ).parents( '.happyforms-part' ).data( 'happyforms-id' );
		api.preview.send( 'happyforms-pencil-click-part', id );
	}

	handlers.pencilPartialClick = function( e ) {
		e.preventDefault();

		var $partial = $( e.target ).closest( '.happyforms-partial-edit-shortcut' ).parent();

		api.preview.send( 'happyforms-' + $partial.attr( 'data-partial-id' ) + '-pencil-click' );
	}

	handlers.submitButtonTextUpdate = function ( text ) {
		$( '.happyforms-button--submit' ).attr( 'value', text );
	}

	handlers.silenceEvent = function( e ) {
		e.preventDefault();
	}

	handlers.init = function() {
		// Populate pointers
		$pencil = $( '#happyforms-pencil-template' ).html();
		$formContainer = $( '.happyforms-form' );
		$form = $( '.happyforms-form form' );
		$submit = $( '.happyforms-part.happyforms-part--submit', $form );

		// Append pencils to existing elements
		$( '.happyforms-block-editable:not(.no-pencil)' ).prepend( $( $pencil ) );

		// Remove unpreviewable
		$( '.happyforms-form form' ).removeClass( 'customize-unpreviewable' );
		$( '.notice a' ).removeClass( 'customize-unpreviewable' );
	}

	handlers.bind = function() {
		// Bind preview handlers
		api.preview.bind( 'happyforms-form-title-update', handlers.formTitleUpdate );
		api.preview.bind( 'happyforms-form-dom-update', handlers.formDomUpdate );
		api.preview.bind( 'happyforms-form-part-add', handlers.formPartAdd );
		api.preview.bind( 'happyforms-form-part-remove', handlers.formPartRemove );
		api.preview.bind( 'happyforms-form-parts-sort', handlers.formPartsSort );
		api.preview.bind( 'happyforms-form-part-refresh', handlers.formPartRefresh );
		api.preview.bind( 'happyforms-form-part-disable', handlers.formPartDisable );
		api.preview.bind( 'happyforms-form-partial-html-fetch', handlers.partialHtmlFetch );
		api.preview.bind( 'happyforms-form-partial-dom-update', handlers.partialDomUpdate );
		api.preview.bind( 'happyforms-form-partial-disable', handlers.partialDisable );
		api.preview.bind( 'happyforms-form-partial-remove', handlers.partialRemove );
		api.preview.bind( 'happyforms-part-dom-update', handlers.partDomUpdate );
		api.preview.bind( 'happyforms-css-variable-update', handlers.cssVariableUpdate );
		api.preview.bind( 'happyforms-form-class-update', handlers.formClassUpdate );
		api.preview.bind( 'happyforms-form-class-updated', handlers.formClassUpdated );
		api.preview.bind( 'happyforms-submit-button-text-update', handlers.submitButtonTextUpdate );

		// Bind DOM handlers
		$( document.body ).on(
			'click',
			'.happyforms-block-editable--part .customize-partial-edit-shortcut',
			handlers.pencilPartClick
		);

		$( document.body ).on(
			'click',
			'.happyforms-block-editable--partial .customize-partial-edit-shortcut',
			handlers.pencilPartialClick
		);

		$( '.happyforms-ask-link, .happyforms-notice p a' ).on( 'click', function() {
			window.open( $( this ).attr( 'href' ) );
		} );

		$( '.happyforms-notice a.happyforms-dismiss-notice' ).on( 'click', function( e ) {
			e.preventDefault();

			var $target = $( e.target );
			var $parent = $target.parents( '.happyforms-notice' ).first();
			var id = $parent.attr( 'id' ).replace( 'happyforms-notice-', '' );
			var nonce = $parent.data( 'nonce' );

			$.post( settings.ajaxurl, {
					action: 'happyforms_hide_notice',
					nid: id,
					nonce: nonce
				}
			);

			$parent.fadeOut();
		}),

		// Silence unwanted events
		$( document.body ).on( 'click', 'button', handlers.silenceEvent );
		$( document.body ).on( 'click', 'input[type=submit]', handlers.silenceEvent );
		$( '.happyforms-form' ).on( 'submit', handlers.silenceEvent );
	}

	$( document ).ready( function() {
		handlers.init();
		handlers.bind();
		api.preview.send( 'happyforms-preview-ready' );
	} );

} )( jQuery, _, Backbone, wp.customize, _happyformsPreviewSettings );