<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * efcb_instagram Shortcode function.
 *
 *
 * @package Event Framework
 * @since 1.0.0
 */
function efcb_instagram($atts, $content) {
    global $instagram;

    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $tag = isset($atts['tag']) ? $atts['tag'] : '';
    $photoscount = isset($atts['photos_count']) ? $atts['photos_count'] : '';

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

    if (!empty($instagram) && !empty($tag)) {
        $photos = $instagram->getTagMedia($tag, $photoscount);
    } else
        $photos = array();

    do_action('set_ajax_params', $tag, $photoscount);

    echo apply_filters('efcb_shortcode_render', '', 'efcb_instagram', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
        'photos' => $photos));
}

// Register Shortcode

add_shortcode('efcb-section-instagram', 'efcb_instagram');



