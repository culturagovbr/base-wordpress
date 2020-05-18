<?php

class HappyForms_Part_Attachment_Dummy extends HappyForms_Form_Part {

	public $type = 'attachment_dummy';
	
	public function __construct() {
		$this->label = __( 'Attachment', 'happyforms' );
		$this->description = __( 'For allowing file uploads with easy drag and drop zone.', 'happyforms' );
	}
	
}