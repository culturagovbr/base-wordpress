<?php
add_action( 'init', 'create_sessiontype_taxonomy' );

function create_sessiontype_taxonomy() {
	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Session Types', 'taxonomy general name' ),
		'singular_name'              => _x( 'Session Type', 'taxonomy singular name' ),
		'search_items'               => __( 'Search Session Types' ),
		'popular_items'              => __( 'Popular Session Types' ),
		'all_items'                  => __( 'All ession Types' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Session Type' ),
		'update_item'                => __( 'Update Session Type' ),
		'add_new_item'               => __( 'Add New Session Type' ),
		'new_item_name'              => __( 'New Session Type Name' ),
		'separate_items_with_commas' => __( 'Separate Session Types with commas' ),
		'add_or_remove_items'        => __( 'Add or remove Session Types' ),
		'choose_from_most_used'      => __( 'Choose from the most used Session Types' ),
		'not_found'                  => __( 'No Session Types found.' ),
		'menu_name'                  => __( 'Session Types' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'session-type' ),
	);

	register_taxonomy( 'session-type', 'session', $args );
}
?>
