<?php

if ( ! function_exists( 'happyforms_form_field' ) ):
/**
 * Output a hidden field with the current form ID.
 *
 * @since 1.0
 *
 * @param int|string $id The id of the current form.
 *
 * @return void
 */
function happyforms_form_field( $id ) {
	$parameter = happyforms_get_message_controller()->form_parameter; ?>
	<input type="hidden" name="<?php echo esc_attr( $parameter ); ?>" value="<?php echo esc_attr( $id ); ?>" />
	<?php
}

endif;

if ( ! function_exists( 'happyforms_action_field' ) ):
/**
 * Output a form's action attribute.
 *
 * @since 1.0
 *
 * @return void
 */
function happyforms_action_field() {
	$action = happyforms_get_message_controller()->submit_action; ?>
	<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
	<?php
}

endif;

if ( ! function_exists( 'happyforms_nonce_field' ) ):
/**
 * Output the nonce field for the current form.
 *
 * @since 1.0
 *
 * @param array $form Current form data.
 *
 * @return void
 */
function happyforms_nonce_field( $form ) {
	$prefix = happyforms_get_message_controller()->nonce_prefix;
	$name = happyforms_get_message_controller()->nonce_name;
	$form_id = $form['ID'];
	$action = "{$prefix}{$form_id}";

	wp_nonce_field( $action, $name );
}

endif;

if ( ! function_exists( 'happyforms_honeypot' ) ) :

function happyforms_honeypot( $form ) {
	$controller = happyforms_get_form_controller();

	if ( $controller->has_spam_protection( $form ) ) : ?>
	<label>
		<input type="checkbox" name="<?php echo $form['ID']; ?>single_line_text_-1" value="1" style="display: none;" tabindex="-1" autocomplete="off"> <span class="screen-reader-text"><?php _e( 'Spam protection, skip this field', 'happyforms' ); ?></span>
	</label>
	<?php endif;
}

endif;

if ( ! function_exists( 'happyforms_submit' ) ):
/**
 * Output the form submit button.
 *
 * @since 1.0
 *
 * @param array $form Current form data.
 *
 * @return void
 */
function happyforms_submit( $form ) {
	$template_path = happyforms_get_core_folder() . '/templates/partials/form-submit.php';
	$template_path = apply_filters( 'happyforms_get_submit_template_path', $template_path, $form );
	include( $template_path );
}

endif;

if ( ! function_exists( 'happyforms_message_notices' ) ):
/**
 * Output notices for the current submission,
 * related to the form.
 *
 * @since 1.0
 *
 * @param string $location The notice location to display.
 *
 * @return void
 */
function happyforms_message_notices( $location = '' ) {
	$notices = happyforms_get_session()->get_messages( $location );

	happyforms_the_message_notices( $notices );
}

endif;

if ( ! function_exists( 'happyforms_part_error_message' ) ):
/**
 * Output error message related to part.
 *
 * @since 1.0
 *
 * @param string $part_name Full part name to check for.
 *
 * @return void
 */
function happyforms_part_error_message( $part_name = '', $component = 0 ) {
	$notices = happyforms_get_session()->get_messages( $part_name );

	happyforms_the_part_error_message( $notices, $part_name, $component );
}

endif;

if ( ! function_exists( 'happyforms_the_message_notices' ) ):
/**
 * Output notices.
 *
 * @param string $notices A list of notices to display.
 *
 * @return void
 */
function happyforms_the_message_notices( $notices = array(), $class = '' ) {
	if ( ! empty( $notices ) ) : ?>
		<div class="happyforms-message-notices <?php echo $class; ?>">
			<?php foreach( $notices as $notice ): ?>
			<div class="happyforms-message-notice <?php echo esc_attr( $notice['type'] ); ?>">
				<h2><?php echo $notice['message']; ?></h2>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
	endif;
}

endif;

if ( ! function_exists( 'happyforms_the_part_error_message' ) ):
/**
 * Output part error message.
 *
 * @param string $notices A list of notices to display.
 * @param string $part_name Full name of the part to display notice for.
 *
 * @return void
 */
function happyforms_the_part_error_message( $notices = array(), $part_name = '', $component = 0 ) {
	if ( ! empty( $notices ) ) : ?>
		<?php
		$notice_id = "happyforms-error-{$part_name}";
		$notice_id = ( $component ) ? "{$notice_id}_{$component}" : $notice_id;
		?>
		<div class="happyforms-part-error-notice" id="<?php echo $notice_id; ?>">
			<?php
			foreach( $notices as $notice ) :
				if ( is_array( $notice['message'] ) && isset( $notice['message'][$component] ) ) {
					$message = $notice['message'][$component];
				} elseif ( ! is_array( $notice['message'] ) && 0 === $component ) {
					$message = $notice['message'];
				} else {
					continue;
				}
			?>
				<p><svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z" class=""></path></svg> <?php echo $message; ?></p>
			<?php endforeach; ?>
		</div>
		<?php
	endif;
}

endif;

if ( ! function_exists( 'happyforms_print_part_description' ) ):
/**
 * Output description of the part.
 *
 * @since 1.1
 *
 * @param array  $part_data Form part data.
 *
 * @return void
 */
function happyforms_print_part_description( $part_data ) {
	if ( happyforms_part_has_tooltip( $part_data ) || is_customize_preview() ) : ?>
		<span class="happyforms-part__tooltip happyforms-tooltip"<?php if ( ! happyforms_part_has_tooltip( $part_data ) ) : ?>  style="display: none"<?php endif; ?>>
			<span class="happyforms-tooltip__trigger"></span>
			<span class="happyforms-part__description"><?php echo esc_html( $part_data['description'] ); ?></span>
		</span>
	<?php endif; ?>
	<?php if ( ! happyforms_part_has_tooltip( $part_data ) || is_customize_preview() ) : ?>
		<span class="happyforms-part__description"<?php if ( is_customize_preview() && happyforms_part_has_tooltip( $part_data ) ) : ?> style="display: none"<?php endif; ?>><?php echo esc_html( $part_data['description'] ); ?></span>
	<?php endif;
}

endif;

if ( ! function_exists( 'happyforms_part_has_tooltip' ) ) :

