<?php

class EF_Select_Field extends EF_Field_Base {

	public $type = 'select';

	public function display() {
		$defaults = array(
				'section_prefix' => 'section_prefix_',
				'class' => 'ef-section',
				'style' => '',
				'id_prefix' => 'id_prefix',
				'type' => $this->type
		);

		$args = wp_parse_args( $this->args, $defaults );
		extract( $args );

		?>
		<section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?> >
			<label for="<?php echo $this->id_prefix . $this->id ?>"><?php echo $this->name ?></label>	
			<select name="eventframework[<?php echo $this->id ?>]" id="<?php echo $this->id_prefix . $this->id ?>" <?php echo ( $this->type == 'multiselect' ? 'multiple="multiple"' : '' ) ?> class="ef-select" >
				<?php foreach ( $options as $key => $text ): ?>
					<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ( $key == $this->value ? 'selected' : '' ) ?>><?php echo $text ?></option>
				<?php endforeach ?>
			</select>
		</section>
		<?php
	}	
}