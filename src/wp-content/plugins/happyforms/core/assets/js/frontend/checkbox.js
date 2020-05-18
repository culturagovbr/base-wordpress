( function( $ ) {

	HappyForms.parts = HappyForms.parts || {};

	HappyForms.parts.checkbox = {
		init: function() {
			this.type = this.$el.data( 'happyforms-type' );
			this.$input = $( 'input[data-serialize]', this.$el );
			this.$selectAllCheckbox = $( 'input.happyforms-select-all', this.$el );

			if ( this.$selectAllCheckbox.length ) {
				this.$input.on( 'change', this.onCheckboxChange.bind( this ) );
				this.$selectAllCheckbox.on( 'change', this.onSelectAllChange.bind( this ) );
			}

			this.$input.on( 'change', this.triggerChange.bind( this ) );
		},

		onSelectAllChange: function() {
			if ( this.$selectAllCheckbox.is( ':checked' ) ) {
				this.$input.prop( 'checked', true );
			} else {
				this.$input.prop( 'checked', false );
			}
		},

		onCheckboxChange: function( e ) {
			var $checkbox = $( e.target );

			if ( ! $checkbox.is( ':checked' ) ) {
				this.$selectAllCheckbox.prop( 'checked', false );
			}
		}
	};

} )( jQuery );