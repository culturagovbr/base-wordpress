<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_event_timer($atts, $content) {
    $ef_options = EF_Event_Options::get_theme_options();
    $datetime = isset($ef_options['ef_countdown']) ? $ef_options['ef_countdown'] : '';

    $style_items = array(
        'section' => array(
            'background-color' => 'background_color',
            'color' => 'text_font_color',
            'font-size' => 'text_font_size',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        ),
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_event_timer', array(
        'date' => $datetime,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
    ));
}

// Register Shortcode
add_shortcode('efcb-section-event_timer', 'efcb_event_timer');
