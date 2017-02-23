<?php

class EF_Field_Base {
	protected $id;
	protected $name;
	protected $description;
	protected $type;
	protected $args; // CSS class, options list, default value, etc.
	protected $value;

	public function __construct( $id, $name, $description = '', $args = array() ) {
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
// 		$this->type = $type;
		$this->args = $args;
		$this->value = $this->process_value( $id );
	}
	
	private function process_value( $id ) {
		$theme_options = EF_Event_Options::get_theme_options();
		return isset( $theme_options[ $id ] ) ? $theme_options[ $id ] : '';
	}
	
	public function __get( $name ) {
		if ( $name === 'value' ) {
			return $this->value;
		}
		
		if ( $name === 'selector' ) {
			return isset( $this->args['selector'] ) ? $this->args['selector'] : NULL;
		}
		
		if ( $name === 'description' ) {
			return isset( $this->description ) ? $this->description : '';
		}
	}
	
	/**
	 * Override rules for some fields that allow automatic CSS mapping
	 * 
	 * @return array property-value:
	 */
	public function get_css_rules() {
		return array();
	}
}