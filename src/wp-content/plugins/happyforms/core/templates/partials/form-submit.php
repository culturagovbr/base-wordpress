<?php $submit_button_extra_class = ( happyforms_get_form_property( $form, 'submit_button_html_class' ) ) ? happyforms_get_form_property( $form, 'submit_button_html_class' ) : ''; ?>
<div class="happyforms-form__part happyforms-part happyforms-part--submit">
	<?php do_action( 'happyforms_form_submit_before', $form ); ?>
	<input type="submit" class="happyforms-submit happyforms-button--submit <?php echo $submit_button_extra_class; ?>" value="<?php echo esc_attr( happyforms_get_form_property( $form, 'submit_button_label' ) ); ?>">
	<?php do_action( 'happyforms_form_submit_after', $form ); ?>
</div>