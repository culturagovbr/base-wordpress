<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Register the Latest Tweets Shortcode
 *
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * efcb_latest_tweets Shortcode function.
 *
 *
 * @package Event Framework
 * @since 1.0.0
 */
function efcb_latest_tweets($atts, $content) {
    global $twitter;

    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['title']) ? $atts['subtitle'] : '';
    $hashtag = isset($atts['hashtag']) ? $atts['hashtag'] : '';
    $tweetscount = isset($atts['tweets_count']) ? $atts['tweets_count'] : '9';

    $style_items = array(
        'section' => array(
            'background-color' => 'background_color',
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        ),
        'icon' => array(
            'color' => 'icon_font_color',
            'font-size' => 'icon_font_size',
        ),
        'title' => array(
            'color' => 'title_font_color',
            'font-size' => 'title_font_size',
        ),
        'subtitle' => array(
            'color' => 'subtitle_font_color',
            'font-size' => 'subtitle_font_size',
        ),
        'tweet' => array(
            'background-color' => 'tweet_background_color',
        ),
        'tweet_text' => array(
            'color' => 'tweet_text_font_color',
        ),
        'tweet_link' => array(
            'color' => 'tweet_link_font_color',
        ),
    );

    $tweets = array();

    if (isset($twitter) && !empty($hashtag)) {
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $getfield = "?q=#$hashtag&count=" . $tweetscount;
        $requestMethod = 'GET';
        $store = $twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest();
        $tweets = json_decode($store);
        do_action('set_ajax_params', $hashtag, $tweetscount);
    }

    echo apply_filters('efcb_shortcode_render', '', 'efcb_twitter', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'tweets' => $tweets,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),));
}

// Register Shortcode
add_shortcode('efcb-section-twitter', 'efcb_latest_tweets');

