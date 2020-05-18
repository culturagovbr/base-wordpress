( function( $ ) {

	HappyForms.parts = HappyForms.parts || {};

	HappyForms.parts.number = {
		init: function() {
			this.type = this.$el.data( 'happyforms-type' );
			this.allowedKeys = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Meta', 'Delete', 'ArrowLeft', 'ArrowRight' ];

			var masked = this.$el.attr( 'data-mask' );

			this.$input = $( 'input', this.$el );
			this.cleaveInstances = [];

			this.$input.on( 'keyup', this.onKeyUp.bind( this ) );
			this.$input.on( 'change', this.triggerChange.bind( this ) );
			this.$input.on( 'focus', this.onInputFocus.bind( this ) );
			this.$input.on( 'blur', this.onBlur.bind( this ) );

			this.numeralDecimalMark = this.$el.attr( 'data-decimal-mark' ) || '';
			this.delimiter = this.$el.attr( 'data-thousands-delimiter' ) || '';

			if ( masked ) {
				var self = this;

				this.$input.each( function() {
					var $input = $( this );

					var cleave = new Cleave( $input, {
						numeral: true,
						numeralDecimalMark: this.numeralDecimalMark,
						delimiter: this.delimiter,
					} );

					self.cleaveInstances.push( cleave );
				} );
			}

			this.onBlur();
		},

		onKeyUp: function( e ) {
			var value = $( e.target ).val();
			var regex = new RegExp( "[^0-9" + this.numeralDecimalMark + this.delimiter + "-]", "g" );

			value = value.replace( regex, '' );
			$( e.target ).val( value );

			this.triggerChange();
		},

		reinit: function() {
			$.each( this.cleaveInstances, function( i, instance ) {
				var input = instance.element;
				var rawValue = instance.getRawValue();

				instance.destroy();

				input.value = rawValue;
			} );

			this.init();
		},
	};

} )( jQuery );
