<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_samplepage($atts, $content) {
    $hero_image = isset($atts['hero_image']) ? $atts['hero_image'] : '';
    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $content = EF_Framework_Helper::get_text_between_two_tags($content, '[content]', '[/content]');


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
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_samplepage', array(
        'hero_image' => $hero_image,
        'title' => $title,
        'subtitle' => $subtitle,
        'content' => $content,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts)
    ));
}

// Register Shortcode
add_shortcode('efcb-section-samplepage', 'efcb_samplepage');

