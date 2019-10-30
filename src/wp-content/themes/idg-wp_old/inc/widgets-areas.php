<?php
/**
 * Register widgets area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function pp_wp_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Main menu area', 'idg-wp' ),
		'id'            => 'main-menu-area',
		'description'   => esc_html__( 'Add widgets here.', 'idg-wp' ),
		'before_widget' => '<div id="%1$s" class="col %2$s"><div class="menu-col">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3 class="menu-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar - Right', 'idg-wp' ),
		'id'            => 'sidebar-right',
		'description'   => esc_html__( 'Add widgets here.', 'idg-wp' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Area', 'idg-wp' ) . ' #1',
		'id' => 'footer-widgets-area-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Area', 'idg-wp' ) . ' #2',
		'id' => 'footer-widgets-area-2',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Area', 'idg-wp' ) . ' #3',
		'id' => 'footer-widgets-area-3',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Area', 'idg-wp' ) . ' #4',
		'id' => 'footer-widgets-area-4',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title' => '</h4>',
	) );
}
add_action( 'widgets_init', 'pp_wp_widgets_init' );