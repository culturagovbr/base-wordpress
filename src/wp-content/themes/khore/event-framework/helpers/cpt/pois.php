<?php

/**
 * POI helper
 * 
 * @author nofearinc
 *
 */
class EF_Poi_Helper {

	/**
	 * List all field types with aliases
	 * 
	 * @var array field types
	 */
	private static $field_types = array(
		'address' => 'poi_address',
		'manual' => 'poi_manual_coordinates',
		'lat' => 'poi_latitude',
		'long' => 'poi_longitude',
	);

	/**
	 * Retrieve a meta field
	 * 
	 * @param int $id post ID
	 * @param string $meta_key key alias
	 * @param int $order numeric order of field
	 */
	public static function get_meta( $id, $meta_key, $order = null ) {
		$post_meta_key = $meta_key;
		
		if( isset( self::$field_types[$meta_key] ) ) {
			$post_meta_key = self::$field_types[$meta_key];
		}
		
		if ( ! is_null( $order ) ) {
			$post_meta_key .= $order;
		}
		
		return get_post_meta(get_the_ID(), $post_meta_key, true);
	}
	
	/**
	 * Print meta
	 * 
	 * @param unknown $id
	 * @param unknown $meta_key
	 * @param string $order
	 * @return Ambigous <mixed, string, multitype:, boolean, unknown, string>
	 */
	public static function print_meta( $id, $meta_key, $order = null ) {
		echo self::get_meta( $id, $meta_key, $order );
	}
}