<?php

class HappyForms_Part_Title_Dummy extends HappyForms_Form_Part {

	public $type = 'title_dummy';
	
	public function __construct() {
		$this->label = __( 'Title', 'happyforms' );
		$this->description = __( 'For displaying personal honorifics.', 'happyforms' );
	}
	
}