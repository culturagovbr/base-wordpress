<?php

/**
 * 
 * Options Tab class
 * 
 * Holding fields together in a tab
 * 
 * @author nofearinc
 *
 */
class EF_Options_Tab {
	
	/**
	 * Array of fields
	 * 
	 * @var array fields
	 */
	private $fields = array();
	
	public function __construct() { }
	
	/**
	 * Get all fields
	 * 
	 * @return array $fields:
	 */
	public function get_fields() {
		return $this->fields;
	}
	
	/**
	 * Add a field 
	 * 
	 * @param string $field_name
	 * @param Field $field
	 */
	public function add_field( $field_name, $field ) {
		$this->fields[ $field_name ] = $field;
	}
}