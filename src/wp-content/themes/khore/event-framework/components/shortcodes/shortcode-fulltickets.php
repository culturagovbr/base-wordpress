<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Register the tickets Widget
 *
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Tickets_Widget Widget Class.
 *
 *
 * @package Event Framework
 * @since 1.0.0
 */
function efcb_fulltickets($atts, $content) {
    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $entities = isset($atts['entities']) ? $atts['entities'] : '';

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
        'ticket' => array(
            'background-color' => 'ticket_background_color',
        ),
        'ticket_title' => array(
            'color' => 'ticket_title_font_color',
            'font-size' => 'ticket_title_font_size',
        ),
        'ticket_price_box' => array(
            'color' => 'ticket_price_font_color',
            'font-size' => 'ticket_price_font_size',
            'background-color' => 'ticket_pricebox_background_color',
        ),
        'ticket_text' => array(
            'color' => 'ticket_text_font_color',
            'font-size' => 'ticket_text_font_size',
        ),
        'ticket_button' => array(
            'background-color' => 'ticket_button_background_color',
            'color' => 'ticket_button_font_color',
            'font-size' => 'ticket_button_font_size',
        ),
    );
    if (!empty($entities)) {
        $tickets = get_posts(array(
            'post_type' => 'ticket',
            'posts_per_page' => -1,
            'orderby' => 'post__in',
            'post__in' => explode(',', $entities),
            'order' => 'ASC'
        ));
    } else {
        $tickets = array();
    }
    echo apply_filters('efcb_shortcode_render', '', 'efcb_fulltickets', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
        'tickets' => $tickets));
}

// Register Shortcode
add_shortcode('efcb-section-fulltickets', 'efcb_fulltickets');
