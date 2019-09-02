<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_picture($atts) {
    $url = isset($atts['url']) ? $atts['url'] : '';
    $alt = isset($atts['alt']) ? $atts['alt'] : '';
    $width = isset($atts['width']) ? $atts['width'] : '';
    $height = isset($atts['height']) ? $atts['height'] : '';

    $style_items = array(
        'section' => array(
            'float' => 'alignment',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        )
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_picture', array(
        'url' => $url,
        'alt' => $alt,
        'width' => $width,
        'height' => $height,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts)
    ));
}

// Register Shortcode
add_shortcode('efcb-section-picture', 'efcb_picture');

