<?php

class HappyForms_Form_Email {

	private static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		add_filter( 'happyforms_meta_fields', array( $this, 'meta_fields' ) );
		add_action( 'happyforms_do_email_control', array( happyforms_get_setup(), 'do_control' ), 10, 3 );
	}

	public function get_fields() {
		global $current_user;

		$fields = array(
			'receive_email_alerts' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'email_recipient' => array(
				'default' => ( $current_user->user_email ) ? $current_user->user_email : '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'email_bccs' => array(
				'default' => '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'email_mark_and_reply' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'alert_email_subject' => array(
				'default' => __( 'You received a new message', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'send_confirmation_email' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'confirmation_email_sender_address' => array(
				'default' => ( $current_user->user_email ) ? $current_user->user_email : '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'confirmation_email_reply_to' => array(
				'default' => ( $current_user->user_email ) ? $current_user->user_email : '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'confirmation_email_from_name' => array(
				'default' => get_bloginfo( 'name' ),
				'sanitize' => 'sanitize_text_field',
			),
			'confirmation_email_subject' => array(
				'default' => __( 'We received your message', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'confirmation_email_content' => array(
				'default' => __( 'Your message has been successfully sent. We appreciate you contacting us and we’ll be in touch soon.', 'happyforms' ),
				'sanitize' => 'esc_html',
			),
			'confirmation_email_include_values' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
		);

		return $fields;
	}

	public function get_controls() {
		$controls = array(
			200 => array(
				'type' => 'checkbox',
				'label' => __( 'Receive submission alerts', 'happyforms' ),
				'field' => 'receive_email_alerts',
			),
			201 => array(
				'type' => 'group_start',
				'trigger' => 'receive_email_alerts'
			),
			300 => array(
				'type' => 'text',
				'label' => __( 'Email address', 'happyforms' ),
				'tooltip' => __( 'Add your email address here to receive a confirmation email for each form response. You can add multiple email addresses by separating each address with a comma.', 'happyforms' ),
				'field' => 'email_recipient',
			),
			310 => array(
				'type' => 'text',
				'label' => __( 'Email Bcc address', 'happyforms' ),
				'tooltip' => __( 'Add your Bcc email address here to receive a confirmation email for each form response  without appearing in the received message header. You can add multiple email addresses by separating each address with a comma.', 'happyforms' ),
				'field' => 'email_bccs',
			),
			400 => array(
				'type' => 'text',
				'label' => __( 'Email subject', 'happyforms' ),
				'tooltip' => __( 'Each time a user submits a message, you\'ll receive an email with this subject.', 'happyforms' ),
				'field' => 'alert_email_subject',
			),
			490 => array(
				'type' => 'group_end'
			),
			500 => array(
				'type' => 'checkbox',
				'label' => __( 'Send confirmation email', 'happyforms' ),
				'field' => 'send_confirmation_email',
			),
			501 => array(
				'type' => 'group_start',
				'trigger' => 'send_confirmation_email'
			),
			580 => array(
				'type' => 'text',
				'label' => __( 'Email address', 'happyforms' ),
				'field' => 'confirmation_email_sender_address',
			),
			590 => array(
				'type' => 'text',
				'label' => __( 'Reply email address', 'happyforms' ),
				'field' => 'confirmation_email_reply_to',
			),
			600 => array(
				'type' => 'text',
				'label' => __( 'Email display name', 'happyforms' ),
				'tooltip' => __( 'If your form contains an email field, users will receive an email with this sender name.', 'happyforms' ),
				'field' => 'confirmation_email_from_name',
			),
			700 => array(
				'type' => 'text',
				'label' => __( 'Email subject', 'happyforms' ),
				'tooltip' => __( 'If your form contains an email field, users will receive an email with this subject.', 'happyforms' ),
				'field' => 'confirmation_email_subject',
			),
			800 => array(
				'type' => 'editor',
				'label' => __( 'Email content', 'happyforms' ),
				'tooltip' => __( 'If your form contains an email field, users will receive an email with this content.', 'happyforms' ),
				'field' => 'confirmation_email_content',
			),
			810 => array(
				'type' => 'checkbox',
				'label' => __( 'Include submitted values', 'happyforms' ),
				'field' => 'confirmation_email_include_values'
			),
			820 => array(
				'type' => 'group_end'
			)
		);

		$controls = apply_filters( 'happyforms_email_controls', $controls );
		ksort( $controls, SORT_NUMERIC );

		return $controls;
	}

	/**
	 * Filter: add fields to form meta.
	 *
	 * @hooked filter happyforms_meta_fields
	 *
	 * @param array $fields Current form meta fields.
	 *
	 * @return array
	 */
	public function meta_fields( $fields ) {
		$fields = array_merge( $fields, $this->get_fields() );

		return $fields;
	}

}

if ( ! function_exists( 'happyforms_get_email' ) ):

function happyforms_get_email() {
	return HappyForms_Form_Email::instance();
}

endif;

happyforms_get_email();
