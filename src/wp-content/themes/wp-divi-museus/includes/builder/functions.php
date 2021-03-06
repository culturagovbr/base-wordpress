<?php

if ( ! defined( 'ET_BUILDER_VERSION' ) ) {
	define( 'ET_BUILDER_VERSION', 0.7 );
}

if ( ! defined( 'ET_BUILDER_FORCE_CACHE_PURGE' ) ) {
	define( 'ET_BUILDER_FORCE_CACHE_PURGE', false );
}

// exclude predefined layouts from import
function et_remove_predefined_layouts_from_import( $posts ) {
	$processed_posts = $posts;

	if ( isset( $posts ) && is_array( $posts ) ) {
		$processed_posts = array();

		foreach ( $posts as $post ) {
			if ( isset( $post['postmeta'] ) && is_array( $post['postmeta'] ) ) {
				foreach ( $post['postmeta'] as $meta ) {
					if ( '_et_pb_predefined_layout' === $meta['key'] && 'on' === $meta['value'] )
						continue 2;
				}
			}

			$processed_posts[] = $post;
		}
	}

	return $processed_posts;
}
add_filter( 'wp_import_posts', 'et_remove_predefined_layouts_from_import', 5 );

// set the layout_type taxonomy to "layout" for layouts imported from old version of Divi.
function et_update_old_layouts_taxonomy( $posts ) {
	$processed_posts = $posts;

	if ( isset( $posts ) && is_array( $posts ) ) {
		$processed_posts = array();

		foreach ( $posts as $post ) {
			$update_built_for_post_type = false;

			if ( 'et_pb_layout' === $post['post_type'] ) {
				if ( ! isset( $post['terms'] ) ) {
					$post['terms'][] = array(
						'name'   => 'layout',
						'slug'   => 'layout',
						'domain' => 'layout_type'
					);
					$post['terms'][] = array(
						'name'   => 'not_global',
						'slug'   => 'not_global',
						'domain' => 'scope'
					);
				}

				$update_built_for_post_type = true;

				// check whether _et_pb_built_for_post_type custom field exists
				if ( ! empty( $post['postmeta'] ) ) {
					foreach ( $post['postmeta'] as $index => $value ) {
						if ( '_et_pb_built_for_post_type' === $value['key'] ) {
							$update_built_for_post_type = false;
						}
					}
				}
			}

			// set _et_pb_built_for_post_type value to 'page' if not exists
			if ( $update_built_for_post_type ) {
				$post['postmeta'][] = array(
					'key'   => '_et_pb_built_for_post_type',
					'value' => 'page',
				);
			}

			$processed_posts[] = $post;
		}
	}

	return $processed_posts;
}
add_filter( 'wp_import_posts', 'et_update_old_layouts_taxonomy', 10 );

// add custom filters for posts in the Divi Library
if ( ! function_exists( 'et_pb_add_layout_filters' ) ) :
function et_pb_add_layout_filters() {
	if ( isset( $_GET['post_type'] ) && 'et_pb_layout' === $_GET['post_type'] ) {
		$layout_categories = get_terms( 'layout_category' );
		$filter_category = array();
		$filter_category[''] = esc_html__( 'All Categories', 'et_builder' );

		if ( is_array( $layout_categories ) && ! empty( $layout_categories ) ) {
			foreach( $layout_categories as $category ) {
				$filter_category[$category->slug] = $category->name;
			}
		}

		$filter_layout_type = array(
			''        => esc_html__( 'All Layouts', 'et_builder' ),
			'module'  => esc_html__( 'Modules', 'et_builder' ),
			'row'     => esc_html__( 'Rows', 'et_builder' ),
			'section' => esc_html__( 'Sections', 'et_builder' ),
			'layout'  => esc_html__( 'Layouts', 'et_builder' ),
		);

		$filter_scope = array(
			''           => esc_html__( 'Global/not Global', 'et_builder' ),
			'global'     => esc_html__( 'Global', 'et_builder' ),
			'not_global' => esc_html__( 'not Global', 'et_builder' )
		);
		?>

		<select name="layout_type">
		<?php
			$selected = isset( $_GET['layout_type'] ) ? $_GET['layout_type'] : '';
			foreach ( $filter_layout_type as $value => $label ) {
				printf( '<option value="%1$s"%2$s>%3$s</option>',
					esc_attr( $value ),
					$value == $selected ? ' selected="selected"' : '',
					esc_html( $label )
				);
			} ?>
		</select>

		<select name="scope">
		<?php
			$selected = isset( $_GET['scope'] ) ? $_GET['scope'] : '';
			foreach ( $filter_scope as $value => $label ) {
				printf( '<option value="%1$s"%2$s>%3$s</option>',
					esc_attr( $value ),
					$value == $selected ? ' selected="selected"' : '',
					esc_html( $label )
				);
			} ?>
		</select>

		<select name="layout_category">
		<?php
			$selected = isset( $_GET['layout_category'] ) ? $_GET['layout_category'] : '';
			foreach ( $filter_category as $value => $label ) {
				printf( '<option value="%1$s"%2$s>%3$s</option>',
					esc_attr( $value ),
					$value == $selected ? ' selected="selected"' : '',
					esc_html( $label )
				);
			} ?>
		</select>
	<?php
	}
}
endif;
add_action( 'restrict_manage_posts', 'et_pb_add_layout_filters' );

// Add "Export Divi Layouts" button to the Divi Library page
if ( ! function_exists( 'et_pb_load_export_section' ) ) :
function et_pb_load_export_section(){
	$current_screen = get_current_screen();

	if ( 'edit-et_pb_layout' === $current_screen->id ) {
		// display wp error screen if library is disabled for current user
		if ( ! et_pb_is_allowed( 'divi_library' ) || ! et_pb_is_allowed( 'add_library' ) || ! et_pb_is_allowed( 'save_library' ) ) {
			wp_die( esc_html__( "you don't have sufficient permissions to access this page", 'et_builder' ) );
		}

		add_action( 'all_admin_notices', 'et_pb_export_layouts_interface' );
	}
}
endif;
add_action( 'load-edit.php', 'et_pb_load_export_section' );

// Check whether the library editor page should be displayed or not
function et_pb_check_library_permissions(){
	$current_screen = get_current_screen();

	if ( 'et_pb_layout' === $current_screen->id && ( ! et_pb_is_allowed( 'divi_library' ) || ! et_pb_is_allowed( 'save_library' ) ) ) {
		// display wp error screen if library is disabled for current user
		wp_die( esc_html__( "you don't have sufficient permissions to access this page", 'et_builder' ) );
	}
}
add_action( 'load-post.php', 'et_pb_check_library_permissions' );

// exclude premade layouts from the list of all templates in the library.
if ( ! function_exists( 'exclude_premade_layouts_library' ) ) :
function exclude_premade_layouts_library( $query ) {
	global $pagenow;
	$current_post_type = get_query_var( 'post_type' );

	if ( is_admin() && 'edit.php' === $pagenow && $current_post_type && 'et_pb_layout' === $current_post_type ) {
		$meta_query = array(
			array(
				'key'     => '_et_pb_predefined_layout',
				'value'   => 'on',
				'compare' => 'NOT EXISTS',
			),
		);

		$used_built_for_post_types = et_pb_get_used_built_for_post_types();
		if ( isset( $_GET['built_for'] ) && count( $used_built_for_post_types ) > 1 ) {
			$built_for_post_type = sanitize_text_field( $_GET['built_for'] );
			// get array of all standard post types if built_for is one of them
			$built_for_post_type_processed = in_array( $built_for_post_type, et_pb_get_standard_post_types() ) ? et_pb_get_standard_post_types() : $built_for_post_type;

			if ( in_array( $built_for_post_type, $used_built_for_post_types ) ) {
				$meta_query[] = array(
					'key'     => '_et_pb_built_for_post_type',
					'value'   => $built_for_post_type_processed,
					'compare' => 'IN',
				);
			}
		}

		$query->set( 'meta_query', $meta_query );
	}

	return $query;
}
endif;
add_action( 'pre_get_posts', 'exclude_premade_layouts_library' );

if ( ! function_exists( 'et_pb_is_pagebuilder_used' ) ) :
function et_pb_is_pagebuilder_used( $page_id ) {
	return ( 'on' === get_post_meta( $page_id, '_et_pb_use_builder', true ) );
}
endif;

if ( ! function_exists( 'et_pb_get_font_icon_symbols' ) ) :
function et_pb_get_font_icon_symbols() {
	$symbols = array( '&amp;#x21;', '&amp;#x22;', '&amp;#x23;', '&amp;#x24;', '&amp;#x25;', '&amp;#x26;', '&amp;#x27;', '&amp;#x28;', '&amp;#x29;', '&amp;#x2a;', '&amp;#x2b;', '&amp;#x2c;', '&amp;#x2d;', '&amp;#x2e;', '&amp;#x2f;', '&amp;#x30;', '&amp;#x31;', '&amp;#x32;', '&amp;#x33;', '&amp;#x34;', '&amp;#x35;', '&amp;#x36;', '&amp;#x37;', '&amp;#x38;', '&amp;#x39;', '&amp;#x3a;', '&amp;#x3b;', '&amp;#x3c;', '&amp;#x3d;', '&amp;#x3e;', '&amp;#x3f;', '&amp;#x40;', '&amp;#x41;', '&amp;#x42;', '&amp;#x43;', '&amp;#x44;', '&amp;#x45;', '&amp;#x46;', '&amp;#x47;', '&amp;#x48;', '&amp;#x49;', '&amp;#x4a;', '&amp;#x4b;', '&amp;#x4c;', '&amp;#x4d;', '&amp;#x4e;', '&amp;#x4f;', '&amp;#x50;', '&amp;#x51;', '&amp;#x52;', '&amp;#x53;', '&amp;#x54;', '&amp;#x55;', '&amp;#x56;', '&amp;#x57;', '&amp;#x58;', '&amp;#x59;', '&amp;#x5a;', '&amp;#x5b;', '&amp;#x5c;', '&amp;#x5d;', '&amp;#x5e;', '&amp;#x5f;', '&amp;#x60;', '&amp;#x61;', '&amp;#x62;', '&amp;#x63;', '&amp;#x64;', '&amp;#x65;', '&amp;#x66;', '&amp;#x67;', '&amp;#x68;', '&amp;#x69;', '&amp;#x6a;', '&amp;#x6b;', '&amp;#x6c;', '&amp;#x6d;', '&amp;#x6e;', '&amp;#x6f;', '&amp;#x70;', '&amp;#x71;', '&amp;#x72;', '&amp;#x73;', '&amp;#x74;', '&amp;#x75;', '&amp;#x76;', '&amp;#x77;', '&amp;#x78;', '&amp;#x79;', '&amp;#x7a;', '&amp;#x7b;', '&amp;#x7c;', '&amp;#x7d;', '&amp;#x7e;', '&amp;#xe000;', '&amp;#xe001;', '&amp;#xe002;', '&amp;#xe003;', '&amp;#xe004;', '&amp;#xe005;', '&amp;#xe006;', '&amp;#xe007;', '&amp;#xe009;', '&amp;#xe00a;', '&amp;#xe00b;', '&amp;#xe00c;', '&amp;#xe00d;', '&amp;#xe00e;', '&amp;#xe00f;', '&amp;#xe010;', '&amp;#xe011;', '&amp;#xe012;', '&amp;#xe013;', '&amp;#xe014;', '&amp;#xe015;', '&amp;#xe016;', '&amp;#xe017;', '&amp;#xe018;', '&amp;#xe019;', '&amp;#xe01a;', '&amp;#xe01b;', '&amp;#xe01c;', '&amp;#xe01d;', '&amp;#xe01e;', '&amp;#xe01f;', '&amp;#xe020;', '&amp;#xe021;', '&amp;#xe022;', '&amp;#xe023;', '&amp;#xe024;', '&amp;#xe025;', '&amp;#xe026;', '&amp;#xe027;', '&amp;#xe028;', '&amp;#xe029;', '&amp;#xe02a;', '&amp;#xe02b;', '&amp;#xe02c;', '&amp;#xe02d;', '&amp;#xe02e;', '&amp;#xe02f;', '&amp;#xe030;', '&amp;#xe103;', '&amp;#xe0ee;', '&amp;#xe0ef;', '&amp;#xe0e8;', '&amp;#xe0ea;', '&amp;#xe101;', '&amp;#xe107;', '&amp;#xe108;', '&amp;#xe102;', '&amp;#xe106;', '&amp;#xe0eb;', '&amp;#xe010;', '&amp;#xe105;', '&amp;#xe0ed;', '&amp;#xe100;', '&amp;#xe104;', '&amp;#xe0e9;', '&amp;#xe109;', '&amp;#xe0ec;', '&amp;#xe0fe;', '&amp;#xe0f6;', '&amp;#xe0fb;', '&amp;#xe0e2;', '&amp;#xe0e3;', '&amp;#xe0f5;', '&amp;#xe0e1;', '&amp;#xe0ff;', '&amp;#xe031;', '&amp;#xe032;', '&amp;#xe033;', '&amp;#xe034;', '&amp;#xe035;', '&amp;#xe036;', '&amp;#xe037;', '&amp;#xe038;', '&amp;#xe039;', '&amp;#xe03a;', '&amp;#xe03b;', '&amp;#xe03c;', '&amp;#xe03d;', '&amp;#xe03e;', '&amp;#xe03f;', '&amp;#xe040;', '&amp;#xe041;', '&amp;#xe042;', '&amp;#xe043;', '&amp;#xe044;', '&amp;#xe045;', '&amp;#xe046;', '&amp;#xe047;', '&amp;#xe048;', '&amp;#xe049;', '&amp;#xe04a;', '&amp;#xe04b;', '&amp;#xe04c;', '&amp;#xe04d;', '&amp;#xe04e;', '&amp;#xe04f;', '&amp;#xe050;', '&amp;#xe051;', '&amp;#xe052;', '&amp;#xe053;', '&amp;#xe054;', '&amp;#xe055;', '&amp;#xe056;', '&amp;#xe057;', '&amp;#xe058;', '&amp;#xe059;', '&amp;#xe05a;', '&amp;#xe05b;', '&amp;#xe05c;', '&amp;#xe05d;', '&amp;#xe05e;', '&amp;#xe05f;', '&amp;#xe060;', '&amp;#xe061;', '&amp;#xe062;', '&amp;#xe063;', '&amp;#xe064;', '&amp;#xe065;', '&amp;#xe066;', '&amp;#xe067;', '&amp;#xe068;', '&amp;#xe069;', '&amp;#xe06a;', '&amp;#xe06b;', '&amp;#xe06c;', '&amp;#xe06d;', '&amp;#xe06e;', '&amp;#xe06f;', '&amp;#xe070;', '&amp;#xe071;', '&amp;#xe072;', '&amp;#xe073;', '&amp;#xe074;', '&amp;#xe075;', '&amp;#xe076;', '&amp;#xe077;', '&amp;#xe078;', '&amp;#xe079;', '&amp;#xe07a;', '&amp;#xe07b;', '&amp;#xe07c;', '&amp;#xe07d;', '&amp;#xe07e;', '&amp;#xe07f;', '&amp;#xe080;', '&amp;#xe081;', '&amp;#xe082;', '&amp;#xe083;', '&amp;#xe084;', '&amp;#xe085;', '&amp;#xe086;', '&amp;#xe087;', '&amp;#xe088;', '&amp;#xe089;', '&amp;#xe08a;', '&amp;#xe08b;', '&amp;#xe08c;', '&amp;#xe08d;', '&amp;#xe08e;', '&amp;#xe08f;', '&amp;#xe090;', '&amp;#xe091;', '&amp;#xe092;', '&amp;#xe0f8;', '&amp;#xe0fa;', '&amp;#xe0e7;', '&amp;#xe0fd;', '&amp;#xe0e4;', '&amp;#xe0e5;', '&amp;#xe0f7;', '&amp;#xe0e0;', '&amp;#xe0fc;', '&amp;#xe0f9;', '&amp;#xe0dd;', '&amp;#xe0f1;', '&amp;#xe0dc;', '&amp;#xe0f3;', '&amp;#xe0d8;', '&amp;#xe0db;', '&amp;#xe0f0;', '&amp;#xe0df;', '&amp;#xe0f2;', '&amp;#xe0f4;', '&amp;#xe0d9;', '&amp;#xe0da;', '&amp;#xe0de;', '&amp;#xe0e6;', '&amp;#xe093;', '&amp;#xe094;', '&amp;#xe095;', '&amp;#xe096;', '&amp;#xe097;', '&amp;#xe098;', '&amp;#xe099;', '&amp;#xe09a;', '&amp;#xe09b;', '&amp;#xe09c;', '&amp;#xe09d;', '&amp;#xe09e;', '&amp;#xe09f;', '&amp;#xe0a0;', '&amp;#xe0a1;', '&amp;#xe0a2;', '&amp;#xe0a3;', '&amp;#xe0a4;', '&amp;#xe0a5;', '&amp;#xe0a6;', '&amp;#xe0a7;', '&amp;#xe0a8;', '&amp;#xe0a9;', '&amp;#xe0aa;', '&amp;#xe0ab;', '&amp;#xe0ac;', '&amp;#xe0ad;', '&amp;#xe0ae;', '&amp;#xe0af;', '&amp;#xe0b0;', '&amp;#xe0b1;', '&amp;#xe0b2;', '&amp;#xe0b3;', '&amp;#xe0b4;', '&amp;#xe0b5;', '&amp;#xe0b6;', '&amp;#xe0b7;', '&amp;#xe0b8;', '&amp;#xe0b9;', '&amp;#xe0ba;', '&amp;#xe0bb;', '&amp;#xe0bc;', '&amp;#xe0bd;', '&amp;#xe0be;', '&amp;#xe0bf;', '&amp;#xe0c0;', '&amp;#xe0c1;', '&amp;#xe0c2;', '&amp;#xe0c3;', '&amp;#xe0c4;', '&amp;#xe0c5;', '&amp;#xe0c6;', '&amp;#xe0c7;', '&amp;#xe0c8;', '&amp;#xe0c9;', '&amp;#xe0ca;', '&amp;#xe0cb;', '&amp;#xe0cc;', '&amp;#xe0cd;', '&amp;#xe0ce;', '&amp;#xe0cf;', '&amp;#xe0d0;', '&amp;#xe0d1;', '&amp;#xe0d2;', '&amp;#xe0d3;', '&amp;#xe0d4;', '&amp;#xe0d5;', '&amp;#xe0d6;', '&amp;#xe0d7;', '&amp;#xe600;', '&amp;#xe601;', '&amp;#xe602;', '&amp;#xe603;', '&amp;#xe604;', '&amp;#xe605;', '&amp;#xe606;', '&amp;#xe607;', '&amp;#xe608;', '&amp;#xe609;', '&amp;#xe60a;', '&amp;#xe60b;', '&amp;#xe60c;', '&amp;#xe60d;', '&amp;#xe60e;', '&amp;#xe60f;', '&amp;#xe610;', '&amp;#xe611;', '&amp;#xe612;', '&amp;#xe008;', );

	$symbols = apply_filters( 'et_pb_font_icon_symbols', $symbols );

	return $symbols;
}
endif;

if ( ! function_exists( 'et_pb_get_font_icon_list' ) ) :
function et_pb_get_font_icon_list() {
	$output = is_customize_preview() ? et_pb_get_font_icon_list_items() : '<%= window.et_builder.font_icon_list_template() %>';

	$output = sprintf( '<ul class="et_font_icon">%1$s</ul>', $output );

	return $output;
}
endif;

if ( ! function_exists( 'et_pb_get_font_icon_list_items' ) ) :
function et_pb_get_font_icon_list_items() {
	$output = '';

	$symbols = et_pb_get_font_icon_symbols();

	foreach ( $symbols as $symbol ) {
		$output .= sprintf( '<li data-icon="%1$s"></li>', esc_attr( $symbol ) );
	}

	return $output;
}
endif;

if ( ! function_exists( 'et_pb_font_icon_list' ) ) :
function et_pb_font_icon_list() {
	echo et_pb_get_font_icon_list();
}
endif;

if ( ! function_exists( 'et_pb_get_font_down_icon_symbols' ) ) :
function et_pb_get_font_down_icon_symbols() {
	$symbols = array( '&amp;#x22;', '&amp;#x33;', '&amp;#x37;', '&amp;#x3b;', '&amp;#x3f;', '&amp;#x43;', '&amp;#x47;', '&amp;#xe03a;', '&amp;#xe044;', '&amp;#xe048;', '&amp;#xe04c;' );

	return $symbols;
}
endif;

if ( ! function_exists( 'et_pb_get_font_down_icon_list' ) ) :
function et_pb_get_font_down_icon_list() {
	$output = is_customize_preview() ? et_pb_get_font_down_icon_list_items() : '<%= window.et_builder.font_down_icon_list_template() %>';

	$output = sprintf( '<ul class="et_font_icon">%1$s</ul>', $output );

	return $output;
}
endif;

if ( ! function_exists( 'et_pb_get_font_down_icon_list_items' ) ) :
function et_pb_get_font_down_icon_list_items() {
	$output = '';

	$symbols = et_pb_get_font_down_icon_symbols();

	foreach ( $symbols as $symbol ) {
		$output .= sprintf( '<li data-icon="%1$s"></li>', esc_attr( $symbol ) );
	}

	return $output;
}
endif;

if ( ! function_exists( 'et_pb_font_down_icon_list' ) ) :
function et_pb_font_down_icon_list() {
	echo et_pb_get_font_down_icon_list();
}
endif;

/**
 * Processes font icon value for use on front-end
 *
 * @param string $font_icon        Font Icon ( exact value or in %%index_number%% format ).
 * @param string $symbols_function Optional. Name of the function that gets an array of font icon values.
 *                                 et_pb_get_font_icon_symbols function is used by default.
 * @return string $font_icon       Font Icon value
 */
if ( ! function_exists( 'et_pb_process_font_icon' ) ) :
function et_pb_process_font_icon( $font_icon, $symbols_function = 'default' ) {
	// the exact font icon value is saved
	if ( 1 !== preg_match( "/^%%/", trim( $font_icon ) ) ) {
		return $font_icon;
	}

	// the font icon value is saved in the following format: %%index_number%%
	$icon_index   = (int) str_replace( '%', '', $font_icon );
	$icon_symbols = 'default' === $symbols_function ? et_pb_get_font_icon_symbols() : call_user_func( $symbols_function );
	$font_icon    = isset( $icon_symbols[ $icon_index ] ) ? $icon_symbols[ $icon_index ] : '';

	return $font_icon;
}
endif;

if ( ! function_exists( 'et_builder_accent_color' ) ) :
function et_builder_accent_color( $default_color = '#7EBEC5' ) {
	$accent_color = ! et_is_builder_plugin_active() ? et_get_option( 'accent_color', $default_color ) : $default_color;

	return apply_filters( 'et_builder_accent_color', $accent_color );
}
endif;

