<?php
function happyforms_uninstall_cleanup() {
	if ( file_exists( ABSPATH . 'wp-content/plugins/happyforms-upgrade' ) ) {
		return;
	}

	// Cleanup forms
	$forms = get_posts( array(
		'post_type' => 'happyform',
		'post_status' => 'any',
		'numberposts' => -1,
	) );

	foreach( $forms as $form ) {
		wp_delete_post( $form->ID, true );
	}

	// Cleanup responses
	$responses = get_posts( array(
		'post_type' => 'happyforms-message',
		'post_status' => 'any',
		'numberposts' => -1,
	) );

	foreach( $responses as $response ) {
		wp_delete_post( $response->ID, true );
	}

	// Cleanup options
	delete_option( 'happyforms-data-version' );
	delete_option( 'happyforms-tracking' );
	delete_option( 'widget_happyforms_widget' );
	delete_transient( '_happyforms_has_responses' );
}

add_action( 'happyforms_uninstall', 'happyforms_uninstall_cleanup' );
