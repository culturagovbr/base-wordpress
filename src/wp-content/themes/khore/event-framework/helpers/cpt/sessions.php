<?php

class EF_Session_Helper {

    private static $field_types = array(
        'home'     => 'session_home',
        'date'     => 'session_date',
        'time'     => 'session_time',
        'end_time' => 'session_end_time',
        'speakers' => 'session_speakers_list',
        'code'     => 'session_registration_code',
        'text'     => 'session_registration_text',
    );

    public static function get_meta($id, $meta_key, $order = null) {
        echo self::print_meta($id, $meta_key, $order);
    }

    public static function print_meta($id, $meta_key) {
        $post_meta_key = $meta_key;

        if (isset(self::$field_types[$meta_key])) {
            $post_meta_key = self::$field_types[$meta_key];
        }

        if (!is_null($order)) {
            $post_meta_key .= $order;
        }

        return get_post_meta(get_the_ID(), $post_meta_key, true);
    }

    public static function get_sessions_loop() {
        $session_loop = new WP_Query(
                array(
            'post_type'  => 'session',
            'nopaging'   => true,
            'meta_query' => array(
                array(
                    'key'     => 'session_date',
                    'compare' => 'EXISTS',
                ),
                array(
                    'key'     => 'session_time',
                    'compare' => 'EXISTS',
                ),
            ),
            'meta_key'   => 'session_date',
            'orderby'    => 'meta_value',
            'order'      => 'DESC'
                )
        );

        return $session_loop;
    }

    public static function ef_sessions_posts_fields($sql) {
        global $wpdb;

        return $sql . ", $wpdb->postmeta.meta_value as time, mt1.meta_value as date";
    }

    public static function ef_sessions_posts_orderby($sql) {
        global $wpdb;

        return "mt1.meta_value ASC, $wpdb->postmeta.meta_value ASC";
    }

    public static function ef_get_session_dates() {
        global $wpdb;

        $metas = $wpdb->get_results(
                "SELECT DISTINCT meta_value
				FROM $wpdb->postmeta
				INNER JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID
				WHERE
				$wpdb->posts.post_type = 'session' AND
				$wpdb->posts.post_status = 'publish' AND
				$wpdb->postmeta.meta_key = 'session_date' AND
				$wpdb->postmeta.meta_value != ''
				ORDER BY meta_value ASC");

        return $metas;
    }

    public static function ef_ajax_get_schedule() {
        $ret = array(
            'sessions' => array(),
            'strings'  => array(
                'more_info' => __('More info', 'dxef')
            )
        );

        $timestamp      = !empty($_POST['data-timestamp']) ? $_POST['data-timestamp'] : 0;
        $location       = !empty($_POST['data-location']) && ctype_digit($_POST['data-location']) ? intval($_POST['data-location']) : '0';
        $track          = !empty($_POST['data-track']) && ctype_digit($_POST['data-track']) ? intval($_POST['data-track']) : '0';
        $wp_time_format = get_option("time_format");

        add_filter('posts_fields', array('EF_Session_Helper', 'ef_sessions_posts_fields'));
        add_filter('posts_orderby', array('EF_Session_Helper', 'ef_sessions_posts_orderby'));

        $session_loop_args = array(
            'post_type'   => 'session',
            'post_status' => 'publish',
            'nopaging'    => true,
            'meta_query'  => array(
                array(
                    'key'     => 'session_time',
                    'compare' => 'EXISTS',
                ),
                array(
                    'key'     => 'session_date',
                    'compare' => 'EXISTS',
                )
            ),
            'tax_query'   => array(),
            //'meta_key' => 'session_date',
            'orderby'     => 'meta_value',
            'order'       => 'ASC'
        );

        if ($timestamp > 0)
            $session_loop_args['meta_query'][] = array(
                'key'   => 'session_date',
                'value' => $timestamp
            );
        if ($location > 0)
            $session_loop_args['tax_query'][]  = array(
                'taxonomy' => 'session-location',
                'field'    => 'id',
                'terms'    => $location
            );
        if ($track > 0)
            $session_loop_args['tax_query'][]  = array(
                'taxonomy' => 'session-track',
                'field'    => 'id',
                'terms'    => $track
            );
        $sessions_loop                     = new WP_Query($session_loop_args);

        remove_filter('posts_fields', array('EF_Session_Helper', 'ef_sessions_posts_fields'));
        remove_filter('posts_orderby', array('EF_Session_Helper', 'ef_sessions_posts_orderby'));

        while ($sessions_loop->have_posts()) {
            $sessions_loop->the_post();
            global $post;

            $time = $post->time;
            if (!empty($time)) {
                $time_parts = explode(':', $time);
                if (count($time_parts) == 2)
                    $time       = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
            }

            $end_time = $post->session_end_time;
            if (!empty($end_time)) {
                $time_parts = explode(':', $end_time);
                if (count($time_parts) == 2)
                    $end_time   = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
            }

            $locations = wp_get_post_terms(get_the_ID(), 'session-location');
            if ($locations && count($locations) > 0)
                $location  = $locations[0];
            $tracks    = wp_get_post_terms(get_the_ID(), 'session-track');
            if ($tracks && count($tracks) > 0) {
                foreach ($tracks as $track)
                    $track->color = EF_Taxonomy_Helper::ef_get_term_meta('session-track-metas', $track->term_id, 'session_track_color');
                $track        = $tracks[0]->term_id;
            }
            $speakers_list = get_post_meta(get_the_ID(), 'session_speakers_list', true);
            $speakers      = array();
            if ($speakers_list && count($speakers_list) > 0) {
                foreach ($speakers_list as $speaker_id)
                    $speakers[] = array(
                        'post_title' => get_the_title($speaker_id),
                        'featured'   => get_post_meta($speaker_id, 'speaker_keynote', true),
                        'url'        => get_permalink($speaker_id),
                        'post_image' => catch_that_image(get_the_post_thumbnail($speaker_id, apply_filters('ef_schedule_speakers_thumbnail_size', 'khore-speaker'), array('alt' => get_the_title($speaker_id), 'class' => apply_filters('ef_schedule_speakers_thumbnail_class', ''))))
                    );
            }

            $session_date = get_post_meta(get_the_ID(), 'session_date', true);

            if (empty($session_date)) {
                // If session date is empty, get the Post publish time
                $session_date = get_the_date(get_option(' date_format'), $post->ID);
            } else {
                // else get the session_date
                $session_date = date_i18n(get_option('date_format'), $session_date);
            }

            array_push($ret['sessions'], array(
                'post_title'   => get_the_title(),
                'post_excerpt' => get_the_excerpt(),
                'url'          => get_permalink(get_the_ID()),
                'time'         => $time,
                'id'           => get_the_ID(),
                'end_time'     => $end_time,
                'date'         => $session_date,
                'location'     => $location ? $location->name : '',
                'color'        => $track ? EF_Taxonomy_Helper::ef_get_term_meta('session-track-metas', $track, 'session_track_color') : '',
                'tracks'       => $tracks,
                'speakers'     => $speakers,
                'thumbnail'    => catch_that_image(get_the_post_thumbnail(get_the_ID(), apply_filters('ef_schedule_sessions_thumbnail_size', 'thumbnail')))
            ));
        }

        echo json_encode($ret);
        die;
    }

}
