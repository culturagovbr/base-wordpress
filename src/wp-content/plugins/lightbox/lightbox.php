<?php
/*
Plugin Name: Huge IT Lightbox
Plugin URI: https://huge-it.com/lightbox
Description: Lightbox is the perfect tool for viewing photos.
Version: 2.1.0
Author: Huge-IT
Author URI: https://huge-it.com
License: GPL
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'admin_menu', 'hugeit_lightbox_options_panel' );
register_activation_hook( __FILE__, 'hugeit_lightbox_activate' );
define( 'HUGEIT_LIGHTBOX_PLUGIN_DIR', WP_PLUGIN_DIR . "/" . plugin_basename( dirname( __FILE__ ) ) );

function hugeit_lightbox_options_panel() {
	add_menu_page( 'Theme page title', 'Huge IT Lightbox', 'manage_options', 'huge_it_light_box', 'hugeit_lightbox', plugins_url( 'images/huge_it_lightboxLogoHover-for_menu.png', __FILE__ ) );
	$page_option      = add_submenu_page( 'huge_it_light_box', 'Lightbox', 'Lightbox', 'manage_options', 'huge_it_light_box', 'hugeit_lightbox' );
	$featured_plugins = add_submenu_page( 'huge_it_light_box', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'huge_it_featured_plugins', 'hugeit_featured_plugins' );
	$licensing        = add_submenu_page( 'huge_it_light_box', 'Licensing', 'Licensing', 'manage_options', 'huge_it_licensing', 'huge_it_licensing' );
	add_action( 'admin_print_styles-' . $page_option, 'hugeit_lightbox_option_admin_script' );
	add_action( 'admin_print_styles-' . $featured_plugins, 'hugeit_lightbox_featured_scripts_styles' );
	add_action( 'admin_print_styles-' . $licensing, 'hugeit_lightbox_licensing_scripts_styles' );

}


function hugeit_lightbox_option_admin_script() {
	wp_enqueue_media();
	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( "simple_slider_js", plugins_url( "js/admin/simple-slider.js", __FILE__ ), false );
	wp_enqueue_style( "simple_slider_css", plugins_url( "css/admin/simple-slider.css", __FILE__ ), false );

	wp_enqueue_style( "admin_css", plugins_url( "css/admin/admin.style.css", __FILE__ ), false );
	wp_enqueue_script( "admin_js", plugins_url( "js/admin/admin.js", __FILE__ ), false );
	wp_enqueue_script( 'param_block2', plugins_url( "js/admin/jscolor/jscolor.js", __FILE__ ) );
}

function hugeit_lightbox_featured_scripts_styles() {
	wp_register_style( "hugeit-lightbox-featured", plugins_url( "css/admin/featured.css", __FILE__ ), false );
	wp_enqueue_style( "hugeit-lightbox-featured" );
}

function hugeit_lightbox_licensing_scripts_styles() {
	wp_register_style( "hugeit-lightbox-licensing", plugins_url( "css/admin/licensing.css", __FILE__ ), false );
	wp_enqueue_style( "hugeit-lightbox-licensing" );
}

function hugeit_lightbox() {
	include_once( "admin/controller/huge_it_light_box.php" );
	$controller = new Hugeit_Lightbox_Controller();
	$controller->invoke();
}

function hugeit_featured_plugins() {
	include_once( HUGEIT_LIGHTBOX_PLUGIN_DIR . "/admin/view/huge_it_featured_plugins.php" );
}

function huge_it_licensing() {
	include_once( HUGEIT_LIGHTBOX_PLUGIN_DIR . "/admin/view/huge_it_licensing.php" );
}

function hugeit_lightbox_activate() {
	include_once( HUGEIT_LIGHTBOX_PLUGIN_DIR . "/admin/model/huge_it_light_box.php" );
	include_once( HUGEIT_LIGHTBOX_PLUGIN_DIR . "/admin/model/install_base.php" );
}


add_action( 'init', 'hugeit_lightbox_enqueue_scripts_and_styles' );
function hugeit_lightbox_enqueue_scripts_and_styles() {
	if ( ! is_admin() ) {
		if ( get_option( 'hugeit_lightbox_type' ) == 'old_type' ) {
			wp_register_style( 'hugeit-colorbox-css', plugins_url( '/css/frontend/colorbox-' . get_option( 'hugeit_lightbox_style' ) . '.css', __FILE__ ) );
			wp_enqueue_style( 'hugeit-colorbox-css' );
			wp_register_script( 'hugeit-colorbox-js', plugins_url( '/js/frontend/jquery.colorbox.js', __FILE__ ), array( 'jquery' ), '1.0', 'true' );
			wp_enqueue_script( 'hugeit-colorbox-js' );
		} elseif ( get_option( 'hugeit_lightbox_type' ) == 'new_type' ) {
			wp_register_style( 'hugeit-lightbox-css', plugins_url( '/css/frontend/lightbox.css', __FILE__ ) );
			wp_enqueue_style( 'hugeit-lightbox-css' );
			wp_register_script( 'mousewheel-min-js', plugins_url( '/js/frontend/mousewheel.min.js', __FILE__ ), array( 'jquery' ), '1.0', 'true' );
			wp_enqueue_script( 'mousewheel-min-js' );
			wp_register_script( 'hugeit-froogaloop-js', plugins_url( '/js/frontend/froogaloop2.min.js', __FILE__ ) );
			wp_enqueue_script( 'hugeit-froogaloop-js' );
			wp_register_script( 'hugeit-lightbox-js', plugins_url( '/js/frontend/lightbox.js', __FILE__ ), array( 'jquery' ), '1.0', 'true' );
			wp_enqueue_script( 'hugeit-lightbox-js' );
		}
		wp_register_script( 'hugeit-custom-js', plugins_url( '/js/frontend/custom.js', __FILE__ ), array( 'jquery' ), '1.0', 'true' );
		wp_enqueue_script( 'hugeit-custom-js' );
	}

}

add_action( 'init', 'hugeit_lightbox_localize_scripts' );
function hugeit_lightbox_localize_scripts() {
	include_once( HUGEIT_LIGHTBOX_PLUGIN_DIR . "/admin/model/huge_it_light_box.php" );
	$model = new Hugeit_Lightbox_Model();
	if ( ! is_admin() ) {
		if ( get_option( 'hugeit_lightbox_type' ) == 'old_type' ) {
			$lightbox_options                                    = $model->lightbox_get_option();
			$lightbox_default_options                            = $model->default_options();
			$lightbox_default_options['hugeit_lightbox_opacity'] = ( $lightbox_default_options['hugeit_lightbox_opacity'] / 100 ) + 0.001;
			if ( $lightbox_default_options['hugeit_lightbox_size_fix'] == 'false' ) {
				$lightbox_default_options['hugeit_lightbox_width'] = '';
			} else {
				$lightbox_default_options['hugeit_lightbox_width'] = $lightbox_default_options['hugeit_lightbox_width'];
			}
			if ( $lightbox_default_options['hugeit_lightbox_size_fix'] == 'false' ) {
				$lightbox_default_options['hugeit_lightbox_height'] = '';
			} else {
				$lightbox_default_options['hugeit_lightbox_height'] = $lightbox_default_options['hugeit_lightbox_height'];
			}
			$pos = $lightbox_default_options['hugeit_lightbox_title_position'];
			switch ( $pos ) {
				case 1:
					$lightbox_default_options['lightbox_top']    = '10%';
					$lightbox_default_options['lightbox_bottom'] = 'false';
					$lightbox_default_options['lightbox_left']   = '10%';
					$lightbox_default_options['lightbox_right']  = 'false';
					break;
				case 2:
					$lightbox_default_options['lightbox_top']    = '10%';
					$lightbox_default_options['lightbox_bottom'] = 'false';
					$lightbox_default_options['lightbox_left']   = 'false';
					$lightbox_default_options['lightbox_right']  = 'false';
					break;
				case 3:
					$lightbox_default_options['lightbox_top']    = '10%';
					$lightbox_default_options['lightbox_bottom'] = 'false';
					$lightbox_default_options['lightbox_left']   = 'false';
					$lightbox_default_options['lightbox_right']  = '10%';
					break;
				case 4:
					$lightbox_default_options['lightbox_top']    = 'false';
					$lightbox_default_options['lightbox_bottom'] = 'false';
					$lightbox_default_options['lightbox_left']   = '10%';
					$lightbox_default_options['lightbox_right']  = 'false';
					break;
				case 5:
					$lightbox_default_options['lightbox_top']    = 'false';
					$lightbox_default_options['lightbox_bottom'] = 'false';
					$lightbox_default_options['lightbox_left']   = 'false';
					$lightbox_default_options['lightbox_right']  = 'false';
					break;
				case 6:
					$lightbox_default_options['lightbox_top']    = 'false';
					$lightbox_default_options['lightbox_bottom'] = 'false';
					$lightbox_default_options['lightbox_left']   = 'false';
					$lightbox_default_options['lightbox_right']  = '10%';
					break;
				case 7:
					$lightbox_default_options['lightbox_top']    = 'false';
					$lightbox_default_options['lightbox_bottom'] = '10%';
					$lightbox_default_options['lightbox_left']   = '10%';
					$lightbox_default_options['lightbox_right']  = 'false';
					break;
				case 8:
					$lightbox_default_options['lightbox_top']    = 'false';
					$lightbox_default_options['lightbox_bottom'] = '10%';
					$lightbox_default_options['lightbox_left']   = 'false';
					$lightbox_default_options['lightbox_right']  = 'false';
					break;
				case 9:
					$lightbox_default_options['lightbox_top']    = 'false';
					$lightbox_default_options['lightbox_bottom'] = '10%';
					$lightbox_default_options['lightbox_left']   = 'false';
					$lightbox_default_options['lightbox_right']  = '10%';
					break;
			}
			wp_localize_script( 'hugeit-colorbox-js', 'hugeit_lightbox_obj', $lightbox_default_options );
			wp_localize_script( 'hugeit-colorbox-js', 'hugeit_gen_lightbox_obj', $lightbox_options );
		} elseif ( get_option( 'hugeit_lightbox_type' ) == 'new_type' ) {
			$lightbox_resp_options = $model->lightbox_get_resp_option();
			$lightbox_resp_default_options = $model->default_resp_options();
			list( $r, $g, $b ) = array_map( 'hexdec', str_split( $lightbox_resp_default_options['hugeit_lightbox_watermark_containerBackground'], 2 ) );
			$titleopacity                                                          = $lightbox_resp_default_options["hugeit_lightbox_watermark_containerOpacity"] / 100;
			$lightbox_resp_default_options['hugeit_lightbox_watermark_container_bg_color'] = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $titleopacity . ')';
			wp_localize_script( 'hugeit-lightbox-js', 'hugeit_resp_lightbox_obj', $lightbox_resp_options );
			wp_localize_script( 'hugeit-lightbox-js', 'hugeit_gen_resp_lightbox_obj', $lightbox_resp_default_options );
			wp_localize_script( 'hugeit-lightbox-js', 'hugeit_resp_lightbox_plugins_url', plugins_url('/images/image_frames/', __FILE__) );
		}
		wp_localize_script( 'hugeit-custom-js', 'lightbox_type', get_option( 'hugeit_lightbox_type' ) );
		wp_localize_script( 'hugeit-custom-js', 'ajaxUrl', admin_url( "admin-ajax.php" ) );
	}
}

add_filter( 'wp_get_attachment_link', 'hugeit_lightbox_add_title_attachment_link', 10, 2 );
function hugeit_lightbox_add_title_attachment_link( $link, $id = null ) {
	$id         = intval( $id );
	$_post      = get_post( $id );
	$post_title = esc_attr( $_post->post_title );

	return str_replace( '<a href', '<a title="' . $post_title . '" href', $link );
}

function hugeit_lightbox_plugins_url() {
	return plugins_url( '', __FILE__ );
}

add_action('wp_ajax_lightbox_description', 'get_images_url');
add_action('wp_ajax_nopriv_lightbox_description', 'get_images_url');

function get_images_url(){
	global $wpdb;
	$image_urls = $_POST['urls'];
	$all_urls = array();
	foreach ($image_urls as $image_url) {
		$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s'", $image_url);
		$id = $wpdb->get_var($query);
		$attachment = get_post( $id );
		$description = $attachment->post_content;
		array_push($all_urls,$description);
	}
	echo json_encode($all_urls);
	die();
}