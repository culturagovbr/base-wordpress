<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_fullschedule($atts, $content) {
    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $viewbuttontext = isset($atts['view_button_text']) ? $atts['view_button_text'] : '';

    $style_items = array(
        'section' => array(
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
            'background-color' => 'background_color',
        ),
        'title' => array(
            'color' => 'title_font_color',
            'font-size' => 'title_font_size'
        ),
        'subtitle' => array(
            'color' => 'subtitle_font_color',
            'font-size' => 'subtitle_font_size'
        ),
        'session' => array(
            'background-color' => 'session_background_color',
        ),
        'day_bar' => array(
            'color' => 'day_bar_font_color',
            'background-color' => 'day_bar_background_color',
            'font-size' => 'day_bar_font_size'
        ),
        'session_title' => array(
            'color' => 'session_title_font_color',
            'font-size' => 'session_title_font_size'
        ),
        'session_time' => array(
            'color' => 'session_time_font_color',
        ),
        'session_location' => array(
            'color' => 'session_location_font_color',
            'font-size' => 'session_location_font_size'
        ),
        'session_button' => array(
            'color' => 'session_button_font_color',
            'font-size' => 'session_button_font_size'
        ),
    );

    $dates = EF_Session_Helper::ef_get_session_dates();
    $tracks = get_terms('session-track');
    $locations = get_terms('session-location');

    echo apply_filters('efcb_shortcode_render', '', 'efcb_fullschedule', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'view_button_text' => $viewbuttontext,
        'dates' => $dates,
        'tracks' => $tracks,
        'locations' => $locations,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
        'atts' => $atts
    ));
}

function efcb_home_schedule_where($where) {
    return $where . ' AND menu_order > 0';
}

// Register Shortcode
add_shortcode('efcb-section-fullschedule', 'efcb_fullschedule');
