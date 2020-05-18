<?php

if ( ! function_exists( 'happyforms_parse_pixel_value' ) ):
/**
 * Sanitize checkbox values.
 *
 * @since 1.0
 *
 * @param int|string $value The original value.
 *
 * @return int|string       1 if value was 1, or empty string.
 */
function happyforms_parse_pixel_value( $value ) {
	return is_numeric( $value ) ? "{$value}px" : $value;
}

endif;

if ( ! function_exists( 'happyforms_get_frontend_stylesheet_url' ) ):

	function happyforms_get_frontend_stylesheet_url( $stylesheet_name = '' ) {
		if ( empty( $stylesheet_name ) ) {
			return;
		}

		$stylesheets_url = happyforms_get_plugin_url() . '/core/assets/css';
		$stylesheets_url = apply_filters( 'happyforms_frontend_stylesheets_url', $stylesheets_url );
		$style_suffix = ( defined( 'HAPPYFORMS_UPGRADE_VERSION' ) ) ? HAPPYFORMS_UPGRADE_VERSION : HAPPYFORMS_VERSION;

		$style_url = "{$stylesheets_url}/{$stylesheet_name}?ver={$style_suffix}";

		return $style_url;
	}

endif;
