<?php

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}


//add_action( 'cmb2_admin_init', 'vtlr_register_side_metabox' );
/**
 * Hook in and add a metabox that only appears on the 'About' page
 */
/*function vtlr_register_side_metabox() {
	$prefix = 'vtlr_settings_';

	/**
	 * Metabox to be displayed on a single page ID
	 */
	/*$cmb_side = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Vtlr settings', 'cmb2' ),
		'object_types' => array( 'vtlr', ), // Post type
		'context'      => 'side',
		'priority'     => 'default',
		'show_names'   => true, // Show field names on the left
		//'show_on'      => array( 'id' => array( 2, ) ), // Specific post IDs to display this metabox
	) );

		$cmb_side->add_field( array(
		'name'    => __( 'Odd timeline event background', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'colorpickerone',
		'type'    => 'colorpicker',
		'default' => '#ffffff',
		 'attributes' => array(
		 	'data-colorpicker' => json_encode( array(
		 		'palettes' => array( '#3dd0cc', '#ff834c', '#4fa2c0', '#0bc991', ),
		 	) ),
		 ),
	) );

		$cmb_side->add_field( array(
		'name'    => __( 'Even timeline event background', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'colorpickertwo',
		'type'    => 'colorpicker',
		'default' => '#ffffff',
		 'attributes' => array(
		 	'data-colorpicker' => json_encode( array(
		 		'palettes' => array( '#3dd0cc', '#ff834c', '#4fa2c0', '#0bc991', ),
		 	) ),
		 ),
	) );

} */

add_action( 'cmb2_admin_init', 'vtlr_register_repeatable_group_field_metabox' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function vtlr_register_repeatable_group_field_metabox() {
	$prefix = 'vtlr_group_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb_group = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Add Timeline Events', 'cmb2' ),
		'object_types' => array( 'vtlr', ),
	) );

	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_field_id = $cmb_group->add_field( array(
		'id'          => $prefix . 'demo',
		'type'        => 'group',
		//'description' => __( 'Generates reusable form entries', 'cmb2' ),
		'options'     => array(
			'group_title'   => __( 'Timeline Event {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => __( 'Add Timeline Event', 'cmb2' ),
			'remove_button' => __( 'Remove Timeline Event', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb_group->add_group_field( $group_field_id, array(
		'name'       => __( 'Date or text', 'cmb2' ),
		'id'         => 'dateortext',
		'type'       => 'text',
		//'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name'       => __( 'Title', 'cmb2' ),
		'desc'    => __( 'You can keep it blank', 'cmb2' ),
		'id'         => 'title',
		'type'       => 'text',
		//'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name'    => __( 'Description', 'cmb2' ),
		'id'      => 'textarea1',
		'type'    => 'textarea',
		'options' => array( 'textarea_rows' => 5, ),
	) );

	

	

	

}

