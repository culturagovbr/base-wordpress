<?php

class HappyForms_Part_Table_Dummy extends HappyForms_Form_Part {

	public $type = 'table_dummy';
	
	public function __construct() {
		$this->label = __( 'Table', 'happyforms' );
		$this->description = __( 'For radios and checkboxes displaying in a grid of rows and columns.', 'happyforms' );
	}
	
}