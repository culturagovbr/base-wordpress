<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_button($atts) {
    $title = isset($atts['text']) ? $atts['text'] : '';
    $url = isset($atts['url']) ? $atts['url'] : '';
    $element_id = 'efcb-button-' . rand(100, 1000);
    $style_items = array(
        'section' => array(
            'text-align' => 'text_alignment',
            'width' => 'width',
            'height' => 'height',
            'color' => 'text_font_color',
            'background-color' => 'background_color',
            'border-color' => 'border_color',
            'font-size' => 'text_font_size',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
            'background-color' => 'background_color',
        ),
    );
    $additional_style_items = array(
        'section' => array(
            'color' => !empty($atts['text_font_hover_color']) ? $atts['text_font_hover_color'] : '',
            'background-color' => !empty($atts['background_hover_color']) ? $atts['background_hover_color'] : ''
        )
    );
    echo apply_filters('efcb_shortcode_render', '', 'efcb_button', array(
        'title' => $title,
        'url' => $url,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
        'additional_styles' => $additional_style_items,
        'element_id' => $element_id));
}

// Register Shortcode
add_shortcode('efcb-section-button', 'efcb_button');
