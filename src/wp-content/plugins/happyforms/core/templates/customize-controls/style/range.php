<li class="customize-control <?php echo esc_attr( 'happyforms-' . $control['type'] . '-control' ); ?>" data-target="<?php echo esc_attr( $field['target'] ); ?>" data-variable="<?php echo $field['variable']; ?>" data-unit="<?php echo $field['unit']; ?>" id="customize-control-<?php echo $control['field']; ?>">
	<label class="customize-control-title" for="<?php echo $control['field']; ?>"><?php echo $control['label']; ?></label>
	<div class="happyforms-range-control-wrap<?php echo ( isset( $field['include_unit_switch'] ) ) ? ' has-unit-switch': ''; ?>">
		<?php if ( isset( $field['include_unit_switch'] ) ) : ?>
		<div class="happyforms-range-control__unit">
			<select name="<?php echo $control['field']; ?>_unit" id="<?php echo $control['field']; ?>_unit" class="happyforms-unit-switch">
				<?php if ( is_array( $field['units'] ) ) :
					foreach ( $field['units'] as $unit ) : ?>
				<option value="<?php echo $unit; ?>"><?php echo $unit; ?></option>
				<?php endforeach; endif; ?>
			</select>
		</div>
		<?php endif; ?>
		<input type="range" name="<?php echo $control['field']; ?>" id="<?php echo $control['field']; ?>" min="<?php echo $field['min']; ?>" max="<?php echo $field['max']; ?>" step="<?php echo $field['step']; ?>" value="<%= <?php echo $control['field']; ?> %>" data-attribute="<?php echo $control['field']; ?>">
	</div>
</li>
