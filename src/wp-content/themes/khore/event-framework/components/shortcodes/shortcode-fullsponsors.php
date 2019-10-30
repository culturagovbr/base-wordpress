<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_fullsponsors($atts, $content) {
    $title = isset($atts['title']) ? $atts['title'] : '';
    $description = isset($atts['description']) ? $atts['description'] : '';
    $type = isset($atts['type']) ? $atts['type'] : '';
    $viewbuttontext = isset($atts['view_button_text']) ? $atts['view_button_text'] : '';
    $entities = isset($atts['entities']) ? explode(',', $atts['entities']) : array();
    
    $sponsors = array();

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
        'tier' => array(
            'color' => 'tier_font_color',
            'background-color' => 'tier_background_color',
            'font-size' => 'tier_font_size',
        ),
        'tier_description' => array(
            'color' => 'tier_description_font_color',
            'font-size' => 'tier_description_font_size',
        ),
        'sponsor' => array(
            'background-color' => 'sponsor_background_color',
        ),
        'sponsor_title' => array(
            'color' => 'sponsor_title_font_color',
            'font-size' => 'sponsor_title_font_size',
        ),
        'sponsor_description' => array(
            'color' => 'sponsor_description_font_color',
            'font-size' => 'sponsor_description_font_size',
        ),
        'sponsor_detail_button' => array(
            'color' => 'view_button_font_color',
            'font-size' => 'view_button_font_size',
        ),
    );

    if (!empty($entities)) {
        foreach ($entities as $entityID) {
            $sponsors[] = get_post($entityID);
        }
    }
    echo apply_filters('efcb_shortcode_render', '', 'efcb_fullsponsors', array(
        'title' => $title,
        'description' => $description,
        'type' => $type,
        'view_button_text' => $viewbuttontext,
        'sponsors' => $sponsors,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts)
    ));
}

// Register Shortcode
add_shortcode('efcb-section-fullsponsors', 'efcb_fullsponsors');
