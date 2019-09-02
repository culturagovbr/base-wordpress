<?php

class EF_Taxonomy_Helper {
	
	public static function ef_get_term_meta( $field_set, $term_id, $field_id ) {
        $meta = get_option( $field_set );
        if ( empty( $meta ) )
            $meta = array();
        if ( ! is_array( $meta ) )
            $meta = (array) $meta;
        $meta = isset( $meta[$term_id] ) ? $meta[$term_id] : array();
        $value = isset ( $meta[$field_id] ) ? $meta[$field_id] : '';

        return $value;
    }

}