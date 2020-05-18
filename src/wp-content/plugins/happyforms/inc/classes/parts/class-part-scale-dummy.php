<?php

class HappyForms_Part_Scale_Dummy extends HappyForms_Form_Part {

	public $type = 'scale_dummy';
	
	public function __construct() {
		$this->label = __( 'Scale', 'happyforms' );
		$this->description = __( 'For collecting opinions using a horizontal slider.', 'happyforms' );
	}
	
}