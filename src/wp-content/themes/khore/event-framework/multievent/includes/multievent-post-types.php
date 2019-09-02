<?php 
// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * 
 * Handles to register post types and texonomy
 *
 * @package Event Framework
 * @since 1.0.0
 */


/**
 * 
 * Handles to register post types and texonomy
 *
 * @package Event Framework
 * @since 1.0.0
 */
function dx_multievent_initialize() {
	
	// Include Multievent part
	register_post_type( 'multievent', array (
			
			'labels' => array (
					'name'					=> __ ( 'Events', 'dxef' ),
					'singular_name'			=> __ ( 'Event', 'dxef' ),
					'add_new'				=> __ ( 'Add New', 'dxef' ),
					'add_new_item'			=> __ ( 'Add New Event', 'dxef' ),
					'edit_item'				=> __ ( 'Edit Event', 'dxef' ),
					'new_item'				=> __ ( 'New Event', 'dxef' ),
					'view_item'				=> __ ( 'View Event', 'dxef' ),
					'search_items'			=> __ ( 'Search Events', 'dxef' ),
					'not_found'				=> __ ( 'No Event found', 'dxef' ),
					'not_found_in_trash'	=> __ ( 'No Event found in trash', 'dxef' ),
					'menu_name'				=> __ ( 'Events', 'dxef' ) 
			),
			'public'			=> true,
			'show_ui'			=> true,
			'capability_type'	=> 'post',
			'hierarchical'		=> false,
			'rewrite'			=> true,
			'query_var'			=> false,
			'supports'			=> array ( 'title', 'editor', 'thumbnail' ) 
		)
	);
	
	register_taxonomy ( 'multievent-location', 'multievent', array (
			
			'hierarchical' => true,
			'labels' => array (
					'name'				=> __ ( 'Events Locations', 'dxef' ),
					'singular_name'		=> __ ( 'Event Location', 'dxef' ),
					'search_items'		=> __ ( 'Search Events Locations', 'dxef' ),
					'all_items'			=> __ ( 'All Events Locations', 'dxef' ),
					'parent_item'		=> __ ( 'Parent Event Location', 'dxef' ),
					'parent_item_colon'	=> __ ( 'Parent Event Location:', 'dxef' ),
					'edit_item'			=> __ ( 'Edit Event Location', 'dxef' ),
					'update_item'		=> __ ( 'Update Event Location', 'dxef' ),
					'add_new_item'		=> __ ( 'Add New Event Location', 'dxef' ),
					'new_item_name'		=> __ ( 'New Event Location', 'dxef' ),
					'menu_name'			=> __ ( 'Locations', 'dxef' ) 
			),
			'query_var'	=> true,
			'rewrite'	=> true 
		)
	);
}

// Add action for register post type and texonomy
add_action( 'init', 'dx_multievent_initialize' );