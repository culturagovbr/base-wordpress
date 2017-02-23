<?php

class EF_Radio_Field extends EF_Field_Base {

	public $type = 'radio';

	public function display() {
		$defaults = array(
				'section_prefix' => 'section_prefix_',
				'class' => 'ef-section',
				'style' => '',
				'id_prefix' => 'id_prefix'
		);

		$options = array();
		
		$args = wp_parse_args( $this->args, $defaults );
		extract( $args );

		?>
		<section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
			<label for="<?php echo $id_prefix . $this->id ?>"><?php echo $this->name ?></label>	
			
			<?php $radioValue = EF_Event_Options::get_theme_option( $id ); ?>
				
			<?php foreach( $options as $key => $text ) : ?>
				<div class="<?php echo $id . '_radios'; ?> ef-radiogroup">
					<label for="<?php echo $key; ?>"><?php echo $text ?></label>
					<?php 
					$checked = '';
					if ( isset( $radioValue[$id] ) && $radioValue[$id] == $key ) :
						$checked = 'checked="checked"';
					endif;
					?>
					<input id="<?php echo $key; ?>" type="radio" value="<?php echo $key; ?>" name="eventframework[<?php echo $id; ?>]" <?php echo $checked ?> />
				</div>
			<?php endforeach; ?>	
		</section>
		<?php
	}	
}