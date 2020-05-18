<?php

class HappyForms_Form_Part {

	public $type = '';
	public $label = '';
	public $description = '';
	public $template_id = '';

	public function get_customize_fields() {
		return array();
	}

	public function get_customize_defaults() {
		$defaults = array();
		$fields = $this->get_customize_fields();

		foreach ( $fields as $field_name => $field_settings ) {
			$defaults[$field_name] = $field_settings['default'];
		}

		return $defaults;
	}

	public function customize_templates() {}

	public function customize_enqueue_scripts( $deps = array() ) {}

	public function get_default_value( $part_data = array() ) {
		return '';
	}

	public function sanitize_value( $part_data = array(), $form_data = array(), $request = array() ) {}

	public function validate_value( $part_data, $value ) {}

	public function frontend_template( $part_data = array(), $form_data = array() ) {}

}