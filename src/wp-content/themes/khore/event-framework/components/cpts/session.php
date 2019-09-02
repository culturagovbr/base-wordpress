<?php
register_post_type ( 'session', array (
		'labels' => array (
				'name' => __( 'Sessions', 'dxef' ),
				'singular_name' => __( 'Session', 'dxef' ),
				'add_new' => __( 'Add New', 'dxef' ),
				'add_new_item' => __( 'Add New Session', 'dxef' ),
				'edit_item' => __( 'Edit Session', 'dxef' ),
				'new_item' => __( 'New Session', 'dxef' ),
				'view_item' => __( 'View Session', 'dxef' ),
				'search_items' => __( 'Search Sessions', 'dxef' ),
				'not_found' => __( 'No Sessions found', 'dxef' ),
				'not_found_in_trash' => __( 'No Sessions found in trash', 'dxef' ),
				'menu_name' => __( 'Sessions', 'dxef' ) 
		),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array (
				'slug' => 'sessions' 
		),
		'capability_type' => 'post',
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => 5,
		'supports' => array (
				'title',
				'editor',
				'page-attributes',
				'thumbnail' 
		) 
) );

/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 */  
function khore_session_updated_messages( $messages ) {

	global $post, $post_ID;

	$messages['session'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Session updated. <a href="%s">View Session</a>', 'dxef' ), esc_url( get_permalink($post_ID) ) ),
		2 => __( 'Custom field updated.', 'dxef' ),
		3 => __( 'Custom field deleted.', 'dxef' ),
		4 => __( 'Session updated.', 'dxef' ),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __( 'Session restored to revision from %s', 'dxef' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Session published. <a href="%s">View Session</a>', 'dxef' ), esc_url( get_permalink($post_ID) ) ),
		7 => __( 'Session saved.', 'dxef' ),
		8 => sprintf( __( 'Session submitted. <a target="_blank" href="%s">Preview Session</a>', 'dxef' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Session scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Session</a>', 'dxef'),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __( 'Session draft updated. <a target="_blank" href="%s">Preview Session</a>', 'dxef' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'khore_session_updated_messages' );