<?php
register_post_type ( 'sponsor', array (
		'labels' => array (
				'name' => __( 'Sponsors', 'dxef' ),
				'singular_name' => __( 'Sponsor', 'dxef' ),
				'add_new' => __( 'Add New', 'dxef' ),
				'add_new_item' => __( 'Add New Sponsor', 'dxef' ),
				'edit_item' => __( 'Edit Sponsor', 'dxef' ),
				'new_item' => __( 'New Sponsor', 'dxef' ),
				'view_item' => __( 'View Sponsor', 'dxef' ),
				'search_items' => __( 'Search Sponsors', 'dxef' ),
				'not_found' => __( 'No Sponsors found', 'dxef' ),
				'not_found_in_trash' => __( 'No Sponsors found in trash', 'dxef' ),
				'menu_name' => __( 'Sponsors', 'dxef' ) 
		),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => true,
		'query_var' => false,
		'supports' => array (
				'title',
				'editor',
				'author',
				'thumbnail' 
		) 
) );

/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 */  
function khore_sponsor_updated_messages( $messages ) {

	global $post, $post_ID;

	$messages['sponsor'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Sponsor updated. <a href="%s">View Sponsor</a>', 'dxef' ), esc_url( get_permalink($post_ID) ) ),
		2 => __( 'Custom field updated.', 'dxef' ),
		3 => __( 'Custom field deleted.', 'dxef' ),
		4 => __( 'Sponsor updated.', 'dxef' ),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __( 'Sponsor restored to revision from %s', 'dxef' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Sponsor published. <a href="%s">View Sponsor</a>', 'dxef' ), esc_url( get_permalink($post_ID) ) ),
		7 => __( 'Sponsor saved.', 'dxef' ),
		8 => sprintf( __( 'Sponsor submitted. <a target="_blank" href="%s">Preview Sponsor</a>', 'dxef' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Sponsor scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Sponsor</a>', 'dxef'),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __( 'Sponsor draft updated. <a target="_blank" href="%s">Preview Sponsor</a>', 'dxef' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'khore_sponsor_updated_messages' );