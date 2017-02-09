<?php
// adicionando css que faz a função do divi custom css (Carrega por último)
function divi_child_enqueue_styles() {
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/custom.css');
}

add_action( 'wp_head', 'divi_child_enqueue_styles' );

?>
