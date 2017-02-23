<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Class
 *
 * Handles all public functionalities of plugin
 *
 * @package Event Framework
 * @since 1.0.0
 */
class Multi_Event_Public{
	
	public $script,$render;
	public function __construct() {
		
		global $multi_event_scripts, $multi_event_render;
		
		$this->script	= $multi_event_scripts;
		$this->render	= $multi_event_render;
	}
	
	/**
	 * Filter posts for multievent
	 * 
	 * Handle to filter posts according to selected event.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function dx_multievent_filter_posts( $posts_chunks, $posts_args, $instance ) {
		
		$prefix			= MUL_META_PREFIX;
		$option_value	= isset( $instance['multievent'] ) ? $instance['multievent'] : '';
		
		if( !empty( $posts_chunks ) ) {
			
			foreach ( $posts_chunks as $chunk_key => $posts_chunk ) {
				
				foreach ( $posts_chunk as $post_key => $post ) {
					
					$_assigned	= get_post_meta( $post->ID, $prefix . 'assigned', true );
					
					$_assigned = (array) $_assigned;
					
					if( !in_array( $option_value, $_assigned ) ) {
						
						unset( $posts_chunks[$chunk_key][$post_key] );
					}
				}
			}
		}
		
		return $posts_chunks;
	}
	
	/**
	 * Add Event Sidebar
	 * 
	 * Handle to add event sidebar
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function dx_multievent_event_sidebar_switch($widgets) {

		global $post;
		
		// check back end
		if( is_admin() ) {
	        return $widgets;
		}
		
		// get sidebar id as a post name
	    $post_name = isset( $post->post_name ) ? $post->post_name : '';
	        
	    $key = 'main'; // the sidebar you want to change!
	
	    // check main sidebar and post name is not empty as well as event widget is set
	    if( isset( $widgets[$key] ) && !empty( $post_name ) && isset( $widgets[$post_name] ) ) {
	        $widgets[$key] = $widgets[$post_name];
	    }
	    
	    return $widgets;
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding proper hooks for the public class
	 *
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		// Add and save multievent functionality to widget
		$widgets = dx_multievent_multievent_added_to_widget();
		
		foreach ( $widgets as $widget ) {
			
			add_filter( 'multievent_filter_posts_'.$widget, array( $this, 'dx_multievent_filter_posts' ), 10, 3 );
		}
		
		//add filter to change dynamic sidebar with particular event sidebar
		add_filter('sidebars_widgets', array( $this, 'dx_multievent_event_sidebar_switch' ) );
	}
}