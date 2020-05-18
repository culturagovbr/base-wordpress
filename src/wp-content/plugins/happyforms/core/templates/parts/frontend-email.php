<div class="<?php happyforms_the_part_class( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>-part" <?php happyforms_the_part_data_attributes( $part, $form ); ?>>
	<?php
	$autocomplete_domains = ( 1 == $part['autocomplete_domains'] );
	$has_suffix = ( '' !== $part['suffix'] );
	$early_label = true;

	if ( $has_suffix ) {
		$autocomplete_domains = false;
	}

	if ( 'as_placeholder' === $part['label_placement'] ) {
		$early_label = false;
	}
	?>
	<div class="happyforms-part-wrap">
		<?php if ( $early_label ) : ?>
			<?php happyforms_the_part_label( $part, $form ); ?>
		<?php endif; ?>

		<div class="happyforms-part__el">
			<?php do_action( 'happyforms_part_input_before', $part, $form ); ?>

			<?php if ( $autocomplete_domains ) : ?>
				<input type="hidden" name="<?php happyforms_the_part_name( $part, $form ); ?>" value="<?php happyforms_the_part_value( $part, $form, 0 ); ?>" data-serialize />

				<input type="email" name="<?php happyforms_the_part_id( $part, $form ); ?>_dummy_<?php echo time(); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>" value="<?php happyforms_the_part_value( $part, $form, 0 ); ?>" autocomplete="none" placeholder="<?php echo esc_attr( $part['placeholder'] ); ?>" <?php happyforms_the_part_attributes( $part, $form, 0 ); ?> />

				<?php if ( 'as_placeholder' === $part['label_placement'] ) : ?>
					<?php happyforms_the_part_label( $part, $form ); ?>
				<?php endif; ?>
			<?php else: ?>
				<?php if ( $has_suffix ) : ?>
					<div class="happyforms-input-group with-suffix">
				<?php endif; ?>

				<div class="happyforms-input">
					<input type="email" name="<?php happyforms_the_part_name( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>" value="<?php happyforms_the_part_value( $part, $form, 0 ); ?>" placeholder="<?php echo esc_attr( $part['placeholder'] ); ?>" <?php happyforms_the_part_attributes( $part, $form, 0 ); ?> />
					<?php if ( 'as_placeholder' === $part['label_placement'] ) : ?>
						<?php happyforms_the_part_label( $part, $form ); ?>
					<?php endif; ?>
				</div>

				<?php if ( $has_suffix ) : ?>
					<div class="happyforms-input-group__suffix">
						<span><?php echo $part['suffix']; ?></span>
					</div>

				</div><!-- /.happyforms-input-group -->
				<?php endif; ?>
			<?php endif; ?>

			<?php
			if ( $autocomplete_domains ) {
				happyforms_select( array(), $part, $form );
			}
			?>

			<?php happyforms_print_part_description( $part ); ?>
			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ) ); ?>

			<?php do_action( 'happyforms_part_input_after', $part, $form ); ?>
		</div>
	</div>
	<?php if ( 1 === intval( $part['confirmation_field'] ) ) : ?>
	<div class="happyforms-part-wrap happyforms-part-wrap--confirmation" id="<?php happyforms_the_part_id( $part, $form ); ?>-part_confirmation">
		<?php if ( $early_label ) : ?>
			<?php happyforms_the_part_confirmation_label( $part, $form ); ?>
		<?php endif; ?>

		<div class="happyforms-part__el">
			<?php if ( $autocomplete_domains ) : ?>
				<input type="hidden" name="<?php happyforms_the_part_name( $part, $form ); ?>_confirmation" value="<?php happyforms_the_part_value( $part, $form, 1 ); ?>" data-serialize />

				<input type="email" name="<?php happyforms_the_part_id( $part, $form ); ?>_dummy_<?php echo time(); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>_confirmation" placeholder="<?php echo esc_attr( $part['confirmation_field_placeholder'] ); ?>" value="<?php happyforms_the_part_value( $part, $form, 1 ); ?>" autocomplete="none" <?php happyforms_the_part_attributes( $part, $form, 1 ); ?> />

				<?php if ( 'as_placeholder' === $part['label_placement'] ) : ?>
					<?php happyforms_the_part_confirmation_label( $part, $form ); ?>
				<?php endif; ?>
			<?php else: ?>
				<?php if ( $has_suffix ) : ?>
					<div class="happyforms-input-group with-suffix">
				<?php endif; ?>

				<div class="happyforms-input">
					<input type="email" id="<?php happyforms_the_part_id( $part, $form ); ?>_confirmation" name="<?php happyforms_the_part_name( $part, $form ); ?>_confirmation" placeholder="<?php echo esc_attr( $part['confirmation_field_placeholder'] ); ?>" value="<?php happyforms_the_part_value( $part, $form, 1 ); ?>" class="happyforms-confirmation-input" data-confirmation-of="<?php echo esc_attr( $part['id'] ); ?>" <?php happyforms_the_part_attributes( $part, $form, 1 ); ?> />

					<?php if ( 'as_placeholder' === $part['label_placement'] ) : ?>
						<?php happyforms_the_part_confirmation_label( $part, $form ); ?>
					<?php endif; ?>
				</div>

				<?php if ( $has_suffix ) : ?>
					<div class="happyforms-input-group__suffix">
						<span><?php echo $part['suffix']; ?></span>
					</div>

				</div><!-- /.happyforms-input-group -->
				<?php endif; ?>
			<?php endif; ?>

			<?php
			if ( $autocomplete_domains ) {
				happyforms_select( array(), $part, $form );
			}
			?>

			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ), 1 ); ?>
		</div>
	</div>
	<?php endif; ?>
</div>
