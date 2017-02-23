<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_event_gallery($atts, $content) {
    echo apply_filters('efcb_shortcode_render', '', 'efcb_event_gallery', array());
}

// Register Shortcode
add_shortcode('efcb-section-event-gallery', 'efcb_event_gallery');