if ( ! function_exists( 'et_builder_get_text_orientation_options' ) ) :
function et_builder_get_text_orientation_options() {
	$text_orientation_options = array(
		'left'      => esc_html__( 'Left', 'et_builder' ),
		'center'    => esc_html__( 'Center', 'et_builder' ),
		'right'     => esc_html__( 'Right', 'et_builder' ),
		'justified' => esc_html__( 'Justified', 'et_builder' ),
	);

	if ( is_rtl() ) {
		$text_orientation_options = array(
			'right'  => esc_html__( 'Right', 'et_builder' ),
			'center' => esc_html__( 'Center', 'et_builder' ),
		);
	}

	return apply_filters( 'et_builder_text_orientation_options', $text_orientation_options );
}
endif;

if ( ! function_exists( 'et_builder_get_gallery_settings' ) ) :
function et_builder_get_gallery_settings() {
	$output = sprintf(
		'<input type="button" class="button button-upload et-pb-gallery-button" value="%1$s" />',
		esc_attr__( 'Update Gallery', 'et_builder' )
	);

	return $output;
}
endif;

if ( ! function_exists( 'et_builder_get_nav_menus_options' ) ) :
function et_builder_get_nav_menus_options() {
	$nav_menus_options = array( 'none' => esc_html__( 'Select a menu', 'et_builder' ) );

	$nav_menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
	foreach ( (array) $nav_menus as $_nav_menu ) {
		$nav_menus_options[ $_nav_menu->term_id ] = $_nav_menu->name;
	}

	return apply_filters( 'et_builder_nav_menus_options', $nav_menus_options );
}
endif;

if ( ! function_exists( 'et_builder_generate_center_map_setting' ) ) :
function et_builder_generate_center_map_setting() {
	return '<div id="et_pb_map_center_map" class="et-pb-map et_pb_map_center_map"></div>';
}
endif;

if ( ! function_exists( 'et_builder_generate_pin_zoom_level_input' ) ) :
function et_builder_generate_pin_zoom_level_input() {
	return '<input class="et_pb_zoom_level" type="hidden" value="18" />';
}
endif;

if ( ! function_exists( 'et_builder_include_categories_option' ) ) :
function et_builder_include_categories_option( $args = array() ) {
	$defaults = apply_filters( 'et_builder_include_categories_defaults', array (
		'use_terms' => true,
		'term_name' => 'project_category',
	) );

	$args = wp_parse_args( $args, $defaults );

	$output = "\t" . "<% var et_pb_include_categories_temp = typeof et_pb_include_categories !== 'undefined' ? et_pb_include_categories.split( ',' ) : []; %>" . "\n";

	if ( $args['use_terms'] ) {
		$cats_array = get_terms( $args['term_name'] );
	} else {
		$cats_array = get_categories( apply_filters( 'et_builder_get_categories_args', 'hide_empty=0' ) );
	}

	if ( empty( $cats_array ) ) {
		$output = '<p>' . esc_html__( "You currently don't have any projects assigned to a category.", 'et_builder' ) . '</p>';
	}

	foreach ( $cats_array as $category ) {
		$contains = sprintf(
			'<%%= _.contains( et_pb_include_categories_temp, "%1$s" ) ? checked="checked" : "" %%>',
			esc_html( $category->term_id )
		);

		$output .= sprintf(
			'%4$s<label><input type="checkbox" name="et_pb_include_categories" value="%1$s"%3$s> %2$s</label><br/>',
			esc_attr( $category->term_id ),
			esc_html( $category->name ),
			$contains,
			"\n\t\t\t\t\t"
		);
	}

	$output = '<div id="et_pb_include_categories">' . $output . '</div>';

	return apply_filters( 'et_builder_include_categories_option_html', $output );
}
endif;

if ( ! function_exists( 'et_builder_include_categories_shop_option' ) ) :
function et_builder_include_categories_shop_option( $args = array() ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return '';
	}

	$defaults = apply_filters( 'et_builder_include_categories_shop_defaults', array (
		'use_terms' => true,
		'term_name' => 'product_category',
	) );

	$args = wp_parse_args( $args, $defaults );

	$output = "\t" . "<% var et_pb_include_categories_shop_temp = typeof et_pb_include_categories !== 'undefined' ? et_pb_include_categories.split( ',' ) : []; %>" . "\n";

	$cats_array = $args['use_terms'] ? get_terms( $args['term_name'] ) : get_categories( apply_filters( 'et_builder_get_categories_shop_args', 'hide_empty=0' ) );

	foreach ( $cats_array as $category ) {
		$contains = sprintf(
			'<%%= _.contains( et_pb_include_categories_shop_temp, "%1$s" ) ? checked="checked" : "" %%>',
			esc_html( $category->slug )
		);

		$output .= sprintf(
			'%4$s<label><input type="checkbox" name="et_pb_include_categories" value="%1$s"%3$s> %2$s</label><br/>',
			esc_attr( $category->slug ),
			esc_html( $category->name ),
			$contains,
			"\n\t\t\t\t\t"
		);
	}

	return apply_filters( 'et_builder_include_categories_option_html', $output );
}
endif;

if ( ! function_exists( 'et_divi_get_projects' ) ) :
function et_divi_get_projects( $args = array() ) {
	$default_args = array(
		'post_type' => 'project',
	);
	$args = wp_parse_args( $args, $default_args );
	return new WP_Query( $args );
}
endif;

if ( ! function_exists( 'et_pb_extract_items' ) ) :
function et_pb_extract_items( $content ) {
	$output = $first_character = '';
	$lines = explode( "\n", str_replace( array( '<p>', '</p>', '<br />' ), '', $content ) );
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '&#8211;' === substr( $line, 0, 7 ) ) {
			$line = '-' . substr( $line, 7 );
		}
		if ( '' === $line ) {
			continue;
		}
		$first_character = $line[0];
		if ( in_array( $first_character, array( '-', '+' ) ) ) {
			$line = trim( substr( $line, 1 ) );
		}
		$output .= sprintf( '[et_pb_pricing_item available="%2$s"]%1$s[/et_pb_pricing_item]',
			$line,
			( '-' === $first_character ? 'off' : 'on' )
		);
	}
	return do_shortcode( $output );
}
endif;

if ( ! function_exists( 'et_builder_process_range_value' ) ) :
function et_builder_process_range_value( $range, $option_type = '' ) {
	$range = trim( $range );
	$range_digit = floatval( $range );
	$range_string = str_replace( $range_digit, '', (string) $range );

	if ( '' === $range_string ) {
		$range_string = 'line_height' === $option_type && 3 >= $range_digit ? 'em' : 'px';
	}

	$result = $range_digit . $range_string;

	return apply_filters( 'et_builder_processed_range_value', $result, $range, $range_string );
}
endif;

if ( ! function_exists( 'et_builder_get_border_styles' ) ) :
function et_builder_get_border_styles() {
	$styles = array(
		'solid'  => esc_html__( 'Solid', 'et_builder' ),
		'dotted' => esc_html__( 'Dotted', 'et_builder' ),
		'dashed' => esc_html__( 'Dashed', 'et_builder' ),
		'double' => esc_html__( 'Double', 'et_builder' ),
		'groove' => esc_html__( 'Groove', 'et_builder' ),
		'ridge'  => esc_html__( 'Ridge', 'et_builder' ),
		'inset'  => esc_html__( 'Inset', 'et_builder' ),
		'outset' => esc_html__( 'Outset', 'et_builder' ),
	);

	return apply_filters( 'et_builder_border_styles', $styles );
}
endif;

if ( ! function_exists( 'et_builder_get_websafe_fonts' ) ) :
function et_builder_get_websafe_fonts() {
	$websafe_fonts = array(
		'Georgia' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'cyrillic,greek,latin',
			'type'			=> 'serif',
		),
		'Times New Roman' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'arabic,cyrillic,greek,hebrew,latin',
			'type'			=> 'serif',
		),
		'Arial' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'arabic,cyrillic,greek,hebrew,latin',
			'type'			=> 'sans-serif',
		),
		'Trebuchet' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'cyrillic,latin',
			'type'			=> 'sans-serif',
			'add_ms_version'=> true,
		),
		'Verdana' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'cyrillic,latin',
			'type'			=> 'sans-serif',
		),
	);

	$_websafe_fonts = array();

	foreach ( $websafe_fonts as $font_name => $settings ) {
		$settings['standard'] = true;

		$_websafe_fonts[ $font_name ] = $settings;
	}

	$websafe_fonts = $_websafe_fonts;

	return apply_filters( 'et_websafe_fonts', $websafe_fonts );
}
endif;

if ( ! function_exists( 'et_builder_get_google_fonts' ) ) :
function et_builder_get_google_fonts() {
	$google_fonts = array(
		'Abel' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'sans-serif',
		),
		'Amatic SC' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Arimo' => array(
			'styles' 		=> '400,400italic,700italic,700',
			'character_set' => 'latin,cyrillic-ext,latin-ext,greek-ext,cyrillic,greek,vietnamese',
			'type'			=> 'sans-serif',
		),
		'Arvo' => array(
			'styles' 		=> '400,400italic,700,700italic',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Bevan' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Bitter' => array(
			'styles' 		=> '400,400italic,700',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'serif',
		),
		'Black Ops One' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'cursive',
		),
		'Boogaloo' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Bree Serif' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'serif',
		),
		'Calligraffitti' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Cantata One' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'serif',
		),
		'Cardo' => array(
			'styles' 		=> '400,400italic,700',
			'character_set' => 'latin,greek-ext,greek,latin-ext',
			'type'			=> 'serif',
		),
		'Changa One' => array(
			'styles' 		=> '400,400italic',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Cherry Cream Soda' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Chewy' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Comfortaa' => array(
			'styles' 		=> '400,300,700',
			'character_set' => 'latin,cyrillic-ext,greek,latin-ext,cyrillic',
			'type'			=> 'cursive',
		),
		'Coming Soon' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Covered By Your Grace' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Crafty Girls' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Crete Round' => array(
			'styles' 		=> '400,400italic',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'serif',
		),
		'Crimson Text' => array(
			'styles' 		=> '400,400italic,600,600italic,700,700italic',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Cuprum' => array(
			'styles' 		=> '400,400italic,700italic,700',
			'character_set' => 'latin,latin-ext,cyrillic',
			'type'			=> 'sans-serif',
		),
		'Dancing Script' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Dosis' => array(
			'styles' 		=> '400,200,300,500,600,700,800',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'sans-serif',
		),
		'Droid Sans' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin',
			'type'			=> 'sans-serif',
		),
		'Droid Serif' => array(
			'styles' 		=> '400,400italic,700,700italic',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Francois One' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'sans-serif',
		),
		'Fredoka One' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'The Girl Next Door' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Gloria Hallelujah' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Happy Monkey' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'cursive',
		),
		'Indie Flower' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Josefin Slab' => array(
			'styles' 		=> '400,100,100italic,300,300italic,400italic,600,700,700italic,600italic',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Judson' => array(
			'styles' 		=> '400,400italic,700',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Kreon' => array(
			'styles' 		=> '400,300,700',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Lato' => array(
			'styles' 		=> '400,100,100italic,300,300italic,400italic,700,700italic,900,900italic',
			'character_set' => 'latin',
			'type'			=> 'sans-serif',
		),
		'Lato Light' => array(
			'parent_font' => 'Lato',
			'styles'      => '300',
		),
		'Leckerli One' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Lobster' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,cyrillic-ext,latin-ext,cyrillic',
			'type'			=> 'cursive',
		),
		'Lobster Two' => array(
			'styles' 		=> '400,400italic,700,700italic',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Lora' => array(
			'styles' 		=> '400,400italic,700,700italic',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Luckiest Guy' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Merriweather' => array(
			'styles' 		=> '400,300,900,700',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Metamorphous' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'cursive',
		),
		'Montserrat' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin',
			'type'			=> 'sans-serif',
		),
		'Noticia Text' => array(
			'styles' 		=> '400,400italic,700,700italic',
			'character_set' => 'latin,vietnamese,latin-ext',
			'type'			=> 'serif',
		),
		'Nova Square' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Nunito' => array(
			'styles' 		=> '400,300,700',
			'character_set' => 'latin',
			'type'			=> 'sans-serif',
		),
		'Old Standard TT' => array(
			'styles' 		=> '400,400italic,700',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Open Sans' => array(
			'styles' 		=> '300italic,400italic,600italic,700italic,800italic,400,300,600,700,800',
			'character_set' => 'latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic',
			'type'			=> 'sans-serif',
		),
		'Open Sans Condensed' => array(
			'styles' 		=> '300,300italic,700',
			'character_set' => 'latin,cyrillic-ext,latin-ext,greek-ext,greek,vietnamese,cyrillic',
			'type'			=> 'sans-serif',
		),
		'Open Sans Light' => array(
			'parent_font' => 'Open Sans',
			'styles'      => '300',
		),
		'Oswald' => array(
			'styles' 		=> '400,300,700',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'sans-serif',
		),
		'Pacifico' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Passion One' => array(
			'styles' 		=> '400,700,900',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'cursive',
		),
		'Patrick Hand' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,vietnamese,latin-ext',
			'type'			=> 'cursive',
		),
		'Permanent Marker' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Play' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin,cyrillic-ext,cyrillic,greek-ext,greek,latin-ext',
			'type'			=> 'sans-serif',
		),
		'Playfair Display' => array(
			'styles' 		=> '400,400italic,700,700italic,900italic,900',
			'character_set' => 'latin,latin-ext,cyrillic',
			'type'			=> 'serif',
		),
		'Poiret One' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,latin-ext,cyrillic',
			'type'			=> 'cursive',
		),
		'PT Sans' => array(
			'styles' 		=> '400,400italic,700,700italic',
			'character_set' => 'latin,latin-ext,cyrillic',
			'type'			=> 'sans-serif',
		),
		'PT Sans Narrow' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin,latin-ext,cyrillic',
			'type'			=> 'sans-serif',
		),
		'PT Serif' => array(
			'styles' 		=> '400,400italic,700,700italic',
			'character_set' => 'latin,cyrillic',
			'type'			=> 'serif',
		),
		'Raleway' => array(
			'styles' 		=> '400,100,200,300,600,500,700,800,900',
			'character_set' => 'latin',
			'type'			=> 'sans-serif',
		),
		'Raleway Light' => array(
			'parent_font' => 'Raleway',
			'styles'      => '300',
		),
		'Reenie Beanie' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Righteous' => array(
			'styles' 		=> '400',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'cursive',
		),
		'Roboto' => array(
			'styles' 		=> '400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic',
			'character_set' => 'latin,cyrillic-ext,latin-ext,cyrillic,greek-ext,greek,vietnamese',
			'type'			=> 'sans-serif',
		),
		'Roboto Condensed' => array(
			'styles' 		=> '400,300,300italic,400italic,700,700italic',
			'character_set' => 'latin,cyrillic-ext,latin-ext,greek-ext,cyrillic,greek,vietnamese',
			'type'			=> 'sans-serif',
		),
		'Roboto Light' => array(
			'parent_font' => 'Roboto',
			'styles'      => '100',
		),
		'Rock Salt' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Rokkitt' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Sanchez' => array(
			'styles' 		=> '400,400italic',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'serif',
		),
		'Satisfy' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Schoolbell' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Shadows Into Light' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Source Sans Pro' => array(
			'styles' 		=> '400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900,900italic',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'sans-serif',
		),
		'Source Sans Pro Light' => array(
			'parent_font' => 'Source Sans Pro',
			'styles'      => '300',
		),
		'Special Elite' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Squada One' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Tangerine' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Ubuntu' => array(
			'styles' 		=> '400,300,300italic,400italic,500,500italic,700,700italic',
			'character_set' => 'latin,cyrillic-ext,cyrillic,greek-ext,greek,latin-ext',
			'type'			=> 'sans-serif',
		),
		'Unkempt' => array(
			'styles' 		=> '400,700',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Vollkorn' => array(
			'styles' 		=> '400,400italic,700italic,700',
			'character_set' => 'latin',
			'type'			=> 'serif',
		),
		'Walter Turncoat' => array(
			'styles' 		=> '400',
			'character_set' => 'latin',
			'type'			=> 'cursive',
		),
		'Yanone Kaffeesatz' => array(
			'styles' 		=> '400,200,300,700',
			'character_set' => 'latin,latin-ext',
			'type'			=> 'sans-serif',
		),
	);

	return apply_filters( 'et_builder_google_fonts', $google_fonts );
}
endif;

if ( ! function_exists( 'et_builder_get_fonts' ) ) :
function et_builder_get_fonts( $settings = array() ) {
	$defaults = array(
		'prepend_standard_fonts' => true,
	);

	$settings = wp_parse_args( $settings, $defaults );

	$fonts = $settings['prepend_standard_fonts']
		? array_merge( et_builder_get_websafe_fonts(), et_builder_get_google_fonts() )
		: array_merge( et_builder_get_google_fonts(), et_builder_get_websafe_fonts() );

	return $fonts;
}
endif;

if ( ! function_exists( 'et_builder_font_options' ) ) :
function et_builder_font_options() {
	$options         = array();

	$default_options = array( 'default' => array(
		'name' => esc_html__( 'Default', 'et_builder' ),
	) );
	$fonts           = array_merge( $default_options, et_builder_get_fonts() );

	foreach ( $fonts as $font_name => $font_settings ) {
		$options[ $font_name ] = 'default' !== $font_name ? $font_name : $font_settings['name'];
	}

	return $options;
}
endif;

if ( ! function_exists( 'et_builder_get_font_options_items' ) ) :
function et_builder_get_font_options_items() {
	$output = '';
	$font_options = et_builder_font_options();

	foreach ( $font_options as $key => $value ) {
		$output .= sprintf(
			'<option value="%1$s">%2$s</option>',
			esc_attr( $key ),
			esc_html( $value )
		);
	}

	return $output;
}
endif;

if ( ! function_exists( 'et_builder_get_websafe_font_stack' ) ) :
function et_builder_get_websafe_font_stack( $type = 'sans-serif' ) {
	$font_stack = '';

	switch ( $type ) {
		case 'sans-serif':
			$font_stack = 'Helvetica, Arial, Lucida, sans-serif';
			break;
		case 'serif':
			$font_stack = 'Georgia, "Times New Roman", serif';
			break;
		case 'cursive':
			$font_stack = 'cursive';
			break;
	}

	return $font_stack;
}
endif;

if ( ! function_exists( 'et_builder_get_font_family' ) ) :
function et_builder_get_font_family( $font_name, $use_important = false ) {
	$fonts = et_builder_get_fonts();

	$font_style = $font_weight = '';

	$font_name_ms = isset( $fonts[ $font_name ] ) && isset( $fonts[ $font_name ]['add_ms_version'] ) ? "'{$font_name} MS', " : "";

	if ( isset( $fonts[ $font_name ]['parent_font'] ) ){
		$font_style = $fonts[ $font_name ]['styles'];
		$font_name = $fonts[ $font_name ]['parent_font'];
	}

	if ( '' !== $font_style ) {
		$font_weight = sprintf( ' font-weight: %1$s;', esc_html( $font_style ) );
	}

	$style = sprintf( 'font-family: \'%1$s\', %5$s%2$s%3$s;%4$s',
		esc_html( $font_name ),
		isset( $fonts[ $font_name ] ) ? et_builder_get_websafe_font_stack( $fonts[ $font_name ]['type'] ) : "",
		( $use_important ? ' !important' : '' ),
		$font_weight,
		$font_name_ms
	);

	return $style;
}
endif;

if ( ! function_exists( 'et_builder_set_element_font' ) ) :
function et_builder_set_element_font( $font, $use_important = false, $default = false ) {
	$style = '';

	if ( '' === $font ) {
		return $style;
	}

	$font_values = explode( '|', $font );
	$default = ! $default ? "||||" : $default;
	$font_values_default = explode( '|', $default );

	if ( ! empty( $font_values ) ) {
		$font_values       = array_map( 'trim', $font_values );
		$font_name         = $font_values[0];
		$is_font_bold      = 'on' === $font_values[1] ? true : false;
		$is_font_italic    = 'on' === $font_values[2] ? true : false;
		$is_font_uppercase = 'on' === $font_values[3] ? true : false;
		$is_font_underline = 'on' === $font_values[4] ? true : false;

		$font_name_default         = $font_values_default[0];
		$is_font_bold_default      = 'on' === $font_values_default[1] ? true : false;
		$is_font_italic_default    = 'on' === $font_values_default[2] ? true : false;
		$is_font_uppercase_default = 'on' === $font_values_default[3] ? true : false;
		$is_font_underline_default = 'on' === $font_values_default[4] ? true : false;

		if ( '' !== $font_name && $font_name_default !== $font_name ) {
			et_builder_enqueue_font( $font_name );

			$style .= et_builder_get_font_family( $font_name, $use_important ) . ' ';
		}

		$style .= et_builder_set_element_font_style( 'font-weight', $is_font_bold_default, $is_font_bold, 'normal', 'bold', $use_important );

		$style .= et_builder_set_element_font_style( 'font-style', $is_font_italic_default, $is_font_italic, 'none', 'italic', $use_important );

		$style .= et_builder_set_element_font_style( 'text-transform', $is_font_uppercase_default, $is_font_uppercase, 'none', 'uppercase', $use_important );

		$style .= et_builder_set_element_font_style( 'text-decoration', $is_font_underline_default, $is_font_underline, 'none', 'underline', $use_important );

		$style = rtrim( $style );
	}

	return $style;
}
endif;

if ( ! function_exists( 'et_builder_set_element_font_style' ) ) :
function et_builder_set_element_font_style( $property, $default, $value, $property_default, $property_value, $use_important ) {
	$style = "";

	if ( $value && ! $default ) {
		$style = sprintf(
			'%1$s: %2$s%3$s; ',
			esc_html( $property ),
			$property_value,
			( $use_important ? ' !important' : '' )
		);
	} elseif ( ! $value && $default ) {
		$style = sprintf(
			'%1$s: %2$s%3$s; ',
			esc_html( $property ),
			$property_default,
			( $use_important ? ' !important' : '' )
		);
	}

	return $style;
}
endif;

if ( ! function_exists( 'et_builder_get_element_style_css' ) ) :
function et_builder_get_element_style_css( $value, $property = 'margin', $use_important = false ) {
	$style = '';

	$values = explode( '|', $value );

	if ( ! empty( $values ) ) {
		$element_style = '';
		$i = 0;
		$values = array_map( 'trim', $values );
		$positions = array(
			'top',
			'right',
			'bottom',
			'left',
		);

		foreach ( $values as $element_style_value ) {
			if ( '' !== $element_style_value ) {
				$element_style .= sprintf(
					'%3$s-%1$s: %2$s%4$s; ',
					esc_attr( $positions[ $i ] ),
					esc_attr( et_builder_process_range_value( $element_style_value ) ),
					esc_attr( $property ),
					( $use_important ? ' !important' : '' )
				);
			}

			$i++;
		}

		$style .= rtrim( $element_style );
	}

	return $style;
}
endif;

