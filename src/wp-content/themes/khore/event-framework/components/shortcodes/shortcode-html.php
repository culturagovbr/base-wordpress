<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_html($atts, $content) {
    $content = EF_Framework_Helper::get_text_between_two_tags($content, '[content]', '[/content]');
    
    $style_items = array(
        'section' => array(
            'text-align' => 'text_alignment',
            'line-height' => 'line-spacing',
            'color' => 'text_font_color',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        )
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_html', array(
        'content' => $content,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
    ));
}

// Register Shortcode
add_shortcode('efcb-section-html', 'efcb_html');

