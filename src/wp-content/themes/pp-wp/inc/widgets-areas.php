<?php
function pp_wp_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'pp-wp' ),
		'id'            => 'sidebar-left',
		'description'   => esc_html__( 'Add widgets here.', 'pp-wp' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar - Right', 'pp-wp' ),
		'id'            => 'sidebar-right',
		'description'   => esc_html__( 'Add widgets here.', 'pp-wp' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Area', 'pp-wp' ) . ' #1',
		'id' => 'footer-widgets-area-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Area', 'pp-wp' ) . ' #2',
		'id' => 'footer-widgets-area-2',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Area', 'pp-wp' ) . ' #3',
		'id' => 'footer-widgets-area-3',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => esc_html__( 'Footer Area', 'pp-wp' ) . ' #4',
		'id' => 'footer-widgets-area-4',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title' => '</h4>',
	) );
}
add_action( 'widgets_init', 'pp_wp_widgets_init' );