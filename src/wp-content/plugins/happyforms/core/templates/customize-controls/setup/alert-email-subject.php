<div class="customize-control" id="customize-control-<?php echo $control['field']; ?>">
	<label for="<?php echo $control['field']; ?>" class="customize-control-title"><?php echo $control['label']; ?> <?php if ( isset( $control['tooltip'] ) ) : ?><i class="dashicons dashicons-editor-help" aria-hidden="true" data-pointer><span><?php echo $control['tooltip']; ?></span></i><?php endif; ?></label>
	<input type="text" id="<?php echo $control['field']; ?>" value="<%= <?php echo $control['field']; ?> %>" data-attribute="<?php echo $control['field']; ?>" data-pointer-target />
	<p class="description"><span></span> <?php _e( 'part value is currently used as subject', 'happyforms' ); ?>
</div>
