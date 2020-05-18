<?php

class HappyForms_Part_WebsiteUrl_Dummy extends HappyForms_Form_Part {

	public $type = 'website_url_dummy';

	public function __construct() {
		$this->label = __( 'Web Address', 'happyforms' );
		$this->description = __( 'For formatted site URLs.', 'happyforms' );
	}

}
