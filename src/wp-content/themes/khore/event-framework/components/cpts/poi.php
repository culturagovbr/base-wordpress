<?php
register_post_type ( 'poi', array (
		'labels' => array (
				'name' => __( 'Points of Interest', 'dxef' ),
				'singular_name' => __( 'Point of Interest', 'dxef' ),
				'add_new' => __( 'Add New', 'dxef' ),
				'add_new_item' => __( 'Add New Point of Interest', 'dxef' ),
				'edit_item' => __( 'Edit Point of Interest', 'dxef' ),
				'new_item' => __( 'New Point of Interest', 'dxef' ),
				'view_item' => __( 'View Point of Interest', 'dxef' ),
				'search_items' => __( 'Search Points of Interest', 'dxef' ),
				'not_found' => __( 'No Points of Interest found', 'dxef' ),
				'not_found_in_trash' => __( 'No Points of Interest found in trash', 'dxef' ),
				'menu_name' => __( 'Points of Interest', 'dxef' ) 
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
				'page-attributes' 
		) 
) );

/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 */  
function khore_poi_updated_messages( $messages ) {

	global $post, $post_ID;

	$messages['poi'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Point of Interest updated. <a href="%s">View Point of Interest</a>', 'dxef' ), esc_url( get_permalink($post_ID) ) ),
		2 => __( 'Custom field updated.', 'dxef' ),
		3 => __( 'Custom field deleted.', 'dxef' ),
		4 => __( 'Point of Interest updated.', 'dxef' ),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __( 'Point of Interest restored to revision from %s', 'dxef' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Point of Interest published. <a href="%s">View Point of Interest</a>', 'dxef' ), esc_url( get_permalink($post_ID) ) ),
		7 => __( 'Point of Interest saved.', 'dxef' ),
		8 => sprintf( __( 'Point of Interest submitted. <a target="_blank" href="%s">Preview Point of Interest</a>', 'dxef' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Point of Interest scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Point of Interest</a>', 'dxef'),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __( 'Point of Interest draft updated. <a target="_blank" href="%s">Preview Point of Interest</a>', 'dxef' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'khore_poi_updated_messages' );