<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package Event Framework
 * @since 1.0.0
 */
class Multi_Event_Scripts {
	
	function __construct() {
		
		
	}
	
	/**
	 * Add Css Styles
	 *
	 * Handle to add Css styles to admin side
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function dx_multievent_admin_styles() {
		
		global $post, $wp_version;
		
		$allow_post_types = dx_multievent_meta_added_to_posts();
		
		//Check post types
		if( isset( $post->post_type ) && in_array( $post->post_type, $allow_post_types ) ) {
			
			wp_register_style( 'multievent-chosen-styles', MULTI_EVENT_URL . 'includes/css/chosen/chosen.css' );
			wp_enqueue_style( 'multievent-chosen-styles' );
			
			wp_register_style( 'multievent-admin-styles', MULTI_EVENT_URL . 'includes/css/multievent-admin.css' );
			wp_enqueue_style( 'multievent-admin-styles' );
			
		}
	}
	
	/**
	 * Add JS Scripts
	 *
	 * Handle to add Js scripts to admin side
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function dx_multievent_admin_scripts() {
		
		global $post, $wp_version;
		
		$allow_post_types = dx_multievent_meta_added_to_posts();
		
		//Check post types
		if( isset( $post->post_type ) && in_array( $post->post_type, $allow_post_types ) ) {
			
			wp_register_script( 'multievent-chosen-scripts', MULTI_EVENT_URL . 'includes/js/chosen/chosen.jquery.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'multievent-chosen-scripts' );
			
			wp_register_script( 'multievent-admin-scripts', MULTI_EVENT_URL . 'includes/js/multievent-admin.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'multievent-admin-scripts' );
			
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//Style for back end
		add_action( 'admin_print_styles-post.php', array( $this, 'dx_multievent_admin_styles' ) );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'dx_multievent_admin_styles' ) );
		
		// Script for back end
		add_action( 'admin_print_styles-post.php', array( $this, 'dx_multievent_admin_scripts' ) );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'dx_multievent_admin_scripts' ) );
		
	}
}
?>