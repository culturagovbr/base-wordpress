<?php
/*
Plugin Name: List Events Shortcode
Plugin URI:
Description: Lista eventos da API do mapas culturais
Author: midiaLab
Version: 1.0
Text Domain:
*/

class ListEventsShortcode {
    public $url;

    function ListEventsShortcode() {
        add_shortcode('list_events', array(&$this, 'shortcode'));
        add_action( 'wp_enqueue_scripts', array(&$this, 'addScripts') );
        add_action('wp_ajax_nopriv_list_events_shortcode', array(&$this, 'ajaxJS'));
        add_action('wp_ajax_list_events_shortcode', array(&$this, 'ajaxJS'));
    }

    function addScripts() {
        wp_enqueue_style('bootst', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css');
        wp_enqueue_style('list-events-css-daterangepicker', plugin_dir_url( __FILE__ ) . '/css/daterangepicker.css');
        wp_enqueue_style('list-events-shortcode', plugin_dir_url( __FILE__ ) . '/css/list-events.css' );

        wp_enqueue_script('jquery');
        wp_enqueue_script('moment', plugin_dir_url( __FILE__ ) . '/js/moment.min.js');
        wp_enqueue_script('list-events-js-daterangepicker', plugin_dir_url( __FILE__ ) . '/js/daterangepicker.js');
        wp_enqueue_script('listevents-ajax-script', plugin_dir_url( __FILE__ ) . '/js/app.js', array('jquery'));
    }


    function shortcode($atts, $content) {
        if (!is_array($atts) || !isset($atts['url']))
        return;

        $params = [
            '@files'     => '(header.header,avatar.avatarBig):url',
            '@select'    => 'name,shortDescription,occurrences.rule,occurrences.space.name,occurrences.space.endereco,occurrences.space.En_Municipio,occurrences.space.En_Estado&space',
            'space:type' => 'BET(20,29)'
        ];

        $url = add_query_arg($params, $atts['url'] . '/api/event/findByLocation');
        $dataRange = 30;
        if (isset($atts['date_range'])) {
            $dataRange   = $atts['date_range'];
        }

        ob_start();
        include('template.php');
        $html = ob_get_clean();
        return $html;
    }
}

add_action('init', function() {
    $ListEventsShortcode = new ListEventsShortcode;
});
