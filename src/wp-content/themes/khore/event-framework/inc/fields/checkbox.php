<?php

class EF_Checkbox_Field extends EF_Field_Base {

	public $type = 'checkbox';

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
			<label for="<?php echo $this->id_prefix . $this->id ?>"><?php echo $this->name ?></label>
			
			<?php
			$checkboxValue = EF_Event_Options::get_theme_option( $this->id );
			
			$checked = '';
			if ( isset( $checkboxValue ) && $checkboxValue != '' ) {
				$checked = 'checked="checked"';
			}
			?>
			
			<input id="<?php echo $this->id_prefix . $this->id; ?>" type="checkbox" value="<?php echo $this->id; ?>" name="eventframework[<?php echo $this->id; ?>]" <?php echo $checked ?> />
		</section>
		<?php
	}	
}