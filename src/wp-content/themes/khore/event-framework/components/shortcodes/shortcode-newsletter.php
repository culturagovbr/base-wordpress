<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Register the Newsletter shortcodes
 *
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * efcb_newsletter shortcode.
 *
 *
 * @package Event Framework
 * @since 1.0.0
 */
function efcb_newsletter($atts, $content) {
    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $textboxtext = isset($atts['textbox_text']) ? $atts['textbox_text'] : '';
    $buttontext = isset($atts['button_text']) ? $atts['button_text'] : '';
    $mailchimpactionurl = isset($atts['mailchimp_action_url']) ? $atts['mailchimp_action_url'] : '';

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
        'button' => array(
            'color' => 'button_font_color',
            'font-size' => 'button_font_size',
        ),
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_newsletter', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'textbox_text' => $textboxtext,
        'button_text' => $buttontext,
        'mailchimp_action_url' => $mailchimpactionurl,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
    ));
}

//Register Shortcode
add_shortcode('efcb-section-newsletter', 'efcb_newsletter');
