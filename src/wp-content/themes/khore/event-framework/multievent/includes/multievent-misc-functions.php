<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * 
 * Handles generic plugin functionality.
 *
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Get Multievent Data
 * 
 * Handles get all multievent data
 * 
 * @package Event Framework
 * @since 1.0.0
 */
function dx_multievent_get_data( $args=array() ) {
	
	$multieventargs = array( 'post_type' => MULTI_EVENT_POST_TYPE, 'post_status' => 'publish' );
	
	//return only id
	if(isset($args['fields']) && !empty($args['fields'])) {
		$multieventargs['fields'] = $args['fields'];
	}
	
	//show how many per page records
	if(isset($args['posts_per_page']) && !empty($args['posts_per_page'])) {
		$multieventargs['posts_per_page'] = $args['posts_per_page'];
	} else {
		$multieventargs['posts_per_page'] = -1;
	}
	
	//show per page records
	if(isset($args['paged']) && !empty($args['paged'])) {
		$multieventargs['paged']	=	$args['paged'];
	}
	
	//get the data by year
	if(isset($args['year']) && !empty($args['year'])) {
		$multieventargs['year']	= $args['year'];	
	}
	
	//get the data by mont
	if(isset($args['monthnum']) && !empty($args['monthnum'])) {
		$multieventargs['monthnum']	= $args['monthnum'];	
	}
	
	//get the data by day
	if(isset($args['day']) && !empty($args['day'])) {
		$multieventargs['day']	= $args['day'];	
	}
	//get the data by hour
	if(isset($args['hour']) && !empty($args['hour'])) {
		$multieventargs['hour']	= $args['year'];	
	}
	
	//get order by records
	$multieventargs['order'] = 'DESC';
	$multieventargs['orderby'] = 'date';
	
	//fire query in to table for retriving data
	$result = new WP_Query( $multieventargs );
	
	if(isset($args['getcount']) && $args['getcount'] == '1') {
		$postslist = $result->post_count;	
	}  else {
		//retrived data is in object format so assign that data to array for listing
		$postslist = dx_multievent_object_to_array( $result->posts );
	}
	
	return $postslist;
}

/**
 * Convert Object To Array
 *
 * Converting Object Type Data To Array Type
 * 
 * @package Event Framework
 * @since 1.0.0
 * 
 */
function dx_multievent_object_to_array( $result ) {
    
	$array = array();
    
	foreach ( $result as $key => $value ) {	
        
		if( is_object($value) ) {
            
        	$array[$key] = dx_multievent_object_to_array( $value );
        } else {
        	
        	$array[$key] = $value;
        }
    }
    
    return $array;
}

/**
 * Allow metabox to custom post
 * 
 * Handle to allow metabox to custome post types
 * 
 * @package Event Framework
 * @since 1.0.0
 * 
 */
function dx_multievent_meta_added_to_posts() {
	
	return $pages = array( 'poi', 'session', 'speaker', 'sponsor' );
}

/**
 * Allow multievent field to widget
 * 
 * Handle to get allow widget to add multievent
 * field and save functionality to widget
 * 
 * @package Event Framework
 * @since 1.0.0
 * 
 */
function dx_multievent_multievent_added_to_widget() {
	
	return $pages = array( 'ef_speakers', 'ef_sponsors', 'ef_schedule', 'ef_media', 'ef_news' );
}