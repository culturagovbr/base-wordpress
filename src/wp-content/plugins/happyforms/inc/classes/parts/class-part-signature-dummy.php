<?php

class HappyForms_Part_Signature_Dummy extends HappyForms_Form_Part {

	public $type = 'signature_dummy';

	public function __construct() {
		$this->label = __( 'Signature', 'happyforms' );
		$this->description = __( 'For requiring authorization before accepting submission.', 'happyforms' );
	}

}