if ( ! function_exists( 'et_builder_enqueue_font' ) ) :
function et_builder_enqueue_font( $font_name ) {
	$fonts = et_builder_get_fonts();
	$websafe_fonts = et_builder_get_websafe_fonts();
	$protocol = is_ssl() ? 'https' : 'http';

	// Skip enqueueing if font name is not found. Possibly happen if support for particular font need to be dropped
	if ( ! array_key_exists( $font_name, $fonts ) ) {
		return;
	}

	// Skip enqueueing for websafe fonts
	if ( array_key_exists( $font_name, $websafe_fonts ) ) {
		return;
	}

	if ( isset( $fonts[ $font_name ]['parent_font'] ) ){
		$font_name = $fonts[ $font_name ]['parent_font'];
	}
	$font_character_set = $fonts[ $font_name ]['character_set'];

	$query_args = array(
		'family' => sprintf( '%s:%s',
			str_replace( ' ', '+', $font_name ),
			apply_filters( 'et_builder_set_styles', $fonts[ $font_name ]['styles'], $font_name )
		),
		'subset' => apply_filters( 'et_builder_set_character_set', $font_character_set, $font_name ),
	);

	$font_name_slug = sprintf(
		'et-gf-%1$s',
		strtolower( str_replace( ' ', '-', $font_name ) )
	);

	wp_enqueue_style( $font_name_slug, esc_url( add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ) ), array(), null );
}
endif;

function et_pb_maybe_add_advanced_styles() {
	$style = ET_Builder_Element::get_style();

	if ( $style ) {
		printf(
			'<style type="text/css" id="et-builder-advanced-style">
				%1$s
			</style>',
			$style
		);
	}
}
add_action( 'wp_footer', 'et_pb_maybe_add_advanced_styles' );

if ( ! function_exists( 'et_pb_check_oembed_provider' ) ) {
function et_pb_check_oembed_provider( $url ) {
	require_once( ABSPATH . WPINC . '/class-oembed.php' );
	$oembed = _wp_oembed_get_object();
	return $oembed->get_provider( esc_url( $url ), array( 'discover' => false ) );
}
}

if ( ! function_exists( 'et_pb_set_video_oembed_thumbnail_resolution' ) ) :
function et_pb_set_video_oembed_thumbnail_resolution( $image_src, $resolution = 'default' ) {
	// Replace YouTube video thumbnails to high resolution.
	if ( 'high' === $resolution && false !== strpos( $image_src,  'hqdefault.jpg' ) ) {
		return str_replace( 'hqdefault.jpg', 'maxresdefault.jpg', $image_src );
	}

	return $image_src;
}
endif;

function et_builder_widgets_init(){
	$et_pb_widgets = get_theme_mod( 'et_pb_widgets' );

	if ( $et_pb_widgets['areas'] ) {
		foreach ( $et_pb_widgets['areas'] as $id => $name ) {
			register_sidebar( array(
				'name' => sanitize_text_field( $name ),
				'id' => sanitize_text_field( $id ),
				'before_widget' => '<div id="%1$s" class="et_pb_widget %2$s">',
				'after_widget' => '</div> <!-- end .et_pb_widget -->',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			) );
		}
	}
}
add_action( 'widgets_init', 'et_builder_widgets_init' );

if ( ! function_exists( 'et_builder_get_widget_areas' ) ) :
function et_builder_get_widget_areas() {
	global $wp_registered_sidebars;
	$et_pb_widgets = get_theme_mod( 'et_pb_widgets' );

	$output = '<select name="et_pb_area" id="et_pb_area">';

	foreach ( $wp_registered_sidebars as $id => $options ) {
		$selected = sprintf(
			'<%%= typeof( et_pb_area ) !== "undefined" && "%1$s" === et_pb_area ?  " selected=\'selected\'" : "" %%>',
			esc_html( $id )
		);

		$output .= sprintf(
			'<option value="%1$s"%2$s>%3$s</option>',
			esc_attr( $id ),
			$selected,
			esc_html( $options['name'] )
		);
	}

	$output .= '</select>';

	return $output;
}
endif;

if ( ! function_exists( 'et_pb_export_layouts_interface' ) ) :
function et_pb_export_layouts_interface() {
	if ( ! current_user_can( 'export' ) )
		wp_die( esc_html__( 'You do not have sufficient permissions to export the content of this site.', 'et_builder' ) );

?>
	<div class="et_pb_export_section">
		<h2 id="et_page_title"><?php esc_html_e( 'Export Divi Builder Layouts', 'et_builder' ); ?></h2>
		<p><?php esc_html_e( 'When you click the button below WordPress will create an XML file for you to save to your computer.', 'et_builder' ); ?></p>
		<p><?php esc_html_e( 'This format, which we call WordPress eXtended RSS or WXR, will contain all layouts you created using the Page Builder.', 'et_builder' ); ?></p>
		<p><?php esc_html_e( 'Once you&#8217;ve saved the download file, you can use the Import function in another WordPress installation to import all layouts from this site.', 'et_builder' ); ?></p>
		<p><?php esc_html_e( 'Select Templates you want to export:', 'et_builder' ); ?></p>

		<form action="<?php echo esc_url( admin_url( 'export.php' ) ); ?>" method="get" id="et-pb-export-layouts">
			<input type="hidden" name="download" value="true" />
			<input type="hidden" name="content" value="<?php echo esc_attr( ET_BUILDER_LAYOUT_POST_TYPE ); ?>" />

		<?php
			$all_template_types = array(
				'layout'  => esc_html__( 'Layouts', 'et_builder' ),
				'section' => esc_html__( 'Sections', 'et_builder' ),
				'row'     => esc_html__( 'Rows', 'et_builder' ),
				'module'  => esc_html__( 'Modules', 'et_builder' )
			);

			foreach( $all_template_types as $template_type => $template_name ) {
				$term = get_term_by( 'name', $template_type, 'layout_type', OBJECT );

				if ( ! $term ) {
					continue;
				}

				printf(
					'<label>
						<input type="checkbox" name="et_pb_template_%1$s" value="%2$s" checked="checked" />
						%3$s
					</label>
					<br/><br/>',
					esc_attr( $template_type ),
					esc_attr( $term->term_id ),
					esc_html( $template_name )
				);
			}

			submit_button( esc_html__( 'Download Export File', 'et_builder' ) );
		?>
		</form>
	</div>
	<div class="et_export_section_link_wrap">
		<a href="#" id="et_show_export_section"><?php esc_html_e( 'Export Divi Layouts', 'et_builder' ); ?></a>
	</div>
	<div class="clearfix"></div>
	<div class="et_manage_library_cats">
		<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=layout_category' ) ); ?>" id="et_load_category_page"><?php esc_html_e( 'Manage Categories', 'et_builder' ); ?></a>
	</div>
<?php }
endif;

add_action( 'export_wp', 'et_pb_edit_export_query' );
function et_pb_edit_export_query() {
	add_filter( 'query', 'et_pb_edit_export_query_filter' );
}

function et_pb_edit_export_query_filter( $query ) {
	// Apply filter only once
	remove_filter( 'query', 'et_pb_edit_export_query_filter') ;

	global $wpdb;

	$content = ! empty( $_GET['content'] ) ? $_GET['content'] : '';

	if ( ET_BUILDER_LAYOUT_POST_TYPE !== $content ) {
		return $query;
	}

	$sql = '';
	$i = 0;
	$possible_types = array(
		'layout',
		'section',
		'row',
		'module',
		'fullwidth_section',
		'specialty_section',
		'fullwidth_module',
	);

	foreach ( $possible_types as $template_type ) {
		$selected_type = 'et_pb_template_' . $template_type;

		if ( isset( $_GET[ $selected_type ] ) ) {
			if ( 0 === $i ) {
				$sql = " AND ( {$wpdb->term_relationships}.term_taxonomy_id = %d";
			} else {
				$sql .= " OR {$wpdb->term_relationships}.term_taxonomy_id = %d";
			}

			$sql_args[] = (int) $_GET[ $selected_type ];

			$i++;
		}
	}

	if ( '' !== $sql ) {
		$sql  .= ' )';

		$sql = sprintf(
			'SELECT ID FROM %4$s
			 INNER JOIN %3$s ON ( %4$s.ID = %3$s.object_id )
			 WHERE %4$s.post_type = "%1$s"
			 AND %4$s.post_status != "auto-draft"
			 %2$s',
			ET_BUILDER_LAYOUT_POST_TYPE,
			$sql,
			$wpdb->term_relationships,
			$wpdb->posts
		);

		$query = $wpdb->prepare( $sql, $sql_args );
	}

	return $query;
}

function et_pb_setup_theme(){
	add_action( 'add_meta_boxes', 'et_pb_add_custom_box' );
}
add_action( 'init', 'et_pb_setup_theme', 11 );

function et_builder_set_post_type( $post_type = '' ) {
	global $et_builder_post_type, $post;

	$et_builder_post_type = ! empty( $post_type ) ? $post_type : $post->post_type;
}

function et_pb_metabox_settings_save_details( $post_id, $post ){
	global $pagenow;

	if ( 'post.php' != $pagenow ) return $post_id;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	if ( ! isset( $_POST['et_pb_settings_nonce'] ) || ! wp_verify_nonce( $_POST['et_pb_settings_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	if ( isset( $_POST['et_pb_use_builder'] ) ) {
		update_post_meta( $post_id, '_et_pb_use_builder', sanitize_text_field( $_POST['et_pb_use_builder'] ) );
	} else {
		delete_post_meta( $post_id, '_et_pb_use_builder' );
	}

	if ( isset( $_POST['et_pb_old_content'] ) ) {
		update_post_meta( $post_id, '_et_pb_old_content', $_POST['et_pb_old_content'] );
	} else {
		delete_post_meta( $post_id, '_et_pb_old_content' );
	}
}
add_action( 'save_post', 'et_pb_metabox_settings_save_details', 10, 2 );

function _et_pb_sanitize_code_module_content_regex( $matches ) {
	$sanitized_content = wp_kses_post( htmlspecialchars_decode( $matches[1] ) );
	$sanitized_shortcode = str_replace( $matches[1], $sanitized_content, $matches[0] );
	return $sanitized_shortcode;
}

function et_pb_before_main_editor( $post ) {
	if ( ! in_array( $post->post_type, et_builder_get_builder_post_types() ) ) return;

	$_et_builder_use_builder = get_post_meta( $post->ID, '_et_pb_use_builder', true );
	$is_builder_used = 'on' === $_et_builder_use_builder ? true : false;

	$builder_always_enabled = apply_filters('et_builder_always_enabled', false, $post->post_type, $post );
	if ( $builder_always_enabled || 'et_pb_layout' === $post->post_type ) {
		$is_builder_used = true;
		$_et_builder_use_builder = 'on';
	}

	// Add button only if current user is allowed to use it otherwise display placeholder with all required data
	if ( et_pb_is_allowed( 'divi_builder_control' ) ) {
		printf( '<div class="et_pb_toggle_builder_wrapper%5$s"><a href="#" id="et_pb_toggle_builder" data-builder="%2$s" data-editor="%3$s" class="button button-primary button-large%5$s%6$s">%1$s</a></div><div id="et_pb_main_editor_wrap"%4$s>',
			( $is_builder_used ? esc_html__( 'Use Default Editor', 'et_builder' ) : esc_html__( 'Use The Divi Builder', 'et_builder' ) ),
			esc_html__( 'Use The Divi Builder', 'et_builder' ),
			esc_html__( 'Use Default Editor', 'et_builder' ),
			( $is_builder_used ? ' class="et_pb_hidden"' : '' ),
			( $is_builder_used ? ' et_pb_builder_is_used' : '' ),
			( $builder_always_enabled ? ' et_pb_hidden' : '' )
		);
	} else {
		printf( '<div class="et_pb_toggle_builder_wrapper%2$s"></div><div id="et_pb_main_editor_wrap"%1$s>',
			( $is_builder_used ? ' class="et_pb_hidden"' : '' ),
			( $is_builder_used ? ' et_pb_builder_is_used' : '' )
		);
	}

	?>
	<p class="et_pb_page_settings" style="display: none;">
		<?php wp_nonce_field( basename( __FILE__ ), 'et_pb_settings_nonce' ); ?>
		<input type="hidden" id="et_pb_use_builder" name="et_pb_use_builder" value="<?php echo esc_attr( $_et_builder_use_builder ); ?>" />
		<textarea id="et_pb_old_content" name="et_pb_old_content"><?php echo esc_attr( get_post_meta( $post->ID, '_et_pb_old_content', true ) ); ?></textarea>
	</p>
	<?php
}
add_action( 'edit_form_after_title', 'et_pb_before_main_editor' );

function et_pb_after_main_editor( $post ) {
	if ( ! in_array( $post->post_type, et_builder_get_builder_post_types() ) ) return;
	echo '</div> <!-- #et_pb_main_editor_wrap -->';
}
add_action( 'edit_form_after_editor', 'et_pb_after_main_editor' );

function et_pb_admin_scripts_styles( $hook ) {
	global $typenow;

	//load css file for the Divi menu
	wp_enqueue_style( 'library-menu-styles', ET_BUILDER_URI . '/styles/library_menu.css', array(), ET_BUILDER_VERSION );

	if ( $hook === 'widgets.php' ) {
		wp_enqueue_script( 'et_pb_widgets_js', ET_BUILDER_URI . '/scripts/ext/widgets.js', array( 'jquery' ), ET_BUILDER_VERSION, true );

		wp_localize_script( 'et_pb_widgets_js', 'et_pb_options', apply_filters( 'et_pb_options_admin', array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'et_admin_load_nonce' => wp_create_nonce( 'et_admin_load_nonce' ),
			'widget_info'   => sprintf( '<div id="et_pb_widget_area_create"><p>%1$s.</p><p>%2$s.</p><p><label>%3$s <input id="et_pb_new_widget_area_name" value="" /></label></p><p class="et_pb_widget_area_result"></p><button class="button button-primary et_pb_create_widget_area">%4$s</button></div>',
				esc_html__( 'Here you can create new widget areas for use in the Sidebar module', 'et_builder' ),
				esc_html__( 'Note: Naming your widget area "sidebar 1", "sidebar 2", "sidebar 3", "sidebar 4" or "sidebar 5" will cause conflicts with this theme', 'et_builder' ),
				esc_html__( 'Widget Name', 'et_builder' ),
				esc_html__( 'Create', 'et_builder' )
			),
			'delete_string' => esc_html__( 'Delete', 'et_builder' ),
		) ) );

		wp_enqueue_style( 'et_pb_widgets_css', ET_BUILDER_URI . '/styles/widgets.css', array(), ET_BUILDER_VERSION );

		return;
	}

	if ( ! in_array( $hook, array( 'post-new.php', 'post.php' ) ) ) return;

	/*
	 * Load the builder javascript and css files for custom post types
	 * custom post types can be added using et_builder_post_types filter
	*/

	$post_types = et_builder_get_builder_post_types();

	if ( isset( $typenow ) && in_array( $typenow, $post_types ) ){
		et_pb_add_builder_page_js_css();
	}
}
add_action( 'admin_enqueue_scripts', 'et_pb_admin_scripts_styles', 10, 1 );

function et_pb_fix_builder_shortcodes( $content ) {
	// if the builder is used for the page, get rid of random p tags
	if ( is_singular() && 'on' === get_post_meta( get_the_ID(), '_et_pb_use_builder', true ) ) {
		$content = et_pb_fix_shortcodes( $content );
	}

	return $content;
}
add_filter( 'the_content', 'et_pb_fix_builder_shortcodes' );

// generate the html for "Add new template" Modal in Library
if ( ! function_exists( 'et_pb_generate_new_layout_modal' ) ) {
	function et_pb_generate_new_layout_modal() {
		$template_type_option_output = '';
		$template_module_tabs_option_output = '';
		$template_global_option_output = '';
		$layout_cat_option_output = '';

		$template_type_options = apply_filters( 'et_pb_new_layout_template_types', array(
			'module'            => esc_html__( 'Module', 'et_builder' ),
			'fullwidth_module'  => esc_html__( 'Fullwidth Module', 'et_builder' ),
			'row'               => esc_html__( 'Row', 'et_builder' ),
			'section'           => esc_html__( 'Section', 'et_builder' ),
			'fullwidth_section' => esc_html__( 'Fullwidth Section', 'et_builder' ),
			'specialty_section' => esc_html__( 'Specialty Section', 'et_builder' ),
			'layout'            => esc_html__( 'Layout', 'et_builder' ),
		) );

		$template_module_tabs_options = apply_filters( 'et_pb_new_layout_module_tabs', array(
			'general'  => esc_html__( 'Include General Settings', 'et_builder' ),
			'advanced' => esc_html__( 'Include Advanced Design Settings', 'et_builder' ),
			'css'      => esc_html__( 'Include Custom CSS', 'et_builder' ),
		) );

		// construct output for the template type option
		if ( ! empty( $template_type_options ) ) {
			$template_type_option_output = sprintf(
				'<br><label>%1$s:</label>
				<select id="new_template_type">',
				esc_html__( 'Template Type', 'et_builder' )
			);

			foreach( $template_type_options as $option_id => $option_name ) {
				$template_type_option_output .= sprintf(
					'<option value="%1$s">%2$s</option>',
					esc_attr( $option_id ),
					esc_html( $option_name )
				);
			}

			$template_type_option_output .= '</select>';
		}

		// construct output for the module tabs option
		if ( ! empty( $template_module_tabs_options ) ) {
			$template_module_tabs_option_output = '<br><div class="et_module_tabs_options">';

			foreach( $template_module_tabs_options as $option_id => $option_name ) {
				$template_module_tabs_option_output .= sprintf(
					'<label>%1$s<input type="checkbox" value="%2$s" id="et_pb_template_general" checked /></label>',
					esc_html( $option_name ),
					esc_attr( $option_id )
				);
			}

			$template_module_tabs_option_output .= '</div>';
		}

		$template_global_option_output = apply_filters( 'et_pb_new_layout_global_option', sprintf(
			'<br><label>%1$s<input type="checkbox" value="global" id="et_pb_template_global"></label>',
			esc_html__( 'Global', 'et_builder' )
		) );

		// construct output for the layout category option
		$layout_cat_option_output .= sprintf(
			'<br><label>%1$s</label>',
			esc_html__( 'Select category(ies) for new template or type a new name ( optional )', 'et_builder' )
		);

		$layout_categories = apply_filters( 'et_pb_new_layout_cats_array', get_terms( 'layout_category', array( 'hide_empty' => false ) ) );
		if ( is_array( $layout_categories ) && ! empty( $layout_categories ) ) {
			$layout_cat_option_output .= '<div class="layout_cats_container">';

			foreach( $layout_categories as $category ) {
				$layout_cat_option_output .= sprintf(
					'<label>%1$s<input type="checkbox" value="%2$s"/></label>',
					esc_html( $category->name ),
					esc_attr( $category->term_id )
				);
			}

			$layout_cat_option_output .= '</div>';
		}

		$layout_cat_option_output .= '<input type="text" value="" id="et_pb_new_cat_name" class="regular-text">';

		$output = sprintf(
			'<div class="et_pb_modal_overlay et_modal_on_top et_pb_new_template_modal">
				<div class="et_pb_prompt_modal">
					<h2>%1$s</h2>
					<div class="et_pb_prompt_modal_inside">
						<label>%2$s:</label>
							<input type="text" value="" id="et_pb_new_template_name" class="regular-text">
							%7$s
							%3$s
							%4$s
							%5$s
							%6$s
							%8$s
							<input id="et_builder_layout_built_for_post_type" type="hidden" value="page">
					</div>
					<a href="#"" class="et_pb_prompt_dont_proceed et-pb-modal-close"></a>
					<div class="et_pb_prompt_buttons">
						<br>
						<span class="spinner"></span>
						<input type="submit" class="et_pb_create_template button-primary et_pb_prompt_proceed">
					</div>
				</div>
			</div>',
			esc_html__( 'New Template Settings', 'et_builder' ),
			esc_html__( 'Template Name', 'et_builder' ),
			$template_type_option_output,
			$template_module_tabs_option_output,
			$template_global_option_output, //#5
			$layout_cat_option_output,
			apply_filters( 'et_pb_new_layout_before_options', '' ),
			apply_filters( 'et_pb_new_layout_after_options', '' )
		);

		return apply_filters( 'et_pb_new_layout_modal_output', $output );
	}
}

/**
 * Get layout type of given post ID
 * @return string|bool
 */
if ( ! function_exists( 'et_pb_get_layout_type' ) ) :
function et_pb_get_layout_type( $post_id ) {
	// Get taxonomies
	$layout_type_data = wp_get_post_terms( $post_id, 'layout_type' );

	if ( empty( $layout_type_data ) ) {
		return false;
	}

	// Pluck name out of taxonomies
	$layout_type_array = wp_list_pluck( $layout_type_data, 'name' );

	// Logically, a layout only have one layout type.
	$layout_type = implode( "|", $layout_type_array );

	return $layout_type;
}
endif;

if ( ! function_exists( 'et_pb_add_builder_page_js_css' ) ) :
function et_pb_add_builder_page_js_css(){
	global $typenow, $post;

	if ( et_is_yoast_seo_plugin_active() ) {
		// save the original content of $post variable
		$post_original = $post;
		// get the content for yoast
		$post_content_processed = do_shortcode( $post->post_content );
		// set the $post to the original content to make sure it wasn't changed by do_shortcode()
		$post = $post_original;
	}

	// we need some post data when editing saved templates.
	if ( 'et_pb_layout' === $typenow ) {
		$template_scope = wp_get_object_terms( get_the_ID(), 'scope' );
		$is_global_template = ! empty( $template_scope[0] ) ? $template_scope[0]->slug : 'regular';
		$post_id = get_the_ID();

		// Check whether it's a Global item's page and display wp error if Global items disabled for current user
		if ( ! et_pb_is_allowed( 'edit_global_library' ) && 'global' === $is_global_template ) {
			wp_die( esc_html__( "you don't have sufficient permissions to access this page", 'et_builder' ) );
		}

		$built_for_post_type = get_post_meta( get_the_ID(), '_et_pb_built_for_post_type', true );
		$built_for_post_type = '' !== $built_for_post_type ? $built_for_post_type : 'page';
		$post_type = apply_filters( 'et_pb_built_for_post_type', $built_for_post_type, get_the_ID() );
	} else {
		$is_global_template = '';
		$post_id = '';
		$post_type = $typenow;
	}

	// we need this data to create the filter when adding saved modules
	$layout_categories = get_terms( 'layout_category' );
	$layout_cat_data = array();
	$layout_cat_data_json = '';

	if ( is_array( $layout_categories ) && ! empty( $layout_categories ) ) {
		foreach( $layout_categories as $category ) {
			$layout_cat_data[] = array(
				'slug' => $category->slug,
				'name' => $category->name,
			);
		}
	}
	if ( ! empty( $layout_cat_data ) ) {
		$layout_cat_data_json = json_encode( $layout_cat_data );
	}

	// Set fixed protocol for preview URL to prevent cross origin issue
	$preview_scheme = is_ssl() ? 'https' : 'http';

	$preview_url = esc_url( home_url( '/' ) );

	if ( 'https' === $preview_scheme && ! strpos( $preview_url, 'https://' ) ) {
		$preview_url = str_replace( 'http://', 'https://', $preview_url );
	}

	// force update cache if need to regenerate MailChimp or AWeber Lists or if et_pb_clear_templates_cache option is set to on
	if ( 'on' === et_get_option( 'divi_regenerate_mailchimp_lists', 'false' ) || 'on' === et_get_option( 'divi_regenerate_aweber_lists', 'false' ) || 'on' === et_get_option( 'et_pb_clear_templates_cache', 'off' ) ) {
		$force_cache_value = true;
	} else {
		$force_cache_value = false;
	}

	$force_cache_update = false !== $force_cache_value ? $force_cache_value : ET_BUILDER_FORCE_CACHE_PURGE;

	// delete et_pb_clear_templates_cache option it's not needed anymore
	et_delete_option( 'et_pb_clear_templates_cache' );

	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'underscore' );
	wp_enqueue_script( 'backbone' );

	wp_enqueue_script( 'google-maps-api', esc_url( add_query_arg( array( 'v' => 3, 'sensor' => 'false' ), is_ssl() ? 'https://maps-api-ssl.google.com/maps/api/js' : 'http://maps.google.com/maps/api/js' ) ), array(), '3', true );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker-alpha', ET_BUILDER_URI . '/scripts/ext/wp-color-picker-alpha.min.js', array( 'jquery', 'wp-color-picker' ), ET_BUILDER_VERSION, true );

	wp_enqueue_script( 'et_pb_admin_date_js', ET_BUILDER_URI . '/scripts/ext/jquery-ui-1.10.4.custom.min.js', array( 'jquery' ), ET_BUILDER_VERSION, true );
	wp_enqueue_script( 'et_pb_admin_date_addon_js', ET_BUILDER_URI . '/scripts/ext/jquery-ui-timepicker-addon.js', array( 'et_pb_admin_date_js' ), ET_BUILDER_VERSION, true );

	wp_enqueue_script( 'validation', ET_BUILDER_URI . '/scripts/ext/jquery.validate.js', array( 'jquery' ), ET_BUILDER_VERSION, true );
	wp_enqueue_script( 'minicolors', ET_BUILDER_URI . '/scripts/ext/jquery.minicolors.js', array( 'jquery' ), ET_BUILDER_VERSION, true );

	wp_enqueue_script( 'et_pb_admin_js', ET_BUILDER_URI .'/scripts/builder.js', array( 'jquery', 'jquery-ui-core', 'underscore', 'backbone' ), ET_BUILDER_VERSION, true );

	wp_localize_script( 'et_pb_admin_js', 'et_pb_options', apply_filters( 'et_pb_options_builder', array(
		'debug'                                    => true,
		'ajaxurl'                                  => admin_url( 'admin-ajax.php' ),
		'home_url'                                 => home_url(),
		'preview_url'                              => add_query_arg( 'et_pb_preview', 'true', $preview_url ),
		'et_admin_load_nonce'                      => wp_create_nonce( 'et_admin_load_nonce' ),
		'images_uri'                               => ET_BUILDER_URI .'/images',
		'post_type'                                => $post_type,
		'et_builder_module_parent_shortcodes'      => ET_Builder_Element::get_parent_shortcodes( $post_type ),
		'et_builder_module_child_shortcodes'       => ET_Builder_Element::get_child_shortcodes( $post_type ),
		'et_builder_module_raw_content_shortcodes' => ET_Builder_Element::get_raw_content_shortcodes( $post_type ),
		'et_builder_modules'                       => ET_Builder_Element::get_modules_js_array( $post_type ),
		'et_builder_modules_count'                 => ET_Builder_Element::get_modules_count( $post_type ),
		'et_builder_modules_with_children'         => ET_Builder_Element::get_shortcodes_with_children( $post_type ),
		'et_builder_templates_amount'              => ET_BUILDER_AJAX_TEMPLATES_AMOUNT,
		'default_initial_column_type'              => apply_filters( 'et_builder_default_initial_column_type', '4_4' ),
		'default_initial_text_module'              => apply_filters( 'et_builder_default_initial_text_module', 'et_pb_text' ),
		'section_only_row_dragged_away'            => esc_html__( 'The section should have at least one row.', 'et_builder' ),
		'fullwidth_module_dragged_away'            => esc_html__( 'Fullwidth module can\'t be used outside of the Fullwidth Section.', 'et_builder' ),
		'stop_dropping_3_col_row'                  => esc_html__( '3 column row can\'t be used in this column.', 'et_builder' ),
		'preview_image'                            => esc_html__( 'Preview', 'et_builder' ),
		'empty_admin_label'                        => esc_html__( 'Module', 'et_builder' ),
		'video_module_image_error'                 => esc_html__( 'Still images cannot be generated from this video service and/or this video format', 'et_builder' ),
		'geocode_error'                            => esc_html__( 'Geocode was not successful for the following reason', 'et_builder' ),
		'geocode_error_2'                          => esc_html__( 'Geocoder failed due to', 'et_builder' ),
		'no_results'                               => esc_html__( 'No results found', 'et_builder' ),
		'all_tab_options_hidden'                   => esc_html__( 'No available options for this configuration.', 'et_builder' ),
		'update_global_module'                     => esc_html__( 'You\'re about to update global module. This change will be applied to all pages where you use this module. Press OK if you want to update this module', 'et_builder' ),
		'global_row_alert'                         => esc_html__( 'You cannot add global rows into global sections', 'et_builder' ),
		'global_module_alert'                      => esc_html__( 'You cannot add global modules into global sections or rows', 'et_builder' ),
		'all_cat_text'                             => esc_html__( 'All Categories', 'et_builder' ),
		'is_global_template'                       => $is_global_template,
		'template_post_id'                         => $post_id,
		'layout_categories'                        => $layout_cat_data_json,
		'map_pin_address_error'                    => esc_html__( 'Map Pin Address cannot be empty', 'et_builder' ),
		'map_pin_address_invalid'                  => esc_html__( 'Invalid Pin and address data. Please try again.', 'et_builder' ),
		'locked_section_permission_alert'          => esc_html__( 'You do not have permission to unlock this section.', 'et_builder' ),
		'locked_row_permission_alert'              => esc_html__( 'You do not have permission to unlock this row.', 'et_builder' ),
		'locked_module_permission_alert'           => esc_html__( 'You do not have permission to unlock this module.', 'et_builder' ),
		'locked_item_permission_alert'             => esc_html__( 'You do not have permission to perform this task.', 'et_builder' ),
		'localstorage_unavailability_alert'        => esc_html__( 'Unable to perform copy/paste process due to inavailability of localStorage feature in your browser. Please use latest modern browser (Chrome, Firefox, or Safari) to perform copy/paste process', 'et_builder' ),
		'verb'          => array(
			'did'       => esc_html__( 'Did', 'et_builder' ),
			'added'     => esc_html__( 'Added', 'et_builder' ),
			'edited'    => esc_html__( 'Edited', 'et_builder' ),
			'removed'   => esc_html__( 'Removed', 'et_builder' ),
			'moved'     => esc_html__( 'Moved', 'et_builder' ),
			'expanded'  => esc_html__( 'Expanded', 'et_builder' ),
			'collapsed' => esc_html__( 'Collapsed', 'et_builder' ),
			'locked'    => esc_html__( 'Locked', 'et_builder' ),
			'unlocked'  => esc_html__( 'Unlocked', 'et_builder' ),
			'cloned'    => esc_html__( 'Cloned', 'et_builder' ),
			'cleared'   => esc_html__( 'Cleared', 'et_builder' ),
			'enabled'   => esc_html__( 'Enabled', 'et_builder' ),
			'disabled'  => esc_html__( 'Disabled', 'et_builder' ),
			'copied'    => esc_html__( 'Copied', 'et_builder' ),
			'renamed'   => esc_html__( 'Renamed', 'et_builder' ),
			'loaded'    => esc_html__( 'Loaded', 'et_builder' ),
		),
		'noun'                  => array(
			'section'           => esc_html__( 'Section', 'et_builder' ),
			'saved_section'     => esc_html__( 'Saved Section', 'et_builder' ),
			'fullwidth_section' => esc_html__( 'Fullwidth Section', 'et_builder' ),
			'specialty_section' => esc_html__( 'Specialty Section', 'et_builder' ),
			'column'            => esc_html__( 'Column', 'et_builder' ),
			'row'               => esc_html__( 'Row', 'et_builder' ),
			'saved_row'         => esc_html__( 'Saved Row', 'et_builder' ),
			'module'            => esc_html__( 'Module', 'et_builder' ),
			'saved_module'      => esc_html__( 'Saved Module', 'et_builder' ),
			'page'              => esc_html__( 'Page', 'et_builder' ),
			'layout'            => esc_html__( 'Layout', 'et_builder' ),
		),
		'addition' => array(
			'phone' => esc_html__( 'on Phone', 'et_builder' ),
			'tablet' => esc_html__( 'on Tablet', 'et_builder' ),
			'desktop' => esc_html__( 'on Desktop', 'et_builder' ),
		),
		'invalid_color'    => esc_html__( 'Invalid Color', 'et_builder' ),
		'et_pb_preview_nonce' => wp_create_nonce( 'et_pb_preview_nonce' ),
		'is_divi_library'  => 'et_pb_layout' === $typenow ? 1 : 0,
		'layout_type'      => 'et_pb_layout' === $typenow ? et_pb_get_layout_type( get_the_ID() ) : 0,
		'is_plugin_used'   => et_is_builder_plugin_active(),
		'yoast_content'    => et_is_yoast_seo_plugin_active() ? $post_content_processed : '',
		'product_version'  => ET_BUILDER_VERSION,
		'force_cache_purge' => (int) $force_cache_update,
	) ) );

	wp_enqueue_style( 'et_pb_admin_css', ET_BUILDER_URI .'/styles/style.css', array(), ET_BUILDER_VERSION );
	wp_enqueue_style( 'et_pb_admin_date_css', ET_BUILDER_URI . '/styles/jquery-ui-1.10.4.custom.css', array(), ET_BUILDER_VERSION );
}
endif;

