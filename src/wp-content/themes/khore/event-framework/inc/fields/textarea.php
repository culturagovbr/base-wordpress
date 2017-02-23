<?php

class EF_Textarea_Field extends EF_Field_Base {

	public $type = 'textarea';

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
			<textarea id="<?php echo $id_prefix . $this->id ?>" name="eventframework[<?php echo $this->id ?>]" rows="2" cols="20" class="ef-textarea"><?php if ( ! empty( $this->value ) ) echo $this->value ?></textarea>
		</section>
		<?php
	}	
}