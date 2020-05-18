<?php

class HappyForms_Part_Poll_Dummy extends HappyForms_Form_Part {

	public $type = 'poll_dummy';

	public function __construct() {
		$this->label = __( 'Poll', 'happyforms' );
		$this->description = __( 'For collecting opinions and showing published results in a bar chart.', 'happyforms' );
	}
	
}