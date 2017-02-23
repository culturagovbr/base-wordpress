<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_media($atts, $content) {
    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $medias = isset($atts['entities']) ? explode(',', $atts['entities']) : array();

    $style_items = array(
        'section' => array(
            'background-color' => 'background_color',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        ),
        'icon' => array(
            'color' => 'icon_font_color',
        ),
        'title' => array(
            'color' => 'title_font_color',
            'font-size' => 'title_font_size',
        ),
        'subtitle' => array(
            'color' => 'subtitle_font_color',
            'font-size' => 'subtitle_font_size',
        ),
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_media_grid', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'medias' => $medias,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts)
    ));
}

// Register Shortcode
add_shortcode('efcb-section-media', 'efcb_media');
