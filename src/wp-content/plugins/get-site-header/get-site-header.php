<?php
/**
 * Plugin Name:       Get Site Header
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       Retorna o cabeçalho do site para ser usado de forma dinamica em outros locais
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/darciro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'GetSiteHeader' ) ) :

	class GetSiteHeader {

		public function __construct() {
            add_action( 'wp_ajax_nopriv_get_header', array($this, 'get_header'));
            add_action( 'wp_ajax_get_header', array($this, 'get_header'));
            add_action( 'wp_ajax_nopriv_get_footer', array($this, 'get_footer'));
            add_action( 'wp_ajax_get_footer', array($this, 'get_footer'));
		}

		public function get_header () {
            get_header();
            die;
		}

        public function get_footer () {
            wp_head();
            get_footer();
            die;
        }

    }

	// Initialize our plugin
	$gewp = new GetSiteHeader();

endif;