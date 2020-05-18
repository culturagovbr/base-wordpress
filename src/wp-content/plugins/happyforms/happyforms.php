<?php

/**
 * Plugin Name: HappyForms
 * Plugin URI:  https://happyforms.me
 * Description: Your friendly drag and drop contact form builder for creating contact forms, lead generation forms, feedback forms, quote forms, survey forms and more!
 * Author:      HappyForms
 * Version:     1.9.10
 * Author URI:  https://happyforms.me
 * Upgrade URI: https://happyforms.me/upgrade
 */

/**
 * The current version of the plugin.
 */
define( 'HAPPYFORMS_VERSION', '1.9.10' );

if ( ! function_exists( 'happyforms_plugin_file' ) ):
/**
 * Get the absolute path to the plugin file.
 *
 * @return string Absolute path to the plugin file.
 */
function happyforms_plugin_file() {
	return __FILE__;
}

endif;

if ( ! function_exists( 'happyforms_plugin_name' ) ):
/**
 * Get the plugin basename.
 *
 * @return string The plugin basename.
 */
function happyforms_plugin_name() {
	return plugin_basename( __FILE__ );
}

endif;

if ( ! function_exists( 'happyforms_get_plugin_url' ) ):
/**
 * Get the plugin url.
 *
 * @return string The url of the plugin.
 */
function happyforms_get_plugin_url() {
	return plugin_dir_url( __FILE__ );
}

endif;

if ( ! function_exists( 'happyforms_get_plugin_path' ) ):
/**
 * Get the absolute path of the plugin folder.
 *
 * @return string The absolute path of the plugin folder.
 */
function happyforms_get_plugin_path() {
	return plugin_dir_path( __FILE__ );
}

endif;

if ( ! function_exists( 'happyforms_get_include_folder' ) ):
/**
 * Get the path of the PHP include folder.
 *
 * @return string The path of the PHP include folder.
 */
function happyforms_get_include_folder() {
	return dirname( __FILE__ ) . '/inc';
}

endif;

if ( ! function_exists( 'happyforms_get_core_folder' ) ):

function happyforms_get_core_folder() {
	return dirname( __FILE__ ) . '/core';
}

endif;

/**
 * Activate
 */
require_once( happyforms_get_core_folder() . '/helpers/helper-activation.php' );

/**
 * Core
 */
require_once( happyforms_get_core_folder() . '/classes/class-happyforms-core.php' );

require_once( happyforms_get_include_folder() . '/classes/class-happyforms.php' );

/**
 * Helpers
 */
require_once( happyforms_get_include_folder() . '/helpers/helper-activation.php' );

/**
 * Main handler
 */
if ( ! function_exists( 'HappyForms' ) ):
/**
 * Get the global HappyForms class.
 *
 * @return HappyForms
 */
function HappyForms() {
	global $happyforms;

	if ( is_null( $happyforms ) ) {
		$happyforms = new HappyForms();
	}

	return $happyforms;
}

endif;

/**
 * Start general admin and frontend hooks.
 */
add_action( 'plugins_loaded', array( HappyForms(), 'initialize_plugin' ) );

/**
 * Start Customize screen specific hooks.
 */
add_filter( 'customize_loaded_components', array( HappyForms(), 'initialize_customize_screen' ) );
