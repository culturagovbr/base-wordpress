<?php

class HappyForms_Part_MultiLineText extends HappyForms_Form_Part {

	public $type = 'multi_line_text';

	public function __construct() {
		$this->label = __( 'Long Text', 'happyforms' );
		$this->description = __( 'For paragraph text fields.', 'happyforms' );

		add_filter( 'happyforms_the_part_value', array( $this, 'output_part_value' ), 10, 3 );
		add_filter( 'happyforms_email_part_value', array( $this, 'email_part_value' ), 10, 4 );
		add_filter( 'happyforms_message_part_value', array( $this, 'message_part_value' ), 10, 4 );
		add_filter( 'happyforms_part_attributes', array( $this, 'html_part_attributes' ), 10, 2 );
		add_action( 'happyforms_part_input_after', array( $this, 'part_input_after' ) );
		add_filter( 'happyforms_part_class', array( $this, 'html_part_class' ), 10, 3 );
		add_filter( 'happyforms_frontend_dependencies', array( $this, 'script_dependencies' ), 10, 2 );
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
			'placeholder' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'limit_input' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'character_limit' => array(
				'default' => 250,
				'sanitize' => 'intval',
			),
			'character_limit_mode' => array(
				'default' => 'word_max',
				'sanitize' => array(
					'happyforms_sanitize_choice',
					array( 'character_max', 'character_min', 'word_max', 'word_min' ),
				),
			),
			'characters_label' => array(
				'default' => __( 'Min characters', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'words_label' => array(
				'default' => __( 'Max words', 'happyforms' ),
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
			)
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
		$template_path = happyforms_get_core_folder() . '/templates/parts/customize-multi-line-text.php';
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

		include( happyforms_get_core_folder() . '/templates/parts/frontend-multi-line-text.php' );
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
			'part-multi-line-text',
			happyforms_get_plugin_url() . 'core/assets/js/parts/part-multi-line-text.js',
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
			$sanitized_value = sanitize_textarea_field( $request[$part_name] );
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

		if ( 1 === $part['required'] && empty( $validated_value ) ) {
			$validated_value = new WP_Error( 'error', happyforms_get_validation_message( 'field_empty' ) );
			return $validated_value;
		}

		$limit_input = intval( $part['limit_input'] );
		$character_limit = intval( $part['character_limit'] );
		$character_limit = $limit_input ? $character_limit : 0;
		$character_limit_mode = $part['character_limit_mode'];

		if ( $character_limit > 0 ) {
			$character_count = strlen( $validated_value );
			$word_count = str_word_count( $validated_value );

			if ( 'character_max' === $character_limit_mode && $character_count > $character_limit ) {
				return new WP_Error( 'error', happyforms_get_validation_message( 'message_too_long' ) );
			} else if ( 'character_min' === $character_limit_mode && $character_count < $character_limit ) {
				return new WP_Error( 'error', happyforms_get_validation_message( 'message_too_short' ) );
			} else if ( 'word_max' === $character_limit_mode && $word_count > $character_limit ) {
				return new WP_Error( 'error', happyforms_get_validation_message( 'message_too_long' ) );
			} else if ( 'word_min' === $character_limit_mode && $word_count < $character_limit ) {
				return new WP_Error( 'error', happyforms_get_validation_message( 'message_too_short' ) );
			}
		}

		return $validated_value;
	}

	public function output_part_value( $value, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			$value = stripslashes( $value );
		}

		return $value;
	}

	public function html_part_class( $class, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			if ( happyforms_get_part_value( $part, $form ) ) {
				$class[] = 'happyforms-part--filled';
			}

			if ( 'focus-reveal' === $part['description_mode'] ) {
				$class[] = 'happyforms-part--focus-reveal-description';
			}
		}

		return $class;
	}

	private function get_limit_mode( $part ) {
		$mode = 'character';

		if ( 0 === strpos( $part['character_limit_mode'], 'word' ) ) {
			$mode = 'word';
		}

		return $mode;
	}

	private function get_limit_label( $part ) {
		$min_or_max = '';
		$type = '';

		switch( $part['character_limit_mode'] ) {
			case 'character_min':
				$type = $part['characters_label'];
				break;
			case 'character_max':
				$type = $part['characters_label'];
				break;
			case 'word_min':
				$type = $part['words_label'];
				break;
			case 'word_max':
				$type = $part['words_label'];
				break;
		}

		$label = sprintf(
			'<span class="counter-label">%s</span>',
			$type,
			$min_or_max
		);

		return $label;
	}

	public function html_part_attributes( $attrs, $part ) {
		if ( $this->type !== $part['type'] ) {
			return $attrs;
		}

		$limit_input = intval( $part['limit_input'] );
		$character_limit = intval( $part['character_limit'] );
		$character_limit = $limit_input ? $character_limit : 0;

		if ( $character_limit || happyforms_is_preview() ) {
			$mode = $this->get_limit_mode( $part );
			$attrs[] = "data-length=\"{$character_limit}\"";
			$attrs[] = "data-length-mode=\"{$mode}\"";
		}

		return $attrs;
	}

	public function part_input_after( $part ) {
		if ( $this->type !== $part['type'] ) {
			return;
		}

		$limit_input = intval( $part['limit_input'] );
		$character_limit = intval( $part['character_limit'] );
		$character_limit = $limit_input ? $character_limit : 0;

		if ( $character_limit || happyforms_is_preview() ) {
			$label = $this->get_limit_label( $part );
			?>
			<div class="happyforms-part__char-counter" <?php if ( happyforms_is_preview() && ! $character_limit ) : ?>style="display: none;"<?php endif; ?>>
				<span class="counter">0</span>/<?php echo $character_limit; ?> <?php echo $label; ?>
			</div>
			<?php
		}
	}

	public function email_part_value( $value, $message, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			$value = nl2br( $value );
		}

		return $value;
	}

	public function message_part_value( $value, $original_value, $part, $destination ) {
		if ( $this->type === $part['type'] ) {
			if ( 'admin-column' === $destination ) {
				$value = make_clickable( $value );
			} else {
				$value = nl2br( $value );
			}
		}

		return $value;
	}

	public function script_dependencies( $deps, $forms ) {
		$contains_long_text = false;
		$form_controller = happyforms_get_form_controller();

		foreach ( $forms as $form ) {
			if ( $form_controller->get_first_part_by_type( $form, $this->type ) ) {
				$contains_long_text = true;
				break;
			}
		}

		if ( ! happyforms_is_preview() && ! $contains_long_text ) {
			return $deps;
		}

		wp_register_script(
			'happyforms-part-long-text',
			happyforms_get_plugin_url() . 'core/assets/js/frontend/long-text.js',
			array(), HAPPYFORMS_VERSION, true
		);

		$deps[] = 'happyforms-part-long-text';

		return $deps;
	}

}
