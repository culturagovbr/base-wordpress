<?php
// adicionando css que faz a função do divi custom css (Carrega por último)
function divi_child_enqueue_styles() {
    // wp_enqueue_style( 'open-sans-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' );
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'feedRead-style', get_stylesheet_directory_uri() . '/assets/css/FeedEk.css');
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css');
    wp_enqueue_style( 'alto-contraste', get_stylesheet_directory_uri() . '/assets/css/alto-contraste.css');
    wp_enqueue_script( 'alto-contraste', get_stylesheet_directory_uri() . '/assets/js/alto-contraste.js');
        wp_enqueue_script( 'jqueryMin', get_stylesheet_directory_uri() . '/assets/js/jquery.min.js');
    wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js');
    wp_enqueue_script( 'feedRead', get_stylesheet_directory_uri() . '/assets/js/FeedEk.js');

    $pdaJSObj = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'post_id' => get_the_ID(),
        'post_name' => get_the_title( get_the_ID() ),
    );

    wp_localize_script( 'scripts', 'pdaJSObj', $pdaJSObj );
}

add_action( 'wp_head', 'divi_child_enqueue_styles' );

// adicionando area editável barra de servico
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => 'barra-servico',
'before_widget' => '<div class="barra-servico">',
'after_widget' => '</div>',
'before_title' => '<h2>',
'after_title' => '</h2>',
));
