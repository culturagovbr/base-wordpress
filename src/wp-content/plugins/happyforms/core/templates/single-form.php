<div class="happyforms-form <?php happyforms_the_form_class( $form ); ?>" id="<?php happyforms_the_form_container_id( $form ); ?>">
	<?php do_action( 'happyforms_form_before', $form ); ?>

	<form action="<?php happyforms_form_action( $form['ID'] ); ?>" id="<?php happyforms_the_form_id( $form ); ?>" method="post" <?php happyforms_the_form_attributes( $form ); ?>>
		<?php do_action( 'happyforms_form_open', $form ); ?>

		<?php happyforms_action_field(); ?>
		<?php happyforms_form_field( $form['ID'] ); ?>
		<?php happyforms_nonce_field( $form ); ?>
		<?php happyforms_step_field( $form ); ?>

		<div class="happyforms-flex">
			<?php happyforms_message_notices( $form['ID'] ); ?>
			<?php happyforms_honeypot( $form ); ?>
			<?php $parts = apply_filters( 'happyforms_get_form_parts', $form['parts'], $form ); ?>
			<?php do_action( 'happyforms_parts_before', $form ); ?>
			<?php foreach ( $parts as $part ) {
				happyforms_the_form_part( $part, $form );
			} ?>
			<?php do_action( 'happyforms_parts_after', $form ); ?>
			<?php happyforms_submit( $form ); ?>
		</div>

		<?php do_action( 'happyforms_form_close', $form ); ?>
	</form>

	<?php do_action( 'happyforms_form_after', $form ); ?>
</div>