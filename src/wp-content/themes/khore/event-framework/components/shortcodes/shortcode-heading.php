<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_heading($atts, $content) {
    $text = isset($atts['text']) ? $atts['text'] : '';
    if (empty($atts['size'])) {
        $atts['size'] = 'small';
    }

    if (isset($atts['size']) && strlen($atts['size'])) {
        switch ($atts['size']) {
            case 'large':
                $size = '2';
                break;
            case 'medium':
                $size = '3';
                break;
            case 'small':
                $size = '4';
                break;
        }
    }

    $style_items = array(
        'section' => array(
            'text-align' => 'text_alignment',
            'color' => 'text_font_color',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        )
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_heading', array(
        'title' => $text,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
        'size' => $size));
}

// Register Shortcode
add_shortcode('efcb-section-heading', 'efcb_heading');

