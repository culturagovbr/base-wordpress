<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Handles all admin functionalities of plugin
 *
 * @package Event Framework
 * @since 1.0.0
 */
class Multi_Event_Admin{
	
	public $script,$render,$model;
	public function __construct() {
		
		global $multi_event_scripts, $multi_event_render, $multi_event_model;
		
		$this->script	= $multi_event_scripts;
		$this->render	= $multi_event_render;
		$this->model	= $multi_event_model;
	}
	
	/**
	 * Add MetaBox
	 * 
	 * Handle add meta box
	 * to custom post types
	 *
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function dx_multievent_add_meta_box() {
		
		$pages = dx_multievent_meta_added_to_posts();
		
		foreach ( $pages as $page ) {
			
			add_meta_box( 'wp_meta_multievent', __( 'Event', 'dxef'), array( $this, 'dx_multievent_meta_box' ), $page, 'normal', 'low' );
			
		}
	}
	
	/**
	 * Display MultiEvent Post meta
	 * 
	 * Handle to display MultiEvent
	 * post meta to custom post types
	 *
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function dx_multievent_meta_box() {
		
		include_once( MULTI_EVENT_ADMIN . '/forms/multievent-box.php' );
	}
	
	/**
	 * Save Post meta
	 * 
	 * Handle to save multievent
	 * meta data to custom post
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function dx_multievent_save_meta_values( $post_id ) {
		
		global $post_type;
		
		$prefix = MUL_META_PREFIX;
		
		$post_type_object = get_post_type_object( $post_type );
		
		// Check for which post type we need to add the meta box
		$pages = dx_multievent_meta_added_to_posts();
		
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                			// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )        	// Check Revision
		|| ( ! in_array( $post_type, $pages ) )              // Check if current post type is supported.
		|| ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) )      // Check permission
		{
			return $post_id;
		}
		
		// Update multievent 
		$assigned = isset($_POST[ $prefix . 'assigned' ]) ? $_POST[ $prefix . 'assigned' ] : array();
		update_post_meta( $post_id, $prefix . 'assigned', $assigned );
	}
	
	/**
	 * Add search widget
	 * 
	 * Handle add search functionality
	 * on admin side widget page
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function multievent_add_widget_field( $widget_instance ) { 
		
		$number		= isset( $widget_instance->number ) ? $widget_instance->number : 0;
		$settings	= $widget_instance->get_settings();
		
		$widget_id			= isset( $widget_instance->id_base ) ? $widget_instance->id_base : '';
		$widget_name_field	= $widget_instance->get_field_name( 'multievent' );
		$widget_id_field	= $widget_instance->get_field_id( 'multievent' );
		$widget_value_field	= isset( $settings[$number]['multievent'] ) ? $settings[$number]['multievent'] : '';
		
		$allow_added_field	= dx_multievent_multievent_added_to_widget();
		$multievent_data	= dx_multievent_get_data();
		
		if( in_array( $widget_id, $allow_added_field ) ) {
			?>
			
			<label for="<?php echo $widget_id_field; ?>"><?php echo __( 'Select Event', 'dxef' );?></label><br />
			<select id="<?php echo $widget_id_field; ?>" name="<?php echo $widget_name_field; ?>">
				<option value="">- <?php echo __( 'Select Event', 'dxef' )?> -</option><?php
				
				foreach ( $multievent_data as $multievent ) {
					
					$selected = '';
					if( $multievent['ID'] == $widget_value_field ) {
						
						$selected = ' selected="selected"';
					}?>
					
					<option value="<?php echo $multievent['ID'];?>" <?php echo $selected;?>><?php echo $multievent['post_title'];?></option><?php 
				}?>
			</select><?php 
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding proper hooks for the admin class
	 *
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function multievent_update_widget_field( $instance, $new_instance ) {
		
		$instance['multievent'] = isset( $new_instance['multievent'] ) ? $new_instance['multievent'] : '';
		return $instance;
	}
	
	/**
	 * Event Widget Area
	 * 
	 * Handle to create Widget event area
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function multievent_create_event_widget_area() {
		
		$events = $this->model->dx_multievent_get_events();
		
		if( !empty( $events ) ) {
			
			foreach ( $events as $event ) {
				
				$multievent_sidebar_args = array(
								'name'          => $event['post_title'],
								'id'            => $event['post_name'],
								'description'   => __('This widget area is for event.', 'dxef'),
							    'class'         => 'multievent-widget-event',
								'before_title'  => '',
								'after_title'   => ''
							);
				register_sidebar( $multievent_sidebar_args );
			}
		}
	}
	
	/**
	 * Save multievent field
	 * 
	 * Handle to save multievent
	 * field to widgets
	 *
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		// Add Meta boxes
		add_action( 'add_meta_boxes', array( $this, 'dx_multievent_add_meta_box' ) );
		
		// Save Metabox data
		add_action( 'save_post', array( $this, 'dx_multievent_save_meta_values' ) );
		
		// Add and save multievent functionality to widget
		add_action( 'in_widget_form', array( $this, 'multievent_add_widget_field' ) );
		
		// Update multievent field in widget
		add_filter( 'widget_update_callback', array( $this, 'multievent_update_widget_field' ), 10, 2 );
		
		// Update multievent field in widget
		add_action( 'widgets_init', array( $this, 'multievent_create_event_widget_area' ) );
		
	}
}