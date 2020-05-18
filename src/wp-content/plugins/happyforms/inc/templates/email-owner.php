<?php
/**
 *
 * Part loop
 *
 */
?>

<?php $message = array( 'parts' => $response ); ?>

<?php foreach( $form['parts'] as $part ) : ?>
	<?php if ( happyforms_email_is_part_visible( $part, $form, $response ) ) : ?>

	<b><?php echo happyforms_get_email_part_label( $response, $part, $form ); ?></b><br>
	<?php echo happyforms_get_email_part_value( $message, $part, $form ); ?>
	<br><br>

	<?php endif; ?>

<?php endforeach; ?>

<?php
/**
 *
 * Tracking number
 *
 */
?>
<?php if ( happyforms_get_form_property( $form, 'unique_id' ) ) : ?>

<b><?php _e( 'Tracking number', 'happyforms' ); ?></b><br>
<?php echo $response['tracking_id']; ?>

<?php endif; ?>
