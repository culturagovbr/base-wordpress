<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_headline($atts) {
    $text = isset($atts['text']) ? $atts['text'] : '';

    $style_items = array(
        'section' => array(
            'text-align' => 'text_alignment',
            'color' => 'text_font_color',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        )
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_headline', array(
        'text' => $text,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts)
    ));
}

// Register Shortcode
add_shortcode('efcb-section-headline', 'efcb_headline');

