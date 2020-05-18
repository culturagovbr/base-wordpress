<?php
$controller = happyforms_get_form_controller();
$controls = happyforms_get_email()->get_controls();
?>

<script type="text/template" id="happyforms-form-email-template">
	<div class="happyforms-stack-view happyforms-email-view">
	<?php
	$c = 0;
	foreach( $controls as $control ) {
		$field = false;

		if ( isset( $control['field'] ) ) {
			$field = $controller->get_field( $control['field'] );
		}

		do_action( 'happyforms_do_email_control', $control, $field, $c );
		$c ++;
	}
	?>
	</div>
</script>