function happyforms_part_has_tooltip( $part ) {
	if ( ( isset( $part['description_mode'] )
		&& 'tooltip' === $part['description_mode'] )
		|| ( isset( $part['tooltip_description'] )
		&& 1 === intval( $part['tooltip_description'] ) ) ) {

		return true;
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_form_action' ) ):
/**
 * Returns the action for this form.
 *
 * @since 1.1
 *
 * @param array $form_id Current form id.
 *
 * @return string
 */
function happyforms_get_form_action( $form_id ) {
	/**
	 * Filter the action for this form.
	 *
	 * @since 1.1
	 *
	 * @param string $value The default action, an empty string.
	 *
	 * @return string The filtered action value.
	 */
	return apply_filters( 'happyforms_form_action', '', $form_id );
}

endif;

if ( ! function_exists( 'happyforms_form_action' ) ):
/**
 * Prints the action for this form.
 *
 * @since 1.1
 *
 * @param array $form_id Current form id.
 *
 * @return void
 */
function happyforms_form_action( $form_id ) {
	echo happyforms_get_form_action( $form_id );
}

endif;

if ( ! function_exists( 'happyforms_get_part_name' ) ):
/**
 * Returns the current form part field name.
 *
 * @since 1.1
 *
 * @param array $part_id Current part data.
 * @param array $form_id Current form data.
 *
 * @return string
 */
function happyforms_get_part_name( $part, $form ) {
	$name = $form['ID'] . '_' . $part['id'];

	/**
	 * Filter the field name for this form part.
	 *
	 * @since 1.1
	 *
	 * @param string $name The default name.
	 * @param array  $part Current part data.
	 * @param array  $form Current form data.
	 *
	 * @return string The filtered part name.
	 */
	return apply_filters( 'happyforms_part_name', $name, $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_the_part_name' ) ):
/**
 * Output the current form part field name.
 *
 * @since 1.3
 *
 * @param array $part Current part data.
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_the_part_name( $part, $form ) {
	echo esc_attr( happyforms_get_part_name( $part, $form ) );
}

endif;

if ( ! function_exists( 'happyforms_get_part_value' ) ):
/**
 * Returns the default submission value for this form part.
 *
 * @since 1.4
 *
 * @param array  $part_id   Current part data.
 * @param array  $form_id   Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return string
 */
function happyforms_get_part_value( $part, $form, $component = false, $empty = '' ) {
	$default_value = happyforms_get_part_library()->get_part_default_value( $part );

	/**
	 * Filter the default submission value for this form part.
	 *
	 * @since 1.4
	 *
	 * @param string $value     The default value.
	 * @param array  $part      Current part data.
	 * @param array  $form      Current form data.
	 * @param string $component An optional part sub-component.
	 *
	 * @return string The filtered part name.
	 */
	$default_value = apply_filters( 'happyforms_part_value', $default_value, $part, $form );
	$part_name = happyforms_get_part_name( $part, $form );
	$session_value = happyforms_get_session()->get_value( $part_name );
	$value = ( false !== $session_value ) ? $session_value : $default_value;

	if ( false !== $component && is_array( $value ) ) {
		$value = isset( $value[$component] ) ? $value[$component] : $empty;
	}

	return $value;
}

endif;

if ( ! function_exists( 'happyforms_the_part_value' ) ):
/**
 * Output the default submission value for this form part.
 *
 * @since 1.4
 *
 * @param array  $part      Current part data.
 * @param array  $form      Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return void
 */
function happyforms_the_part_value( $part, $form, $component = false ) {
	$value = happyforms_get_part_value( $part, $form, $component );
	$value = apply_filters( 'happyforms_the_part_value', $value, $part, $form );

	echo esc_attr( $value );
}

endif;

if ( ! function_exists( 'happyforms_get_part_preview_value' ) ):
/**
 * Get the submitted part value in form preview context.
 *
 * @since 1.4
 *
 * @param array  $part      Current part data.
 * @param array  $form      Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return void
 */
function happyforms_get_part_preview_value( $part, $form ) {
	$part_class = happyforms_get_part_library()->get_part( $part['type'] );
	$part_value = happyforms_get_part_value( $part, $form );
	$validated_value = $part_class->validate_value( $part_value, $part, $form );
	$value = happyforms_stringify_part_value( $validated_value, $part, $form );
	$value = happyforms_get_message_part_value( $value, $part );

	return $value;
}

endif;

if ( ! function_exists( 'happyforms_the_part_preview_value' ) ):
/**
 * Output the submitted part value in form preview context.
 *
 * @since 1.4
 *
 * @param array  $part      Current part data.
 * @param array  $form      Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return void
 */
function happyforms_the_part_preview_value( $part, $form ) {
	echo happyforms_get_part_preview_value( $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_get_part_attributes' ) ):
/**
 * Returns additional HTML attributes for this form part.
 *
 * @since 1.4
 *
 * @param array  $part_id   Current part data.
 * @param array  $form_id   Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return string
 */
function happyforms_get_part_attributes( $part, $form, $component = false ) {
	/**
	 * Filter the default submission value for this form part.
	 *
	 * @since 1.4
	 *
	 * @param string $value     The default value.
	 * @param array  $part      Current part data.
	 * @param array  $form      Current form data.
	 * @param string $component An optional part sub-component.
	 *
	 * @return string The filtered part attributes.
	 */
	return apply_filters( 'happyforms_part_attributes', array(), $part, $form, $component );
}

endif;

if ( ! function_exists( 'happyforms_the_part_attributes' ) ):
/**
 * Output additional HTML attributes for this form part.
 *
 * @since 1.4
 *
 * @param array  $part      Current part data.
 * @param array  $form      Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return void
 */
function happyforms_the_part_attributes( $part, $form, $component = false ) {
	$attributes = happyforms_get_part_attributes( $part, $form, $component );
	$attributes = implode( ' ', $attributes );

	echo $attributes;
}

endif;

if ( ! function_exists( 'happyforms_get_form_title' ) ):
/**
 * Return the form title.
 *
 * @since 1.3
 *
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_get_form_title( $form ) {
	return esc_html( $form['post_title'] );
}

endif;

if ( ! function_exists( 'happyforms_the_form_title' ) ):
/**
 * Output the form title.
 *
 * @since 1.3
 *
 * @param array  $form   Current form data.
 *
 * @return void
 */
function happyforms_the_form_title( $form ) {
	$classes = 'happyforms-form__title';
	$classes = apply_filters( 'happyforms_form_title_classes', $classes );

	$before = '<h3 class="'. $classes .'">';
	$after = '</h3>';
	$title = happyforms_get_form_title( $form );
	$form_title = "{$before}{$title}{$after}";

	/**
	 * Filter the output of a form title.
	 *
	 * @since 1.3
	 *
	 * @param string $form_title Current title markup.
	 * @param string $before     Content to output before the title.
	 * @param string $after      Content to output after the title.
	 * @param array  $form       Current form data.
	 *
	 * @return void
	 */
	$form_title = apply_filters( 'happyforms_the_form_title', $form_title, $before, $after, $form );

	echo $form_title;
}

endif;

if ( ! function_exists( 'happyforms_get_form_wrapper_id' ) ) :
/**
 * Get form wrapper's HTML ID.
 *
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_get_form_wrapper_id( $form ) {
	$id = 'happyforms-' . esc_attr( $form['ID'] );

	return apply_filters( 'happyforms_form_id', $id, $form );
}

endif;

if ( ! function_exists( 'happyforms_get_form_id' ) ):
/**
 * Get a form's html id.
 *
 * @param array $form    Current form data.
 *
 * @return string
 */
function happyforms_get_form_id( $form ) {
	/**
	 * Filter the id a form element.
	 *
	 * @param string $id    Current id.
	 * @param array $form   Current form data.
	 *
	 * @return string
	 */
	$id = 'happyforms-form-' . esc_attr( $form['ID'] );

	return $id;
}

endif;

if ( ! function_exists( 'happyforms_the_form_id' ) ):
/**
 * Output a form's html id.
 *
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_the_form_id( $form ) {
	echo happyforms_get_form_id( $form );
}

endif;

if ( ! function_exists( 'happyforms_get_form_container_id' ) ):
	/**
	 * Get a form's html id.
	 *
	 * @param array $form    Current form data.
	 *
	 * @return string
	 */
	function happyforms_get_form_container_id( $form ) {
		/**
		 * Filter the id a form container element.
		 *
		 * @param string $id    Current id.
		 * @param array $form   Current form data.
		 *
		 * @return string
		 */
		$id = 'happyforms-' . esc_attr( $form['ID'] );
		$id = apply_filters( 'happyforms_form_id', $id, $form );

		return $id;
	}

	endif;

	if ( ! function_exists( 'happyforms_the_form_container_id' ) ):
	/**
	 * Output a form's container html id.
	 *
	 * @param array $form Current form data.
	 *
	 * @return string
	 */
	function happyforms_the_form_container_id( $form ) {
		echo happyforms_get_form_container_id( $form );
	}

	endif;

if ( ! function_exists( 'happyforms_get_form_class' ) ):
/**
 * Get a form's html class.
 *
 * @since 1.3
 *
 * @param array $form    Current form data.
 *
 * @return string
 */
function happyforms_get_form_class( $form ) {
	/**
	 * Filter the list of classes of a form element.
	 *
	 * @since 1.3
	 *
	 * @param array $classes List of current classes.
	 * @param array $form    Current form data.
	 *
	 * @return string
	 */
	$classes = apply_filters( 'happyforms_form_class', array(), $form );
	$classes = implode( ' ', $classes );

	return $classes;
}

endif;

if ( ! function_exists( 'happyforms_the_form_class' ) ):
/**
 * Output a form's html class.
 *
 * @since 1.3
 *
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_the_form_class( $form ) {
	echo happyforms_get_form_class( $form );
}

endif;

if ( ! function_exists( 'happyforms_get_form_part' ) ):
/**
 * Get a part block markup.
 *
 * @since 1.3
 *
 * @param array $part Current part data.
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_get_form_part( $part, $form ) {
	$html = happyforms_get_part_library()->get_part_template( $part, $form );

	return $html;
}

endif;

if ( ! function_exists( 'happyforms_the_form_part' ) ):
/**
 * Output a part block.
 *
 * @since 1.3
 *
 * @param array $part Current part data.
 * @param array $form Current form data.
 *
 * @return void
 */
function happyforms_the_form_part( $part, $form ) {
	do_action( 'happyforms_part_before', $part, $form );
	echo happyforms_get_form_part( $part, $form );
	do_action( 'happyforms_part_after', $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_get_part_class' ) ):
/**
 * Get a part wrapper's html classe.
 *
 * @since 1.3
 *
 * @param array $part    Current part data.
 * @param array $form    Current form data.
 *
 * @return string
 */
function happyforms_get_part_class( $part, $form ) {
	/**
	 * Filter the list of classes of a form part element.
	 *
	 * @since 1.3
	 *
	 * @param array $classes List of current classes.
	 * @param array $part    Current part data.
	 * @param array $form    Current form data.
	 *
	 * @return string
	 */
	$classes = apply_filters( 'happyforms_part_class', array(), $part, $form );
	$classes = implode( ' ', $classes );

	return $classes;
}

endif;

if ( ! function_exists( 'happyforms_the_part_class' ) ):
/**
 * Output a part wrapper's html class.
 *
 * @since 1.3
 *
 * @param array $part Current part data.
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_the_part_class( $part, $form ) {
	echo happyforms_get_part_class( $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_get_part_id' ) ):
/**
 * Get a part wrapper's id.
 *
 * @since 1.3
 *
 * @param string $part_id Current part id.
 * @param string $form_id Current form id.
 *
 * @return string
 */
function happyforms_get_part_id( $part_id, $form_id ) {
	$id = esc_attr( 'happyforms-' . $form_id . '_' . $part_id );

	/**
	 * Filter the html id of a form part element.
	 *
	 * @since 1.3
	 *
	 * @param string $id         Current part id.
	 * @param string $part_id    Current part id.
	 * @param string $form_id    Current form id.
	 *
	 * @return string
	 */
	$id = apply_filters( 'happyforms_part_id', $id, $part_id, $form_id );

	return $id;
}

endif;

if ( ! function_exists( 'happyforms_the_part_id' ) ):
/**
 * Outputs a part wrapper's id.
 *
 * @since 1.3
 *
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return string
 */
function happyforms_the_part_id( $part, $form ) {
	echo happyforms_get_part_id( $part['id'], $form['ID'] );
}

endif;

if ( ! function_exists( 'happyforms_get_form_styles' ) ):
/**
 * Get a form's styles.
 *
 * @since 1.4.5
 *
 * @param array $form Current form data.
 *
 * @return array
 */
function happyforms_get_form_styles( $form ) {
	$styles = happyforms_get_styles()->form_html_styles( $form );

	/**
	 * Filter the css styles of a form.
	 *
	 * @since 1.4.5
	 *
	 * @param array $styles Current styles attributes.
	 * @param array $form   Current form data.
	 *
	 * @return array
	 */
	$styles = apply_filters( 'happyforms_form_styles', $styles, $form );

	return $styles;
}

endif;

if ( ! function_exists( 'happyforms_the_form_styles' ) ):
/**
 * Output a form's styles.
 *
 * @since 1.4.5
 *
 * @param array $form Current form data.
 *
 * @return array
 */
function happyforms_the_form_styles( $form ) {
	HappyForms()->print_frontend_styles( $form );
	$styles = happyforms_get_form_styles( $form );
	?>
	<!-- HappyForms CSS variables -->
	<style>
	#<?php happyforms_the_form_container_id( $form ); ?> {
		<?php foreach( $styles as $key => $style ) {
			$variable = $style['variable'];
			$value = $form[$key];
			$unit = isset( $style['unit'] ) ? $style['unit']: '';

			echo "{$variable}: {$value}{$unit};\n";
		} ?>
	}
	</style>
	<!-- End of HappyForms CSS variables -->
	<?php
}

endif;

if ( ! function_exists( 'happyforms_additional_css' ) ):
/**
 * Output a form's styles.
 *
 * @param array $form Current form data.
 */
function happyforms_additional_css( $form ) {
	$additional_css = happyforms_get_meta( $form['ID'], 'additional_css', true );

	if ( ! $additional_css ) {
		return;
	}

	$form_wrapper_id = happyforms_get_form_wrapper_id( $form );
	$additional_css = happyforms_get_prefixed_css( $additional_css, "#{$form_wrapper_id}" );
	?>
	<!-- HappyForms Additional CSS -->
	<style>
	<?php echo $additional_css; ?>
	</style>
	<!-- End of HappyForms Additional CSS -->
	<?php
}

endif;

if ( ! function_exists( 'happyforms_get_form_property' ) ):
/**
 * Get a form property.
 *
 * @since 1.3
 *
 * @param array  $form Current form data.
 * @param string $key  The key to retrieve the style for.
 *
 * @return string
 */
function happyforms_get_form_property( $form, $key ) {
	if ( is_array( $form ) ) {
		$value = isset( $form[$key] ) ? $form[$key] : '';
		$value = is_numeric( $value ) ? intval( $value ) : $value;
	} else {
		$value = happyforms_get_meta( $form, $key, true );
	}

	return $value;
}

endif;

if ( ! function_exists( 'happyforms_get_part_data_attributes' ) ) :
/**
 * Get the html data- attributes of a form part element.
 *
 * @since 1.3
 *
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return array
 */
function happyforms_get_part_data_attributes( $part, $form ) {
	/**
	 * Filter the html data- attributes of a form part element.
	 *
	 * @since 1.3
	 *
	 * @param array  $attributes Current part attributes.
	 * @param string $part_id    Current part data.
	 * @param string $form_id    Current form data.
	 *
	 * @return string
	 */
	$attributes = apply_filters( 'happyforms_part_data_attributes', array(), $part, $form );
	$data = array();

	foreach ( $attributes as $attribute => $value ) {
		$data[] = "data-{$attribute}=\"{$value}\"";
	}

	$data = implode( ' ', $data );

	return $data;
}

endif;

if ( ! function_exists( 'happyforms_the_part_data_attributes' ) ) :
/**
 * Output a part's html data- attributes
 *
 * @since 1.3
 *
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return void
 */
function happyforms_the_part_data_attributes( $part, $form ) {
	echo happyforms_get_part_data_attributes( $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_the_part_label' ) ) :
/**
 * Output a part label
 *
 * @since 1.3
 *
 * @param string $id   Current part id.
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return void
 */
function happyforms_the_part_label( $part, $form ) {
	?>
	<label for="<?php happyforms_the_part_id( $part, $form ); ?>" class="happyforms-part__label">
		<span class="label"><?php echo esc_html( $part['label'] ); ?></span>
		<?php $is_required = isset( $part['required'] ) && 1 === intval( $part['required'] ); ?>
		<?php if ( ! $is_required || happyforms_is_preview_context() ): ?>
			<span class="happyforms-optional"><?php echo happyforms_get_form_property( $form, 'optional_part_label' ); ?></span>
		<?php endif; ?>
	</label>
	<?php
}

endif;

if ( ! function_exists( 'happyforms_the_part_confirmation_label' ) ) :
/**
 * Output a part confirmation label
 *
 * @since 1.3
 *
 * @param string $id   Current part id.
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return void
 */
function happyforms_the_part_confirmation_label( $part, $form ) {
	?>
	<label for="<?php happyforms_the_part_id( $part, $form ); ?>_confirmation" class="happyforms-part__label happyforms-part__label--confirmation">
		<span class="label"><?php echo esc_html( $part['confirmation_field_label'] ); ?></span>
		<?php if ( 1 === intval( $part['required'] ) ) : ?>
			<span class="happyforms-required"><?php echo happyforms_get_form_property( $form, 'hf_style_required_text' ); ?></span>
		<?php endif; ?>
	</label>
	<?php
}

endif;

if ( ! function_exists( 'happyforms_get_part_options' ) ) :

function happyforms_get_part_options( $options, $part, $form ) {
	$options = apply_filters( 'happyforms_part_options', $options, $part, $form );

	return $options;
}

endif;

if ( ! function_exists( 'happyforms_get_months' ) ) :

function happyforms_get_months( $form = array() ) {
	$months = array(
		1 => __( 'January', 'happyforms' ),
		2 => __( 'February', 'happyforms' ),
		3 => __( 'March', 'happyforms' ),
		4 => __( 'April', 'happyforms' ),
		5 => __( 'May', 'happyforms' ),
		6 => __( 'June', 'happyforms' ),
		7 => __( 'July', 'happyforms' ),
		8 => __( 'August', 'happyforms' ),
		9 => __( 'September', 'happyforms' ),
		10 => __( 'October', 'happyforms' ),
		11 => __( 'November', 'happyforms' ),
		12 => __( 'December', 'happyforms' )
	);

	$months = apply_filters( 'happyforms_get_months', $months, $form );

	return $months;
}

endif;

if ( ! function_exists( 'happyforms_get_days' ) ) :

function happyforms_get_days() {
	$days = apply_filters( 'happyforms_get_days', range( 1, 31 ) );

	return $days;
}

endif;

if ( ! function_exists( 'happyforms_get_site_date_format' ) ) :

function happyforms_get_site_date_format() {
	$site_date_format = get_option( 'date_format' );

	$format = 'day_first';

	if ( 0 === strpos( $site_date_format, 'F' ) || 0 === strpos( $site_date_format, 'm' ) ||
		0 === strpos( $site_date_format, 'M' ) || 0 === strpos( $site_date_format, 'n' ) ) {
		$format = 'month_first';
	}

	return apply_filters( 'happyforms_date_part_format', $format );
}

endif;

if ( ! function_exists( 'happyforms_get_phone_countries' ) ) :

function happyforms_get_phone_countries() {
	$countries = array(
		'AD' => array( 'name' => __( 'Andorra', 'happyforms' ), 'code' => '376', 'flag' => '🇦🇩' ),
		'AE' => array( 'name' => __( 'United Arab Emirates', 'happyforms' ), 'code' => '971', 'flag' => '🇦🇪' ),
		'AF' => array( 'name' => __( 'Afghanistan', 'happyforms' ), 'code' => '93', 'flag' => '🇦🇫' ),
		'AG' => array( 'name' => __( 'Antigua and Barbuda', 'happyforms' ), 'code' => '1268', 'flag' => '🇦🇬' ),
		'AI' => array( 'name' => __( 'Anguilla', 'happyforms' ), 'code' => '1264', 'flag' => '🇦🇮' ),
		'AL' => array( 'name' => __( 'Albania', 'happyforms' ), 'code' => '355', 'flag' => '🇦🇱' ),
		'AM' => array( 'name' => __( 'Armenia', 'happyforms' ), 'code' => '374', 'flag' => '🇦🇲' ),
		'AO' => array( 'name' => __( 'Angola', 'happyforms' ), 'code' => '244', 'flag' => '🇦🇴' ),
		'AQ' => array( 'name' => __( 'Antarctica', 'happyforms' ), 'code' => '672', 'flag' => '🇦🇶' ),
		'AR' => array( 'name' => __( 'Argentina', 'happyforms' ), 'code' => '54', 'flag' => '🇦🇷' ),
		'AS' => array( 'name' => __( 'American Samoa', 'happyforms' ), 'code' => '1684', 'flag' => '🇦🇸' ),
		'AT' => array( 'name' => __( 'Austria', 'happyforms' ), 'code' => '43', 'flag' => '🇦🇹' ),
		'AU' => array( 'name' => __( 'Australia', 'happyforms' ), 'code' => '61', 'flag' => '🇦🇺' ),
		'AW' => array( 'name' => __( 'Aruba', 'happyforms' ), 'code' => '297', 'flag' => '🇦🇼' ),
		'AZ' => array( 'name' => __( 'Azerbaijan', 'happyforms' ), 'code' => '994', 'flag' => '🇦🇿' ),
		'BA' => array( 'name' => __( 'Bosnia and Herzegovina', 'happyforms' ), 'code' => '387', 'flag' => '🇧🇦' ),
		'BB' => array( 'name' => __( 'Barbados', 'happyforms' ), 'code' => '1246', 'flag' => '🇧🇧' ),
		'BD' => array( 'name' => __( 'Bangladesh', 'happyforms' ), 'code' => '880', 'flag' => '🇧🇩' ),
		'BE' => array( 'name' => __( 'Belgium', 'happyforms' ), 'code' => '32', 'flag' => '🇧🇪' ),
		'BF' => array( 'name' => __( 'Burkina Faso', 'happyforms' ), 'code' => '226', 'flag' => '🇧🇫' ),
		'BG' => array( 'name' => __( 'Bulgaria', 'happyforms' ), 'code' => '359', 'flag' => '🇧🇬' ),
		'BH' => array( 'name' => __( 'Bahrain', 'happyforms' ), 'code' => '973', 'flag' => '🇧🇭' ),
		'BI' => array( 'name' => __( 'Burundi', 'happyforms' ), 'code' => '257', 'flag' => '🇧🇮' ),
		'BJ' => array( 'name' => __( 'Benin', 'happyforms' ), 'code' => '229', 'flag' => '🇧🇯' ),
		'BL' => array( 'name' => __( 'Saint Barthelemy', 'happyforms' ), 'code' => '590', 'flag' => '🇧🇱' ),
		'BM' => array( 'name' => __( 'Bermuda', 'happyforms' ), 'code' => '1441', 'flag' => '🇧🇲' ),
		'BN' => array( 'name' => __( 'Brunei Darussalam', 'happyforms' ), 'code' => '673', 'flag' => '🇧🇳' ),
		'BO' => array( 'name' => __( 'Bolivia', 'happyforms' ), 'code' => '591', 'flag' => '🇧🇴' ),
		'BR' => array( 'name' => __( 'Brazil', 'happyforms' ), 'code' => '55', 'flag' => '🇧🇷' ),
		'BS' => array( 'name' => __( 'Bahamas', 'happyforms' ), 'code' => '1242', 'flag' => '🇧🇸' ),
		'BT' => array( 'name' => __( 'Bhutan', 'happyforms' ), 'code' => '975', 'flag' => '🇧🇹' ),
		'BW' => array( 'name' => __( 'Botswana', 'happyforms' ), 'code' => '267', 'flag' => '🇧🇼' ),
		'BY' => array( 'name' => __( 'Belarus', 'happyforms' ), 'code' => '375', 'flag' => '🇧🇾' ),
		'BZ' => array( 'name' => __( 'Belize', 'happyforms' ), 'code' => '501', 'flag' => '🇧🇿' ),
		'CA' => array( 'name' => __( 'Canada', 'happyforms' ), 'code' => '1', 'flag' => '🇨🇦' ),
		'CD' => array( 'name' => __( 'Congo, The Democratic Republic of the', 'happyforms' ), 'code' => '243', 'flag' => '🇨🇩' ),
		'CF' => array( 'name' => __( 'Central African Republic', 'happyforms' ), 'code' => '236', 'flag' => '🇨🇫' ),
		'CG' => array( 'name' => __( 'Congo', 'happyforms' ), 'code' => '242', 'flag' => '🇨🇬' ),
		'CH' => array( 'name' => __( 'Switzerland', 'happyforms' ), 'code' => '41', 'flag' => '🇨🇭' ),
		'CK' => array( 'name' => __( 'Cook Islands', 'happyforms' ), 'code' => '682', 'flag' => '🇨🇰' ),
		'CL' => array( 'name' => __( 'Chile', 'happyforms' ), 'code' => '56', 'flag' => '🇨🇱' ),
		'CM' => array( 'name' => __( 'Cameroon', 'happyforms' ), 'code' => '237', 'flag' => '🇨🇲' ),
		'CN' => array( 'name' => __( 'China', 'happyforms' ), 'code' => '86', 'flag' => '🇨🇳' ),
		'CO' => array( 'name' => __( 'Colombia', 'happyforms' ), 'code' => '57', 'flag' => '🇨🇴' ),
		'CR' => array( 'name' => __( 'Costa Rica', 'happyforms' ), 'code' => '506', 'flag' => '🇨🇷' ),
		'CU' => array( 'name' => __( 'Cuba', 'happyforms' ), 'code' => '53', 'flag' => '🇨🇺' ),
		'CV' => array( 'name' => __( 'Cape Verde', 'happyforms' ), 'code' => '238', 'flag' => '🇨🇻' ),
		'CY' => array( 'name' => __( 'Cyprus', 'happyforms' ), 'code' => '357', 'flag' => '🇨🇾' ),
		'CZ' => array( 'name' => __( 'Czech Republic', 'happyforms' ), 'code' => '420', 'flag' => '🇨🇿' ),
		'DE' => array( 'name' => __( 'Germany', 'happyforms' ), 'code' => '49', 'flag' => '🇩🇪' ),
		'DJ' => array( 'name' => __( 'Djibouti', 'happyforms' ), 'code' => '253', 'flag' => '🇩🇯' ),
		'DK' => array( 'name' => __( 'Denmark', 'happyforms' ), 'code' => '45', 'flag' => '🇩🇰' ),
		'DM' => array( 'name' => __( 'Dominica', 'happyforms' ), 'code' => '1767', 'flag' => '🇩🇲' ),
		'DO' => array( 'name' => __( 'Dominican Republic', 'happyforms' ), 'code' => '1809', 'flag' => '🇩🇴' ),
		'DZ' => array( 'name' => __( 'Algeria', 'happyforms' ), 'code' => '213', 'flag' => '🇩🇿' ),
		'EC' => array( 'name' => __( 'Ecuador', 'happyforms' ), 'code' => '593', 'flag' => '🇪🇨' ),
		'EE' => array( 'name' => __( 'Estonia', 'happyforms' ), 'code' => '372', 'flag' => '🇪🇪' ),
		'EG' => array( 'name' => __( 'Egypt', 'happyforms' ), 'code' => '20', 'flag' => '🇪🇬' ),
		'ER' => array( 'name' => __( 'Eritrea', 'happyforms' ), 'code' => '291', 'flag' => '🇪🇷' ),
		'ES' => array( 'name' => __( 'Spain', 'happyforms' ), 'code' => '34', 'flag' => '🇪🇸' ),
		'ET' => array( 'name' => __( 'Ethiopia', 'happyforms' ), 'code' => '251', 'flag' => '🇪🇹' ),
		'FI' => array( 'name' => __( 'Finland', 'happyforms' ), 'code' => '358', 'flag' => '🇫🇮' ),
		'FJ' => array( 'name' => __( 'Fiji', 'happyforms' ), 'code' => '679', 'flag' => '🇫🇯' ),
		'FK' => array( 'name' => __( 'Falkland Islands (Malvinas)', 'happyforms' ), 'code' => '500', 'flag' => '🇫🇰' ),
		'FM' => array( 'name' => __( 'Micronesia, Federated States of', 'happyforms' ), 'code' => '691', 'flag' => '🇫🇲' ),
		'FO' => array( 'name' => __( 'Faroe Islands', 'happyforms' ), 'code' => '298', 'flag' => '🇫🇴' ),
		'FR' => array( 'name' => __( 'France', 'happyforms' ), 'code' => '33', 'flag' => '🇫🇷' ),
		'GA' => array( 'name' => __( 'Gabon', 'happyforms' ), 'code' => '241', 'flag' => '🇬🇦' ),
		'GB' => array( 'name' => __( 'United Kingdom', 'happyforms' ), 'code' => '44', 'flag' => '🇬🇧' ),
		'GD' => array( 'name' => __( 'Grenada', 'happyforms' ), 'code' => '1473', 'flag' => '🇬🇩' ),
		'GE' => array( 'name' => __( 'Georgia', 'happyforms' ), 'code' => '995', 'flag' => '🇬🇪' ),
		'GH' => array( 'name' => __( 'Ghana', 'happyforms' ), 'code' => '233', 'flag' => '🇬🇭' ),
		'GI' => array( 'name' => __( 'Gibraltar', 'happyforms' ), 'code' => '350', 'flag' => '🇬🇮' ),
		'GL' => array( 'name' => __( 'Greenland', 'happyforms' ), 'code' => '299', 'flag' => '🇬🇱' ),
		'GM' => array( 'name' => __( 'Gambia', 'happyforms' ), 'code' => '220', 'flag' => '🇬🇲' ),
		'GN' => array( 'name' => __( 'Guinea', 'happyforms' ), 'code' => '224', 'flag' => '🇬🇳' ),
		'GR' => array( 'name' => __( 'Greece', 'happyforms' ), 'code' => '30', 'flag' => '🇬🇷' ),
		'GT' => array( 'name' => __( 'Guatemala', 'happyforms' ), 'code' => '502', 'flag' => '🇬🇹' ),
		'GU' => array( 'name' => __( 'Guam', 'happyforms' ), 'code' => '1671', 'flag' => '🇬🇺' ),
		'GW' => array( 'name' => __( 'Guinea-bissau', 'happyforms' ), 'code' => '245', 'flag' => '🇬🇼' ),
		'GY' => array( 'name' => __( 'Guyana', 'happyforms' ), 'code' => '592', 'flag' => '🇬🇾' ),
		'HK' => array( 'name' => __( 'Hong Kong', 'happyforms' ), 'code' => '852', 'flag' => '🇭🇰' ),
		'HN' => array( 'name' => __( 'Honduras', 'happyforms' ), 'code' => '504', 'flag' => '🇭🇳' ),
		'HR' => array( 'name' => __( 'Croatia', 'happyforms' ), 'code' => '385', 'flag' => '🇭🇷' ),
		'HT' => array( 'name' => __( 'Haiti', 'happyforms' ), 'code' => '509', 'flag' => '🇭🇹' ),
		'HU' => array( 'name' => __( 'Hungary', 'happyforms' ), 'code' => '36', 'flag' => '🇭🇺' ),
		'ID' => array( 'name' => __( 'Indonesia', 'happyforms' ), 'code' => '62', 'flag' => '🇮🇩' ),
		'IE' => array( 'name' => __( 'Ireland', 'happyforms' ), 'code' => '353', 'flag' => '🇮🇪' ),
		'IL' => array( 'name' => __( 'Israel', 'happyforms' ), 'code' => '972', 'flag' => '🇮🇱' ),
		'IN' => array( 'name' => __( 'India', 'happyforms' ), 'code' => '91', 'flag' => '🇮🇳' ),
		'IQ' => array( 'name' => __( 'Iraq', 'happyforms' ), 'code' => '964', 'flag' => '🇮🇶' ),
		'IR' => array( 'name' => __( 'Iran, Islamic Republic of', 'happyforms' ), 'code' => '98', 'flag' => '🇮🇷' ),
		'IS' => array( 'name' => __( 'Iceland', 'happyforms' ), 'code' => '354', 'flag' => '🇮🇸' ),
		'IT' => array( 'name' => __( 'Italy', 'happyforms' ), 'code' => '39', 'flag' => '🇮🇹' ),
		'JM' => array( 'name' => __( 'Jamaica', 'happyforms' ), 'code' => '1876', 'flag' => '🇯🇲' ),
		'JO' => array( 'name' => __( 'Jordan', 'happyforms' ), 'code' => '962', 'flag' => '🇯🇴' ),
		'JP' => array( 'name' => __( 'Japan', 'happyforms' ), 'code' => '81', 'flag' => '🇯🇵' ),
		'KE' => array( 'name' => __( 'Kenya', 'happyforms' ), 'code' => '254', 'flag' => '🇰🇪' ),
		'KG' => array( 'name' => __( 'Kyrgyzstan', 'happyforms' ), 'code' => '996', 'flag' => '🇰🇬' ),
		'KH' => array( 'name' => __( 'Cambodia', 'happyforms' ), 'code' => '855', 'flag' => '🇰🇭' ),
		'KI' => array( 'name' => __( 'Kiribati', 'happyforms' ), 'code' => '686', 'flag' => '🇰🇮' ),
		'KM' => array( 'name' => __( 'Comoros', 'happyforms' ), 'code' => '269', 'flag' => '🇰🇲' ),
		'KN' => array( 'name' => __( 'Saint Kitts and Nevis', 'happyforms' ), 'code' => '1869', 'flag' => '🇰🇳' ),
		'KP' => array( 'name' => __( 'Korea Democratic Peoples Republic of', 'happyforms' ), 'code' => '850', 'flag' => '🇰🇵' ),
		'KR' => array( 'name' => __( 'Korea Republic of', 'happyforms' ), 'code' => '82', 'flag' => '🇰🇷' ),
		'KW' => array( 'name' => __( 'Kuwait', 'happyforms' ), 'code' => '965', 'flag' => '🇰🇼' ),
		'KY' => array( 'name' => __( 'Cayman Islands', 'happyforms' ), 'code' => '1345', 'flag' => '🇰🇾' ),
		'LA' => array( 'name' => __( 'Lao Peoples Democratic Republic', 'happyforms' ), 'code' => '856', 'flag' => '🇱🇦' ),
		'LB' => array( 'name' => __( 'Lebanon', 'happyforms' ), 'code' => '961', 'flag' => '🇱🇧' ),
		'LC' => array( 'name' => __( 'Saint Lucia', 'happyforms' ), 'code' => '1758', 'flag' => '🇱🇨' ),
		'LI' => array( 'name' => __( 'Liechtenstein', 'happyforms' ), 'code' => '423', 'flag' => '🇱🇮' ),
		'LK' => array( 'name' => __( 'Sri Lanka', 'happyforms' ), 'code' => '94', 'flag' => '🇱🇰' ),
		'LR' => array( 'name' => __( 'Liberia', 'happyforms' ), 'code' => '231', 'flag' => '🇱🇷' ),
		'LS' => array( 'name' => __( 'Lesotho', 'happyforms' ), 'code' => '266', 'flag' => '🇱🇸' ),
		'LT' => array( 'name' => __( 'Lithuania', 'happyforms' ), 'code' => '370', 'flag' => '🇱🇹' ),
		'LU' => array( 'name' => __( 'Luxembourg', 'happyforms' ), 'code' => '352', 'flag' => '🇱🇺' ),
		'LV' => array( 'name' => __( 'Latvia', 'happyforms' ), 'code' => '371', 'flag' => '🇱🇻' ),
		'LY' => array( 'name' => __( 'Libyan Arab Jamahiriya', 'happyforms' ), 'code' => '218', 'flag' => '🇱🇾' ),
		'MA' => array( 'name' => __( 'Morocco', 'happyforms' ), 'code' => '212', 'flag' => '🇲🇦' ),
		'MC' => array( 'name' => __( 'Monaco', 'happyforms' ), 'code' => '377', 'flag' => '🇲🇨' ),
		'MD' => array( 'name' => __( 'Moldova, Republic of', 'happyforms' ), 'code' => '373', 'flag' => '🇲🇩' ),
		'ME' => array( 'name' => __( 'Montenegro', 'happyforms' ), 'code' => '382', 'flag' => '🇲🇪' ),
		'MG' => array( 'name' => __( 'Madagascar', 'happyforms' ), 'code' => '261', 'flag' => '🇲🇬' ),
		'MH' => array( 'name' => __( 'Marshall Islands', 'happyforms' ), 'code' => '692', 'flag' => '🇲🇭' ),
		'MK' => array( 'name' => __( 'Macedonia, The Former Yugoslav Republic of', 'happyforms' ), 'code' => '389', 'flag' => '🇲🇰' ),
		'ML' => array( 'name' => __( 'Mali', 'happyforms' ), 'code' => '223', 'flag' => '🇲🇱' ),
		'MM' => array( 'name' => __( 'Myanmar', 'happyforms' ), 'code' => '95', 'flag' => '🇲🇲' ),
		'MN' => array( 'name' => __( 'Mongolia', 'happyforms' ), 'code' => '976', 'flag' => '🇲🇳' ),
		'MO' => array( 'name' => __( 'Macau', 'happyforms' ), 'code' => '853', 'flag' => '🇲🇴' ),
		'MP' => array( 'name' => __( 'Northern Mariana Islands', 'happyforms' ), 'code' => '1670', 'flag' => '🇲🇵' ),
		'MR' => array( 'name' => __( 'Mauritania', 'happyforms' ), 'code' => '222', 'flag' => '🇲🇺' ),
		'MS' => array( 'name' => __( 'Montserrat', 'happyforms' ), 'code' => '1664', 'flag' => '🇲🇸' ),
		'MT' => array( 'name' => __( 'Malta', 'happyforms' ), 'code' => '356', 'flag' => '🇲🇹' ),
		'MU' => array( 'name' => __( 'Mauritius', 'happyforms' ), 'code' => '230', 'flag' => '🇲🇺' ),
		'MV' => array( 'name' => __( 'Maldives', 'happyforms' ), 'code' => '960', 'flag' => '🇲🇻' ),
		'MW' => array( 'name' => __( 'Malawi', 'happyforms' ), 'code' => '265', 'flag' => '🇲🇼' ),
		'MX' => array( 'name' => __( 'Mexico', 'happyforms' ), 'code' => '52', 'flag' => '🇲🇽' ),
		'MY' => array( 'name' => __( 'Malaysia', 'happyforms' ), 'code' => '60', 'flag' => '🇲🇾' ),
		'MZ' => array( 'name' => __( 'Mozambique', 'happyforms' ), 'code' => '258', 'flag' => '🇲🇿' ),
		'NA' => array( 'name' => __( 'Namibia', 'happyforms' ), 'code' => '264', 'flag' => '🇳🇦' ),
		'NC' => array( 'name' => __( 'New Caledonia', 'happyforms' ), 'code' => '687', 'flag' => '🇳🇨' ),
		'NE' => array( 'name' => __( 'Niger', 'happyforms' ), 'code' => '227', 'flag' => '🇳🇪' ),
		'NG' => array( 'name' => __( 'Nigeria', 'happyforms' ), 'code' => '234', 'flag' => '🇳🇬' ),
		'NI' => array( 'name' => __( 'Nicaragua', 'happyforms' ), 'code' => '505', 'flag' => '🇳🇮' ),
		'NL' => array( 'name' => __( 'Netherlands', 'happyforms' ), 'code' => '31', 'flag' => '🇳🇱' ),
		'NO' => array( 'name' => __( 'Norway', 'happyforms' ), 'code' => '47', 'flag' => '🇳🇴' ),
		'NP' => array( 'name' => __( 'Nepal', 'happyforms' ), 'code' => '977', 'flag' => '🇳🇵' ),
		'NR' => array( 'name' => __( 'Nauru', 'happyforms' ), 'code' => '674', 'flag' => '🇳🇷' ),
		'NU' => array( 'name' => __( 'Niue', 'happyforms' ), 'code' => '683', 'flag' => '🇳🇺' ),
		'NZ' => array( 'name' => __( 'New Zealand', 'happyforms' ), 'code' => '64', 'flag' => '🇳🇿' ),
		'OM' => array( 'name' => __( 'Oman', 'happyforms' ), 'code' => '968', 'flag' => '🇴🇲' ),
		'PA' => array( 'name' => __( 'Panama', 'happyforms' ), 'code' => '507', 'flag' => '🇵🇦' ),
		'PE' => array( 'name' => __( 'Peru', 'happyforms' ), 'code' => '51', 'flag' => '🇵🇪' ),
		'PF' => array( 'name' => __( 'French Polynesia', 'happyforms' ), 'code' => '689', 'flag' => '🇵🇫' ),
		'PG' => array( 'name' => __( 'Papua New Guinea', 'happyforms' ), 'code' => '675', 'flag' => '🇵🇬' ),
		'PH' => array( 'name' => __( 'Philippines', 'happyforms' ), 'code' => '63', 'flag' => '🇵🇭' ),
		'PK' => array( 'name' => __( 'Pakistan', 'happyforms' ), 'code' => '92', 'flag' => '🇵🇰' ),
		'PL' => array( 'name' => __( 'Poland', 'happyforms' ), 'code' => '48', 'flag' => '🇵🇱' ),
		'PM' => array( 'name' => __( 'Saint Pierre and Miquelon', 'happyforms' ), 'code' => '508', 'flag' => '🇵🇲' ),
		'PN' => array( 'name' => __( 'Pitcairn', 'happyforms' ), 'code' => '870', 'flag' => '🇵🇳' ),
		'PT' => array( 'name' => __( 'Portugal', 'happyforms' ), 'code' => '351', 'flag' => '🇵🇹' ),
		'PW' => array( 'name' => __( 'Palau', 'happyforms' ), 'code' => '680', 'flag' => '🇵🇼' ),
		'PY' => array( 'name' => __( 'Paraguay', 'happyforms' ), 'code' => '595', 'flag' => '🇵🇾' ),
		'QA' => array( 'name' => __( 'Qatar', 'happyforms' ), 'code' => '974', 'flag' => '🇶🇦' ),
		'RO' => array( 'name' => __( 'Romania', 'happyforms' ), 'code' => '40', 'flag' => '🇷🇴' ),
		'RS' => array( 'name' => __( 'Serbia', 'happyforms' ), 'code' => '381', 'flag' => '🇷🇸' ),
		'RU' => array( 'name' => __( 'Russian Federation', 'happyforms' ), 'code' => '7', 'flag' => '🇷🇺' ),
		'RW' => array( 'name' => __( 'Rwanda', 'happyforms' ), 'code' => '250', 'flag' => '🇷🇼' ),
		'SA' => array( 'name' => __( 'Saudi Arabia', 'happyforms' ), 'code' => '966', 'flag' => '🇸🇦' ),
		'SB' => array( 'name' => __( 'Solomon Islands', 'happyforms' ), 'code' => '677', 'flag' => '🇸🇧' ),
		'SC' => array( 'name' => __( 'Seychelles', 'happyforms' ), 'code' => '248', 'flag' => '🇸🇨' ),
		'SD' => array( 'name' => __( 'Sudan', 'happyforms' ), 'code' => '249', 'flag' => '🇸🇩' ),
		'SE' => array( 'name' => __( 'Sweden', 'happyforms' ), 'code' => '46', 'flag' => '🇸🇪' ),
		'SG' => array( 'name' => __( 'Singapore', 'happyforms' ), 'code' => '65', 'flag' => '🇸🇬' ),
		'SH' => array( 'name' => __( 'Saint Helena', 'happyforms' ), 'code' => '290', 'flag' => '🇸🇭' ),
		'SI' => array( 'name' => __( 'Slovenia', 'happyforms' ), 'code' => '386', 'flag' => '🇸🇮' ),
		'SK' => array( 'name' => __( 'Slovakia', 'happyforms' ), 'code' => '421', 'flag' => '🇸🇰' ),
		'SL' => array( 'name' => __( 'Sierra Leone', 'happyforms' ), 'code' => '232', 'flag' => '🇸🇱' ),
		'SM' => array( 'name' => __( 'San Marino', 'happyforms' ), 'code' => '378', 'flag' => '🇸🇲' ),
		'SN' => array( 'name' => __( 'Senegal', 'happyforms' ), 'code' => '221', 'flag' => '🇸🇳' ),
		'SO' => array( 'name' => __( 'Somalia', 'happyforms' ), 'code' => '252', 'flag' => '🇸🇴' ),
		'SR' => array( 'name' => __( 'Suriname', 'happyforms' ), 'code' => '597', 'flag' => '🇸🇷' ),
		'ST' => array( 'name' => __( 'Sao Tome and Principe', 'happyforms' ), 'code' => '239', 'flag' => '🇸🇹' ),
		'SV' => array( 'name' => __( 'El Salvador', 'happyforms' ), 'code' => '503', 'flag' => '🇸🇻' ),
		'SY' => array( 'name' => __( 'Syrian Arab Republic', 'happyforms' ), 'code' => '963', 'flag' => '🇸🇾' ),
		'SZ' => array( 'name' => __( 'Swaziland', 'happyforms' ), 'code' => '268', 'flag' => '🇸🇿' ),
		'TC' => array( 'name' => __( 'Turks and Caicos Islands', 'happyforms' ), 'code' => '1649', 'flag' => '🇹🇨' ),
		'TD' => array( 'name' => __( 'Chad', 'happyforms' ), 'code' => '235', 'flag' => '🇹🇩' ),
		'TG' => array( 'name' => __( 'Togo', 'happyforms' ), 'code' => '228', 'flag' => '🇹🇬' ),
		'TH' => array( 'name' => __( 'Thailand', 'happyforms' ), 'code' => '66', 'flag' => '🇹🇭' ),
		'TJ' => array( 'name' => __( 'Tajikistan', 'happyforms' ), 'code' => '992', 'flag' => '🇹🇯' ),
		'TK' => array( 'name' => __( 'Tokelau', 'happyforms' ), 'code' => '690', 'flag' => '🇹🇰' ),
		'TL' => array( 'name' => __( 'Timor-leste', 'happyforms' ), 'code' => '670', 'flag' => '🇹🇱' ),
		'TM' => array( 'name' => __( 'Turkmenistan', 'happyforms' ), 'code' => '993', 'flag' => '🇹🇲' ),
		'TN' => array( 'name' => __( 'Tunisia', 'happyforms' ), 'code' => '216', 'flag' => '🇹🇳' ),
		'TO' => array( 'name' => __( 'Tonga', 'happyforms' ), 'code' => '676', 'flag' => '🇹🇴' ),
		'TR' => array( 'name' => __( 'Turkey', 'happyforms' ), 'code' => '90', 'flag' => '🇹🇷' ),
		'TT' => array( 'name' => __( 'Trinidad and Tobago', 'happyforms' ), 'code' => '1868', 'flag' => '🇹🇹' ),
		'TV' => array( 'name' => __( 'Tuvalu', 'happyforms' ), 'code' => '688', 'flag' => '🇹🇻' ),
		'TW' => array( 'name' => __( 'Taiwan, Province of China', 'happyforms' ), 'code' => '886', 'flag' => '🇹🇼' ),
		'TZ' => array( 'name' => __( 'Tanzania, United Republic of', 'happyforms' ), 'code' => '255', 'flag' => '🇹🇿' ),
		'UA' => array( 'name' => __( 'Ukraine', 'happyforms' ), 'code' => '380', 'flag' => '🇺🇦' ),
		'UG' => array( 'name' => __( 'Uganda', 'happyforms' ), 'code' => '256', 'flag' => '🇺🇬' ),
		'US' => array( 'name' => __( 'United States', 'happyforms' ), 'code' => '1', 'flag' => '🇺🇸' ),
		'UY' => array( 'name' => __( 'Uruguay', 'happyforms' ), 'code' => '598', 'flag' => '🇺🇾' ),
		'UZ' => array( 'name' => __( 'Uzbekistan', 'happyforms' ), 'code' => '998', 'flag' => '🇺🇿' ),
		'VC' => array( 'name' => __( 'Saint Vincent and the Grenadines', 'happyforms' ), 'code' => '1784', 'flag' => '🇻🇨' ),
		'VE' => array( 'name' => __( 'Venezuela', 'happyforms' ), 'code' => '58', 'flag' => '🇻🇪' ),
		'VG' => array( 'name' => __( 'Virgin Islands, British', 'happyforms' ), 'code' => '1284', 'flag' => '🇻🇬' ),
		'VI' => array( 'name' => __( 'Virgin Islands, U.S.', 'happyforms' ), 'code' => '1340', 'flag' => '🇻🇮' ),
		'VN' => array( 'name' => __( 'Vietnam', 'happyforms' ), 'code' => '84', 'flag' => '🇻🇳' ),
		'VU' => array( 'name' => __( 'Vanuatu', 'happyforms' ), 'code' => '678', 'flag' => '🇻🇺' ),
		'WF' => array( 'name' => __( 'Wallis and Futuna', 'happyforms' ), 'code' => '681', 'flag' => '🇼🇫' ),
		'WS' => array( 'name' => __( 'Samoa', 'happyforms' ), 'code' => '685', 'flag' => '🇼🇸' ),
		'YE' => array( 'name' => __( 'Yemen', 'happyforms' ), 'code' => '967', 'flag' => '🇾🇪' ),
		'YT' => array( 'name' => __( 'Mayotte', 'happyforms' ), 'code' => '262', 'flag' => '🇾🇹' ),
		'ZA' => array( 'name' => __( 'South Africa', 'happyforms' ), 'code' => '27', 'flag' => '🇿🇦' ),
		'ZM' => array( 'name' => __( 'Zambia', 'happyforms' ), 'code' => '260', 'flag' => '🇿🇲' ),
		'ZW' => array( 'name' => __( 'Zimbabwe', 'happyforms' ), 'code' => '263', 'flag' => '🇿🇼' ),
	);

	return $countries;
}

endif;

if ( ! function_exists( 'happyforms_select' ) ) :

function happyforms_select( $options, $part, $form, $placeholder = '' ) {
	$value = happyforms_get_part_value( $part, $form );
	$placeholder_value = ( isset( $part['placeholder'] ) ) ? $part['placeholder'] : $placeholder;

	foreach( $options as $option_value => $option ) {
		if ( isset( $option['is_default'] ) && 1 == $option['is_default'] ) {
			$value = $option_value;
		}
	}

	include( happyforms_get_core_folder() . '/templates/partials/happyforms-select.php' );
}

endif;

if ( ! function_exists( 'happyforms_get_steps' ) ) :

function happyforms_get_steps( $form ) {
	$steps = happyforms_get_form_controller()->get_default_steps( $form );
	$steps = apply_filters( 'happyforms_get_steps', $steps, $form );
	ksort( $steps );
	$steps = array_values( $steps );

	return $steps;
}

endif;

if ( ! function_exists( 'happyforms_get_current_step' ) ) :

function happyforms_get_current_step( $form, $index = false ) {
	$steps = happyforms_get_steps( $form );
	$session = happyforms_get_session();
	$step = $session->current_step();

	if ( isset( $steps[$step] ) ) {
		return $index ? $step : $steps[$step];
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_next_step' ) ) :

function happyforms_get_next_step( $form, $index = false ) {
	$steps = happyforms_get_steps( $form );
	$session = happyforms_get_session();
	$step = $session->current_step() + 1;

	if ( isset( $steps[$step] ) ) {
		return $index ? $step : $steps[$step];
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_last_step' ) ) :

function happyforms_get_last_step( $form, $index = false ) {
	$steps = happyforms_get_steps( $form );
	$last_step = count( $steps ) - 1;

	return $index ? $last_step : $steps[$last_step];
}

endif;

if ( ! function_exists( 'happyforms_is_last_step' ) ) :

function happyforms_is_last_step( $form, $step = false ) {
	$steps = happyforms_get_steps( $form );
	$step = false !== $step ? $step : happyforms_get_current_step( $form );
	$is_last = $steps[count( $steps ) - 1] === $step;

	return $is_last;
}

endif;

if ( ! function_exists( 'happyforms_step_field' ) ) :

function happyforms_step_field( $form ) {
	$session = happyforms_get_session();
	$step = $session->current_step();
	?>
	<input type="hidden" name="happyforms_step" value="<?php echo $step; ?>" />
	<?php
}

endif;

if ( ! function_exists( 'happyforms_is_falsy' ) ) :

function happyforms_is_falsy( $value ) {
	$falsy = empty( $value ) || 'false' === $value || 0 === intval( $value );

	return $falsy;
}

endif;

if ( ! function_exists( 'happyforms_is_truthy' ) ) :

function happyforms_is_truthy( $value ) {
	$truthy = ! happyforms_is_falsy( $value );

	return $truthy;
}

endif;

if ( ! function_exists( 'happyforms_get_rating_icons' ) ) :

function happyforms_get_rating_icons( $part ) {
	$icons = array( '😢', '😟', '😐', '🙂',  '😍' );

	if ( 'yesno' === $part['rating_type'] ) {
		switch ( $part['rating_visuals'] ) {
			case 'smileys':
				$icons = array( '😟', '😁' );
				break;
			case 'thumbs':
				$icons = array( '👎', '👍' );
				break;
		}
	}

	return $icons;
}

endif;

if ( ! function_exists( 'happyforms_get_narrative_format' ) ) :

function happyforms_get_narrative_format( $format ) {
	$format = preg_replace( '/\[([^\/\]]*)\]/m', '%s', $format );

	return $format;
}

endif;

if ( ! function_exists( 'happyforms_get_narrative_tokens' ) ) :

function happyforms_get_narrative_tokens( $format, $with_placeholders = false ) {
	$matches = preg_match_all( '/\[([^\/\]]*)\]/m', $format, $tokens );

	if ( ! $matches ) {
		return array();
	}

	$tokens = $tokens[1];

	if ( ! $with_placeholders ) {
		$tokens = array_fill( 0, count( $tokens ), '' );
	}

	return $tokens;
}

endif;

if ( ! function_exists( 'happyforms_get_form_attributes' ) ):

function happyforms_get_form_attributes( $form ) {
	$attributes = apply_filters( 'happyforms_get_form_attributes', array(
		'novalidate' => 'true'
	), $form );

	return $attributes;
}

endif;

if ( ! function_exists( 'happyforms_the_form_attributes' ) ):

function happyforms_the_form_attributes( $form ) {
	$attributes = happyforms_get_form_attributes( $form );
	$html_attributes = array();

	foreach( $attributes as $attribute => $value ) {
		$value = esc_attr( $value );
		$html_attributes[] = "{$attribute}=\"{$value}\"";
	}

	$html_attributes = implode( ' ', $html_attributes );
	echo $html_attributes;
}

endif;

if ( ! function_exists( 'happyforms_get_shortcode' ) ):

function happyforms_get_shortcode( $form_id = 'ID' ) {
	$shortcode = "[happyforms id=\"{$form_id}\" /]";
	$shortcode = apply_filters( 'happyforms_get_shortcode', $shortcode, $form_id );

	return $shortcode;
}

endif;

if ( ! function_exists( 'happyforms_get_previous_part' ) ):

function happyforms_get_previous_part( $part, $form ) {
	$part_id = $part['id'];
	$parts = array_values( $form['parts'] );
	$part_ids = wp_list_pluck( $parts, 'id' );
	$part_index = array_search( $part_id, $part_ids );
	$part_index = $part_index - 1;

	if ( isset( $parts[$part_index] ) ) {
		return $parts[$part_index];
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_next_part' ) ):

function happyforms_get_next_part( $part, $form ) {
	$part_id = $part['id'];
	$parts = array_values( $form['parts'] );
	$part_ids = wp_list_pluck( $parts, 'id' );
	$part_index = array_search( $part_id, $part_ids );
	$part_index = $part_index + 1;

	if ( isset( $parts[$part_index] ) ) {
		return $parts[$part_index];
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_form_partial' ) ):

function happyforms_get_form_partial( $partial_name, $form ) {
	$file = happyforms_get_include_folder() . '/templates/partials/' . $partial_name . '.php';

	if ( ! file_exists( $file ) ) {
		$file = happyforms_get_core_folder() . '/templates/partials/' . $partial_name . '.php';
	}

	ob_start();
	require( $file );
	$html = ob_get_clean();

	return $html;
}

endif;

if ( ! function_exists( 'happyforms_is_stepping' ) ):

function happyforms_is_stepping() {
	$stepping = defined( 'HAPPYFORMS_STEPPING' ) && HAPPYFORMS_STEPPING;

	return $stepping;
}

endif;

if ( ! function_exists( 'happyforms_get_part_states' ) ):
/**
 * Output notices for the current submission,
 * related to the form as a whole or specific parts.
 *
 * @since 1.0
 *
 * @param string $location The notice location to display.
 *
 * @return void
 */
function happyforms_get_part_states( $location = '' ) {
	$states = happyforms_get_session()->get_states( $location );

	return $states;
}

endif;

if ( ! function_exists( 'happyforms_get_prefixed_css' ) ):
/**
 * Prefix CSS selectors with specified prefix.
 *
 * @param string $css CSS to be prefixed.
 * @param string $prefix Prefix to add in front of each selector.
 *
 * @return string
 */
function happyforms_get_prefixed_css( $css, $prefix ) {
	$css = preg_replace( '!/\*.*?\*/!s', '', $css );
	$parts = explode( '}', $css );
	$is_media_query = false;

	foreach ( $parts as &$part ) {
		$part = trim( $part );

		if ( empty( $part ) ) {
			continue;
		}

		$part_contents = explode( '{', $part );

		if ( 2 === substr_count( $part, '{' ) ) {
			$media_query = $part_contents[0] . '{';
			$part_contents[0] = $part_contents[1];
			$is_media_query = true;
		}

		$sub_parts = explode( ',', $part_contents[0] );

		foreach ( $sub_parts as &$sub_part ) {
			$sub_part = $prefix . ' ' . trim( $sub_part );
		}

		if ( 2 === substr_count( $part, '{' ) ) {
			$part = $media_query . "\n" . implode( ', ', $sub_parts ) . '{'. $part_contents[2];
		} else if ( empty($part[0] ) && $is_media_query ) {
			$is_media_query = false;
			$part = implode( ', ', $sub_parts ). '{'. $part_contents[2]. "}\n";
		} else {
			if ( isset( $part_contents[1] ) ) {
				$part = implode( ', ', $sub_parts ) . '{'. $part_contents[1];
			}
		}
	}

	return preg_replace( '/\s+/',' ', implode( '} ', $parts ) );
}

endif;
