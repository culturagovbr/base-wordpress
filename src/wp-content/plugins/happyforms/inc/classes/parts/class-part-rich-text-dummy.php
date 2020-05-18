<?php

class HappyForms_Part_RichText_Dummy extends HappyForms_Form_Part {

	public $type = 'rich_text_dummy';
	
	public function __construct() {
		$this->label = __( 'Text Editor', 'happyforms' );
		$this->description = __( 'For formatting text, code blocks, lists and more.', 'happyforms' );
	}
	
}