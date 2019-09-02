<?php
register_taxonomy ( 'session-location', 'session', array (
		'hierarchical' => true,
		'labels' => array (
				'name' => __( 'Session Locations', 'dxef' ),
				'singular_name' => __( 'Session Location', 'dxef' ),
				'search_items' => __( 'Search Session Locations', 'dxef' ),
				'all_items' => __( 'All Session Locations', 'dxef' ),
				'parent_item' => __( 'Parent Session Location', 'dxef' ),
				'parent_item_colon' => __( 'Parent Session Location:', 'dxef' ),
				'edit_item' => __( 'Edit Session Location', 'dxef' ),
				'update_item' => __( 'Update Session Location', 'dxef' ),
				'add_new_item' => __( 'Add New Session Location', 'dxef' ),
				'new_item_name' => __( 'New Session Location', 'dxef' ),
				'menu_name' => __( 'Locations', 'dxef' ) 
		),
		'query_var' => true,
		'rewrite' => true 
) );