function et_pb_add_custom_box() {
	$post_types = et_builder_get_builder_post_types();

	foreach ( $post_types as $post_type ){
		add_meta_box( ET_BUILDER_LAYOUT_POST_TYPE, esc_html__( 'The Divi Builder', 'et_builder' ), 'et_pb_pagebuilder_meta_box', $post_type, 'normal', 'high' );
	}
}

if ( ! function_exists( 'et_pb_get_the_author_posts_link' ) ) :
function et_pb_get_the_author_posts_link(){
	global $authordata, $post;

	// Fallback for preview
	if ( empty( $authordata ) && isset( $post->post_author ) ) {
		$authordata = get_userdata( $post->post_author );
	}

	// If $authordata is empty, don't continue
	if ( empty( $authordata ) ) {
		return;
	}

	$link = sprintf(
		'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
		esc_url( get_author_posts_url( $authordata->ID, $authordata->user_nicename ) ),
		esc_attr( sprintf( __( 'Posts by %s', 'et_builder' ), get_the_author() ) ),
		get_the_author()
	);
	return apply_filters( 'the_author_posts_link', $link );
}
endif;

if ( ! function_exists( 'et_pb_get_comments_popup_link' ) ) :
function et_pb_get_comments_popup_link( $zero = false, $one = false, $more = false ){
	$id = get_the_ID();
	$number = get_comments_number( $id );

	if ( 0 == $number && !comments_open() && !pings_open() ) return;

	if ( $number > 1 )
		$output = str_replace( '%', number_format_i18n( $number ), ( false === $more ) ? __( '% Comments', $themename ) : $more );
	elseif ( $number == 0 )
		$output = ( false === $zero ) ? __( 'No Comments', 'et_builder' ) : $zero;
	else // must be one
		$output = ( false === $one ) ? __( '1 Comment', 'et_builder' ) : $one;

	return '<span class="comments-number">' . '<a href="' . esc_url( get_permalink() . '#respond' ) . '">' . apply_filters( 'comments_number', esc_html( $output ), esc_html( $number ) ) . '</a>' . '</span>';
}
endif;

if ( ! function_exists( 'et_pb_postinfo_meta' ) ) :
function et_pb_postinfo_meta( $postinfo, $date_format, $comment_zero, $comment_one, $comment_more ){
	$postinfo_meta = '';

	if ( in_array( 'author', $postinfo ) )
		$postinfo_meta .= ' ' . esc_html__( 'by', 'et_builder' ) . ' <span class="author vcard">' . et_pb_get_the_author_posts_link() . '</span>';

	if ( in_array( 'date', $postinfo ) ) {
		if ( in_array( 'author', $postinfo ) ) $postinfo_meta .= ' | ';
		$postinfo_meta .= '<span class="published">' . esc_html( get_the_time( wp_unslash( $date_format ) ) ) . '</span>';
	}

	if ( in_array( 'categories', $postinfo ) ){
		if ( in_array( 'author', $postinfo ) || in_array( 'date', $postinfo ) ) $postinfo_meta .= ' | ';
		$postinfo_meta .= get_the_category_list(', ');
	}

	if ( in_array( 'comments', $postinfo ) ){
		if ( in_array( 'author', $postinfo ) || in_array( 'date', $postinfo ) || in_array( 'categories', $postinfo ) ) $postinfo_meta .= ' | ';
		$postinfo_meta .= et_pb_get_comments_popup_link( $comment_zero, $comment_one, $comment_more );
	}

	return $postinfo_meta;
}
endif;


if ( ! function_exists( 'et_pb_fix_shortcodes' ) ){
	function et_pb_fix_shortcodes( $content, $decode_entities = false ){
		if ( $decode_entities ) {
			$content = et_builder_replace_code_content_entities( $content );
			$content = ET_Builder_Element::convert_smart_quotes_and_amp( $content );
			$content = html_entity_decode( $content, ENT_QUOTES );
		}

		$replace_tags_from_to = array (
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']',
			"<br />\n[" => '[',
		);

		return strtr( $content, $replace_tags_from_to );
	}
}

if ( ! function_exists( 'et_pb_load_global_module' ) ) {
	function et_pb_load_global_module( $global_id, $row_type = '' ) {
		$global_shortcode = '';

		if ( '' !== $global_id ) {
			$query = new WP_Query( array(
				'p'         => (int) $global_id,
				'post_type' => ET_BUILDER_LAYOUT_POST_TYPE
			) );

			wp_reset_postdata();
			if ( ! empty( $query->post ) ) {
				$global_shortcode = $query->post->post_content;

				if ( '' !== $row_type && 'et_pb_row_inner' === $row_type ) {
					$global_shortcode = str_replace( 'et_pb_row', 'et_pb_row_inner', $global_shortcode );
				}
			}
		}

		return $global_shortcode;
	}
}

if ( ! function_exists( 'et_pb_extract_shortcode_content' ) ) {
	function et_pb_extract_shortcode_content( $content, $shortcode_name ) {

		$start = strpos( $content, ']' ) + 1;
		$end = strrpos( $content, '[/' . $shortcode_name );

		if ( false !== $end ) {
			$content = substr( $content, $start, $end - $start );
		} else {
			$content = (bool) false;
		}

		return $content;
	}
}

function et_builder_get_columns_layout() {
	$layout_columns =
		'<% if ( typeof et_pb_specialty !== \'undefined\' && et_pb_specialty === \'on\' ) { %>
			<li data-layout="1_2,1_2" data-specialty="1,0" data-specialty_columns="2">
				<div class="et_pb_layout_column et_pb_column_layout_1_2 et_pb_variations et_pb_2_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
				</div>
				<div class="et_pb_layout_column et_pb_column_layout_1_2 et_pb_specialty_column"></div>
			</li>

			<li data-layout="1_2,1_2" data-specialty="0,1" data-specialty_columns="2">
				<div class="et_pb_layout_column et_pb_column_layout_1_2 et_pb_specialty_column"></div>

				<div class="et_pb_layout_column et_pb_column_layout_1_2 et_pb_variations et_pb_2_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
				</div>
			</li>

			<li data-layout="1_4,3_4" data-specialty="0,1" data-specialty_columns="3">
				<div class="et_pb_layout_column et_pb_column_layout_1_4 et_pb_specialty_column"></div>
				<div class="et_pb_layout_column et_pb_column_layout_3_4 et_pb_variations et_pb_3_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_3"></div>
						<div class="et_pb_variation et_pb_variation_1_3"></div>
						<div class="et_pb_variation et_pb_variation_1_3"></div>
					</div>
				</div>
			</li>

			<li data-layout="3_4,1_4" data-specialty="1,0" data-specialty_columns="3">
				<div class="et_pb_layout_column et_pb_column_layout_3_4 et_pb_variations et_pb_3_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_3"></div>
						<div class="et_pb_variation et_pb_variation_1_3"></div>
						<div class="et_pb_variation et_pb_variation_1_3"></div>
					</div>
				</div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4 et_pb_specialty_column"></div>
			</li>

			<li data-layout="1_4,1_2,1_4" data-specialty="0,1,0" data-specialty_columns="2">
				<div class="et_pb_layout_column et_pb_column_layout_1_4 et_pb_specialty_column"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_2 et_pb_variations et_pb_2_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
				</div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4 et_pb_specialty_column"></div>
			</li>

			<li data-layout="1_2,1_4,1_4" data-specialty="1,0,0" data-specialty_columns="2">
				<div class="et_pb_layout_column et_pb_column_layout_1_2 et_pb_variations et_pb_2_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
				</div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4 et_pb_specialty_column"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4 et_pb_specialty_column"></div>
			</li>

			<li data-layout="1_4,1_4,1_2" data-specialty="0,0,1" data-specialty_columns="2">
				<div class="et_pb_layout_column et_pb_column_layout_1_4 et_pb_specialty_column"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4 et_pb_specialty_column"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_2 et_pb_variations et_pb_2_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
				</div>
			</li>

			<li data-layout="1_3,2_3" data-specialty="0,1" data-specialty_columns="2">
				<div class="et_pb_layout_column et_pb_column_layout_1_3 et_pb_specialty_column"></div>
				<div class="et_pb_layout_column et_pb_column_layout_2_3 et_pb_variations et_pb_2_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
				</div>
			</li>

			<li data-layout="2_3,1_3" data-specialty="1,0" data-specialty_columns="2">
				<div class="et_pb_layout_column et_pb_column_layout_2_3 et_pb_variations et_pb_2_variations">
					<div class="et_pb_variation et_pb_variation_full"></div>
					<div class="et_pb_variation_row">
						<div class="et_pb_variation et_pb_variation_1_2"></div>
						<div class="et_pb_variation et_pb_variation_1_2"></div>
					</div>
				</div>
				<div class="et_pb_layout_column et_pb_column_layout_1_3 et_pb_specialty_column"></div>
			</li>
		<% } else if ( typeof view !== \'undefined\' && typeof view.model.attributes.specialty_columns !== \'undefined\' ) { %>
			<li data-layout="4_4">
				<div class="et_pb_layout_column et_pb_column_layout_fullwidth"></div>
			</li>
			<li data-layout="1_2,1_2">
				<div class="et_pb_layout_column et_pb_column_layout_1_2"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_2"></div>
			</li>

			<% if ( view.model.attributes.specialty_columns === 3 ) { %>
				<li data-layout="1_3,1_3,1_3">
					<div class="et_pb_layout_column et_pb_column_layout_1_3"></div>
					<div class="et_pb_layout_column et_pb_column_layout_1_3"></div>
					<div class="et_pb_layout_column et_pb_column_layout_1_3"></div>
				</li>
			<% } %>
		<% } else { %>
			<li data-layout="4_4">
				<div class="et_pb_layout_column et_pb_column_layout_fullwidth"></div>
			</li>
			<li data-layout="1_2,1_2">
				<div class="et_pb_layout_column et_pb_column_layout_1_2"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_2"></div>
			</li>
			<li data-layout="1_3,1_3,1_3">
				<div class="et_pb_layout_column et_pb_column_layout_1_3"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_3"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_3"></div>
			</li>
			<li data-layout="1_4,1_4,1_4,1_4">
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
			</li>
			<li data-layout="2_3,1_3">
				<div class="et_pb_layout_column et_pb_column_layout_2_3"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_3"></div>
			</li>
			<li data-layout="1_3,2_3">
				<div class="et_pb_layout_column et_pb_column_layout_1_3"></div>
				<div class="et_pb_layout_column et_pb_column_layout_2_3"></div>
			</li>
			<li data-layout="1_4,3_4">
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_3_4"></div>
			</li>
			<li data-layout="3_4,1_4">
				<div class="et_pb_layout_column et_pb_column_layout_3_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
			</li>
			<li data-layout="1_2,1_4,1_4">
				<div class="et_pb_layout_column et_pb_column_layout_1_2"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
			</li>
			<li data-layout="1_4,1_4,1_2">
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_2"></div>
			</li>
			<li data-layout="1_4,1_2,1_4">
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_2"></div>
				<div class="et_pb_layout_column et_pb_column_layout_1_4"></div>
			</li>
	<%
		}
	%>';

	return apply_filters( 'et_builder_layout_columns', $layout_columns );
}


