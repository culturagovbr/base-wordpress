<?php

class HappyForms_Form_Setup {

	/**
	 * The singleton instance.
	 *
	 * @var HappyForms_Form_Setup
	 */
	private static $instance;

	/**
	 * The singleton constructor.
	 *
	 * @return HappyForms_Form_Setup
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public function hook() {
		// Common form extensions
		add_filter( 'happyforms_meta_fields', array( $this, 'meta_fields' ) );
		add_filter( 'happyforms_update_form_data', array( $this, 'update_html_id_checkbox' ) );

		// Customizer form display
		add_filter( 'happyforms_part_class', array( $this, 'part_class_customizer' ) );
		add_filter( 'happyforms_the_form_title', array( $this, 'form_title_customizer' ) );

		// Reviewable form display
		add_filter( 'happyforms_form_id', array( $this, 'form_html_id' ), 10, 2 );
		add_filter( 'happyforms_form_class', array( $this, 'form_html_class' ), 10, 2 );
		add_action( 'happyforms_do_setup_control', array( $this, 'do_control' ), 10, 3 );
	}

	public function get_fields() {
		global $current_user;

		$fields = array(
			'confirm_submission' => array(
				'default' => 'success_message',
				'sanitize' => 'sanitize_text_field',
			),
			'confirmation_message' => array(
				'default' => __( 'Thank you! Your submission has been sent.', 'happyforms' ),
				'sanitize' => 'esc_html',
			),
			'error_message' => array(
				'default' => __( 'There is a problem! Please review your submission.', 'happyforms' ),
				'sanitize' => 'esc_html'
			),
			'redirect_on_complete' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'redirect_url' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'redirect_blank' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'spam_prevention' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'required_part_label' => array(
				'default' => __( 'This field is required.', 'happyforms' ),
				'sanitize' => 'sanitize_text_field'
			),
			'optional_part_label' => array(
				'default' => __( '(optional)', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'submit_button_label' => array(
				'default' => __( 'Send', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'form_expiration_datetime' => array(
				'default' => date( 'Y-m-d H:i:s', time() + 3600 * 24 * 7 ),
				'sanitize' => 'happyforms_sanitize_datetime',
			),
			'save_entries' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'captcha' => array(
				'default' => '',
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'captcha_site_key' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'captcha_secret_key' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'captcha_label' => array(
				'default' => __( 'Validate your submission', 'happyforms' ),
				'sanitize' => 'sanitize_text_field'
			),
			'preview_before_submit' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'review_button_label' => array(
				'default' => __( 'Review submission', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'edit_button_label' => array(
				'default' => __( 'Edit', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'unique_id' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'unique_id_start_from' => array(
				'default' => 1,
				'sanitize' => 'intval',
			),
			'unique_id_prefix' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'unique_id_suffix' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'use_html_id' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'html_id' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'disable_submit_until_valid' => array(
				'default' => '',
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'submit_button_html_class' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'form_hide_on_submit' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
		);

		return $fields;
	}

	public function get_controls() {
		$controls = array(
			10 => array(
				'type' => 'select',
				'label' => __( 'Confirm submission', 'happyforms' ),
				'options' => array(
					'success_message' => __( 'Success message', 'happyforms' ),
					'redirect' => __( 'Redirect to web address', 'happyforms' ),
				),
				'field' => 'confirm_submission',
				'tooltip' => __( 'Choose how the form should confirm successful submission.', 'happyforms' ),
			),
			11 => array(
				'type' => 'upsell',
				'label' => __( 'Upgrade', 'happyforms' ),
				'field' => '',
				'id' => 'happyforms-redirect-upsell',
			),
			20 => array(
				'type' => 'group_start',
				'trigger' => 'confirm_submission',
			),
			100 => array(
				'type' => 'editor',
				'label' => __( 'Success message', 'happyforms' ),
				'field' => 'confirmation_message',
			),
			101 => array(
				'type' => 'group_end'
			),
			110 => array(
				'type' => 'editor',
				'label' => __( 'Error message', 'happyforms' ),
				'tooltip' => __( 'This is the message your users will see when there are form errors preventing submission.', 'happyforms' ),
				'field' => 'error_message',
			),
			900 => array(
				'type' => 'text',
				'label' => __( 'Optional part label', 'happyforms' ),
				'tooltip' => __( 'Mark optional parts in your form to let your users distinguish them from required parts.', 'happyforms' ),
				'field' => 'optional_part_label',
			),
			1000 => array(
				'type' => 'text',
				'label' => __( 'Submit button label', 'happyforms' ),
				'tooltip' => __( 'Change text of submit button to describe an action your form performs.', 'happyforms' ),
				'field' => 'submit_button_label',
			),
			1100 => array(
				'type' => 'text',
				'label' => __( 'Submit button CSS classes', 'happyforms' ),
				'tooltip' => __( 'Add custom CSS classes separated by space for targeting a button in your stylesheet.', 'happyforms' ),
				'field' => 'submit_button_html_class'
			),
			1200 => array(
				'type' => 'checkbox',
				'label' => __( 'Use custom HTML ID', 'happyforms' ),
				'field' => 'use_html_id',
				'tooltip' => __( 'Add a unique HTML ID to your form. Write without a hash (#) character.', 'happyforms' ),
			),
			1201 => array(
				'type' => 'group_start',
				'trigger' => 'use_html_id'
			),
			1202 => array(
				'type' => 'text',
				'label' => __( 'Form HTML ID', 'happyforms' ),
				'field' => 'html_id',
			),
			1203 => array(
				'type' => 'group_end',
			),
			1204 => array(
				'type' => 'checkbox',
				'label' => __( 'Hide form after submit', 'happyforms' ),
				'tooltip' => __( 'Hide all form parts and display just title and confirmation message on submit.', 'happyforms' ),
				'field' => 'form_hide_on_submit'
			),
			1400 => array(
				'type' => 'checkbox',
				'label' => __( 'Spam prevention', 'happyforms' ),
				'tooltip' => __( 'Protect your form against bots by using HoneyPot security.', 'happyforms' ),
				'field' => 'spam_prevention',
			),
		);

		$controls = apply_filters( 'happyforms_setup_controls', $controls );
		ksort( $controls, SORT_NUMERIC );

		return $controls;
	}

	public function do_control( $control, $field, $index ) {
		$type = $control['type'];
		$path = happyforms_get_core_folder() . '/templates/customize-controls/setup';

		switch( $control['type'] ) {
			case 'editor':
			case 'checkbox':
			case 'text':
			case 'number':
			case 'radio':
			case 'select':
			case 'textarea':
			case 'group_start':
			case 'group_end':
			case 'upsell':
				require( "{$path}/{$type}.php" );
				break;
			default:
				break;
		}
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

	/**
	 * Filter: append -editable class to part templates.
	 *
	 * @hooked filter happyforms_part_class
	 *
	 * @return void
	 */
	public function part_class_customizer( $classes ) {
		if ( ! is_customize_preview() ) {
			return $classes;
		}

		$classes[] = 'happyforms-block-editable happyforms-block-editable--part';

		return $classes;
	}

