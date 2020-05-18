<?php
$controller = happyforms_get_form_controller();
$controls = happyforms_get_styles()->get_controls();
?>

<script type="text/template" id="happyforms-form-style-template">
    <div class="happyforms-stack-view happyforms-style-view">
        <ul class="happyforms-form-widgets happyforms-style-controls">
		<?php
		$c = 0;
		foreach( $controls as $control ) {
			$field = isset( $control['field'] ) ?
				$controller->get_field( $control['field'] ) : '';
			do_action( 'happyforms_do_style_control', $control, $field, $c );
			$c ++;
		}
		?>
		</ul>
    </div>
</script>