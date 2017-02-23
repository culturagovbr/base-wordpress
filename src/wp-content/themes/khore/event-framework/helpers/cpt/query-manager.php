<?php

/**
 * Manages queries for fetching custom post type data 
 * 
 * @author nofearinc
 *
 */
class EF_Query_Manager {
	public static function get_single_session_fields() {
		global $post; 
		
		$tracks = wp_get_post_terms(get_the_ID(), 'session-track', array('fields' => 'all'));
		foreach ($tracks as &$track)
			$track->color = EF_Taxonomy_Helper::ef_get_term_meta('session-track-metas', $track->term_id, 'session_track_color');
		unset($track);
		$locations = wp_get_post_terms(get_the_ID(), 'session-location', array('fields' => 'all'));
		$date = get_post_meta(get_the_ID(), 'session_date', true);
		$time = get_post_meta(get_the_ID(), 'session_time', true);
		$end_time = get_post_meta(get_the_ID(), 'session_end_time', true);
		if (!empty($time)) {
			$time_parts = explode(':', $time);
			if (count($time_parts) == 2)
				$time = date(get_option("time_format"), mktime($time_parts[0], $time_parts[1], 0));
		}
		if (!empty($end_time)) {
			$time_parts = explode(':', $end_time);
			if (count($time_parts) == 2)
				$end_time = date(get_option("time_format"), mktime($time_parts[0], $time_parts[1], 0));
		}
		$speakers_list = get_post_meta(get_the_ID(), 'session_speakers_list', true);
		$registration_title = get_post_meta(get_the_ID(), 'session_registration_title', true);
		$registration_code = get_post_meta(get_the_ID(), 'session_registration_code', true);
		
		return compact( 'tracks', 'locations', 'date', 'time', 'end_time', 'speakers_list', 'registration_title', 'registration_code' );
	}
}