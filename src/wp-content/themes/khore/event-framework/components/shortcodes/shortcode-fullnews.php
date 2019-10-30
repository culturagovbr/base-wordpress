<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_fullnews($atts, $content) {
    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $view_button_text = isset($atts['view_button_text']) ? $atts['view_button_text'] : '';
    $category_ids = isset($atts['categories']) ? explode(',', $atts['categories']) : '';
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
        'news_title' => array(
            'color' => 'news_title_font_color',
            'font-size' => 'news_title_font_size',
        ),
        'news_subtitle' => array(
            'color' => 'news_subtitle_font_color',
            'font-size' => 'news_subtitle_font_size',
        ),
        'news_detail_button' => array(
            'color' => 'news_detail_button_font_color',
            'font-size' => 'news_detail_button_font_size',
        ),
        'speaker_pagination_active' => array(
            'color' => 'speaker_pagination_active_font_color',
        ),
        'speaker_pagination_disabled' => array(
            'color' => 'speaker_pagination_disabled_font_color',
        ),
    );
    $additional_styles = array(
        'search_form' => array(
            'color' => !empty($atts['search_form_font_color']) ? $atts['search_form_font_color'] : '',
        ),
    );

    if (is_category() || is_archive()) {
        global $wp_query;
        $news = $wp_query;
    } else {
        if (!empty($entities)) {
            $paged = !empty($_REQUEST['paged']) ? absint($_REQUEST['paged']) : 1;
            $text = !empty($_REQUEST['text']) ? $_REQUEST['text'] : '';
            $news_args = array(
                'post_type' => 'post',
//                'posts_per_page' => 6,
                'paged' => $paged,
                'orderby' => 'post__in',
                'post__in' => explode(',', $entities),
                's' => $text
            );
            $news = new WP_Query($news_args);
        } else {
            $news = array();
        }
    }
    echo apply_filters('efcb_shortcode_render', '', 'efcb_fullnews', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'view_button_text' => $view_button_text,
        'news' => !empty($news) ? $news->posts : array(),
        'total' => !empty($news) ? $news->max_num_pages : 0,
        'paged' => !empty($news) ? $paged : 0,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
        'additional_styles' => $additional_styles
    ));
}

// Register Shortcode
add_shortcode('efcb-section-fullnews', 'efcb_fullnews');
