<?php
register_taxonomy ( 'session-track', 'session', array (
		'hierarchical' => true,
		'labels' => array (
				'name' => __( 'Session Tracks', 'dxef' ),
				'singular_name' => __( 'Session Track', 'dxef' ),
				'search_items' => __( 'Search Session Tracks', 'dxef' ),
				'all_items' => __( 'All Session Tracks', 'dxef' ),
				'parent_item' => __( 'Parent Session Track', 'dxef' ),
				'parent_item_colon' => __( 'Parent Session Track:', 'dxef' ),
				'edit_item' => __( 'Edit Session Track', 'dxef' ),
				'update_item' => __( 'Update Session Track', 'dxef' ),
				'add_new_item' => __( 'Add New Session Track', 'dxef' ),
				'new_item_name' => __( 'New Session Track', 'dxef' ),
				'menu_name' => __( 'Tracks', 'dxef' ) 
		),
		'query_var' => true,
		'rewrite' => true 
) );