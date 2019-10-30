<?php
/**
 * Portal Padrão WP Theme Customizer
 *
 * @package Portal_Padrão_WP
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function pp_wp_customize_register( $wp_customize ) {

	// $wp_customize->remove_control('blogdescription');
	$wp_customize->remove_section('static_front_page');
	$wp_customize->remove_section('WpBarraBrasil');

	$wp_customize->add_section( 'header_image' , array(
		'title'      => 'Cabeçalho',
	) );

	$wp_customize->add_setting( 'blogdenomination', array(
		'default'     => get_option( 'blogdenomination' ),
		'transport' => 'postMessage',
		'type'       => 'option',
		'capability'    => 'edit_theme_options',
	) );

	$wp_customize->add_control( 'blogdenomination', array(
		'label'      => 'Denominação do órgão',
		'type' 		=> 'text',
		'section'    => 'title_tagline',
		'settings' => 'blogdenomination'
	) );

	$wp_customize->add_setting( 'color_palette', array(
		'default'     => get_option( 'color_palette' ) ? get_option( 'color_palette' ) : 'green',
		'transport' => 'postMessage',
		'type'       => 'option',
		'capability'    => 'edit_theme_options'
	) );

	$wp_customize->add_control( 'color_palette', array(
		'label'      => 'Paleta de cores',
		'type' 		=> 'select',
		'section'    => 'colors',
		'priority' => 0,
		'settings' => 'color_palette',
		'choices' => array(
			'yellow' => 'Amarelo',
			'blue' => 'Azul',
			'white' => 'Branco',
			'green' => 'Verde',
		),
	) );

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdenomination' )->transport = 'postMessage';
	$wp_customize->get_setting( 'color_palette' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'pp_wp_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'pp_wp_customize_partial_blogdescription',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdenomination', array(
			'selector'        => '.denomination',
			'render_callback' => 'pp_wp_customize_partial_blogdenomination',
		) );
		/*$wp_customize->selective_refresh->add_partial( 'color_palette', array(
			'selector'        => 'body',
			'render_callback' => 'pp_wp_customize_partial_color_palette',
		) );*/
	}
}
add_action( 'customize_register', 'pp_wp_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function pp_wp_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function pp_wp_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Render the site denomination for the selective refresh partial.
 *
 * @return void
 */
function pp_wp_customize_partial_blogdenomination() {
	echo get_option( 'blogdenomination' );
}

/**
 * Set the theme color palette
 *
 * @return void
 */
function pp_wp_customize_partial_color_palette($data) {
	die( var_dump ($data) );
	// echo get_option( 'pp_theme_options_option_name' )['color_palette'];
	$options = get_option( 'color_palette' );
	$options['color_palette'] = $data;
	update_option( 'pp_theme_options_option_name', $data );

}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pp_wp_customize_preview_js() {
	wp_enqueue_script( 'pp-wp-customizer', get_template_directory_uri() . '/assets/js/dist/customizer.js', array( 'jquery', 'customize-preview' ), false, true );
}
add_action( 'customize_preview_init', 'pp_wp_customize_preview_js' );
