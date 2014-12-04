<?php

/**
 * Transfer theme data into new settings
 */
function siteorigin_panels_transfer_home_page(){

	if(get_option('siteorigin_panels_home_page', false) === false && get_theme_mod('panels_home_page', false) !== false) {
		// Transfer settings from theme mods into settings
		update_option( 'siteorigin_panels_home_page', get_theme_mod( 'panels_home_page', false ) );
		update_option( 'siteorigin_panels_home_page_enabled', get_theme_mod( 'panels_home_page_enabled', false ) );

		// Remove the theme mod data
		remove_theme_mod( 'panels_home_page' );
		remove_theme_mod( 'panels_home_page_enabled' );
	}

	// Transfer the home page setting to a page
	if( !get_option('siteorigin_panels_home_page_id') && get_option('siteorigin_panels_home_page') && siteorigin_panels_setting( 'home-page' ) ) {
		// Lets create a new page
		$page_id = wp_insert_post( array(
			'post_title' => __('Home', 'siteorigin-panels'),
			'post_status' => get_option('siteorigin_panels_home_page_enabled') ? 'publish' : 'draft',
			'post_type' => 'page',
			'comment_status' => 'closed',
		) );

		update_post_meta( $page_id, 'panels_data', get_option('siteorigin_panels_home_page') );
		update_post_meta( $page_id, '_wp_page_template', siteorigin_panels_setting('home-template') );
		update_option( 'siteorigin_panels_home_page_id', $page_id );

		if( get_option('siteorigin_panels_home_page_enabled') ) {
			// Lets make this page the home page
			update_option('show_on_front', 'page');
			update_option('page_on_front', $page_id);
		}
	}

}
add_action('admin_init', 'siteorigin_panels_transfer_home_page');