<li class="customize-control <?php echo esc_attr( 'happyforms-' . $control['type'] . '-control' ); ?>" data-target="<?php echo esc_attr( $field['target'] ); ?>" data-variable="<?php echo ( isset( $field['variable'] ) ) ? esc_attr( $field['variable'] ) : ''; ?>" id="customize-control-<?php echo $control['field']; ?>">
	<div class="customize-control-content">
		<label class="customize-control-title" for="<?php echo $control['field']; ?>"><?php echo $control['label']; ?></label>
		<?php foreach ( $field[ 'options' ] as $option_key => $option ) : ?>
		<span class="customize-inside-control-row">
			<input type="radio" name="<?php echo $control['field']; ?>" id="<?php echo $control['field']; ?>_<?php echo esc_attr( $option_key ); ?>" value="<?php echo esc_attr( $option_key ); ?>" data-attribute="<?php echo $control['field']; ?>" <?php echo ( isset( $field['target_control_class'] ) ) ? ' data-target-control-class="'. $field['target_control_class'] .'" ' : ''; ?><% if (<?php echo $control['field']; ?> === '<?php echo esc_attr( $option_key ); ?>') { %>checked="checked"<% } %>>
			<label for="<?php echo $control['field']; ?>_<?php echo esc_attr( $option_key ); ?>">
				<span class="ui-button-text"></span><?php echo esc_attr( $option ); ?></span>
			</label>
		</span>
		<?php endforeach; ?>
	</div>
</li>