	public function form_title_customizer( $title ) {
		if ( ! is_customize_preview() ) {
			return $title;
		}

		$before = '<div class="happyforms-block-editable happyforms-block-editable--partial" data-partial-id="title">';
		$after = '</div>';
		$title = "{$before}{$title}{$after}";

		return $title;
	}

	public function form_html_id( $id, $form ) {
		$has_html_id_checkbox = ( metadata_exists( 'post', $form['ID'], '_happyforms_use_html_id' ) );

		if ( ! empty( $form['html_id'] ) ) {
			if ( ! $has_html_id_checkbox || $has_html_id_checkbox && 1 == $form['use_html_id'] ) {
				$id = $form['html_id'];
			}
		}

		return esc_attr( $id );
	}

	public function form_html_class( $class, $form ) {
		if ( 1 == $form['form_hide_on_submit'] ) {
			$class[] = 'happyforms-form--hide-on-submit';
		}

		return $class;
	}

	/**
	 * Updates 'Use HTML ID' value to 1 if meta data for it does not exist
	 * but HTML ID input is not empty.
	 *
	 * @hooked filter `happyforms_update_form_data`
	 *
	 * @return array
	 */
	public function update_html_id_checkbox( $update_data ) {
		$has_html_id_checkbox = ( metadata_exists( 'post', $update_data['ID'], '_happyforms_use_html_id' ) );

		if ( ! $has_html_id_checkbox && ! empty( $update_data['_happyforms_html_id'] ) ) {
			$update_data['_happyforms_use_html_id'] = 1;
		}

		return $update_data;
	}
}

if ( ! function_exists( 'happyforms_get_setup' ) ):

function happyforms_get_setup() {
	return HappyForms_Form_Setup::instance();
}

endif;

happyforms_get_setup();
