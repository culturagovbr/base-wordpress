<?php

class HappyForms_Part_Phone_Dummy extends HappyForms_Form_Part {

	public $type = 'phone_dummy';
	
	public function __construct() {
		$this->label = __( 'Phone', 'happyforms' );
		$this->description = __( 'For phone numbers. Includes country specific formatting.', 'happyforms' );
	}
	
}