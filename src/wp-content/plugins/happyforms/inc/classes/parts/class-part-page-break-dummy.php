<?php

class HappyForms_Part_PageBreak_Dummy extends HappyForms_Form_Part {

	public $type = 'page_break_dummy';

	public function __construct() {
		$this->label = __( 'Page Break', 'happyforms' );
		$this->description = __( 'For splitting your form across multiple pages with navigation controls.', 'happyforms' );
	}
	
}