function et_pb_pagebuilder_meta_box() {
	global $typenow;

	do_action( 'et_pb_before_page_builder' );

	echo '<div id="et_pb_hidden_editor">';
	wp_editor(
		'',
		'et_pb_content_new',
		array(
			'media_buttons' => true,
			'tinymce' => array(
				'wp_autoresize_on' => true
			)
		)
	);
	echo '</div>';

	printf(
		'<div id="et_pb_main_container" class="post-type-%1$s%2$s"></div>',
		esc_attr( $typenow ),
		! et_pb_is_allowed( 'move_module' ) ? ' et-pb-disable-sort' : ''
	);
	$rename_module_menu = sprintf(
		'<%% if ( this.hasOption( "rename" ) ) { %%>
			<li><a class="et-pb-right-click-rename" href="#">%1$s</a></li>
		<%% } %%>',
		esc_html__( 'Rename', 'et_builder' )
	);
	$copy_module_menu = sprintf(
		'<%% if ( this.hasOption( "copy" ) ) { %%>
			<li><a class="et-pb-right-click-copy" href="#">%1$s</a></li>
		<%% } %%>',
		esc_html__( 'Copy', 'et_builder' )
	);
	$paste_after_menu = sprintf(
		'<%% if ( this.hasOption( "paste-after" ) ) { %%>
			<li><a class="et-pb-right-click-paste-after" href="#">%1$s</a></li>
		<%% } %%>',
		esc_html__( 'Paste After', 'et_builder' )
	);
	$paste_menu_item = sprintf(
		'<%% if ( this.hasOption( "paste-column" ) ) { %%>
			<li><a class="et-pb-right-click-paste-column" href="#">%1$s</a></li>
		<%% } %%>',
		esc_html__( 'Paste', 'et_builder' )
	);
	$paste_app_menu_item = sprintf(
		'<%% if ( this.hasOption( "paste-app" ) ) { %%>
			<li><a class="et-pb-right-click-paste-app" href="#">%1$s</a></li>
		<%% } %%>',
		esc_html__( 'Paste', 'et_builder' )
	);
	$save_to_lib_menu = sprintf(
		'<%% if ( this.hasOption( "save-to-library") ) { %%>
			<li><a class="et-pb-right-click-save-to-library" href="#">%1$s</a></li>
		<%% } %%>',
		esc_html__( 'Save to Library', 'et_builder' )
	);
	$lock_unlock_menu = sprintf(
		'<%% if ( this.hasOption( "lock" ) ) { %%>
			<li><a class="et-pb-right-click-lock" href="#"><span class="unlock">%1$s</span><span class="lock">%2$s</span></a></li>
		<%% } %%>',
		esc_html__( 'Unlock', 'et_builder' ),
		esc_html__( 'Lock', 'et_builder' )
	);
	$enable_disable_menu = sprintf(
		'<%% if ( this.hasOption( "disable" ) ) { %%>
			<li><a class="et-pb-right-click-disable" href="#"><span class="enable">%1$s</span><span class="disable">%2$s</span></a>
				<span class="et_pb_disable_on_options"><span class="et_pb_disable_on_option et_pb_disable_on_phone"></span><span class="et_pb_disable_on_option et_pb_disable_on_tablet"></span><span class="et_pb_disable_on_option et_pb_disable_on_desktop"></span></span>
			</li>
		<%% } %%>',
		esc_html__( 'Enable', 'et_builder' ),
		esc_html__( 'Disable', 'et_builder' )
	);
	// Right click options Template
	printf(
		'<script type="text/template" id="et-builder-right-click-controls-template">
		<ul class="options">
			<%% if ( "module" !== this.options.model.attributes.type || _.contains( %13$s, this.options.model.attributes.module_type ) ) { %%>
				%1$s

				%8$s

				<%% if ( this.hasOption( "undo" ) ) { %%>
				<li><a class="et-pb-right-click-undo" href="#">%9$s</a></li>
				<%% } %%>

				<%% if ( this.hasOption( "redo" ) ) { %%>
				<li><a class="et-pb-right-click-redo" href="#">%10$s</a></li>
				<%% } %%>

				%2$s

				%3$s

				<%% if ( this.hasOption( "collapse" ) ) { %%>
				<li><a class="et-pb-right-click-collapse" href="#"><span class="expand">%4$s</span><span class="collapse">%5$s</span></a></li>
				<%% } %%>

				%6$s

				%7$s

				%12$s

				%11$s

			<%% } %%>

			<%% if ( this.hasOption( "preview" ) ) { %%>
			<li><a class="et-pb-right-click-preview" href="#">%14$s</a></li>
			<%% } %%>
		</ul>
		</script>',
		et_pb_is_allowed( 'edit_module' ) && ( et_pb_is_allowed( 'general_settings' ) || et_pb_is_allowed( 'advanced_settings' ) || et_pb_is_allowed( 'custom_css_settings' ) ) ? $rename_module_menu : '',
		et_pb_is_allowed( 'disable_module' ) ? $enable_disable_menu : '',
		et_pb_is_allowed( 'lock_module' ) ? $lock_unlock_menu : '',
		esc_html__( 'Expand', 'et_builder' ),
		esc_html__( 'Collapse', 'et_builder' ), //#5
		et_pb_is_allowed( 'add_module' ) ? $copy_module_menu : '',
		et_pb_is_allowed( 'add_module' ) ? $paste_after_menu : '',
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'save_library' ) ? $save_to_lib_menu : '',
		esc_html__( 'Undo', 'et_builder' ),
		esc_html__( 'Redo', 'et_builder' ), //#10
		et_pb_is_allowed( 'add_module' ) ? $paste_menu_item : '',
		et_pb_is_allowed( 'add_module' ) ? $paste_app_menu_item : '',
		et_pb_allowed_modules_list(),
		esc_html__( 'Preview', 'et_builder' )
	);

	// "Rename Module Admin Label" Modal Window Template
	printf(
		'<script type="text/template" id="et-builder-prompt-modal-rename_admin_label">
			<div class="et_pb_prompt_modal">
				<a href="#" class="et_pb_prompt_dont_proceed et-pb-modal-close">
					<span>%1$s</span>
				</a>
				<div class="et_pb_prompt_buttons">
					<br/>
					<input type="submit" class="et_pb_prompt_proceed" value="%2$s" />
				</div>
			</div>
		</script>',
		esc_html__( 'Cancel', 'et_builder' ),
		esc_attr__( 'Save', 'et_builder' )
	);

	// "Rename Module Admin Label" Modal Content Template
	printf(
		'<script type="text/template" id="et-builder-prompt-modal-rename_admin_label-text">
			<h3>%1$s</h3>
			<p>%2$s</p>

			<input type="text" value="" id="et_pb_new_admin_label" class="regular-text" />
		</script>',
		esc_html__( 'Rename', 'et_builder' ),
		esc_html__( 'Enter a new name for this module', 'et_builder' )
	);

	$save_to_lib_button = sprintf(
		'<a href="#" class="et-pb-layout-buttons et-pb-layout-buttons-save" title="%1$s">
			<span>%2$s</span>
		</a>',
		esc_attr__( 'Save to Library', 'et_builder' ),
		esc_html__( 'Save to Library', 'et_builder' )
	);
	$load_from_lib_button = sprintf(
		'<a href="#" class="et-pb-layout-buttons et-pb-layout-buttons-load" title="%1$s">
			<span>%2$s</span>
		</a>',
		esc_attr__( 'Load From Library', 'et_builder' ),
		esc_html__( 'Load From Library', 'et_builder' )
	);
	$clear_layout_button = sprintf(
		'<a href="#" class="et-pb-layout-buttons et-pb-layout-buttons-clear" title="%1$s">
			<span>%2$s</span>
		</a>',
		esc_attr__( 'Clear Layout', 'et_builder' ),
		esc_html__( 'Clear Layout', 'et_builder' )
	);
	// App Template
	printf(
		'<script type="text/template" id="et-builder-app-template">
			<div id="et_pb_layout_controls">

				%1$s

				%2$s

				%3$s

				<a href="#" class="et-pb-layout-buttons et-pb-layout-buttons-history" title="%8$s">
					<span class="icon"></span><span class="label">%9$s</span>
				</a>

				<a href="#" class="et-pb-layout-buttons et-pb-layout-buttons-redo" title="%4$s">
					<span class="icon"></span><span class="label">%5$s</span>
				</a>

				<a href="#" class="et-pb-layout-buttons et-pb-layout-buttons-undo" title="%6$s">
					<span class="icon"></span><span class="label">%7$s</span>
				</a>
			</div>
			<div id="et-pb-histories-visualizer-overlay"></div>
			<ol id="et-pb-histories-visualizer"></ol>
		</script>',
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'save_library' ) ? $save_to_lib_button : '',
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'load_layout' ) && et_pb_is_allowed( 'add_library' ) && et_pb_is_allowed( 'add_module' ) ? $load_from_lib_button : '',
		et_pb_is_allowed( 'add_module' ) ? $clear_layout_button : '',
		esc_attr__( 'Redo', 'et_builder' ),
		esc_html__( 'Redo', 'et_builder' ),
		esc_attr__( 'Undo', 'et_builder' ),
		esc_html__( 'Undo', 'et_builder' ),
		esc_attr__( 'See History', 'et_builder' ),
		esc_html__( 'See History', 'et_builder' )
	);

	$section_settings_button = sprintf(
		'<%% if ( ( typeof et_pb_template_type === \'undefined\' || \'section\' === et_pb_template_type || \'\' === et_pb_template_type )%3$s ) { %%>
			<a href="#" class="et-pb-settings et-pb-settings-section" title="%1$s"><span>%2$s</span></a>
		<%% } %%>',
		esc_attr__( 'Settings', 'et_builder' ),
		esc_html__( 'Settings', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? ' && typeof et_pb_global_module === "undefined"' : '' // do not display settings on global sections if not allowed for current user
	);
	$section_clone_button = sprintf(
		'<a href="#" class="et-pb-clone et-pb-clone-section" title="%1$s"><span>%2$s</span></a>',
		esc_attr__( 'Clone Section', 'et_builder' ),
		esc_html__( 'Clone Section', 'et_builder' )
	);
	$section_remove_button = sprintf(
		'<a href="#" class="et-pb-remove et-pb-remove-section" title="%1$s"><span>%2$s</span></a>',
		esc_attr__( 'Delete Section', 'et_builder' ),
		esc_html__( 'Delete Section', 'et_builder' )
	);
	$section_unlock_button = sprintf(
		'<a href="#" class="et-pb-unlock" title="%1$s"><span>%2$s</span></a>',
		esc_attr__( 'Unlock Section', 'et_builder' ),
		esc_html__( 'Unlock Section', 'et_builder' )
	);
	// Section Template
	$settings_controls = sprintf(
		'<div class="et-pb-controls">
			%1$s

			<%% if ( typeof et_pb_template_type === \'undefined\' || ( \'section\' !== et_pb_template_type && \'row\' !== et_pb_template_type && \'module\' !== et_pb_template_type ) ) { %%>
				%2$s
				%3$s
			<%% } %%>

			<a href="#" class="et-pb-expand" title="%4$s"><span>%5$s</span></a>
			%6$s
		</div>',
		et_pb_is_allowed( 'edit_module' ) && ( et_pb_is_allowed( 'general_settings' ) || et_pb_is_allowed( 'advanced_settings' ) || et_pb_is_allowed( 'custom_css_settings' ) ) ? $section_settings_button : '',
		et_pb_is_allowed( 'add_module' ) ? $section_clone_button : '',
		et_pb_is_allowed( 'add_module' ) ? $section_remove_button : '',
		esc_attr__( 'Expand Section', 'et_builder' ),
		esc_html__( 'Expand Section', 'et_builder' ),
		et_pb_is_allowed( 'lock_module' ) ? $section_unlock_button : ''
	);

	$add_from_lib_section = sprintf(
		'<span class="et-pb-section-add-saved">%1$s</span>',
		esc_html__( 'Add From Library', 'et_builder' )
	);

	$add_standard_section_button = sprintf(
		'<span class="et-pb-section-add-main">%1$s</span>',
		esc_html__( 'Standard Section', 'et_builder' )
	);
	$add_standard_section_button = apply_filters( 'et_builder_add_main_section_button', $add_standard_section_button );

	$add_fullwidth_section_button = sprintf(
		'<span class="et-pb-section-add-fullwidth">%1$s</span>',
		esc_html__( 'Fullwidth Section', 'et_builder' )
	);
	$add_fullwidth_section_button = apply_filters( 'et_builder_add_fullwidth_section_button', $add_fullwidth_section_button );

	$add_specialty_section_button = sprintf(
		'<span class="et-pb-section-add-specialty">%1$s</span>',
		esc_html__( 'Specialty Section', 'et_builder' )
	);
	$add_specialty_section_button = apply_filters( 'et_builder_add_specialty_section_button', $add_specialty_section_button );

	$settings_add_controls = sprintf(
		'<%% if ( typeof et_pb_template_type === \'undefined\' || ( \'section\' !== et_pb_template_type && \'row\' !== et_pb_template_type && \'module\' !== et_pb_template_type ) ) { %%>
			<a href="#" class="et-pb-section-add">
				%1$s
				%2$s
				%3$s
				%4$s
			</a>
		<%% } %%>',
		$add_standard_section_button,
		$add_fullwidth_section_button,
		$add_specialty_section_button,
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'add_library' ) ? $add_from_lib_section : ''
	);

	printf(
		'<script type="text/template" id="et-builder-section-template">
			<div class="et-pb-right-click-trigger-overlay"></div>
			%1$s
			<div class="et-pb-section-content et-pb-data-cid%3$s%4$s" data-cid="<%%= cid %%>" data-skip="<%%= typeof( et_pb_skip_module ) === \'undefined\' ? \'false\' : \'true\' %%>">
			</div>
			%2$s
			<div class="et-pb-locked-overlay et-pb-locked-overlay-section"></div>
			<span class="et-pb-section-title"><%%= admin_label.replace( /%%22/g, "&quot;" ) %%></span>
		</script>',
		apply_filters( 'et_builder_section_settings_controls', $settings_controls ),
		et_pb_is_allowed( 'add_module' ) ? apply_filters( 'et_builder_section_add_controls', $settings_add_controls ) : '',
		! et_pb_is_allowed( 'move_module' ) ? ' et-pb-disable-sort' : '',
		! et_pb_is_allowed( 'edit_global_library' )
			? sprintf( '<%%= typeof et_pb_global_module !== \'undefined\' ? \' et-pb-disable-sort\' : \'\' %%>' )
			: ''
	);

	$row_settings_button = sprintf(
		'<%% if ( ( typeof et_pb_template_type === \'undefined\' || et_pb_template_type !== \'module\' )%3$s ) { %%>
			<a href="#" class="et-pb-settings et-pb-settings-row" title="%1$s"><span>%2$s</span></a>
		<%% } %%>',
		esc_attr__( 'Settings', 'et_builder' ),
		esc_html__( 'Settings', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? ' && ( typeof et_pb_global_module === "undefined" || "" === et_pb_global_module ) && ( typeof et_pb_global_parent === "undefined" || "" === et_pb_global_parent )' : '' // do not display settings button on global rows if not allowed for current user
	);
	$row_clone_button = sprintf(
		'%3$s
			<a href="#" class="et-pb-clone et-pb-clone-row" title="%1$s"><span>%2$s</span></a>
		%4$s',
		esc_attr__( 'Clone Row', 'et_builder' ),
		esc_html__( 'Clone Row', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? '<% if ( typeof et_pb_global_parent === "undefined" || "" === et_pb_global_parent ) { %>' : '', // do not display clone button on rows within global sections if not allowed for current user
		! et_pb_is_allowed( 'edit_global_library' ) ? '<% } %>' : ''
	);
	$row_remove_button = sprintf(
		'%3$s
			<a href="#" class="et-pb-remove et-pb-remove-row" title="%1$s"><span>%2$s</span></a>
		%4$s',
		esc_attr__( 'Delete Row', 'et_builder' ),
		esc_html__( 'Delete Row', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? '<% if ( typeof et_pb_global_parent === "undefined" || "" === et_pb_global_parent ) { %>' : '', // do not display clone button on rows within global sections if not allowed for current user
		! et_pb_is_allowed( 'edit_global_library' ) ? '<% } %>' : ''
	);
	$row_change_structure_button = sprintf(
		'%3$s
			<a href="#" class="et-pb-change-structure" title="%1$s"><span>%2$s</span></a>
		%4$s',
		esc_attr__( 'Change Structure', 'et_builder' ),
		esc_html__( 'Change Structure', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? '<% if ( ( typeof et_pb_global_module === "undefined" || "" === et_pb_global_module ) && ( typeof et_pb_global_parent === "undefined" || "" === et_pb_global_parent ) ) { %>' : '', // do not display change structure button on global rows if not allowed for current user
		! et_pb_is_allowed( 'edit_global_library' ) ? '<% } %>' : ''
	);
	$row_unlock_button = sprintf(
		'<a href="#" class="et-pb-unlock" title="%1$s"><span>%2$s</span></a>',
		esc_attr__( 'Unlock Row', 'et_builder' ),
		esc_html__( 'Unlock Row', 'et_builder' )
	);
	// Row Template
	$settings = sprintf(
		'<div class="et-pb-controls">
			%1$s
		<%% if ( typeof et_pb_template_type === \'undefined\' || \'section\' === et_pb_template_type ) { %%>
			%2$s
		<%% }

		if ( typeof et_pb_template_type === \'undefined\' || et_pb_template_type !== \'module\' ) { %%>
			%4$s
		<%% }

		if ( typeof et_pb_template_type === \'undefined\' || \'section\' === et_pb_template_type ) { %%>
			%3$s
		<%% } %%>

		<a href="#" class="et-pb-expand" title="%5$s"><span>%6$s</span></a>
		%7$s
		</div>',
		et_pb_is_allowed( 'edit_module' ) && ( et_pb_is_allowed( 'general_settings' ) || et_pb_is_allowed( 'advanced_settings' ) || et_pb_is_allowed( 'custom_css_settings' ) ) ? $row_settings_button : '',
		et_pb_is_allowed( 'add_module' ) ? $row_clone_button : '',
		et_pb_is_allowed( 'add_module' ) ? $row_remove_button : '',
		et_pb_is_allowed( 'edit_module' ) && ( et_pb_is_allowed( 'general_settings' ) || et_pb_is_allowed( 'advanced_settings' ) || et_pb_is_allowed( 'custom_css_settings' ) ) ? $row_change_structure_button : '',
		esc_attr__( 'Expand Row', 'et_builder' ),
		esc_html__( 'Expand Row', 'et_builder' ),
		et_pb_is_allowed( 'lock_module' ) ? $row_unlock_button : ''
	);

	$row_class = sprintf(
		'class="et-pb-row-content et-pb-data-cid%1$s%2$s <%%= typeof et_pb_template_type !== \'undefined\' && \'module\' === et_pb_template_type ? \' et_pb_hide_insert\' : \'\' %%>"',
		! et_pb_is_allowed( 'move_module' ) ? ' et-pb-disable-sort' : '',
		! et_pb_is_allowed( 'edit_global_library' )
			? sprintf( '<%%= typeof et_pb_global_parent !== \'undefined\' || typeof et_pb_global_module !== \'undefined\' ? \' et-pb-disable-sort\' : \'\' %%>' )
			: ''
	);

	$data_skip = 'data-skip="<%= typeof( et_pb_skip_module ) === \'undefined\' ? \'false\' : \'true\' %>"';

	$add_row_button = sprintf(
		'<%% if ( ( typeof et_pb_template_type === \'undefined\' || \'section\' === et_pb_template_type )%2$s ) { %%>
			<a href="#" class="et-pb-row-add">
				<span>%1$s</span>
			</a>
		<%% } %%>',
		esc_html__( 'Add Row', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? ' && typeof et_pb_global_parent === "undefined"' : '' // do not display add row buton on global sections if not allowed for current user
	);

	$insert_column_button = sprintf(
		'<a href="#" class="et-pb-insert-column">
			<span>%1$s</span>
		</a>',
		esc_html__( 'Insert Column(s)', 'et_builder' )
	);

	printf(
		'<script type="text/template" id="et-builder-row-template">
			<div class="et-pb-right-click-trigger-overlay"></div>
			%1$s
			<div data-cid="<%%= cid %%>" %2$s %3$s>
				<div class="et-pb-row-container"></div>
				%4$s
			</div>
			%5$s
			<div class="et-pb-locked-overlay et-pb-locked-overlay-row"></div>
			<span class="et-pb-row-title"><%%= admin_label.replace( /%%22/g, "&quot;" ) %%></span>
		</script>',
		apply_filters( 'et_builder_row_settings_controls', $settings ),
		$row_class,
		$data_skip,
		et_pb_is_allowed( 'add_module' ) ? $insert_column_button : '',
		et_pb_is_allowed( 'add_module' ) ? $add_row_button : ''
	);


	// Module Block Template
	$clone_button = sprintf(
		'<%% if ( ( typeof et_pb_template_type === \'undefined\' || et_pb_template_type !== \'module\' )%3$s && _.contains(%4$s, module_type) ) { %%>
			<a href="#" class="et-pb-clone et-pb-clone-module" title="%1$s">
				<span>%2$s</span>
			</a>
		<%% } %%>',
		esc_attr__( 'Clone Module', 'et_builder' ),
		esc_html__( 'Clone Module', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? ' &&  ( typeof et_pb_global_parent === "undefined" || "" === et_pb_global_parent )' : '',
		et_pb_allowed_modules_list()
	);
	$remove_button = sprintf(
		'<%% if ( ( typeof et_pb_template_type === \'undefined\' || et_pb_template_type !== \'module\' )%3$s && _.contains(%4$s, module_type) ) { %%>
			<a href="#" class="et-pb-remove et-pb-remove-module" title="%1$s">
				<span>%2$s</span>
			</a>
		<%% } %%>',
		esc_attr__( 'Remove Module', 'et_builder' ),
		esc_html__( 'Remove Module', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? ' &&  ( typeof et_pb_global_parent === "undefined" || "" === et_pb_global_parent )' : '',
		et_pb_allowed_modules_list()
	);
	$unlock_button = sprintf(
		'<%% if ( typeof et_pb_template_type === \'undefined\' || et_pb_template_type !== \'module\' ) { %%>
			<a href="#" class="et-pb-unlock" title="%1$s">
				<span>%2$s</span>
			</a>
		<%% } %%>',
		esc_html__( 'Unlock Module', 'et_builder' ),
		esc_attr__( 'Unlock Module', 'et_builder' )
	);
	$settings_button = sprintf(
		'<%% if (%3$s _.contains( %4$s, module_type ) ) { %%>
			<a href="#" class="et-pb-settings" title="%1$s">
				<span>%2$s</span>
			</a>
		<%% } %%>',
		esc_attr__( 'Module Settings', 'et_builder' ),
		esc_html__( 'Module Settings', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? ' ( typeof et_pb_global_parent === "undefined" || "" === et_pb_global_parent ) && ( typeof et_pb_global_module === "undefined" || "" === et_pb_global_module ) &&' : '',
		et_pb_allowed_modules_list()
	);

	printf(
		'<script type="text/template" id="et-builder-block-module-template">
			%1$s
			%2$s
			%3$s
			%4$s
			<span class="et-pb-module-title"><%%= admin_label.replace( /%%22/g, "&quot;" ) %%></span>
		</script>',
		et_pb_is_allowed( 'edit_module' ) && ( et_pb_is_allowed( 'general_settings' ) || et_pb_is_allowed( 'advanced_settings' ) || et_pb_is_allowed( 'custom_css_settings' ) ) ? $settings_button : '',
		et_pb_is_allowed( 'add_module' ) ? $clone_button : '',
		et_pb_is_allowed( 'add_module' ) ? $remove_button : '',
		et_pb_is_allowed( 'lock_module' ) ? $unlock_button : ''
	);


	// Modal Template
	$save_exit_button = sprintf(
		'<a href="#" class="et-pb-modal-save button button-primary">
			<span>%1$s</span>
		</a>',
		esc_html__( 'Save & Exit', 'et_builder' )
	);

	$save_template_button = sprintf(
		'<%% if ( typeof et_pb_template_type === \'undefined\' || \'\' === et_pb_template_type ) { %%>
			<a href="#" class="et-pb-modal-save-template button">
				<span>%1$s</span>
			</a>
		<%% } %%>',
		esc_html__( 'Save & Add To Library', 'et_builder' )
	);

	$preview_template_button = sprintf(
		'<a href="#" class="et-pb-modal-preview-template button">
			<span class="icon"></span>
			<span class="label">%1$s</span>
		</a>',
		esc_html__( 'Preview', 'et_builder' )
	);

	$can_edit_or_has_modal_view_tab = et_pb_is_allowed( 'edit_module' ) && ( et_pb_is_allowed( 'general_settings' ) || et_pb_is_allowed( 'advanced_settings' ) || et_pb_is_allowed( 'custom_css_settings' ) );

	printf(
		'<script type="text/template" id="et-builder-modal-template">
			<div class="et-pb-modal-container%6$s">

				<a href="#" class="et-pb-modal-close">
					<span>%1$s</span>
				</a>

			<%% if ( ! ( typeof open_view !== \'undefined\' && open_view === \'column_specialty_settings\' ) && typeof type !== \'undefined\' && ( type === \'module\' || type === \'section\' || type === \'row_inner\' || ( type === \'row\' && typeof open_view === \'undefined\' ) ) ) { %%>
				<div class="et-pb-modal-bottom-container%4$s">
					%2$s
					%5$s
					%3$s
				</div>
			<%% } %%>

			</div>
		</script>',
		esc_html__( 'Cancel', 'et_builder' ),
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'save_library' ) ? $save_template_button : '',
		$can_edit_or_has_modal_view_tab ? $save_exit_button : '',
		! et_pb_is_allowed( 'divi_library' ) || ! et_pb_is_allowed( 'save_library' ) ? ' et_pb_single_button' : '',
		$preview_template_button,
		$can_edit_or_has_modal_view_tab ? '' : ' et_pb_no_editing'
	);


	// Column Settings Template
	$columns_number =
		'<% if ( view.model.attributes.specialty_columns === 3 ) { %>
			3
		<% } else { %>
			2
		<% } %>';
	$data_specialty_columns = sprintf(
		'<%% if ( typeof view !== \'undefined\' && typeof view.model.attributes.specialty_columns !== \'undefined\' ) { %%>
			data-specialty_columns="%1$s"
		<%% } %%>',
		$columns_number
	);

	$saved_row_tab = sprintf(
		'<li class="et-pb-saved-module" data-open_tab="et-pb-saved-modules-tab">
			<a href="#">%1$s</a>
		</li>',
		esc_html__( 'Add From Library', 'et_builder' )
	);
	$saved_row_container = '<% if ( ( typeof change_structure === \'undefined\' || \'true\' !== change_structure ) && ( typeof et_pb_specialty === \'undefined\' || et_pb_specialty !== \'on\' ) ) { %>
								<div class="et-pb-main-settings et-pb-main-settings-full et-pb-saved-modules-tab"></div>
							<% } %>';
	printf(
		'<script type="text/template" id="et-builder-column-settings-template">

			<h3 class="et-pb-settings-heading" data-current_row="<%%= cid %%>">%1$s</h3>

		<%% if ( ( typeof change_structure === \'undefined\' || \'true\' !== change_structure ) && ( typeof et_pb_specialty === \'undefined\' || et_pb_specialty !== \'on\' ) ) { %%>
			<ul class="et-pb-options-tabs-links et-pb-saved-modules-switcher" %2$s>
				<li class="et-pb-saved-module et-pb-options-tabs-links-active" data-open_tab="et-pb-new-modules-tab" data-content_loaded="true">
					<a href="#">%3$s</a>
				</li>
				%4$s
			</ul>
		<%% } %%>

			<div class="et-pb-main-settings et-pb-main-settings-full et-pb-new-modules-tab active-container">
				<ul class="et-pb-column-layouts">
					%5$s
				</ul>
			</div>

			%6$s

		</script>',
		esc_html__( 'Insert Columns', 'et_builder' ),
		$data_specialty_columns,
		esc_html__( 'New Row', 'et_builder' ),
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'add_library' ) ? $saved_row_tab : '',
		et_builder_get_columns_layout(),
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'add_library' ) ? $saved_row_container : ''
	);

	// "Add Module" Template
	$fullwidth_class =
		'<% if ( typeof module.fullwidth_only !== \'undefined\' && module.fullwidth_only === \'on\' ) { %> et_pb_fullwidth_only_module<% } %>';
	$saved_modules_tab = sprintf(
		'<li class="et-pb-saved-module" data-open_tab="et-pb-saved-modules-tab">
			<a href="#">%1$s</a>
		</li>',
		esc_html__( 'Add From Library', 'et_builder' )
	);
	$saved_modules_container = '<div class="et-pb-main-settings et-pb-main-settings-full et-pb-saved-modules-tab"></div>';
	printf(
		'<script type="text/template" id="et-builder-modules-template">
			<h3 class="et-pb-settings-heading">%1$s</h3>

			<ul class="et-pb-options-tabs-links et-pb-saved-modules-switcher">
				<li class="et-pb-new-module et-pb-options-tabs-links-active" data-open_tab="et-pb-all-modules-tab">
					<a href="#">%2$s</a>
				</li>

				%3$s
			</ul>

			<div class="et-pb-main-settings et-pb-main-settings-full et-pb-all-modules-tab active-container">
				<ul class="et-pb-all-modules">
				<%% _.each(modules, function(module) { %%>
					<%% if ( "et_pb_row" !== module.label && "et_pb_section" !== module.label && "et_pb_column" !== module.label && "et_pb_row_inner" !== module.label && _.contains(%6$s, module.label ) ) { %%>
						<li class="<%%= module.label %%>%4$s">
							<span class="et_module_title"><%%= module.title %%></span>
						</li>
					<%% } %%>
				<%% }); %%>
				</ul>
			</div>

			%5$s
		</script>',
		esc_html__( 'Insert Module', 'et_builder' ),
		esc_html__( 'New Module', 'et_builder' ),
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'add_library' ) ? $saved_modules_tab : '',
		$fullwidth_class,
		et_pb_is_allowed( 'divi_library' ) && et_pb_is_allowed( 'add_library' ) ? $saved_modules_container : '',
		et_pb_allowed_modules_list()
	);


	// Load Layout Template
	printf(
		'<script type="text/template" id="et-builder-load_layout-template">
			<h3 class="et-pb-settings-heading">%1$s</h3>

		<%% if ( typeof display_switcher !== \'undefined\' && display_switcher === \'on\' ) { %%>
			<ul class="et-pb-options-tabs-links et-pb-saved-modules-switcher">
				<li class="et-pb-new-module et-pb-options-tabs-links-active" data-open_tab="et-pb-all-modules-tab">
					<a href="#">%2$s</a>
				</li>
				<li class="et-pb-saved-module" data-open_tab="et-pb-saved-modules-tab">
					<a href="#">%3$s</a>
				</li>
			</ul>
		<%% } %%>

		<%% if ( typeof display_switcher !== \'undefined\' && display_switcher === \'on\' ) { %%>
			<div class="et-pb-main-settings et-pb-main-settings-full et-pb-all-modules-tab active-container"></div>
			<div class="et-pb-main-settings et-pb-main-settings-full et-pb-saved-modules-tab" style="display: none;"></div>
		<%% } else { %%>
			<div class="et-pb-main-settings et-pb-main-settings-full et-pb-saved-modules-tab active-container"></div>
		<%% } %%>
		</script>',
		esc_html__( 'Load Layout', 'et_builder' ),
		esc_html__( 'Predefined Layouts', 'et_builder' ),
		esc_html__( 'Add From Library', 'et_builder' )
	);

	$insert_module_button = sprintf(
		'%2$s
		<a href="#" class="et-pb-insert-module<%%= typeof et_pb_template_type === \'undefined\' || \'module\' !== et_pb_template_type ? \'\' : \' et_pb_hidden_button\' %%>">
			<span>%1$s</span>
		</a>
		%3$s',
		esc_html__( 'Insert Module(s)', 'et_builder' ),
		! et_pb_is_allowed( 'edit_global_library' ) ? '<% if ( typeof et_pb_global_parent === "undefined" ) { %>' : '',
		! et_pb_is_allowed( 'edit_global_library' ) ? '<% } %>' : ''
	);
	// Column Template
	printf(
		'<script type="text/template" id="et-builder-column-template">
			%1$s
		</script>',
		et_pb_is_allowed( 'add_module' ) ? $insert_module_button : ''
	);


	// Advanced Settings Buttons Module
	printf(
		'<script type="text/template" id="et-builder-advanced-setting">
			<a href="#" class="et-pb-advanced-setting-remove">
				<span>%1$s</span>
			</a>

			<a href="#" class="et-pb-advanced-setting-options">
				<span>%2$s</span>
			</a>

			<a href="#" class="et-pb-clone et-pb-advanced-setting-clone">
				<span>%3$s</span>
			</a>
		</script>',
		esc_html__( 'Delete', 'et_builder' ),
		esc_html__( 'Settings', 'et_builder' ),
		esc_html__( 'Clone Module', 'et_builder' )
	);

	// Advanced Settings Modal Buttons Template
	printf(
		'<script type="text/template" id="et-builder-advanced-setting-edit">
			<div class="et-pb-modal-container">
				<a href="#" class="et-pb-modal-close">
					<span>%1$s</span>
				</a>

				<div class="et-pb-modal-bottom-container">
					<a href="#" class="et-pb-modal-save">
						<span>%2$s</span>
					</a>
				</div>
			</div>
		</script>',
		esc_html__( 'Cancel', 'et_builder' ),
		esc_html__( 'Save', 'et_builder' )
	);


	// "Deactivate Builder" Modal Message Template
	printf(
		'<script type="text/template" id="et-builder-prompt-modal-deactivate_builder-text">
			<h3>%1$s</h3>
			<p>%2$s</p>
			<p>%3$s</p>
		</script>',
		esc_html__( 'Disable Builder', 'et_builder' ),
		esc_html__( 'All content created in the Divi Builder will be lost. Previous content will be restored.', 'et_builder' ),
		esc_html__( 'Do you wish to proceed?', 'et_builder' )
	);


	// "Clear Layout" Modal Window Template
	printf(
		'<script type="text/template" id="et-builder-prompt-modal-clear_layout-text">
			<h3>%1$s</h3>
			<p>%2$s</p>
			<p>%3$s</p>
		</script>',
		esc_html__( 'Clear Layout', 'et_builder' ),
		esc_html__( 'All of your current page content will be lost.', 'et_builder' ),
		esc_html__( 'Do you wish to proceed?', 'et_builder' )
	);


	// "Reset Advanced Settings" Modal Template
	printf(
		'<script type="text/template" id="et-builder-prompt-modal-reset_advanced_settings-text">
			<p>%1$s</p>
			<p>%2$s</p>
		</script>',
		esc_html__( 'All advanced module settings in will be lost.', 'et_builder' ),
		esc_html__( 'Do you wish to proceed?', 'et_builder' )
	);


	// "Save Layout" Modal Window Template
	printf(
		'<script type="text/template" id="et-builder-prompt-modal-save_layout">
			<div class="et_pb_prompt_modal">
				<a href="#" class="et_pb_prompt_dont_proceed et-pb-modal-close">
					<span>%1$s</span>
				</a>
				<div class="et_pb_prompt_buttons">
					<br/>
					<input type="submit" class="et_pb_prompt_proceed" value="%2$s" />
				</div>
			</div>
		</script>',
		esc_html__( 'Cancel', 'et_builder' ),
		esc_html__( 'Save', 'et_builder' )
	);


	// "Save Layout" Modal Content Template
	printf(
		'<script type="text/template" id="et-builder-prompt-modal-save_layout-text">
			<h3>%1$s</h3>
			<p>%2$s</p>

			<label>%3$s</label>
			<input type="text" value="" id="et_pb_new_layout_name" class="regular-text" />
		</script>',
		esc_html__( 'Save To Library', 'et_builder' ),
		esc_html__( 'Save your current page to the Divi Library for later use.', 'et_builder' ),
		esc_html__( 'Layout Name:', 'et_builder' )
	);


	// "Save Template" Modal Window Layout
	printf(
		'<script type="text/template" id="et-builder-prompt-modal-save_template">
			<div class="et_pb_prompt_modal et_pb_prompt_modal_save_library">
				<div class="et_pb_prompt_buttons">
					<br/>
					<input type="submit" class="et_pb_prompt_proceed" value="%1$s" />
				</div>
			</div>
		</script>',
		esc_attr__( 'Save And Add To Library', 'et_builder' )
	);


	// "Save Template" Content Layout
	$layout_categories = get_terms( 'layout_category', array( 'hide_empty' => false ) );
	$categories_output = sprintf( '<div class="et-pb-option"><label>%1$s</label>',
		esc_html__( 'Add To Categories:', 'et_builder' )
	);

	if ( is_array( $layout_categories ) && ! empty( $layout_categories ) ) {
		$categories_output .= '<div class="et-pb-option-container layout_cats_container">';
		foreach( $layout_categories as $category ) {
			$categories_output .= sprintf( '<label>%1$s<input type="checkbox" value="%2$s"/></label>',
				esc_html( $category->name ),
				esc_attr( $category->term_id )
			);
		}
		$categories_output .= '</div></div>';
	}

	$categories_output .= sprintf( '
		<div class="et-pb-option">
			<label>%1$s:</label>
			<div class="et-pb-option-container">
				<input type="text" value="" id="et_pb_new_cat_name" class="regular-text" />
			</div>
		</div>',
		esc_html__( 'Create New Category', 'et_builder' )
	);

	$general_checkbox = sprintf(
		'<label>
			%1$s <input type="checkbox" value="general" id="et_pb_template_general" checked />
		</label>',
		esc_html__( 'Include General settings', 'et_builder' )
	);
	$advanced_checkbox = sprintf(
		'<label>
			%1$s <input type="checkbox" value="advanced" id="et_pb_template_advanced" checked />
		</label>',
		esc_html__( 'Include Advanced Design settings', 'et_builder' )
	);
	$css_checkbox = sprintf(
		'<label>
			%1$s <input type="checkbox" value="css" id="et_pb_template_css" checked />
		</label>',
		esc_html__( 'Include Custom CSS', 'et_builder' )
	);

	printf(
		'<script type="text/template" id="et-builder-prompt-modal-save_template-text">
			<div class="et-pb-main-settings">
				<p>%1$s</p>

				<div class="et-pb-option">
					<label>%2$s:</label>

					<div class="et-pb-option-container">
						<input type="text" value="" id="et_pb_new_template_name" class="regular-text" />
					</div>
				</div>

			<%% if ( \'module\' === module_type ) { %%>
				<div class="et-pb-option">
					<label>%3$s:</label>

					<div class="et-pb-option-container et_pb_select_module_tabs">
						%4$s

						%5$s

						%6$s
						<p class="et_pb_error_message_save_template" style="display: none;">
							%7$s
						</p>
					</div>
				</div>
			<%% } %%>

			<%% if ( \'global\' !== is_global && \'global\' !== is_global_child ) { %%>
				<div class="et-pb-option">
					<label>%8$s</label>

					<div class="et-pb-option-container">
						<label>
							%9$s <input type="checkbox" value="" id="et_pb_template_global" />
						</label>
					</div>
				</div>
			<%% } %%>

				%10$s
			</div>
		</script>',
		esc_html__( 'Here you can save the current item and add it to your Divi Library for later use as well.', 'et_builder' ),
		esc_html__( 'Template Name', 'et_builder' ),
		esc_html__( 'Selective Sync', 'et_builder' ),
		et_pb_is_allowed( 'general_settings' ) ? $general_checkbox : '',
		et_pb_is_allowed( 'advanced_settings' ) ? $advanced_checkbox : '',
		et_pb_is_allowed( 'custom_css_settings' ) ? $css_checkbox : '',
		esc_html__( 'Please select at least 1 tab to save', 'et_builder' ),
		esc_html__( 'Save as Global:', 'et_builder' ),
		esc_html__( 'Make this a global item', 'et_builder' ),
		$categories_output
	);


	// Prompt Modal Window Template
	printf(
		'<script type="text/template" id="et-builder-prompt-modal">
			<div class="et_pb_prompt_modal">
				<a href="#" class="et_pb_prompt_dont_proceed et-pb-modal-close">
					<span>%1$s<span>
				</a>

				<div class="et_pb_prompt_buttons">
					<a href="#" class="et_pb_prompt_proceed">%2$s</a>
				</div>
			</div>
		</script>',
		esc_html__( 'No', 'et_builder' ),
		esc_html__( 'Yes', 'et_builder' )
	);


	// "Add Specialty Section" Button Template
	printf(
		'<script type="text/template" id="et-builder-add-specialty-section-button">
			<a href="#" class="et-pb-section-add-specialty et-pb-add-specialty-template" data-is_template="true">%1$s</a>
		</script>',
		esc_html__( 'Add Specialty Section', 'et_builder' )
	);


	// Saved Entry Template
	echo
		'<script type="text/template" id="et-builder-saved-entry">
			<a class="et_pb_saved_entry_item"><%= title %></a>
		</script>';


	// Font Icons Template
	printf(
		'<script type="text/template" id="et-builder-google-fonts-options-items">
			%1$s
		</script>',
		et_builder_get_font_options_items()
	);


	// Font Icons Template
	printf(
		'<script type="text/template" id="et-builder-font-icon-list-items">
			%1$s
		</script>',
		et_pb_get_font_icon_list_items()
	);

	// Histories Visualizer Item Template
	printf(
		'<script type="text/template" id="et-builder-histories-visualizer-item-template">
			<li id="et-pb-history-<%%= this.options.get( "timestamp" ) %%>" class="<%%= this.options.get( "current_active_history" ) ? "active" : "undo"  %%>" data-timestamp="<%%= this.options.get( "timestamp" )  %%>">
				<span class="datetime"><%%= this.options.get( "datetime" )  %%></span>
				<span class="verb"> <%%= this.getVerb()  %%></span>
				<span class="noun"> <%%= this.getNoun()  %%></span>
				<%% if ( typeof this.getAddition === "function" && "" !== this.getAddition() ) { %%>
					<span class="addition"> <%%= this.getAddition() %%></span>
				<%% } %%>
			</li>
		</script>'
	);

	// Font Down Icons Template
	printf(
		'<script type="text/template" id="et-builder-font-down-icon-list-items">
			%1$s
		</script>',
		et_pb_get_font_down_icon_list_items()
	);

	printf(
		'<script type="text/template" id="et-builder-preview-icons-template">
			<ul class="et-pb-preview-screensize-switcher">
				<li><a href="#" class="et-pb-preview-mobile" data-width="375"><span class="label">%1$s</span></a></li>
				<li><a href="#" class="et-pb-preview-tablet" data-width="768"><span class="label">%2$s</span></a></li>
				<li><a href="#" class="et-pb-preview-desktop active"><span class="label">%3$s</span></a></li>
			</ul>
		</script>',
		esc_html__( 'Mobile', 'et_builder' ),
		esc_html__( 'Tablet', 'et_builder' ),
		esc_html__( 'Desktop', 'et_builder' )
	);

	printf(
		'<script type="text/template" id="et-builder-options-tabs-links-template">
			<ul class="et-pb-options-tabs-links">
				<%% _.each(this.et_builder_template_options.tabs.options, function(tab, index) { %%>
					<li class="et_pb_options_tab_<%%= tab.slug %%><%%= \'1\' === index ? \' et-pb-options-tabs-links-active\' : \'\' %%>">
						<a href="#"><%%= tab.label %%></a>
					</li>
				<%% }); %%>
			</ul>
		</script>'
	);

	printf(
		'<script type="text/template" id="et-builder-mobile-options-tabs-template">
			<div class="et_pb_mobile_settings_tabs">
				<a href="#" class="et_pb_mobile_settings_tab et_pb_mobile_settings_active_tab" data-settings_tab="desktop">
					%1$s
				</a>
				<a href="#" class="et_pb_mobile_settings_tab" data-settings_tab="tablet">
					%2$s
				</a>
				<a href="#" class="et_pb_mobile_settings_tab" data-settings_tab="phone">
					%3$s
				</a>
			</div>
		</script>',
		esc_html__( 'Desktop', 'et_builder' ),
		esc_html__( 'Tablet', 'et_builder' ),
		esc_html__( 'Smartphone', 'et_builder' )
	);

	printf(
		'<script type="text/template" id="et-builder-padding-inputs-template">
			<label>
				<%%= this.et_builder_template_options.padding.options.label %%>
				<input type="text" class="et_custom_margin et_custom_margin_<%%= this.et_builder_template_options.padding.options.side %%><%%= this.et_builder_template_options.padding.options.class %%><%%= \'need_mobile\' === this.et_builder_template_options.padding.options.need_mobile ? \' et_pb_setting_mobile et_pb_setting_mobile_desktop et_pb_setting_mobile_active\' : \'\' %%>"<%%= \'need_mobile\' === this.et_builder_template_options.padding.options.need_mobile ? \' data-device="desktop"\' : \'\' %%> />
				<%% if ( \'need_mobile\' === this.et_builder_template_options.padding.options.need_mobile ) { %%>
					<input type="text" class="et_custom_margin et_pb_setting_mobile et_pb_setting_mobile_tablet et_custom_margin_<%%= this.et_builder_template_options.padding.options.side %%><%%= this.et_builder_template_options.padding.options.class %%>" data-device="tablet" />
					<input type="text" class="et_custom_margin et_pb_setting_mobile et_pb_setting_mobile_phone et_custom_margin_<%%= this.et_builder_template_options.padding.options.side %%><%%= this.et_builder_template_options.padding.options.class %%>" data-device="phone" />
				<%% } %%>
			</label>
		</script>'
	);

	printf(
		'<script type="text/template" id="et-builder-yes-no-button-template">
			<div class="et_pb_yes_no_button et_pb_off_state">
				<span class="et_pb_value_text et_pb_on_value"><%%= this.et_builder_template_options.yes_no_button.options.on %%></span>
				<span class="et_pb_button_slider"></span>
				<span class="et_pb_value_text et_pb_off_value"><%%= this.et_builder_template_options.yes_no_button.options.off %%></span>
			</div>
		</script>'
	);

	printf(
		'<script type="text/template" id="et-builder-font-buttons-option-template">
			<%% _.each(this.et_builder_template_options.font_buttons.options, function(font_button) { %%>
				<div class="et_builder_<%%= font_button %%>_font et_builder_font_style mce-widget mce-btn">
					<button type="button">
						<i class="mce-ico mce-i-<%%= font_button %%>"></i>
					</button>
				</div>
			<%% }); %%>
		</script>'
	);

	do_action( 'et_pb_after_page_builder' );
}

