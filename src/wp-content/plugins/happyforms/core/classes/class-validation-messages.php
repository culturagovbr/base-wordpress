<?php

class HappyForms_Validation_Messages {

	/**
	 * The singleton instance.
	 *
	 * @since 1.0
	 *
	 * @var HappyForms_Validation_Messages
	 */
	private static $instance;

	/**
	 * The singleton constructor.
	 *
	 * @return HappyForms_Validation_Messages
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		add_filter( 'happyforms_part_attributes', array( $this, 'add_accessibility_attributes' ), 10, 4 );
	}

	public function add_accessibility_attributes( $attributes, $part, $form, $component ) {
		$part_name = happyforms_get_part_name( $part, $form );
		$errors = happyforms_get_session()->get_messages( $part_name );

		if ( empty( $errors ) ) {
			return $attributes;
		}

		$error_id = "happyforms-error-{$part_name}";
		$error_id = ( $component ) ? "{$error_id}_{$component}" : $error_id;

		$attributes[] = 'aria-invalid="true"';
		$attributes[] = 'aria-describedby="'. $error_id .'"';

		return $attributes;
	}

	public function get_default_messages() {
		$messages = array(
			'field_empty' => __( 'Please fill in this field', 'happyforms' ),
			'field_invalid' => __( 'This is invalid', 'happyforms' ),
			'values_mismatch' => __( 'This doesn\'t match', 'happyforms' ),
			'select_more_choices' => __( 'Please select more choices', 'happyforms' ),
			'select_less_choices' => __( 'Please select less choices', 'happyforms' ),
			'message_too_long' => __( 'This message is too long', 'happyforms' ),
			'message_too_short' => __( 'This message is too short', 'happyforms' ),
		);

		return apply_filters( 'happyforms_default_validation_messages', $messages );
	}

	public function get_message( $message_key ) {
		$default_messages = $this->get_default_messages();
		$message = '';

		if ( ! isset( $default_messages[$message_key] ) ) {
			return $message;
		}

		$message = $default_messages[$message_key];
		$message = apply_filters( 'happyforms_validation_message', $message, $message_key );

		return $message;
	}

}

if ( ! function_exists( 'happyforms_validation_messages' ) ):
/**
 * Get the HappyForms_Validation_Messages class instance.
 *
 * @since 1.0
 *
 * @return HappyForms_Validation_Messages
 */
function happyforms_validation_messages() {
	return HappyForms_Validation_Messages::instance();
}

endif;

/**
 * Initialize the HappyForms_Validation_Messages class immediately.
 */
happyforms_validation_messages();
