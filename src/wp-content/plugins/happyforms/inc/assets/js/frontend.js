( function( $ ) {

	HappyForms.parts = HappyForms.parts || {};

	HappyForms.parts.base = {
		init: function() {
			this.type = this.$el.data( 'happyforms-type' );
			this.$input = $( 'input, textarea, select', this.$el );

			this.$input.on( 'keyup change', this.triggerChange.bind( this ) );
			this.$input.on( 'blur', this.onBlur.bind( this ) );
			this.$input.on( 'focus', this.onInputFocus.bind( this ) );

			this.onBlur();
		},

		getType: function() {
			return this.type;
		},

		onInputFocus: function() {
			this.$el.addClass( 'focus' );
		},

		onBlur: function() {
			if ( this.$el.is( '.happyforms-part--label-as_placeholder' ) ) {
				if ( this.isFilled() ) {
					this.$el.addClass( 'happyforms-part--filled' );
				} else {
					this.$el.removeClass( 'happyforms-part--filled' );
				}
			}

			this.$el.removeClass( 'focus' );
		},

		triggerChange: function( data ) {
			this.$el.trigger( 'happyforms-change', data );
		},

		isRequired: function() {
			var isRequired = (
				this.$el.is( ':visible' )
				&& this.$el.is( '[data-happyforms-required]' )
			);

			return isRequired;
		},

		isFilled: function() {
			var filledInputs = this.$input.filter( function() {
				var $input = $( this );
				var hasValue = false;

				if ( $input.is( '[type=checkbox]' ) || $input.is( '[type=radio]' ) ) {
					hasValue = $input.is( ':checked' );
				} else {
					hasValue = '' !== $input.val();
				}

				return hasValue;
			} );

			return filledInputs.length > 0;
		},

		confirmationMatches: function() {
			var matches = false;

			var $input = this.$input;

			if ( this.$visualInput ) {
				$input = this.$visualInput;
			}

			var values = $input.map( function() {
				return $( this ).val();
			} ).toArray();

			if ( 2 === values.length ) {
				matches = values[0] === values[1];
			}

			return matches;
		},

		requiresConfirmation: function() {
			return this.$el.is( '[data-happyforms-require-confirmation]' );
		},

		serialize: function() {
			var serialized = this.$input.map( function( i, input ) {
				var $input = $( input );
				var keyValue = {
					name: $input.attr( 'name' ),
					value: $input.val(),
				};

				if ( $input.is( '[type=checkbox]' ) || $input.is( '[type=radio]' ) ) {
					if ( ! $input.is( ':checked' ) ) {
						return;
					}
				}

				return keyValue;
			} ).toArray();

			return serialized;
		},

		isValid: function() {
			var valid = true;

			var type = this.$el.data( 'happyforms-type' );

			if ( ! this.$input ) {
				return valid;
			}

			if ( this.isRequired() ) {
				valid = valid && this.isFilled();
			}

			if ( this.isRequired() && this.requiresConfirmation() ) {
				valid = valid && this.confirmationMatches();
			}

			return valid;
		},

		destroy: function() {
			this.$el.data( 'HappyFormPart', false );
		}
	}

	HappyForms.wrapPart = function( $part, $form ) {
		var type = $part.data( 'happyforms-type' );
		var partMethods = HappyForms.parts.base;

		if ( HappyForms.parts[type] ) {
			partMethods = $.extend( {}, HappyForms.parts.base, HappyForms.parts[type] );
		}

		$part.happyFormPart( partMethods, {
			form: $form,
		} );
	}

	HappyForms.Form = function( el ) {
		this.el = el;
		this.$el = $( this.el );
		this.$form = $( 'form', this.$el );
		this.$parts = $( '[data-happyforms-type]', this.$form );
		this.$submits = $( '[type="submit"], a.submit', this.$form );
		this.$submit = $( '[type="submit"]', this.$form );
		this.$submitLinks = $( 'a.submit', this.$form );
		this.$step = $( '[name="happyforms_step"]', this.$form );

		this.init();
	}

	HappyForms.Form.prototype = {
		init: function() {
			var $form = this.$form;
			var $parts = $( '[data-happyforms-type]', this.$form );

			$parts.each( function() {
				var $part = $( this );
				var type = $part.data( 'happyforms-type' );

				HappyForms.wrapPart( $part, $form );
			} );

			this.$el.trigger( 'happyforms-change' );
			this.$el.trigger( 'happyforms-init' );
			this.$form.submit( this.submit.bind( this ) );
			this.$submit.click( this.buttonSubmit.bind( this ) );
			this.$submitLinks.click( this.linkSubmit.bind( this ) );
			this.$el.on( 'happyforms-scrolltop', this.onScrollTop.bind( this ) );
		},

		detach: function() {
			this.$el.off( 'happyforms-change' );
			this.$el.off( 'happyforms-scrolltop' );
			var $parts = $( '[data-happyforms-type]', this.$form );
			$parts.remove();
		},

		serialize: function( submitEl ) {
			var action = $( '[name=action]', this.$form ).val();
			var form_id = $( '[name=happyforms_form_id]', this.$form ).val();
			var nonce = $( '[name=happyforms_message_nonce]', this.$form ).val();
			var referer = $( '[name=_wp_http_referer]', this.$form ).val();
			var step = this.$step.val();

			var formData = [
				{ name: 'action', value: action },
				{ name: 'happyforms_form_id', value: form_id },
				{ name: 'happyforms_step', value: step },
				{ name: 'happyforms_message_nonce', value: nonce },
				{ name: 'referer', value: referer },
			];

			var $parts = $( '[data-happyforms-type]', this.$form );
			var partData = $parts.map( function( i, part ) {
				return $( part ).happyFormPart( 'serialize' );
			} )
			.toArray()
			.filter( function( entry ) {
				return null !== entry.name && undefined !== entry.name;
			} );

			var data = formData.concat( partData );

			var querystring = data
				.map( function( part ) {
					return part.name + '=' + encodeURIComponent( part.value );
				} )
				.join( '&' );

			return querystring;
		},

		buttonSubmit: function( e ) {
			if ( e.target.hasAttribute( 'data-step' ) ) {
				this.$step.val( e.target.getAttribute( 'data-step' ) );
			}
		},

		linkSubmit: function( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();

			if ( e.target.hasAttribute( 'data-step' ) ) {
				this.$step.val( e.target.getAttribute( 'data-step' ) );
			}

			this.$form.submit();
		},

		submit: function( e ) {
			e.preventDefault();

			this.$form.addClass( 'happyforms-form--submitting' );
			this.$submits.attr( 'disabled', 'disabled' );

			$.ajax( {
				type: 'post',
				data: this.serialize( e.target ),
			} ).done( this.onSubmitComplete.bind( this ) );
		},

		onSubmitComplete: function( response ) {
			this.$form.trigger( 'happyforms.submitted', response );

			if ( ! response.data ) {
				return false;
			}

			if ( response.data.html ) {
				var $el = $( response.data.html );
				var $parts = $( '[data-happyforms-type]', this.$form );

				$parts.each( function() {
					$( this ).trigger( 'happyforms.detach' );
				} );

				this.detach();

				var $form = $( 'form', $el );
				$( 'form', this.$el ).replaceWith( $form );

				this.$el.happyForm();

				var elTopOffset = this.$el.offset().top;
				var $notices = $( '.happyforms-message-notices', this.$el );

				if ( $form.is( '.happyforms-form--notices-below' ) && $notices.length ) {
					elTopOffset = $notices.offset().top;
				}

				// User filterable
				var increment = $form.attr( 'data-happyforms-scroll-offset' );

				if ( increment ) {
					increment = parseInt( increment, 10 );
					elTopOffset += increment;
				}

				this.$el.trigger( 'happyforms-scrolltop', elTopOffset );

				var hasErrorNotices = $( '.happyforms-message-notice.error', $form ).length;

				if ( ! hasErrorNotices && this.$el.hasClass( 'happyforms-form--hide-on-submit' ) ) {
					$( '.happyforms-part', $form ).hide();
				}
			}
		},

		onScrollTop: function( e, offset ) {
			if ( e.isDefaultPrevented() ) {
				return;
			}

			$( 'html, body' ).animate( {
				scrollTop: offset + 'px'
			}, 500 );
		}
	}

	HappyForms.Part = function( el ) {
		this.el = el;
		this.$el = $( this.el );
	}

	$.fn.happyFormPart = function( method ) {
		var args = arguments;

		if ( 'object' === typeof method ) {
			var part = new HappyForms.Part( this );
			$.extend( part, method );
			$( this ).data( 'HappyFormPart', part );
			part.init.apply( part, Array.prototype.slice.call( arguments, 1 ) );
		} else {
			var instance = $( this ).data( 'HappyFormPart' );

			if ( instance && instance[method] ) {
				return instance[method].apply( instance, Array.prototype.slice.call( arguments, 1 ) );
			}
		}
	}

	$.fn.happyForm = function ( method ) {
		this.each(function () {
			if ( ! method ) {
				$.data( this, 'HappyForm', new HappyForms.Form( this, arguments ) );
			} else {
				var instance = $.data( this, 'HappyForm' );

				if ( instance && instance[method] ) {
					return instance[ method ].apply( instance, Array.prototype.slice.call( arguments, 1 ) );
				}
			}
		} );
	}

	$( document ).ready( function() {
		$( '.happyforms-form' ).happyForm();
	} );

} )( jQuery );
