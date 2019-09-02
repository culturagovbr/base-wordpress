<?php
//require_once 'event-framework/event-framework.php';
require_once( get_template_directory() . '/event-framework/event-framework.php'); 
require_once('includes/taxonomy-session-types.php');

function theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
//    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',array( $parent_style ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function khore_child_load_theme_textdomain() {
    load_theme_textdomain( 'khore-child', FALSE,  get_template_directory_uri() . '/languages/' );
}
add_action( 'after_setup_theme', 'khore_child_load_theme_textdomain' );


if (!get_option('curador_criado') == 1){
    update_option('curador_criado', 1);
    add_role('curador', 'Curador', array(
                'read' => true,
                'curate' => true,
            ));
    $admin = get_role('administrator');
    $admin->add_cap('curate');
}
