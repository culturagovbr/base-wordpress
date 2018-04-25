<?php
/**
 * Portal Padrão WP functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Portal_Padrão_WP
 */

if ( ! function_exists( 'pp_wp_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function pp_wp_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Portal Padrão WP, use a find and replace
		 * to change 'pp-wp' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'pp-wp', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'service-menu' => esc_html__( 'Services menu', 'pp-wp' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'pp_wp_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'pp_wp_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function pp_wp_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'pp_wp_content_width', 640 );
}
add_action( 'after_setup_theme', 'pp_wp_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function pp_wp_scripts() {
	wp_enqueue_style( 'pp-wp-style', get_template_directory_uri() . '/assets/stylesheets/dist/bundle.min.css' );

	wp_enqueue_script( 'pp-wp-scripts', get_template_directory_uri() . '/assets/js/dist/bundle.min.js', array('jquery'), false, true );

	// wp_enqueue_script( 'pp-wp-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	// wp_enqueue_script( 'pp-wp-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	/*if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}*/
}
add_action( 'wp_enqueue_scripts', 'pp_wp_scripts' );

/**
 * Implement the theme settings
 *
 */
require get_template_directory() . '/inc/theme-settings.php';

/**
 * Register widgets area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
require get_template_directory() . '/inc/widgets-areas.php';

/**
 * Implement the Custom Header feature.
 *
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 *
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 *
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 *
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Register Custom Navigation Walker
 *
 */
require_once get_template_directory() . '/inc/wp-bootstrap-navwalker.php';

/**
 * Custom Breadcrumb
 *
 */
require_once get_template_directory() . '/inc/breadcrumb.php';

/**
 * Shortcodes
 *
 */
require_once get_template_directory() . '/inc/shortcodes.php';

/**
 * Corrects the error while customizing the site in the frontend
 *
 */
function wp_mediaelement_customize_javascript() {
	wp_register_script('mediaelement', plugins_url('wp-mediaelement.min.js', __FILE__), array('jquery'), false, true);
	wp_enqueue_script('mediaelement');
}
add_action('wp_enqueue_scripts', 'wp_mediaelement_customize_javascript', 100);


/**
 * Based on the option defined, disable comments for whole site
 *
 */
function disable_comments(){
	$pp_theme_options_options = get_option( 'pp_theme_options_option_name' );
	if( $pp_theme_options_options['disable_comments'] ){
		require get_template_directory() . '/inc/disable-comments.php';
	}
}
add_action('init', 'disable_comments');

/**
 * Add unfiltered_html Capability to Admins or Editors in WordPress Multisite 
 *
 */
function km_add_unfiltered_html_capability_to_editors( $caps, $cap, $user_id ) {
	if ( 'unfiltered_html' === $cap && user_can( $user_id, 'editor' ) || 'unfiltered_html' === $cap && user_can( $user_id, 'administrator' ) ) {
		$caps = array( 'unfiltered_html' );
	}
	return $caps;
}
add_filter( 'map_meta_cap', 'km_add_unfiltered_html_capability_to_editors', 1, 3 );