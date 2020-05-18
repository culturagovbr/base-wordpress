<li class="customize-control <?php echo esc_attr( 'happyforms-' . $control['type'] . '-control' ); ?>" data-target="<?php echo esc_attr( $field['target'] ); ?>" id="customize-control-<?php echo $control['field']; ?>">
	<label class="customize-control-title" for="<?php echo $control['field']; ?>"><?php echo $control['label']; ?></label>
	<div class="customize-control-content">
		<select name="<?php echo $control['field']; ?>" id="<?php echo $control['field']; ?>" data-attribute="<?php echo $control['field']; ?>" class="widefat">
			<?php
			foreach ( $field['options'] as $option_key => $option ) : ?>
				<option value="<?php echo esc_attr( $option_key ); ?>" <% if (<?php echo $control['field']; ?> === '<?php echo esc_attr( $option_key ); ?>') {%><%= 'selected' %><% } %>><?php echo esc_attr( $option ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</li>