<?php

class HappyForms_Part_Radio extends HappyForms_Form_Part {

	public $type = 'radio';

	public $template_id = 'happyforms-radio-template';

	public static $parent;

	public function __construct() {
		$this->label = __( 'Single Choice', 'happyforms' );
		$this->description = __( 'For radio buttons allowing one selection.', 'happyforms' );

		$this->hook();
	}

	public function hook() {
		self::$parent = $this;

		add_filter( 'happyforms_part_value', array( $this, 'get_part_value' ), 10, 3 );
		add_filter( 'happyforms_stringify_part_value', array( $this, 'stringify_value' ), 10, 3 );
		add_filter( 'happyforms_part_class', array( $this, 'html_part_class' ), 10, 3 );
	}

	/**
	 * Get all part meta fields defaults.
	 *
	 * @since 1.0.0.
	 *
	 * @return array
	 */
	public function get_customize_fields() {
		$fields = array(
			'type' => array(
				'default' => $this->type,
				'sanitize' => 'sanitize_text_field',
			),
			'label' => array(
				'default' => __( 'Untitled', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'label_placement' => array(
				'default' => 'above',
				'sanitize' => 'sanitize_text_field'
			),
			'description' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'description_mode' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'width' => array(
				'default' => 'full',
				'sanitize' => 'sanitize_key'
			),
			'css_class' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'display_type' => array(
				'default' => 'block',
				'sanitize' => 'sanitize_text_field'
			),
			'required' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'options' => array(
				'default' => array(),
				'sanitize' => 'happyforms_sanitize_array'
			),
			'options_width' => array(
				'default' => 'auto',
				'sanitize' => 'sanitize_text_field'
			)
		);

		return happyforms_get_part_customize_fields( $fields, $this->type );
	}

	/**
	 * Get part option (sub-part) defaults.
	 *
	 * @since 1.0.0.
	 *
	 * @return array
	 */
	protected function get_option_defaults() {
		return array(
			'is_default' => 0,
			'label' => '',
			'description' => ''
		);
	}

	/**
	 * Get template for part item in customize pane.
	 *
	 * @since 1.0.0.
	 *
	 * @return string
	 */
	public function customize_templates() {
		$template_path = happyforms_get_core_folder() . '/templates/parts/customize-radio.php';
		$template_path = happyforms_get_part_customize_template_path( $template_path, $this->type );

		require_once( $template_path );
	}

	/**
	 * Get front end part template with parsed data.
	 *
	 * @since 1.0.0.
	 *
	 * @param array	$part_data 	Form part data.
	 * @param array	$form_data	Form (post) data.
	 *
	 * @return string	Markup for the form part.
	 */
	public function frontend_template( $part_data = array(), $form_data = array() ) {
		$part = wp_parse_args( $part_data, $this->get_customize_defaults() );
		$form = $form_data;

		foreach( $part['options'] as $o => $option ) {
			$part['options'][$o] = wp_parse_args( $option, $this->get_option_defaults() );
		}

		$template_path = happyforms_get_core_folder() . '/templates/parts/frontend-radio.php';
		$template_path = happyforms_get_part_frontend_template_path( $template_path, $this->type );

		include( $template_path );
	}

	/**
	 * Enqueue scripts in customizer area.
	 *
	 * @since 1.0.0.
	 *
	 * @param array	List of dependencies.
	 *
	 * @return void
	 */
	public function customize_enqueue_scripts( $deps = array() ) {
		wp_enqueue_script(
			'part-radio',
			happyforms_get_plugin_url() . 'core/assets/js/parts/part-radio.js',
			$deps, HAPPYFORMS_VERSION, true
		);
	}

	/**
	 * Sanitize submitted value before storing it.
	 *
	 * @since 1.0.0.
	 *
	 * @param array $part_data Form part data.
	 *
	 * @return string
	 */
	public function sanitize_value( $part_data = array(), $form_data = array(), $request = array() ) {
		$sanitized_value = $this->get_default_value( $part_data );
		$part_name = happyforms_get_part_name( $part_data, $form_data );

		if ( isset( $request[$part_name] ) ) {
			$sanitized_value = sanitize_text_field( $request[$part_name] );
		}

		return $sanitized_value;
	}

	/**
	 * Validate value before submitting it. If it fails validation, return WP_Error object, showing respective error message.
	 *
	 * @since 1.0.0.
	 *
	 * @param array $part Form part data.
	 * @param string $value Submitted value.
	 *
	 * @return string|object
	 */
	public function validate_value( $value, $part = array(), $form = array() ) {
		$validated_value = $value;

		if ( 1 === $part['required'] && '' === $validated_value ) {
			return new WP_Error( 'error', happyforms_get_validation_message( 'field_empty' ) );
		}

		if ( '' !== $validated_value ) {
			if ( ! is_numeric( $validated_value ) ) {
				return new WP_Error( 'error', happyforms_get_validation_message( 'field_invalid' ) );
			}

			$options = range( 0, count( $part['options'] ) - 1 );

			if ( ! in_array( $validated_value, $options ) ) {
				return new WP_Error( 'error', happyforms_get_validation_message( 'field_invalid' ) );
			}
		}

		return $validated_value;
	}

	public function get_part_value( $value, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			foreach ( $part['options'] as $o => $option ) {
				if ( ! happyforms_is_falsy( $option['is_default'] ) ) {
					$value = $o;
				}
			}
		}

		return $value;
	}

	public function stringify_value( $value, $part, $form, $force = false ) {
		if ( $this->type === $part['type'] || $force ) {
			if ( '' !== $value ) {
				$options = happyforms_get_part_options( $part['options'], $part, $form );
				$value = $options[$value]['label'];
			}
		}

		return $value;
	}

	public function html_part_class( $class, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			$class[] = 'happyforms-part--choice';

			if ( isset( $part['display_type'] ) && 'block' === $part['display_type'] ) {
				$class[] = 'display-type--block';
			}

			$part_options_width = $part['options_width'];

			$class[] = "happyforms-part-options-width--{$part_options_width}";
		}

		return $class;
	}

}
