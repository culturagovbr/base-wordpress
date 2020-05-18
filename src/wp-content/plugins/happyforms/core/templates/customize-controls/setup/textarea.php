<div class="customize-control" id="customize-control-<?php echo $control['field']; ?>">
	<label for="<?php echo $control['field']; ?>" class="customize-control-title"><?php echo $control['label']; ?> <?php if ( isset( $control['tooltip'] ) ) : ?><i class="dashicons dashicons-editor-help" aria-hidden="true" data-pointer><span><?php echo $control['tooltip']; ?></span></i><?php endif; ?></label>
	<div data-pointer-target>
		<textarea name="" id="<?php echo $control['field']; ?>" cols="34" rows="4" data-attribute="<?php echo $control['field']; ?>"><%= <?php echo $control['field']; ?> %></textarea>
	</div>
</div>
