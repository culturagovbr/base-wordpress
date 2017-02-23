<?php

class EF_Background_Color_Field extends EF_Field_Base {
	
	public $type = 'background-color';
	
	public function display() {
		$defaults = array(
			'section_prefix' => 'section_prefix_',
			'class' => 'ef-section',
			'style' => '',
			'id_prefix' => 'id_prefix',
			'default' => '#FFF',
			'selector' => ''
		);
		
		$args = wp_parse_args( $this->args, $defaults );
		extract( $args );
		
		wp_enqueue_script( 'ef-colorpicker', EF_ASSETS_URL . 'js/farbtastic.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_style( 'ef-colorpicker', EF_ASSETS_URL . 'css/farbtastic.css' );

		?>
		<section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
			<label for="<?php echo $this->id_prefix . $this->id ?>"><?php echo $this->name ?></label>	
			<input id="<?php echo $this->id_prefix . $this->id ?>" type="text" name="eventframework[<?php echo $this->id ?>]" value="<?php if ( ! empty( $this->value ) ) { echo $this->value; } else { echo $default; }?>" placeholder="<?php echo $this->name; ?>" class="ef-picker" />
			<div id="<?php echo $this->id_prefix . $this->id ?>_colorpicker"></div>
		</section>
		<script type="text/javascript">
				jQuery(document).ready(function($) {
					var field_id = '<?php echo $this->id_prefix . $this->id ?>';
					$('#' + field_id + '_colorpicker').farbtastic('#' + field_id);
				});
		</script>
		<?php
	}

	public function get_css_rules() {
		$css = array();
		
		if ( ! empty( $this->value ) ) {
			$css[ 'background-color' ] = $this->value;
		}
		
		return $css;
	}
}