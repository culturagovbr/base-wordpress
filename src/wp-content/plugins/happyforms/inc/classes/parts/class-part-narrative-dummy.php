<?php

class HappyForms_Part_Narrative_Dummy extends HappyForms_Form_Part {

	public $type = 'narrative_dummy';

	public function __construct() {
		$this->label = __( 'Blanks', 'happyforms' );
		$this->description = __( 'For adding fill-in-the-blank style inputs to a paragraph of text.', 'happyforms' );
	}

}
