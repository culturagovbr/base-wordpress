<?php

class HappyForms_Part_Placeholder_Dummy extends HappyForms_Form_Part {

	public $type = 'placeholder_dummy';
	
	public function __construct() {
		$this->label = __( 'Placeholder', 'happyforms' );
		$this->description = __( 'For adding helper text, horizontal rules and extra space.', 'happyforms' );
	}
	
}