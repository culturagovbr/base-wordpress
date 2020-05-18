<ul class="happyforms-custom-select-dropdown">
	<li class="happyforms-custom-select-dropdown__placeholder" data-value=""><?php echo $placeholder_value; ?></li>
	<?php foreach ( $options as $index => $option ) : ?>
		<li <?php echo ( isset( $option['id'] ) ) ? 'data-option-id="'. esc_attr( $option['id'] ) .'" ' : ''; ?>data-value="<?php echo ( isset( $option['value'] ) ) ? $option['value'] : $index; ?>" data-label="<?php echo esc_attr( $option['label'] ); ?>" class="happyforms-dropdown-item happyforms-custom-select-dropdown__item"><?php echo esc_attr( $option['label'] ); ?></li>
	<?php endforeach; ?>
	<?php if ( isset( $part['allow_search'] ) && 1 == $part['allow_search'] ) : ?>
		<li class="happyforms-custom-select-dropdown__not-found"><?php echo esc_attr( $part['no_results_label'] ); ?></li>
	<?php endif; ?>
</ul>
