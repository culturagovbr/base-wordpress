<script type="text/template" id="happyforms-form-build-template">
	<div class="happyforms-stack-view">
		<div class="customize-control">
			<label for="" class="customize-control-title"><?php _e( 'Title', 'happyforms' ); ?></label>
			<input type="text" name="post_title" value="<%= post_title %>" id="happyforms-form-name" placeholder="<?php _e( 'Add title', 'happyforms' ); ?>">
		</div>

		<div class="customize-control">
			<div class="happyforms-parts-placeholder">
				<p><?php _e( 'Add parts here to appear in your form.', 'happyforms' ); ?></p>
			</div>
			<div class="happyforms-form-widgets"></div>
			<button type="button" class="button add-new-widget happyforms-add-new-part"><?php _e( 'Add a Part', 'happyforms' ); ?></button>
		</div>
	</div>
</script>
