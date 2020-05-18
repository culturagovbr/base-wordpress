<?php

class HappyForms_Part_Legal_Dummy extends HappyForms_Form_Part {

	public $type = 'legal_dummy';

	public function __construct() {
		$this->label = __( 'Compliance', 'happyforms' );
		$this->description = __( 'For requiring permission before accepting submission.', 'happyforms' );
	}

}
