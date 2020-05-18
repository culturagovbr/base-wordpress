<?php

class HappyForms_Part_OptIn_Dummy extends HappyForms_Form_Part {

	public $type = 'optin_dummy';

	public function __construct() {
		$this->label = __( 'Opt-In', 'happyforms' );
		$this->description = __( 'For requiring permission before adding email address to mailing list.', 'happyforms' );
	}

}
