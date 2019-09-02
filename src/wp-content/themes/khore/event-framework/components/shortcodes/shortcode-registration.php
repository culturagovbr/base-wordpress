<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;
;

function efcb_registration($atts, $content) {
    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $text = EF_Framework_Helper::get_text_between_two_tags($content, '[text]', '[/text]');
    $embedcode = EF_Framework_Helper::get_text_between_two_tags($content, '[embed_code]', '[/embed_code]');

    $style_items = array(
        'section' => array(
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
            'background-color' => 'background_color',
        ),
        'title' => array(
            'color' => 'title_font_color',
            'font-size' => 'title_font_size',
        ),
        'subtitle' => array(
            'color' => 'subtitle_font_color',
            'font-size' => 'subtitle_font_size',
        ),
        'text' => array(
            'color' => 'text_font_color',
            'font-size' => 'text_font_size',
        ),
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_registration', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'text' => $text,
        'embed_code' => $embedcode,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
    ));
}

// Register Shortcode
add_shortcode('efcb-section-registration', 'efcb_registration');