/**
 * Modify builder editor's TinyMCE configuration
 *
 * @return array
 */
function et_pb_content_new_mce_config( $mceInit, $editor_id ) {
	if ( 'et_pb_content_new' === $editor_id && isset( $mceInit['toolbar1'] ) ) {
		// Get toolbar as array
		$toolbar1 = explode(',', $mceInit['toolbar1'] );

		// Look for read more (wp_more)'s array' key
		$wp_more_key = array_search( 'wp_more', $toolbar1 );

		if ( $wp_more_key ) {
			unset( $toolbar1[ $wp_more_key ] );
		}

		// Update toolbar1 configuration
		$mceInit['toolbar1'] = implode(',', $toolbar1 );
	}

	return $mceInit;
}
add_filter( 'tiny_mce_before_init', 'et_pb_content_new_mce_config', 10, 2 );

/**
 * Get post format with filterable output
 *
 * @todo once WordPress provide filter for get_post_format() output, this function can be retired
 * @see get_post_format()
 *
 * @return mixed string|bool string of post format or false for default
 */
function et_pb_post_format() {
	return apply_filters( 'et_pb_post_format', get_post_format(), get_the_ID() );
}

/**
 * Return post format into false when using pagebuilder
 *
 * @return mixed string|bool string of post format or false for default
 */
