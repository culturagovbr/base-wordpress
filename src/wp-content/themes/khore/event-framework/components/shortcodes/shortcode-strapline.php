<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_strapline($atts) {
    $text = isset($atts['text']) ? $atts['text'] : '';

    $style_items = array(
        'section' => array(
            'text-align' => 'text_alignment',
            'color' => 'text_font_color',
            'font-size' => 'text_font_size',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        )
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_strapline', array(
        'title' => $text,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
    ));
}

// Register Shortcode
add_shortcode('efcb-section-strapline', 'efcb_strapline');

