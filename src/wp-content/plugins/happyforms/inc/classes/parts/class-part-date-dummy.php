<?php

class HappyForms_Part_Date_Dummy extends HappyForms_Form_Part {

	public $type = 'date_dummy';
	
	public function __construct() {
		$this->label = __( 'Date & Time', 'happyforms' );
		$this->description = __( 'For formatted day, month, year and or time fields.', 'happyforms' );
	}
	
}