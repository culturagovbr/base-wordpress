<?php

class EF_Content_Generator_Field extends EF_Field_Base {

	public $type = 'content-generator';

	public function display() {
		$defaults = array(
				'section_prefix' => 'section_prefix_',
				'class' => 'ef-section',
				'style' => '',
				'id_prefix' => 'id_prefix'
		);

		$args = wp_parse_args( $this->args, $defaults );
		extract( $args );

		?>
		<section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
			<label for="<?php echo $id_prefix . $this->id ?>"><?php echo $this->name ?></label>	
			<p class="ef-description"><?php echo $this->description ?></p>
			
		</section>
		<?php
	}	
}