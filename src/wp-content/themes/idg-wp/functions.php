<?php
/**
 * Identidade Digital do Governo - WordPress functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Identidade_Digital_do_Governo_-_WordPress
 */

if ( ! function_exists( 'idg_wp_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function idg_wp_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Identidade Digital do Governo - WordPress, use a find and replace
		 * to change 'idg-wp' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'idg-wp', get_template_directory() . '/languages' );

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
		add_image_size('carousel-feature', 1280, 680, true);

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			// 'main-menu' => esc_html__( 'Main menu', 'idg-wp' ),
			'featured-links' => esc_html__( 'Featured links', 'idg-wp' ),
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
		add_theme_support( 'custom-background', apply_filters( 'idg_wp_custom_background_args', array(
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
add_action( 'after_setup_theme', 'idg_wp_setup' );

/**
 * Enqueue scripts and styles.
 */
function idg_wp_scripts() {
	// wp_enqueue_style( 'idg-wp-style', get_stylesheet_uri() );
    wp_enqueue_style( 'idg-wp-style', get_template_directory_uri() . '/assets/stylesheets/dist/bundle.min.css' );

    wp_enqueue_script( 'idg-wp-scripts', get_template_directory_uri() . '/assets/js/dist/bundle.min.js', array('jquery'), false, true );

    wp_enqueue_script( 'idg-wp-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'idg-wp-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'idg_wp_scripts' );

/**
 * Custom excerpt
 */
function idg_excerpt( $limit = 190 ) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);

	if (count($excerpt) >= $limit) {
		array_pop($excerpt);
		$excerpt = implode(" ", $excerpt) . '...';
	} else {
		$excerpt = implode(" ", $excerpt);
	}

	$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);

	return $excerpt;
}

/**
 * Load widgets areas
 */
require get_template_directory() . '/inc/widgets-areas.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Breadcrumb functionality
 */
require get_template_directory() . '/inc/breadcrumb.php';

	/* ========================================================================================================================
	
	Comments
	
	======================================================================================================================== */
	/**
	 * Custom callback for outputting comments 
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	function bootstrap_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; 
		?>
		<?php if ( $comment->comment_approved == '1' ): ?>

			<li class="comment" id="comment-<?php comment_ID() ?>">
				<div class="thumbnail">
					<?php echo get_avatar( $comment ); ?>
				</div>

				<div class="text-wrapper">
					<div class="panel-heading">
						<strong class="media-heading"><?php comment_author_link() ?></strong> <time class="text-muted"><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
					</div>
					<div class="panel-body">
						<?php comment_text() ?>
					</div>
				</div>
			</li>
		<?php endif;
	}
