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
    wp_enqueue_style( 
        'fancyBox3-style',
        get_stylesheet_directory_uri() . '/assets/css/jquery.fancybox.min.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_style( 
        'divi-seinfra-styles',
        get_stylesheet_directory_uri() . '/assets/css/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );

    wp_enqueue_script( 
        'fancyBox3-scripts', 
        get_stylesheet_directory_uri() . '/assets/js/jquery.fancybox.min.js', 
        array('jquery'), 
        false, 
        wp_get_theme()->get('Version')
    );
    wp_enqueue_script( 
        'divi-seinfra-scripts', 
        get_stylesheet_directory_uri() . '/assets/js/scripts.js', 
        array('jquery'), 
        false, 
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'seinfra_enqueue_styles' );

/**
 * Adiciona um visual diferente para a página de login
 * 
 */
function seinfra_login_page_style() { ?>
    <style type="text/css">
        #loginform{
            background: none;
            box-shadow: none;
            padding: 0;
        }
        #login h1, 
        .login h1,
        #backtoblog {
            display: none;
        }
        h3,
        #nav{
            text-align: center;
        }
        h3{
            font-size: 16px;
            margin-bottom: 30px !important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'seinfra_login_page_style' );

 
function my_login_logo_url_title() {
    return '<h3>Para visualizar esse site é necessário realizar o login abaixo.</h3>';
}
add_filter( 'login_message', 'my_login_logo_url_title' );