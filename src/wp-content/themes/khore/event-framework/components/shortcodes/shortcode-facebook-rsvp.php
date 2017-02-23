<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Register the Facebook Rsvp shortcodes
 *
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * efcb_facebook_rsvp Facebook shortcodes.
 *
 *
 * @package Event Framework
 * @since 1.0.0
 */
function efcb_facebook_rsvp($atts, $content) {
    global $facebook;

    $title         = isset($atts['title']) ? $atts['title'] : '';
    $subtitle      = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $eventid       = isset($atts['event_id']) ? $atts['event_id'] : '';
    $eventlink     = isset($atts['event_link']) ? $atts['event_link'] : '';
    $eventlinktext = isset($atts['event_link_text']) ? $atts['event_link_text'] : '';

    $style_items = array(
        'section'  => array(
            'margin-top'       => 'margin_top',
            'margin-bottom'    => 'margin_bottom',
            'background-color' => 'background_color',
        ),
        'title'    => array(
            'color'     => 'title_font_color',
            'font-size' => 'title_font_size',
        ),
        'subtitle' => array(
            'color'     => 'subtitle_font_color',
            'font-size' => 'subtitle_font_size',
        ),
        'icon'     => array(
            'color'     => 'icon_font_color',
            'font-size' => 'icon_font_size',
        ),
        'box'      => array(
            'color'            => 'box_font_color',
            'background-color' => 'box_background_color',
        ),
        'count'    => array(
            'color'     => 'count_font_color',
            'font-size' => 'count_font_size',
        ),
        'label'    => array(
            'color'     => 'label_font_color',
            'font-size' => 'label_font_size',
        ),
        'button'   => array(
            'color'     => 'button_font_color',
            'font-size' => 'button_font_size',
        ),
    );

    if (isset($facebook) && !empty($eventid)) {
        try {
            $attending = $facebook->api("/$eventid/attending?limit=0&summary=1");
            $maybe     = $facebook->api("/$eventid/maybe?limit=0&summary=1");
            $declined  = $facebook->api("/$eventid/declined?limit=0&summary=1");
        } catch (Exception $ex) {
            error_log("[{$ex->getType()}]: {$ex->getMessage()}");
        }
    }

    echo apply_filters('efcb_shortcode_render', '', 'efcb_facebook_rsvp', array(
        'title'           => $title,
        'subtitle'        => $subtitle,
        'event_link'      => $eventlink,
        'event_link_text' => $eventlinktext,
        'styles'          => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
        'rsvpattending'   => $attending,
        'rsvpmaybe'       => $maybe,
        'rsvpdeclined'    => $declined));
}

//Register Shortcode
add_shortcode('efcb-section-facebook', 'efcb_facebook_rsvp');
