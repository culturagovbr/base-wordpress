<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Catálogo de Softwares
 * Plugin URI:        http://example.com/software-catalog-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       software-catalog
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// require plugin_dir_path( __FILE__ ) . 'inc/class-software-catalog.php';
require plugin_dir_path( __FILE__ ) . 'inc/software-catalog-shortcode.php';
require plugin_dir_path( __FILE__ ) . 'inc/options-page.php';

add_action( 'wp_enqueue_scripts', 'define_public_scripts' );
function define_public_scripts() {
	global $post;
	$post_to_check = get_post(get_the_ID());

	if ( stripos($post_to_check->post_content, '[software-catalog') === false ) {
		return;
	}

	$software_catalog_options = get_option('software_catalog_options');
	if( !$software_catalog_options['disable_default_css'] ){
		wp_enqueue_style( 'software-catalog-styles', plugin_dir_url( __FILE__ ) . 'assets/css/software-catalog-main.css' );
	}

	wp_enqueue_script( 'software-catalog-scripts', plugin_dir_url( __FILE__ ) . 'assets/js/software-catalog-main.js', array('jquery'), false, true );
	
	$organizations = explode(',', $software_catalog_options['organizations']);
	$organizations = array_map('trim', $organizations);
	$team = $software_catalog_options['team'];

	wp_localize_script( 'software-catalog-scripts', 'softwareCatalogJS', array(
	    'organizations' => $organizations,
	    'team' => $team
	));

}

add_action( 'wp_head', 'sc_add_custom_css' );
function sc_add_custom_css() {
	$software_catalog_options = get_option('software_catalog_options');
	$custom_css = $software_catalog_options['add_custom_css'];
    echo '<style type="text/css" rel="stylesheet" id="software-catalog-custom-styles">'. $custom_css .'</style>';
}