<?php
// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * MultiEvent Functionality
 * 
 * Handles to MultiEvent Functionality
 * 
 * @package Event Framework
 * @since 1.0.0
 */
if ( current_theme_supports( 'multievent' ) ) {
	
	if( !defined( 'MUL_META_PREFIX' ) ) {
		define( 'MUL_META_PREFIX', '_multievent_' );
	}
	if( !defined( 'MULTI_EVENT_DIR' ) ) {
		define( 'MULTI_EVENT_DIR', dirname( __FILE__ ) );
	}
	if( !defined( 'MULTI_EVENT_URL' ) ) {
		define( 'MULTI_EVENT_URL', EF_PARENT_URL .'multievent/' );
	}
	if( !defined( 'MULTI_EVENT_ADMIN' ) ) {
		define( 'MULTI_EVENT_ADMIN', MULTI_EVENT_DIR . '/includes/admin' ); // admin dir
	}
	if( !defined( 'MULTI_EVENT_POST_TYPE' ) ) {
		define( 'MULTI_EVENT_POST_TYPE', 'multievent' );
	}
	
	global $multi_event_admin, $multi_event_public, $multi_event_scripts,
			$multi_event_render, $multi_event_model;
	
	// Add functions file
	require_once( MULTI_EVENT_DIR . '/includes/multievent-misc-functions.php' );
	
	//includes model class file
	require_once ( MULTI_EVENT_DIR .'/includes/class-multievent-model.php');
	$multi_event_model = new Multi_Event_Model();
	
	//Add scripts functionality
	require_once( MULTI_EVENT_DIR . '/includes/class-multievent-scripts.php' );
	$multi_event_scripts = new Multi_Event_Scripts();
	$multi_event_scripts->add_hooks();
	
	//Render class to handles most of html design
	require_once( MULTI_EVENT_DIR . '/includes/class-multievent-renderer.php' );
	$multi_event_render = new Multi_Event_Renderer();
	
	//Add admin functionality
	require_once( MULTI_EVENT_ADMIN . '/class-multievent-admin.php' );
	$multi_event_admin = new Multi_Event_Admin();
	$multi_event_admin->add_hooks();
	
	//Add public functionality
	require_once( MULTI_EVENT_DIR . '/includes/class-multievent-public.php' );
	$multi_event_public = new Multi_Event_Public();
	$multi_event_public->add_hooks();
	
	// Add Custom Post Type multievent
	require_once( MULTI_EVENT_DIR . '/includes/multievent-post-types.php' );
	
}