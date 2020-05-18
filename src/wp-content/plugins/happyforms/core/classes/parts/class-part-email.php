<?php

class HappyForms_Part_Email extends HappyForms_Form_Part {

	public $type = 'email';

	public function __construct() {
		$this->label = __( 'Email', 'happyforms' );
		$this->description = __( 'For formatted email addresses. The \'@\' symbol is required.', 'happyforms' );

		add_filter( 'happyforms_part_class', array( $this, 'html_part_class' ), 10, 3 );
		add_filter( 'happyforms_part_data_attributes', array( $this, 'html_part_data_attributes' ), 10, 3 );
		add_filter( 'happyforms_message_part_value', array( $this, 'message_part_value' ), 10, 4 );
		add_filter( 'happyforms_frontend_dependencies', array( $this, 'script_dependencies' ), 10, 2 );
		add_filter( 'happyforms_stringify_part_value', array( $this, 'stringify_value' ), 10, 3 );
		add_filter( 'happyforms_validate_part', array( $this, 'validate_part' ) );
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
			'autocomplete_domains' => array(
				'default' => 1,
				'sanitize' => 'intval'
			),
			'confirmation_field' => array(
				'default' => 0,
				'sanitize' => 'intval'
			),
			'confirmation_field_label' => array(
				'default' => __( 'Untitled', 'happyforms' ),
				'sanitize' => 'sanitize_text_field'
			),
			'confirmation_field_placeholder' => array(
				'default' => __( '', 'happyforms' ),
				'sanitize' => 'sanitize_text_field'
			),
			'placeholder' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'suffix' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'width' => array(
				'default' => 'full',
				'sanitize' => 'sanitize_key'
			),
			'css_class' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'required' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
		);

		return happyforms_get_part_customize_fields( $fields, $this->type );
	}

	/**
	 * Get template for part item in customize pane.
	 *
	 * @since 1.0.0.
	 *
	 * @return string
	 */
	public function customize_templates() {
		$template_path = happyforms_get_core_folder() . '/templates/parts/customize-email.php';
		$template_path = happyforms_get_part_customize_template_path( $template_path, $this->type );

		require_once( $template_path );
	}

	public function validate_part( $part_data ) {
		if ( $this->type !== $part_data['type'] ) {
			return $part_data;
		}

		if ( ! empty( $part_data['suffix'] ) && 1 == $part_data['autocomplete_domains'] ) {
			$part_data['autocomplete_domains'] = 0;
		}

		return $part_data;
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

		include( happyforms_get_core_folder() . '/templates/parts/frontend-email.php' );
	}

	public function get_default_value( $part_data = array() ) {
		return array();
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
			$sanitized_value[0] = sanitize_text_field( $request[$part_name] );
		}

		if ( isset( $request[$part_name . '_confirmation'] ) ) {
			$sanitized_value[1] = sanitize_text_field( $request[$part_name . '_confirmation'] );
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
		$part_name = happyforms_get_part_name( $part, $form );

		if ( empty( $value[0] ) ) {
			if ( 1 == $part['required'] ) {
				$error = new WP_Error( 'error', happyforms_get_validation_message( 'field_empty' ) );

				if ( empty( $value[1] ) ) {
					$error->add_data( array(
						'components' => array( 0, 1 )
					) );
				}

				return $error;
			} else {
				return $value;
			}
		}

		$validation_value = $value[0];
		$validation_confirmation_value = '';

		if ( ! empty( $part['suffix'] ) ) {
			$validation_value = "{$validation_value}{$part['suffix']}";
		}

		if ( isset( $value[1] ) ) {
			$validation_confirmation_value = $value[1];

			if ( ! empty( $part['suffix'] ) ) {
				$validation_confirmation_value = "{$validation_confirmation_value}{$part['suffix']}";
			}
		}

		if ( ! is_email( $validation_value ) ) {
			$error = new WP_error( 'error', happyforms_get_validation_message( 'field_invalid' ) );

			if ( ! empty( $validation_confirmation_value ) && ! is_email( $validation_confirmation_value ) ) {
				$error->add_data( array(
					'components' => array( 0, 1 )
				) );
			}

			return $error;
		}

		if ( isset( $value[1] ) && $validation_value !== $validation_confirmation_value ) {
			$error = new WP_Error();

			if ( ! empty( $value[1] ) ) {
				$error->add( 'error', happyforms_get_validation_message( 'values_mismatch' ), array(
					'components' => array( 1 )
				) );
			} else {
				$error->add( 'error', happyforms_get_validation_message( 'field_empty' ), array(
					'components' => array( 1 )
				) );
			}

			return $error;
		}

		return $value[0];
	}

	public function stringify_value( $value, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			if ( ! empty( $part['suffix'] ) ) {
				$value = "{$value}{$part['suffix']}";
			}
		}

		return $value;
	}

	public function html_part_data_attributes( $attributes, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			if ( $part['confirmation_field'] ) {
				$attributes['happyforms-require-confirmation'] = '';
			}
			if ( $part['autocomplete_domains'] ) {
				$attributes['mode'] = 'autocomplete';
			}
		}

		return $attributes;
	}

	public function html_part_class( $class, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			if ( happyforms_get_part_value( $part, $form, 0 )
				|| happyforms_get_part_value( $part, $form, 1 ) ) {
				$class[] = 'happyforms-part--filled';
			}

			if ( 'focus-reveal' === $part['description_mode'] ) {
				$class[] = 'happyforms-part--focus-reveal-description';
			}

			$class[] = 'happyforms-part--with-autocomplete';
		}

		return $class;
	}

	public function get_domains_for_autocomplete() {
		$domains = array(
			'gmail.com',
			'yahoo.com',
			'hotmail.com',
			'aol.com',
			'icloud.com',
			'outlook.com'
		);

		return apply_filters( 'happyforms_email_domains_autocomplete', $domains );
	}

	public function message_part_value( $value, $original_value, $part, $destination ) {
		if ( isset( $part['type'] )
			&& $this->type === $part['type'] ) {

			switch( $destination ) {
				case 'email':
				case 'admin-column':
					$value = "<a href=\"mailto:{$value}\">{$value}</a>";
					break;
				default:
					break;
			}

		}

		return $value;
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
			'part-email',
			happyforms_get_plugin_url() . 'core/assets/js/parts/part-email.js',
			$deps, HAPPYFORMS_VERSION, true
		);
	}

	public function script_dependencies( $deps, $forms ) {
		$contains_email = false;
		$form_controller = happyforms_get_form_controller();

		foreach ( $forms as $form ) {
			if ( $form_controller->get_first_part_by_type( $form, $this->type ) ) {
				$contains_email = true;
				break;
			}
		}

		if ( ! happyforms_is_preview() && ! $contains_email ) {
			return $deps;
		}

		wp_register_script(
			'happyforms-email',
			happyforms_get_plugin_url() . 'core/assets/js/frontend/email.js',
			array( 'happyforms-select' ), HAPPYFORMS_VERSION, true
		);

		$settings = array(
			'url' => admin_url( 'admin-ajax.php' ),
			'autocompleteSource' => $this->get_domains_for_autocomplete()
		);

		wp_localize_script(
			'happyforms-email',
			'_happyFormsEmailSettings',
			$settings
		);

		$deps[] = 'happyforms-email';

		return $deps;
	}
}
