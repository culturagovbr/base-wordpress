<?php

class HappyForms_Part_Address_Dummy extends HappyForms_Form_Part {

	public $type = 'address_dummy';
	
	public function __construct() {
		$this->label = __( 'Address', 'happyforms' );
		$this->description = __( 'For geographical locations. Includes Google Maps intergration.', 'happyforms' );
	}
	
}