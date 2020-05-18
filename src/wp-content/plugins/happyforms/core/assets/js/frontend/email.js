( function( $, settings ) {

	HappyForms.parts = HappyForms.parts || {};

	HappyForms.parts.email = {
		init: function() {
			this.type = this.$el.data( 'happyforms-type' );
			this.$input = $( 'input', this.$el );
			this.$visualInput = $( 'input[type=email]', this.$el );
			this.mode = this.$el.attr( 'data-mode' );

			if ( 'autocomplete' === this.mode ) {
				this.initAutocomplete();
			}

			this.$input.on( 'keyup', this.triggerChange.bind( this ) );
			this.$input.on( 'change', this.triggerChange.bind( this ) );
			this.$input.on( 'focus', this.onInputFocus.bind( this ) );
			this.$visualInput.on( 'blur', this.onBlur.bind( this ) );
		},

		initAutocomplete: function() {
			var $inputs = $( '[data-serialize]', this.$el );

			$inputs.each( function() {
				var $visualInput = $( this ).next( 'input[type=email]' );
				var $select = $visualInput.next( '.happyforms-custom-select-dropdown' );

				$visualInput.happyFormsSelect( {
					$input: $( this ),
					$select: $select,
					searchable: 'autocomplete',
					autocompleteOptions: {
						url: settings.url,
						source: settings.autocompleteSource,
						trigger: '@',
						partial: true
					},
				});
			});
		}
	};

} )( jQuery, _happyFormsEmailSettings );
