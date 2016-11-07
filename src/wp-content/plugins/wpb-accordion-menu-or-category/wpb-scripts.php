<?php

/*
	WPB Menu And Category Accordion
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/* ==========================================================================
   Adding Latest jQuery
   ========================================================================== */

function wpb_wmca_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'wpb_wmca_jquery');	


/* ==========================================================================
   include js files
   ========================================================================== */

function wpb_wmca_adding_scripts() {
	if ( !is_admin() ) {
		wp_enqueue_script('wpb_wmca_jquery_cookie', plugins_url('assets/js/jquery.cookie.js', __FILE__),'','1.4.1', true);
		wp_enqueue_script('wpb_wmca_accordion_script', plugins_url('assets/js/jquery.navgoco.min.js', __FILE__),'','1.0', true);
	}
}
add_action( 'wp_enqueue_scripts', 'wpb_wmca_adding_scripts' ); 


/* ==========================================================================
   include css files
   ========================================================================== */

function wpb_wmca_adding_style() {
	if ( !is_admin() ) {
		wp_enqueue_style( 'wpb_wmca_accordion_style', plugins_url('assets/css/wpb_wmca_style.css', __FILE__),'','1.0' );
	}
}
add_action( 'wp_enqueue_scripts', 'wpb_wmca_adding_style',11 );