function et_pb_post_format_in_pagebuilder( $post_format, $post_id ) {

	if ( et_pb_is_pagebuilder_used( $post_id ) ) {
		return false;
	}

	return $post_format;
}
add_filter( 'et_pb_post_format', 'et_pb_post_format_in_pagebuilder', 10, 2 );

if ( ! function_exists( 'et_pb_get_mailchimp_lists' ) ) :
function et_pb_get_mailchimp_lists( $regenerate_mailchimp_list = 'off' ) {
	$lists = array();

	if ( et_is_builder_plugin_active() ) {
		$mailchimp_api_option = get_option( 'et_pb_builder_options' );
		$mailchimp_api_key = isset( $mailchimp_api_option['newsletter_main_mailchimp_key'] ) ? $mailchimp_api_option['newsletter_main_mailchimp_key'] : '';
	} else {
		$mailchimp_api_key = et_get_option( 'divi_mailchimp_api_key' );
		$regenerate_mailchimp_list = et_get_option( 'divi_regenerate_mailchimp_lists', 'false' );
	}

	if ( empty( $mailchimp_api_key ) || false === strpos( $mailchimp_api_key, '-' ) ) {
		return false;
	}

	if ( 'on' === $regenerate_mailchimp_list || false === ( $et_pb_mailchimp_lists = get_transient( 'et_pb_mailchimp_lists' ) ) ) {
		if ( ! class_exists( 'MailChimp_Divi' ) ) {
			require_once( ET_BUILDER_DIR . 'subscription/mailchimp/mailchimp.php' );
		}

		try {
			$mailchimp = new MailChimp_Divi( $mailchimp_api_key );
			$retval = $mailchimp->call( 'lists/list', array( 'limit' => 100 ) );
			$retval_body = json_decode( wp_remote_retrieve_body( $retval ), true );
			$retrieved_lists = isset( $retval_body['data'] ) ? $retval_body['data'] : array();

			if ( 200 !== wp_remote_retrieve_response_code( $retval ) || empty( $retval_body['data'] ) || ! is_array( $retval_body['data'] ) ) {
				return $et_pb_mailchimp_lists;
			}

			// if there is more than 100 lists in account, then perform additional calls to retrieve all the lists.
			if ( ! empty( $retval_body['total'] ) && 100 < $retval_body['total'] ) {
				// determine how many requests we need to retrieve all the lists
				$total_pages = ceil( $retval_body['total'] / 100 );

				for ( $i = 1; $i <= $total_pages; $i++ ) {
					$retval_additional = $mailchimp->call( 'lists/list', array(
							'limit' => 100,
							'start' => $i,
						)
					);

					if ( ! empty( $retval_additional ) && empty( $retval_additional['errors'] ) ) {
						if ( ! empty( $retval_additional['data'] ) ) {
							$retrieved_lists = array_merge( $retrieved_lists, $retval_additional['data'] );
						}
					}
				}
			}

			if ( ! empty( $retrieved_lists ) ) {
				foreach ( $retrieved_lists as $list ) {
					$lists[$list['id']] = $list['name'];
				}
			}

			set_transient( 'et_pb_mailchimp_lists', $lists, 60*60*24 );
		} catch ( Exception $exc ) {
			$lists = $et_pb_mailchimp_lists;
		}

		return $lists;
	} else {
		return $et_pb_mailchimp_lists;
	}
}
endif;

if ( ! function_exists( 'et_pb_get_aweber_lists' ) ) :
function et_pb_get_aweber_lists( $regenerate_aweber_list = 'off' ) {
	$lists = array();

	$account = et_pb_get_aweber_account();

	if ( ! et_is_builder_plugin_active() ) {
		$regenerate_aweber_list = et_get_option( 'divi_regenerate_aweber_lists', 'false' );
	}

	if ( ! $account ) {
		return false;
	}

	if ( 'on' === $regenerate_aweber_list || false === ( $et_pb_aweber_lists = get_transient( 'et_pb_aweber_lists' ) ) ) {

		if ( ! class_exists( 'AWeberAPI' ) ) {
			require_once( ET_BUILDER_DIR . 'subscription/aweber/aweber_api.php' );
		}

		$aweber_lists = $account->lists;

		if ( isset( $aweber_lists ) ) {
			foreach ( $aweber_lists as $list ) {
				$lists[$list->id] = $list->name;
			}
		}

		set_transient( 'et_pb_aweber_lists', $lists, 60*60*24 );
	} else {
		$lists = $et_pb_aweber_lists;
	}

	return $lists;
}
endif;

function et_aweber_authorization_option() {
	wp_enqueue_script( 'divi-advanced-options', ET_BUILDER_URI . '/scripts/advanced_options.js', array( 'jquery' ), ET_BUILDER_VERSION, true );
	wp_localize_script( 'divi-advanced-options', 'et_advanced_options', array(
		'et_admin_load_nonce'      => wp_create_nonce( 'et_admin_load_nonce' ),
		'aweber_connecting'        => esc_html__( 'Connecting...', 'et_builder' ),
		'aweber_failed'            => esc_html__( 'Connection failed', 'et_builder' ),
		'aweber_remove_connection' => esc_html__( 'Removing connection...', 'et_builder' ),
		'aweber_done'              => esc_html__( 'Done', 'et_builder' ),
	) );
	wp_enqueue_style( 'divi-advanced-options', ET_BUILDER_URI . '/styles/advanced_options.css', array(), ET_BUILDER_VERSION );

	$app_id = 'b17f3351';

	$aweber_auth_endpoint = 'https://auth.aweber.com/1.0/oauth/authorize_app/' . $app_id;

	$hide_style = ' style="display: none;"';

	$aweber_connection_established = et_get_option( 'divi_aweber_consumer_key', false ) && et_get_option( 'divi_aweber_consumer_secret', false ) && et_get_option( 'divi_aweber_access_key', false ) && et_get_option( 'divi_aweber_access_secret', false );

	$output = sprintf(
		'<div id="et_aweber_connection">
			<ul id="et_aweber_authorization"%4$s>
				<li>%1$s</li>
				<li>
					<p>%2$s</p>
					<p><textarea id="et_aweber_authentication_code" name="et_aweber_authentication_code"></textarea></p>

					<p><button class="et_make_connection button button-primary button-large">%3$s</button></p>
				</li>
			</ul>

			<div id="et_aweber_remove_connection"%5$s>
				<p>%6$s</p>
				<p><button class="et_remove_connection button button-primary button-large">%7$s</button></p>
			</div>
		</div>',
		sprintf( '%1$s <a href="%2$s" target="_blank">%3$s</a>',
			esc_html__( 'Step 1:', 'et_builder' ),
			esc_url( $aweber_auth_endpoint ),
			esc_html__( 'Generate authorization code', 'et_builder' )
		),
		esc_html__( 'Step 2: Paste in the authorization code and click "Make a connection" button: ', 'et_builder' ),
		esc_html__( 'Make a connection', 'et_builder' ),
		( $aweber_connection_established ? $hide_style : ''  ),
		( ! $aweber_connection_established ? $hide_style : ''  ),
		esc_html__( 'Aweber is set up properly. You can remove connection here if you wish.', 'et_builder' ),
		esc_html__( 'Remove the connection', 'et_builder' )
	);

	echo $output;
}

if ( ! function_exists( 'et_pb_get_audio_player' ) ) :
function et_pb_get_audio_player() {
	$output = sprintf(
		'<div class="et_audio_container">
			%1$s
		</div> <!-- .et_audio_container -->',
		do_shortcode( '[audio]' )
	);

	return $output;
}
endif;

/*
 * Displays post audio, quote and link post formats content
 */
if ( ! function_exists( 'et_divi_post_format_content' ) ) :
function et_divi_post_format_content() {
	$post_format = et_pb_post_format();

	$text_color_class = et_divi_get_post_text_color();

	$inline_style = et_divi_get_post_bg_inline_style();

	switch ( $post_format ) {
		case 'audio' :
			printf(
				'<div class="et_audio_content%4$s"%5$s>
					<h2><a href="%3$s">%1$s</a></h2>
					%2$s
				</div> <!-- .et_audio_content -->',
				esc_html( get_the_title() ),
				et_pb_get_audio_player(),
				esc_url( get_permalink() ),
				esc_attr( $text_color_class ),
				$inline_style
			);

			break;
		case 'quote' :
			printf(
				'<div class="et_quote_content%4$s"%5$s>
					%1$s
					<a href="%2$s" class="et_quote_main_link">%3$s</a>
				</div> <!-- .et_quote_content -->',
				et_get_blockquote_in_content(),
				esc_url( get_permalink() ),
				esc_html__( 'Read more', 'et_builder' ),
				esc_attr( $text_color_class ),
				$inline_style
			);

			break;
		case 'link' :
			printf(
				'<div class="et_link_content%5$s"%6$s>
					<h2><a href="%2$s">%1$s</a></h2>
					<a href="%3$s" class="et_link_main_url">%4$s</a>
				</div> <!-- .et_link_content -->',
				esc_html( get_the_title() ),
				esc_url( get_permalink() ),
				esc_url( et_get_link_url() ),
				esc_html( et_get_link_url() ),
				esc_attr( $text_color_class ),
				$inline_style
			);

			break;
	}
}
endif;

/**
 * Extract and return the first blockquote from content.
 */
if ( ! function_exists( 'et_get_blockquote_in_content' ) ) :
function et_get_blockquote_in_content() {
	global $more;
	$more_default = $more;
	$more = 1;

	remove_filter( 'the_content', 'et_remove_blockquote_from_content' );

	$content = apply_filters( 'the_content', get_the_content() );

	add_filter( 'the_content', 'et_remove_blockquote_from_content' );

	$more = $more_default;

	if ( preg_match( '/<blockquote>(.+?)<\/blockquote>/is', $content, $matches ) ) {
		return $matches[0];
	} else {
		return false;
	}
}
endif;

if ( ! function_exists( 'et_get_link_url' ) ) :
function et_get_link_url() {
	if ( '' !== ( $link_url = get_post_meta( get_the_ID(), '_format_link_url', true ) ) ) {
		return $link_url;
	}

	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
endif;

if ( ! function_exists( 'et_get_first_video' ) ) :
function et_get_first_video() {
	$first_video  = '';
	$video_width  = (int) apply_filters( 'et_blog_video_width', 1080 );
	$video_height = (int) apply_filters( 'et_blog_video_height', 630 );

	preg_match_all( '|^\s*https?://[^\s"]+\s*$|im', get_the_content(), $urls );

	foreach ( $urls[0] as $url ) {

		$oembed = wp_oembed_get( esc_url( $url ) );

		if ( !$oembed ) {
			continue;
		}

		$first_video = $oembed;
		$first_video = preg_replace( '/<embed /', '<embed wmode="transparent" ', $first_video );
		$first_video = preg_replace( '/<\/object>/','<param name="wmode" value="transparent" /></object>', $first_video );
		$first_video = preg_replace( "/width=\"[0-9]*\"/", "width={$video_width}", $first_video );
		$first_video = preg_replace( "/height=\"[0-9]*\"/", "height={$video_height}", $first_video );

		break;
	}

	if ( '' === $first_video && has_shortcode( get_the_content(), 'video' )  ) {
		$regex = get_shortcode_regex();
		preg_match( "/{$regex}/s", get_the_content(), $match );

		$first_video = preg_replace( "/width=\"[0-9]*\"/", "width=\"{$video_width}\"", $match[0] );
		$first_video = preg_replace( "/height=\"[0-9]*\"/", "height=\"{$video_height}\"", $first_video );

		add_filter( 'the_content', 'et_delete_post_video' );

		$first_video = do_shortcode( et_pb_fix_shortcodes( $first_video ) );
	}

	return ( '' !== $first_video ) ? $first_video : false;
}
endif;

if ( ! function_exists( 'et_delete_post_video' ) ) :
/*
 * Removes the first video shortcode from content on single pages since it is displayed
 * at the top of the page. This will also remove the video shortcode url from archive pages content
 */
function et_delete_post_video( $content ) {
	if ( has_post_format( 'video' ) ) :
		$regex = get_shortcode_regex();
		preg_match_all( "/{$regex}/s", $content, $matches );

		// $matches[2] holds an array of shortcodes names in the post
		foreach ( $matches[2] as $key => $shortcode_match ) {
			if ( 'video' === $shortcode_match ) {
				$content = str_replace( $matches[0][$key], '', $content );
				if ( is_single() && is_main_query() ) {
					break;
				}
			}
		}
	endif;

	return $content;
}
endif;

/**
 * Fix JetPack post excerpt shortcode issue.
 */
function et_jetpack_post_excerpt( $results ) {
    foreach ( $results as $key => $post ) {
        if ( isset( $post['excerpt'] ) ) {
        	// Remove ET shortcodes from JetPack excerpt.
            $results[$key]['excerpt'] = preg_replace( '#\[et_pb(.*)\]#', '', $post['excerpt'] );
        }
    }
    return $results;
}
add_filter( 'jetpack_relatedposts_returned_results', 'et_jetpack_post_excerpt' );

/**
 * Adds a Divi gallery type when the Jetpack plugin is enabled
 */
function et_jetpack_gallery_type( $types ) {
	$types['divi'] = 'Divi';
	return $types;
}
add_filter( 'jetpack_gallery_types', 'et_jetpack_gallery_type' );

if ( ! function_exists( 'et_get_gallery_attachments' ) ) :
/**
 * Fetch the gallery attachments
 */
function et_get_gallery_attachments( $attr ) {
	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( ! $attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}
	$html5 = current_theme_supports( 'html5', 'gallery' );
	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => get_the_ID() ? get_the_ID() : 0,
		'itemtag'    => $html5 ? 'figure'     : 'dl',
		'icontag'    => $html5 ? 'div'        : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => '',
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );
	if ( 'RAND' == $atts['order'] ) {
		$atts['orderby'] = 'none';
	}
	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array(
			'include'        => $atts['include'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby'],
		) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[ $val->ID ] = $_attachments[ $key ];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array(
			'post_parent'    => $id,
			'exclude'        => $atts['exclude'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby'],
		) );
	} else {
		$attachments = get_children( array(
			'post_parent'    => $id,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby'],
		) );
	}

	return $attachments;
}
endif;

/**
 * Generate the HTML for custom gallery layouts
 */
function et_gallery_layout( $val, $attr ) {
	// check to see if the gallery output is already rewritten
	if ( ! empty( $val ) ) {
		return $val;
	}

	if ( et_is_builder_plugin_active() ) {
		return $val;
	}

	if ( ! apply_filters( 'et_gallery_layout_enable', false ) ) {
		return $val;
	}

	$output = '';

	if ( ! is_singular() && ! et_pb_is_pagebuilder_used( get_the_ID() ) ) {
		$attachments = et_get_gallery_attachments( $attr );
		$gallery_output = '';
		foreach ( $attachments as $attachment ) {
			$attachment_image = wp_get_attachment_url( $attachment->ID, 'et-pb-post-main-image-fullwidth' );
			$gallery_output .= sprintf(
				'<div class="et_pb_slide" style="background: url(%1$s);"></div>',
				esc_attr( $attachment_image )
			);
		}
		$output = sprintf(
			'<div class="et_pb_slider et_pb_slider_fullwidth_off et_pb_gallery_post_type">
				<div class="et_pb_slides">
					%1$s
				</div>
			</div>',
			$gallery_output
		);

	} else {
		if ( ! isset( $attr['type'] ) || ! in_array( $attr['type'], array( 'rectangular', 'square', 'circle', 'rectangle' ) ) ) {
			$attachments = et_get_gallery_attachments( $attr );
			$gallery_output = '';
			foreach ( $attachments as $attachment ) {
				$gallery_output .= sprintf(
					'<li class="et_gallery_item">
						<a href="%1$s" title="%3$s">
							<span class="et_portfolio_image">
								%2$s
								<span class="et_overlay"></span>
							</span>
						</a>
						%4$s
					</li>',
					esc_url( wp_get_attachment_url( $attachment->ID, 'full' ) ),
					wp_get_attachment_image( $attachment->ID, 'et-pb-portfolio-image' ),
					esc_attr( $attachment->post_title ),
					! empty( $attachment->post_excerpt )
						? sprintf( '<p class="et_pb_gallery_caption">%1$s</p>', esc_html( $attachment->post_excerpt ) )
						: ''
				);
			}
			$output = sprintf(
				'<ul class="et_post_gallery clearfix">
					%1$s
				</ul>',
				$gallery_output
			);
		}
	}
	return $output;
}
add_filter( 'post_gallery', 'et_gallery_layout', 1000, 2 );

if ( ! function_exists( 'et_pb_gallery_images' ) ) :
function et_pb_gallery_images( $force_gallery_layout = '' ) {
	if ( 'slider' === $force_gallery_layout ) {
		$attachments = get_post_gallery( get_the_ID(), false );
		$gallery_output = '';
		$output = '';
		$images_array = ! empty( $attachments['ids'] ) ? explode( ',', $attachments['ids'] ) : array();

		if ( empty ( $images_array ) ) {
			return $output;
		}

		foreach ( $images_array as $attachment ) {
			$image_src = wp_get_attachment_url( $attachment, 'et-pb-post-main-image-fullwidth' );
			$gallery_output .= sprintf(
				'<div class="et_pb_slide" style="background: url(%1$s);"></div>',
				esc_url( $image_src )
			);
		}
		printf(
			'<div class="et_pb_slider et_pb_slider_fullwidth_off et_pb_gallery_post_type">
				<div class="et_pb_slides">
					%1$s
				</div>
			</div>',
			$gallery_output
		);
	} else {
		add_filter( 'et_gallery_layout_enable', 'et_gallery_layout_turn_on' );
		printf( do_shortcode( '%1$s' ), get_post_gallery() );
		remove_filter( 'et_gallery_layout_enable', 'et_gallery_layout_turn_on' );
	}
}
endif;

/**
 * Used to always use divi gallery on et_pb_gallery_images
 */
function et_gallery_layout_turn_on() {
	return true;
}

/*
 * Remove Elegant Builder plugin filter, that activates visual mode on each page load in WP-Admin
 */
function et_pb_remove_lb_plugin_force_editor_mode() {
	remove_filter( 'wp_default_editor', 'et_force_tmce_editor' );
}
add_action( 'admin_init', 'et_pb_remove_lb_plugin_force_editor_mode' );

/**
 *
 * Generates array of all Role options
 *
 */
function et_pb_all_role_options() {
	// get all the modules and build array of capabilities for them
	$all_modules_array = ET_Builder_Element::get_modules_array();
	$module_capabilies = array();

	foreach ( $all_modules_array as $module => $module_details ) {
		if ( ! in_array( $module_details['label'], array( 'et_pb_section', 'et_pb_row', 'et_pb_row_inner', 'et_pb_column' ) ) ) {
			$module_capabilies[ $module_details['label'] ] = array(
				'name'    => sanitize_text_field( $module_details['title'] ),
				'default' => 'on',
			);
		}
	}

	// we need to display some options only when theme activated
	$theme_only_options = ! et_is_builder_plugin_active()
		? array(
			'theme_customizer' => array(
				'name'           => esc_html__( 'Theme Customizer', 'et_builder' ),
				'default'        => 'on',
				'applicability'  => array( 'administrator' ),
			),
			'module_customizer' => array(
				'name'           => esc_html__( 'Module Customizer', 'et_builder' ),
				'default'        => 'on',
				'applicability'  => array( 'administrator' ),
			),
			'page_options' => array(
				'name'    => esc_html__( 'Page Options', 'et_builder' ),
				'default' => 'on',
			),
		)
		: array();

	$all_role_options = array(
		'general_capabilities' => array(
			'section_title' => '',
			'options'       => array(
				'theme_options' => array(
					'name'           => et_is_builder_plugin_active() ? esc_html__( 'Plugin Options', 'et_builder' ) : esc_html__( 'Theme Options', 'et_builder' ),
					'default'        => 'on',
					'applicability'  => array( 'administrator' ),
				),
				'divi_library' => array(
					'name'    => esc_html__( 'Divi Library', 'et_builder' ),
					'default' => 'on',
				),
			),
		),
		'builder_capabilities' => array(
			'section_title' => esc_html__( 'Builder Interface', 'et_builder'),
			'options'       => array(
				'add_module' => array(
					'name'    => esc_html__( 'Add/Delete Item', 'et_builder' ),
					'default' => 'on',
				),
				'edit_module' => array(
					'name'    => esc_html__( 'Edit Item', 'et_builder' ),
					'default' => 'on',
				),
				'move_module' => array(
					'name'    => esc_html__( 'Move Item', 'et_builder' ),
					'default' => 'on',
				),
				'disable_module' => array(
					'name'    => esc_html__( 'Disable Item', 'et_builder' ),
					'default' => 'on',
				),
				'lock_module' => array(
					'name'    => esc_html__( 'Lock Item', 'et_builder' ),
					'default' => 'on',
				),
				'divi_builder_control' => array(
					'name'    => esc_html__( 'Toggle Divi Builder', 'et_builder' ),
					'default' => 'on',
				),
				'load_layout' => array(
					'name'    => esc_html__( 'Load Layout', 'et_builder' ),
					'default' => 'on',
				),
			),
		),
		'library_capabilities' => array(
			'section_title' => esc_html__( 'Library Settings', 'et_builder' ),
			'options'       => array(
				'save_library' => array(
					'name'    => esc_html__( 'Save To Library', 'et_builder' ),
					'default' => 'on',
				),
				'add_library' => array(
					'name'    => esc_html__( 'Add From Library', 'et_builder' ),
					'default' => 'on',
				),
				'edit_global_library' => array(
					'name'    => esc_html__( 'Edit Global Items', 'et_builder' ),
					'default' => 'on',
				),
			),
		),
		'module_tabs' => array(
			'section_title' => esc_html__( 'Settings Tabs', 'et_builder' ),
			'options'       => array(
				'general_settings' => array(
					'name'    => esc_html__( 'General Settings', 'et_builder' ),
					'default' => 'on',
				),
				'advanced_settings' => array(
					'name'    => esc_html__( 'Advanced Settings', 'et_builder' ),
					'default' => 'on',
				),
				'custom_css_settings' => array(
					'name'    => esc_html__( 'Custom CSS', 'et_builder' ),
					'default' => 'on',
				),
			),
		),
		'general_module_capabilities' => array(
			'section_title' => esc_html__( 'Settings Types', 'et_builder' ),
			'options'       => array(
				'edit_colors' => array(
					'name'    => esc_html__( 'Edit Colors', 'et_builder' ),
					'default' => 'on',
				),
				'edit_content' => array(
					'name'    => esc_html__( 'Edit Content', 'et_builder' ),
					'default' => 'on',
				),
				'edit_fonts' => array(
					'name'    => esc_html__( 'Edit Fonts', 'et_builder' ),
					'default' => 'on',
				),
				'edit_buttons' => array(
					'name'    => esc_html__( 'Edit Buttons', 'et_builder' ),
					'default' => 'on',
				),
				'edit_layout' => array(
					'name'    => esc_html__( 'Edit Layout', 'et_builder' ),
					'default' => 'on',
				),
				'edit_configuration' => array(
					'name'    => esc_html__( 'Edit Configuration', 'et_builder' ),
					'default' => 'on',
				),
			),
		),
		'module_capabilies' => array(
			'section_title' => esc_html__( 'Module Use', 'et_builder' ),
			'options'       => $module_capabilies,
		),
	);

	$all_role_options['general_capabilities']['options'] = array_merge( $all_role_options['general_capabilities']['options'], $theme_only_options );

	return $all_role_options;
}

