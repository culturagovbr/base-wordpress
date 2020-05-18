<?php if ( $index > 0 ): ?></ul></li><?php endif; ?>
<li class="customize-control control-section <?php echo esc_attr( 'happyforms-' . $control['type'] . '-control' ); ?>" id="customize-control-<?php echo $control['id']; ?>">
	<h3 class="accordion-section-title"><?php echo $control['label']; ?></h3>
</li>

<li class="happyforms-style-controls-group">
	<ul>
		<li class="panel-meta customize-info accordion-section">
			<button class="customize-panel-back" tabindex="0">
				<span class="screen-reader-text"><?php _e( 'Back', 'happyforms' ); ?></span>
			</button>
			<div class="accordion-section-title">
				<span class="preview-notice"><?php _e( 'You are customizing', 'happyforms' ); ?> <strong class="panel-title"><?php echo $control['label']; ?></strong></span>
			</div>
		</li>
		<?php if ( isset( $control['description'] ) ) : ?>
			<div class="description customize-section-description open">
				<?php echo $control['description']; ?>
			</div>
		<?php endif; ?>
