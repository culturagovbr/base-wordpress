<?php
/**
 * Redireciona os usuários para a página de login, caso não estejam devidamente logados
 * 
 */
function redirect_non_logged_users() {
	if( !is_user_logged_in() ){
    	wp_redirect(  home_url() . '/wp-login.php' ); 
		exit;
	}
}
add_action( 'template_redirect', 'redirect_non_logged_users' );

/**
 * Registra a folha de scripts do tema pai e adiciona específicas para o tema atual
 * 
 */
function seinfra_enqueue_styles() {

    $parent_style = 'divi';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'divi-seinfra',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'seinfra_enqueue_styles' );