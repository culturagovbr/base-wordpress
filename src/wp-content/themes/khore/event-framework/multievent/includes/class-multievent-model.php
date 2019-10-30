<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Plugin Model Class
 *
 * Handles generic functionailties
 *
 * @package Event Framework
 * @since 1.0.0
 */
 class Multi_Event_Model {
 	 	
 	//class constructor
	function __construct()	{		

	}
	
	/**
	 * All Event Data
	 *
	 * Handle to get all event data
	 *
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function dx_multievent_get_events( $args = array() ) {
		
		$queryargs = array( 'post_type' => MULTI_EVENT_POST_TYPE, 'post_status' => 'publish' );
		
		//if search is called then retrive searching data
		if( isset( $args['search'] ) ) {
			
			$queryargs['s'] = $args['search'];
		}
		
		if( isset( $args['meta_query'] ) ) {
			
			$queryargs['meta_query'] = $args['meta_query'];
		}
		
		//fire query in to table for retriving data
		$result	= new WP_Query( $queryargs );
		
		//retrived data is in object format so assign that data to array for listing
		$postslist	= $this->dx_multievent_object_to_array( $result->posts );
		
		return $postslist;
	}
	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package Event Framework
 	 * @since 1.0.0
	 */
	function dx_multievent_object_to_array( $result ) {
		
		$array = array();
		
		foreach ( $result as $key => $value ) {	
			
			if( is_object( $value ) ) {
				
				$array[$key]	= $this->dx_multievent_object_to_array($value);
			} else {
				
				$array[$key]	= $value;
			}
		}
		
		return $array;
	}
}