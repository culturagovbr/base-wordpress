<div class="<?php happyforms_the_part_class( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>-part" <?php happyforms_the_part_data_attributes( $part, $form ); ?>>
	<div class="happyforms-part-wrap">
		<?php
		$input_group = false;
		$has_prefix = ( '' !== $part['mask_numeric_prefix'] );
		$has_suffix = ( '' !== $part['mask_numeric_suffix'] );
		$early_label = true;

		if ( $has_prefix || $has_suffix ) {
			$input_group = true;
		}

		if ( 'as_placeholder' === $part['label_placement'] ) {
			$early_label = false;
		}

		if ( $has_prefix && 'inside' === $part['label_placement'] ) {
			$early_label = false;
		}
		?>

		<?php if ( $early_label ) : ?>
			<?php happyforms_the_part_label( $part, $form ); ?>
		<?php endif; ?>

		<div class="happyforms-part__el">
			<?php do_action( 'happyforms_part_input_before', $part, $form ); ?>

			<?php if ( $input_group ) : ?>
				<div class="happyforms-input-group<?php echo ( $has_prefix ) ? ' with-prefix' : ''; ?><?php echo ( $has_suffix ) ? ' with-suffix': '' ?>">
					<?php if ( $has_prefix ) : ?>
						<div class="happyforms-input-group__prefix">
							<span><?php echo $part['mask_numeric_prefix']; ?></span>
						</div>
					<?php endif; ?>
			<?php endif; ?>

			<div class="happyforms-input">
				<?php if ( ! $early_label && 'as_placeholder' !== $part['label_placement'] ) : ?>
					<?php happyforms_the_part_label( $part, $form ); ?>
				<?php endif; ?>
				<input id="<?php happyforms_the_part_id( $part, $form ); ?>" type="number" value="<?php happyforms_the_part_value( $part, $form, 0 ); ?>" name="<?php happyforms_the_part_name( $part, $form ); ?>" placeholder="<?php echo esc_attr( $part['placeholder'] ); ?>" min="<?php echo esc_attr( $part['min_value'] ) ?>" max="<?php echo esc_attr( $part['max_value'] ); ?>" <?php happyforms_the_part_attributes( $part, $form, 0 ); ?> />
				<?php if ( 'as_placeholder' === $part['label_placement'] ) : ?>
					<?php happyforms_the_part_label( $part, $form ); ?>
				<?php endif; ?>
			</div>

			<?php if ( $input_group ) : ?>
				<?php if ( $has_suffix ) : ?>
					<div class="happyforms-input-group__suffix">
						<span><?php echo $part['mask_numeric_suffix']; ?></span>
					</div>
				<?php endif; ?>

				</div><!-- /.happyforms-input-group -->
			<?php endif; ?>

			<?php do_action( 'happyforms_part_input_after', $part, $form ); ?>

			<?php happyforms_print_part_description( $part ); ?>
			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ) ); ?>
		</div>
	</div>
	<?php if ( 1 === intval( $part['confirmation_field'] ) ) : ?>
	<div class="happyforms-part-wrap happyforms-part-wrap--confirmation" id="<?php happyforms_the_part_id( $part, $form ); ?>-part_confirmation">
		<?php if ( $early_label ) : ?>
			<?php happyforms_the_part_confirmation_label( $part, $form ); ?>
		<?php endif; ?>

		<div class="happyforms-part__el">
			<?php if ( $input_group ) : ?>
				<div class="happyforms-input-group<?php echo ( $has_prefix ) ? ' with-prefix' : ''; ?><?php echo ( $has_suffix ) ? ' with-suffix': '' ?>">
					<?php if ( $has_prefix ) : ?>
						<div class="happyforms-input-group__prefix">
							<span><?php echo $part['mask_numeric_prefix']; ?></span>
						</div>
					<?php endif; ?>
			<?php endif; ?>

			<div class="happyforms-input">
				<?php if ( ! $early_label && 'as_placeholder' !== $part['label_placement'] ) : ?>
					<?php happyforms_the_part_confirmation_label( $part, $form ); ?>
				<?php endif; ?>
				<input id="<?php happyforms_the_part_id( $part, $form ); ?>_confirmation" class="happyforms-confirmation-input" type="number" name="<?php happyforms_the_part_name( $part, $form ); ?>_confirmation" value="<?php happyforms_the_part_value( $part, $form, 1 ); ?>"  placeholder="<?php echo esc_attr( $part['confirmation_field_placeholder'] ); ?>" min="<?php echo esc_attr( $part['min_value'] ); ?>" max="<?php echo esc_attr( $part['max_value'] ); ?>" value="<?php echo esc_attr( $part['min_value'] ); ?>" <?php happyforms_the_part_attributes( $part, $form, 1 ); ?> />
				<?php if ( 'as_placeholder' === $part['label_placement'] ) : ?>
					<?php happyforms_the_part_confirmation_label( $part, $form ); ?>
				<?php endif; ?>
			</div>

			<?php if ( $input_group ) : ?>
				<?php if ( $has_suffix ) : ?>
					<div class="happyforms-input-group__suffix">
						<span><?php echo $part['mask_numeric_suffix']; ?></span>
					</div>
				<?php endif; ?>

				</div><!-- /.happyforms-input-group -->
			<?php endif; ?>

			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ), 1 ); ?>
		</div>
	</div>
	<?php endif; ?>
</div>
