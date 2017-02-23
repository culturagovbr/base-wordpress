<?php

class EF_Text_Field extends EF_Field_Base {
	
	public $type = 'text';
	
	public function display() {
		$defaults = array(
			'section_prefix' => 'section_prefix_',
			'class' => 'ef-section ef-text',
			'style' => '',
			'id_prefix' => 'id_prefix'
		);
		
		$args = wp_parse_args( $this->args, $defaults );
		extract( $args );

		?>
		<section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
			<label for="<?php echo $id_prefix . $this->id ?>"><?php echo $this->name ?></label>	
			<input id="<?php echo $id_prefix . $this->id ?>" type="<?php echo $this->type ?>" name="eventframework[<?php echo $this->id ?>]" value="<?php if ( ! empty( $this->value ) ) echo esc_attr( stripslashes( $this->value ) ) ?>" placeholder="<?php echo $this->name; ?>" class="ef-text" />
		</section>
		<?php
	}	
}