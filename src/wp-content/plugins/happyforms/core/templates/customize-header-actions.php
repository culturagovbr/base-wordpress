<script type="text/template" id="happyforms-customize-header-actions">
	<div id="happyforms-save-button-wrapper" class="customize-save-button-wrapper">
		<%
		var buttonLabels = {
			"saveNew" : "<?php _e( 'Save', 'happyforms' ); ?>",
			"saveExisting" : "<?php _e( 'Update', 'happyforms' ); ?>",
			"savedNew" : "<?php _e( 'Saved', 'happyforms' ); ?>",
			"savedExisting" : "<?php _e( 'Updated', 'happyforms' ); ?>"
		};

		var saveLabel = buttonLabels.saveNew;
		var savedLabel = buttonLabels.savedNew;

		if ( ! isNewForm ) {
			saveLabel = buttonLabels.saveExisting;
			savedLabel = buttonLabels.savedExisting;
		}
		%>
		<button id="happyforms-save-button" class="button-primary button" aria-label="<%= saveLabel %>" aria-expanded="false" disabled="disabled" data-text-saved="<%= savedLabel %>" data-text-default="<%= saveLabel %>"><%= saveLabel %></button>
	</div>
	<a href="<?php echo esc_url( $wp_customize->get_return_url() ); ?>" id="happyforms-close-link" data-message="<?php _e( 'The changes you made will be lost if you navigate away from this page.', 'happyforms' ); ?>">
		<span class="screen-reader-text"><?php _e( 'Close', 'happyforms' ); ?></span>
	</a>

	<div id="happyforms-steps-nav">
	</div>
</script>
