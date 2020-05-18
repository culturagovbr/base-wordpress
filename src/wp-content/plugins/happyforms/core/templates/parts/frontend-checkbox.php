<div class="<?php happyforms_the_part_class( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>-part" <?php happyforms_the_part_data_attributes( $part, $form ); ?>>
	<div class="happyforms-part-wrap">
		<?php happyforms_the_part_label( $part, $form ); ?>

		<div class="happyforms-part__el">
			<?php do_action( 'happyforms_part_input_before', $part, $form ); ?>

			<?php if ( 1 == $part['show_select_all'] ) : ?>
				<div class="happyforms-part__option happyforms-part-option">
					<label class="option-label">
						<input type="checkbox" class="happyforms-visuallyhidden happyforms-select-all">
						<span class="checkmark"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path fill="currentColor" d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></span>
						<span class="label"><?php _e( 'Select all', 'happyforms' ); ?></span>
					</label>
				</div>
			<?php endif; ?>

			<?php
			$options = happyforms_get_part_options( $part['options'], $part, $form );
			$value = happyforms_get_part_value( $part, $form );

			foreach( $options as $o => $option ) : ?>
			<div class="happyforms-part__option happyforms-part-option" id="<?php echo esc_attr( $option['id'] ); ?>">
				<?php
				$checked = in_array( $o, $value ) ? 'checked="checked"' : '';

				if ( empty( $checked ) ) {
					$checked = checked( 1, $option['is_default'], false );
				}
				?>
				<label class="option-label">
					<input type="checkbox" class="happyforms-visuallyhidden happyforms-checkbox" name="<?php happyforms_the_part_name( $part, $form ); ?>[]" value="<?php echo $o; ?>" data-serialize <?php echo $checked; ?> <?php happyforms_the_part_attributes( $part, $form ); ?>>
					<span class="checkmark"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path fill="currentColor" d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></span>
					<span class="label"><?php echo esc_attr( $option['label'] ); ?></span>
				</label>
				<span class="happyforms-part-option__description"><?php echo esc_attr( $option['description'] ); ?></span>
			</div>
			<?php endforeach; ?>

			<?php do_action( 'happyforms_part_input_after', $part, $form ); ?>

			<?php happyforms_print_part_description( $part ); ?>

			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ) ); ?>
		</div>
	</div>
</div>