/**
 *
 * Prints the admin page for Role Editor
 *
 */
function et_pb_display_role_editor() {
	$all_role_options = et_pb_all_role_options();
	$option_tabs = '';
	$menu_tabs = '';

	// get all roles registered in current WP
	if ( ! function_exists( 'get_editable_roles' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/user.php' );
	}

	$all_roles = get_editable_roles();
	$builder_roles_array = array();

	if ( ! empty( $all_roles ) ) {
		foreach( $all_roles as $role => $role_data ) {
			// add roles with edit_posts capability into $builder_roles_array
			if ( ! empty( $role_data['capabilities']['edit_posts'] ) && 1 === (int) $role_data['capabilities']['edit_posts'] ) {
				$builder_roles_array[ $role ] = $role_data['name'];
			}
		}
	}

	// fill the builder roles array with default roles if it's empty
	if ( empty( $builder_roles_array ) ) {
		$builder_roles_array = array(
			'administrator' => esc_html__( 'Administrator', 'et_builder' ),
			'editor'        => esc_html__( 'Editor', 'et_builder' ),
			'author'        => esc_html__( 'Author', 'et_builder' ),
			'contributor'   => esc_html__( 'Contributor', 'et_builder' ),
		);
	}

	foreach( $builder_roles_array as $role => $role_title ) {
		$option_tabs .= et_pb_generate_roles_tab( $all_role_options, $role );

		$menu_tabs .= sprintf(
			'<a href="#" class="et-pb-layout-buttons%4$s" data-open_tab="et_pb_role-%3$s_options" title="%1$s">
				<span>%2$s</span>
			</a>',
			esc_attr( $role_title ),
			esc_html( $role_title ),
			esc_attr( $role ),
			'administrator' === $role ? ' et_pb_roles_active_menu' : ''
		);
	}

	printf(
		'<div class="et_pb_roles_main_container">
			<a href="#" id="et_pb_save_roles" class="button button-primary button-large">%3$s</a>
			<h3 class="et_pb_roles_title"><span>%2$s</span></h3>
			<div id="et_pb_main_container" class="post-type-page">
				<div id="et_pb_layout_controls">
					%1$s
					<a href="#" class="et-pb-layout-buttons et-pb-layout-buttons-reset" title="Reset all settings">
						<span class="icon"></span><span class="label">Reset</span>
					</a>
				</div>
			</div>
			<div class="et_pb_roles_container_all">
				%4$s
			</div>
		</div>',
		$menu_tabs,
		esc_html__( 'Divi Role Editor', 'et_builder' ),
		esc_html__( 'Save Divi Roles', 'et_builder' ),
		$option_tabs
	);
}

/**
 *
 * Generates the options tab for specified role.
 *
 * @return string
 */
function et_pb_generate_roles_tab( $all_role_options, $role ) {
	$form_sections = '';

	// generate all sections of the form for current role.
	if ( ! empty( $all_role_options ) ) {
		foreach( $all_role_options as $capability_id => $capability_options ) {
			$form_sections .= sprintf(
				'<div class="et_pb_roles_section_container">
					%1$s
					<div class="et_pb_roles_options_internal">
						%2$s
					</div>
				</div>',
				! empty( $capability_options['section_title'] )
					? sprintf( '<h4 class="et_pb_roles_divider">%1$s <span class="et_pb_toggle_all"></span></h4>', esc_html( $capability_options['section_title'] ) )
					: '',
				et_pb_generate_capabilities_output( $capability_options['options'], $role )
			);
		}
	}

	$output = sprintf(
		'<div class="et_pb_roles_options_container et_pb_role-%2$s_options%3$s">
			<p class="et_pb_roles_notice">%1$s</p>
			<form id="et_pb_%2$s_role" data-role_id="%2$s">
				%4$s
			</form>
		</div>',
		esc_html__( 'Using the Divi Role Editor, you can limit the types of actions that can be taken by WordPress users of different roles. This is a great way to limit the functionality available to your customers or guest authors to ensure that they only have the necessary options available to them.', 'et_builder' ),
		esc_attr( $role ),
		'administrator' === $role ? ' active-container' : '',
		$form_sections // #4
	);

	return $output;
}

/**
 *
 * Generates the enable/disable buttons list based on provided capabilities array and role
 *
 * @return string
 */
function et_pb_generate_capabilities_output( $cap_array, $role ) {
	$output = '';
	$saved_capabilities = get_option( 'et_pb_role_settings', array() );

	if ( ! empty( $cap_array ) ) {
		foreach ( $cap_array as $capability => $capability_details ) {
			if ( empty( $capability_details['applicability'] ) || ( ! empty( $capability_details['applicability'] ) && in_array( $role, $capability_details['applicability'] ) ) ) {
				$output .= sprintf(
					'<div class="et_pb_capability_option">
						<span class="et_pb_capability_title">%4$s</span>
						<div class="et_pb_yes_no_button_wrapper">
							<div class="et_pb_yes_no_button et_pb_on_state">
								<span class="et_pb_value_text et_pb_on_value">%1$s</span>
								<span class="et_pb_button_slider"></span>
								<span class="et_pb_value_text et_pb_off_value">%2$s</span>
							</div>
							<select name="%3$s" id="%3$s" class="et-pb-main-setting regular-text">
								<option value="on" %5$s>Yes</option>
								<option value="off" %6$s>No</option>
							</select>
						</div>
					</div>',
					esc_html__( 'Enable', 'et_builder' ),
					esc_html__( 'Disable', 'et_builder' ),
					esc_attr( $capability ),
					esc_html( $capability_details['name'] ),
					! empty( $saved_capabilities[$role][$capability] ) ? selected( 'on', $saved_capabilities[$role][$capability], false ) : selected( 'on', $capability_details['default'], false ),
					! empty( $saved_capabilities[$role][$capability] ) ? selected( 'off', $saved_capabilities[$role][$capability], false ) : selected( 'off', $capability_details['default'], false )
				);
			}
		}
	}

	return $output;
}

/**
 *
 * Loads scripts and styles for Role Editor Admin page
 *
 */
function et_pb_load_roles_admin( $hook ) {
	// load scripts only on role editor page

	if ( apply_filters( 'et_pb_load_roles_admin_hook', 'divi_page_et_divi_role_editor' ) !== $hook ) {
		return;
	}

	wp_enqueue_style( 'builder-roles-editor-styles', ET_BUILDER_URI . '/styles/roles_style.css' );
	wp_enqueue_script( 'builder-roles-editor-scripts', ET_BUILDER_URI . '/scripts/roles_admin.js', array( 'jquery' ), ET_BUILDER_VERSION, true );
	wp_localize_script( 'builder-roles-editor-scripts', 'et_pb_roles_options', array(
		'ajaxurl'        => admin_url( 'admin-ajax.php' ),
		'et_roles_nonce' => wp_create_nonce( 'et_roles_nonce' ),
		'modal_title'    => esc_html__( 'Reset Roles', 'et_builder' ),
		'modal_message'  => esc_html__( 'All of your current role settings will be set to defaults. Do you wish to proceed?', 'et_builder' ),
		'modal_yes'      => esc_html__( 'Yes', 'et_builder' ),
		'modal_no'       => esc_html__( 'no', 'et_builder' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'et_pb_load_roles_admin' );

/**
 * Generates the array of allowed modules in jQuery Array format
 * @return string
 */
function et_pb_allowed_modules_list( $role = '' ) {
	global $typenow;

	$saved_capabilities = et_pb_get_role_settings();
	$role = '' === $role ? et_pb_get_current_user_role() : $role;

	$all_modules_array = ET_Builder_Element::get_modules_array( $typenow );

	$saved_modules_capabilities = isset( $saved_capabilities[ $role ] ) ? $saved_capabilities[ $role ] : array();

	$alowed_modules = "[";
	foreach ( $all_modules_array as $module => $module_details ) {
		if ( ! in_array( $module_details['label'], array( 'et_pb_section', 'et_pb_row', 'et_pb_row_inner', 'et_pb_column' ) ) ) {
			// Add module into the list if it's not saved or if it's saved not with "off" state
			if ( ! isset( $saved_modules_capabilities[ $module_details['label'] ] ) || ( isset( $saved_modules_capabilities[ $module_details['label'] ] ) && 'off' !== $saved_modules_capabilities[ $module_details['label'] ] ) ) {
				$alowed_modules .= "'" . $module_details['label'] . "',";
			}
		}
	}

	$alowed_modules .= "]";

	return $alowed_modules;
}

if ( ! function_exists( 'et_divi_get_post_text_color' ) ) {
	function et_divi_get_post_text_color() {
		$text_color_class = '';

		$post_format = et_pb_post_format();

		if ( in_array( $post_format, array( 'audio', 'link', 'quote' ) ) ) {
			$text_color_class = ( $text_color = get_post_meta( get_the_ID(), '_et_post_bg_layout', true ) ) ? $text_color : 'light';
			$text_color_class = ' et_pb_text_color_' . $text_color_class;
		}

		return $text_color_class;
	}
}

if ( ! function_exists( 'et_divi_get_post_bg_inline_style' ) ) {
	function et_divi_get_post_bg_inline_style() {
		$inline_style = '';

		$post_id = get_the_ID();

		$post_use_bg_color = get_post_meta( $post_id, '_et_post_use_bg_color', true )
			? true
			: false;
		$post_bg_color  = ( $bg_color = get_post_meta( $post_id, '_et_post_bg_color', true ) ) && '' !== $bg_color
			? $bg_color
			: '#ffffff';

		if ( $post_use_bg_color ) {
			$inline_style = sprintf( ' style="background-color: %1$s;"', esc_html( $post_bg_color ) );
		}

		return $inline_style;
	}
}

function et_remove_blockquote_from_content( $content ) {
	if ( 'quote' !== et_pb_post_format() ) {
		return $content;
	}

	$content = preg_replace( '/<blockquote>(.+?)<\/blockquote>/is', '', $content, 1 );

	return $content;
}
add_filter( 'the_content', 'et_remove_blockquote_from_content' );

/**
 * Check whether current page is pagebuilder preview page
 * @return bool
 */
function is_et_pb_preview() {
	global $wp_query;
	return ( 'true' === $wp_query->get( 'et_pb_preview' ) && isset( $_GET['et_pb_preview_nonce'] ) );
}

/**
 * Register rewrite rule and tag for preview page
 * @return void
 */
function et_pb_register_preview_endpoint() {
	add_rewrite_tag( '%et_pb_preview%', 'true' );
}
add_action( 'init', 'et_pb_register_preview_endpoint', 11 );

/**
 * Flush rewrite rules to fix the issue "preg_match" issue with 2.5
 * @return void
 */
function et_pb_maybe_flush_rewrite_rules() {
	$setting_name = '2_5_flush_rewrite_rules';

	if ( et_get_option( $setting_name ) ) {
		return;
	}

	flush_rewrite_rules();

	et_update_option( $setting_name, 'done' );
}
add_action( 'init', 'et_pb_maybe_flush_rewrite_rules', 9 );

/**
 * Register template for preview page
 * @return string path to template file
 */
function et_pb_register_preview_page( $template ) {
	global $wp_query;

	if ( 'true' === $wp_query->get( 'et_pb_preview' ) && isset( $_GET['et_pb_preview_nonce'] ) ) {
		show_admin_bar( false );

		return ET_BUILDER_DIR . 'template-preview.php';
	}

	return $template;
}
add_action( 'template_include', 'et_pb_register_preview_page' );

/*
 * do_shortcode() replaces square brackers with html entities,
 * convert them back to make sure js code works ok
 */
if ( ! function_exists( 'et_builder_replace_code_content_entities' ) ) :
function et_builder_replace_code_content_entities( $content ) {
	$content = str_replace( '&#091;', '[', $content );
	$content = str_replace( '&#093;', ']', $content );

	return $content;
}
endif;

// adjust the number of all layouts displayed on library page to exclude predefined layouts
function et_pb_fix_count_library_items( $counts ) {
	// do nothing if get_current_screen function doesn't exists at this point to avoid php errors in some plugins.
	if ( ! function_exists( 'get_current_screen' ) ) {
		return $counts;
	}

	$current_screen = get_current_screen();

	if ( isset( $current_screen->id ) && 'edit-et_pb_layout' === $current_screen->id && isset( $counts->publish ) ) {
		// perform query to get all the not predefined layouts
		$query = new WP_Query( array(
			'meta_query'      => array(
				array(
					'key'     => '_et_pb_predefined_layout',
					'value'   => 'on',
					'compare' => 'NOT EXISTS',
				),
			),
			'post_type'       => ET_BUILDER_LAYOUT_POST_TYPE,
			'posts_per_page'  => '-1',
		) );

		// set the $counts->publish = amount of non predefined layouts
		$counts->publish = isset( $query->post_count ) ? (int) $query->post_count : 0;
	}

	return $counts;
}
add_filter( 'wp_count_posts', 'et_pb_fix_count_library_items' );

function et_pb_generate_mobile_options_tabs() {
	$mobile_settings_tabs = '<%= window.et_builder.mobile_tabs_output() %>';

	return $mobile_settings_tabs;
}

// Generates the css code for responsive options.
// Uses array of values for each device as input parameter and css_selector with property to apply the css
function et_pb_generate_responsive_css( $values_array, $css_selector, $css_property, $function_name, $additional_css = '' ) {
	if ( ! empty( $values_array ) ) {
		foreach( $values_array as $device => $current_value ) {
			if ( '' === $current_value ) {
				continue;
			}

			$declaration = '';

			// value can be provided as a string or array in following format - array( 'property_1' => 'value_1', 'property_2' => 'property_2', ... , 'property_n' => 'value_n' )
			if ( is_array( $current_value ) && ! empty( $current_value ) ) {
				foreach( $current_value as $this_property => $this_value ) {
					if ( '' === $this_value ) {
						continue;
					}

					$declaration .= sprintf(
						'%1$s: %2$s%3$s',
						$this_property,
						esc_html( et_builder_process_range_value( $this_value ) ),
						'' !== $additional_css ? $additional_css : ';'
					);
				}
			} else {
				$declaration = sprintf(
					'%1$s: %2$s%3$s',
					$css_property,
					esc_html( et_builder_process_range_value( $current_value ) ),
					'' !== $additional_css ? $additional_css : ';'
				);
			}

			if ( '' === $declaration ) {
				continue;
			}

			$style = array(
				'selector'    => $css_selector,
				'declaration' => $declaration,
			);

			if ( 'desktop' !== $device ) {
				$current_media_query = 'tablet' === $device ? 'max_width_980' : 'max_width_767';
				$style['media_query'] = ET_Builder_Element::get_media_query( $current_media_query );
			}

			ET_Builder_Element::set_style( $function_name, $style );
		}
	}
}

function et_pb_custom_search( $query = false ) {
	if ( is_admin() || ! is_a( $query, 'WP_Query' ) || ! $query->is_search ) {
		return;
	}

	if ( isset( $_GET['et_pb_searchform_submit'] ) ) {
		$postTypes = array();
		if ( ! isset($_GET['et_pb_include_posts'] ) && ! isset( $_GET['et_pb_include_pages'] ) ) $postTypes = array( 'post' );
		if ( isset( $_GET['et_pb_include_pages'] ) ) $postTypes = array( 'page' );
		if ( isset( $_GET['et_pb_include_posts'] ) ) $postTypes[] = 'post';
		$query->set( 'post_type', $postTypes );

		if ( ! empty( $_GET['et_pb_search_cat'] ) ) {
			$categories_array = explode( ',', $_GET['et_pb_search_cat'] );
			$query->set( 'category__not_in', $categories_array );
		}

		if ( isset( $_GET['et-posts-count'] ) ) {
			$query->set( 'posts_per_page', (int) $_GET['et-posts-count'] );
		}
	}
}
add_action( 'pre_get_posts', 'et_pb_custom_search' );

if ( ! function_exists( 'et_custom_comments_display' ) ) :
function et_custom_comments_display( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">
			<div class="comment_avatar">
				<?php echo get_avatar( $comment, $size = '80' ); ?>
			</div>

			<div class="comment_postinfo">
				<?php printf( '<span class="fn">%s</span>', get_comment_author_link() ); ?>
				<span class="comment_date">
				<?php
					/* translators: 1: date, 2: time */
					printf( esc_html__( 'on %1$s at %2$s', 'et_builder' ), get_comment_date(), get_comment_time() );
				?>
				</span>
				<?php edit_comment_link( esc_html__( '(Edit)', 'et_builder' ), ' ' ); ?>
			<?php
				$et_comment_reply_link = get_comment_reply_link( array_merge( $args, array(
					'reply_text' => esc_html__( 'Reply', 'et_builder' ),
					'depth'      => (int) $depth,
					'max_depth'  => (int) $args['max_depth'],
				) ) );
			?>
			</div> <!-- .comment_postinfo -->

			<div class="comment_area">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<em class="moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'et_builder' ) ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-content clearfix">
				<?php
					comment_text();
					if ( $et_comment_reply_link ) echo '<span class="reply-container">' . $et_comment_reply_link . '</span>';
				?>
				</div> <!-- end comment-content-->
			</div> <!-- end comment_area-->
		</article> <!-- .comment-body -->
<?php }
endif;

/* Exclude library related taxonomies from Yoast SEO Sitemap */
function et_wpseo_sitemap_exclude_taxonomy( $value, $taxonomy ) {
	$excluded = array( 'scope', 'module_width', 'layout_type', 'layout_category', 'layout' );

	if ( in_array( $taxonomy, $excluded ) ) {
		return true;
	}

	return false;
}
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'et_wpseo_sitemap_exclude_taxonomy', 10, 2 );

/**
 * Is Yoast SEO plugin active?
 *
 * @return bool  True - if the plugin is active
 */
if ( ! function_exists( 'et_is_yoast_seo_plugin_active' ) ) :
function et_is_yoast_seo_plugin_active() {
	return class_exists( 'WPSEO_Options' );
}
endif;

/**
 * Modify comment count for preview screen. Somehow WordPress' get_comments_number() doesn't get correct $post_id
 * param and doesn't have proper fallback to global $post if $post_id variable isn't found. This causes incorrect
 * comment count in preview screen
 * @see get_comments_number()
 * @see get_comments_number_text()
 * @see comments_number()
 * @return string
 */
function et_pb_preview_comment_count( $count, $post_id ) {
	if ( is_et_pb_preview() ) {
		global $post;
		$count = isset( $post->comment_count ) ? $post->comment_count : $count;
	}

	return $count;
}
add_filter( 'get_comments_number', 'et_pb_preview_comment_count', 10, 2 );


/**
 * Display the Notice about cache once the theme was udpated
 */
function et_pb_maybe_display_cache_notice() {
	$ignore_notice_option = get_option( 'et_pb_cache_notice', array() );
	$ignore_this_notice = empty( $ignore_notice_option[ ET_BUILDER_VERSION ] ) ? 'show' : $ignore_notice_option[ ET_BUILDER_VERSION ];
	$screen = get_current_screen();

	// check whether any cache plugin installed and get its page link
	$plugin_page = et_pb_detect_cache_plugins();

	if ( current_user_can( 'manage_options' ) && 'post' === $screen->base && 'ignore' !== $ignore_this_notice && false !== $plugin_page ) {
		$hide_button = sprintf(
			' <br> <a class="et_pb_hide_cache_notice" href="%3$s">%2$s</a> <a class="et_pb_hide_cache_notice" href="#">%1$s</a>',
			esc_html__( 'Hide Notice', 'et_builder' ),
			esc_html__( 'Clear Cache', 'et_builder' ),
			esc_url( admin_url( $plugin_page ) )
		);

		$notice_text = et_get_safe_localization( __( 'The Divi Builder has been updated, but you are currently using a caching plugin. Please clear your plugin cache <strong>and</strong> clear your browser cache (in that order) to make sure you are loading the updated builder files. Loading cached files may cause the builder to malfunction.', 'et_builder' ) );

		printf( '<div class="update-nag et-pb-update-nag"><p>%1$s%2$s</p></div>', $notice_text, $hide_button );
	}
}
add_action( 'admin_notices', 'et_pb_maybe_display_cache_notice' );

/**
 * Detect the activated cache plugins and return the link to plugin options and return its page link or false
 * @return string or bool
 */
function et_pb_detect_cache_plugins() {
	if ( function_exists( 'edd_w3edge_w3tc_activate_license' ) ) {
		return 'admin.php?page=w3tc_pgcache';
	}

	if ( function_exists( 'wpsupercache_activate' ) ) {
		return 'options-general.php?page=wpsupercache';
	}

	if ( class_exists( 'HyperCache' ) ) {
		return 'options-general.php?page=hyper-cache%2Foptions.php';
	}

	if ( class_exists( '\zencache\plugin' ) ) {
		return 'admin.php?page=zencache';
	}

	if ( class_exists( 'WpFastestCache' ) ) {
		return 'admin.php?page=WpFastestCacheOptions';
	}

	if ( '1' === get_option( 'wordfenceActivated' ) ) {
		return 'admin.php?page=WordfenceSitePerf';
	}

	if ( function_exists( 'cachify_autoload' ) ) {
		return 'options-general.php?page=cachify';
	}

	if ( class_exists( 'FlexiCache' ) ) {
		return 'options-general.php?page=flexicache';
	}

	if ( function_exists( 'rocket_init' ) ) {
		return 'options-general.php?page=wprocket';
	}

	if ( function_exists( 'cloudflare_init' ) ) {
		return 'options-general.php?page=cloudflare';
	}

	return false;
}