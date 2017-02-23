<?php

class EF_Datapicker_Text_Field extends EF_Field_Base {
	
	public $type = 'text';
	public function include_datepicker(){
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-timepicker', get_template_directory_uri() . '/js/admin/jquery-ui-timepicker-addon.min.js', array('jquery-ui-datepicker'));
		wp_enqueue_style('jquery-ui-datepicker', get_template_directory_uri() . '/css/admin/jquery-ui-smoothness/jquery-ui-1.10.3.custom.min.css');
		wp_enqueue_style('jquery-ui-timepicker', get_template_directory_uri() . '/css/admin/jquery-ui-timepicker-addon.min.css');
	}
	public function display() {
		$defaults = array(
			'section_prefix' => 'section_prefix_',
			'class' => 'ef-section ef-text',
			'style' => '',
			'id_prefix' => 'id_prefix'
		);
		
		$args = wp_parse_args( $this->args, $defaults );
		extract( $args );
		$this->include_datepicker();
		?>
		<script type='text/javascript'>
			jQuery(document).ready(function () {
				jQuery('#<?php echo $section_prefix . $this->id ?>').on('focusin', '#<?php echo $id_prefix . $this->id ?>', function () {
					jQuery(this).datetimepicker({
						changeMonth: true,
						changeYear: true,
						altField: '.timerdate',
						altFieldTimeOnly: false,
						altFormat: 'yy-mm-dd',
						timeFormat: 'HH:mm:ss',
						dateFormat: 'yy-mm-dd',
						altTimeFormat: 'HH:mm:ss'
					});
				});
			});
		</script>
		<section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
			<label for="<?php echo $id_prefix . $this->id ?>"><?php echo $this->name ?></label>	
			<input id="<?php echo $id_prefix . $this->id ?>" type="<?php echo $this->type ?>" name="eventframework[<?php echo $this->id ?>]" value="<?php if ( ! empty( $this->value ) ) echo esc_attr( stripslashes( $this->value ) ) ?>" placeholder="<?php echo $this->name; ?>" class="ef-text" />
		</section>
		<?php

	}	
}