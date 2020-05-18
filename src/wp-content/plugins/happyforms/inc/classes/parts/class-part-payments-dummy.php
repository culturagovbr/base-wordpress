<?php

class HappyForms_Part_Payments_Dummy extends HappyForms_Form_Part {

	public $type = 'payments_dummy';

	public function __construct() {
		$this->label = __( 'Payment', 'happyforms' );
		$this->description = __( 'For processing payments using your favorite services.', 'happyforms' );
	}

}
