<div class="customize-control" id="customize-control-<?php echo $control['field']; ?>">
	<?php do_action( "happyforms_setup_control_{$control['field']}_before", $control ); ?>

	<label for="<?php echo $control['field']; ?>" class="customize-control-title"><?php echo $control['label']; ?> <?php if ( isset( $control['tooltip'] ) ) : ?><i class="dashicons dashicons-editor-help" aria-hidden="true" data-pointer><span><?php echo $control['tooltip']; ?></span></i><?php endif; ?></label>
	<input type="text" id="<?php echo $control['field']; ?>" value="<%= <?php echo $control['field']; ?> %>" data-attribute="<?php echo $control['field']; ?>" placeholder="<?php echo ( isset( $control['placeholder'] ) ) ? $control['placeholder'] : ''; ?>" data-pointer-target />

	<?php do_action( "happyforms_setup_control_{$control['field']}_after", $control ); ?>
</div>
