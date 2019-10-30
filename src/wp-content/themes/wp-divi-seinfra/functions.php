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
 * Remove itens desncessários da barra de administracao para usuários assinantes
 * 
 */
function remove_toolbar_nodes($wp_admin_bar) {
    $user = wp_get_current_user();
    if ( $user->roles[0] !== 'administrator' && $user->roles[0] !== 'editor' ) {
        $wp_admin_bar->remove_node('new-content');
        // $wp_admin_bar->remove_node('site-name');
        $wp_admin_bar->remove_node('my-sites');
    }

    if ( $user->roles[0] === 'editor' ) {
        $wp_admin_bar->remove_node('my-sites');
    }
}
add_action('admin_bar_menu', 'remove_toolbar_nodes', 999);

/**
 * Remove menus admininstrativos para usuarios assinantes
 * 
 */
function remove_menus(){
    $user = wp_get_current_user();
    if ( $user->roles[0] !== 'administrator' && $user->roles[0] !== 'editor' ){
        remove_menu_page( 'index.php' );
        remove_menu_page( 'edit.php?post_type=event' );
        remove_menu_page( 'admin.php?page=campaign_contact' );
    }
}
add_action( 'admin_menu', 'remove_menus' );

/**
 * Redireciona o usuário para a página inicial ao logar
 * 
 */
function seinfra_login_redirect() {
  return home_url();
}

add_filter('login_redirect', 'seinfra_login_redirect');

/**
 * Aplica CSS personalizado na área administrativa
 * 
 */
function admin_custom_style() {
    $user = wp_get_current_user();
    if ( $user->roles[0] !== 'administrator' && $user->roles[0] !== 'editor' ){
    // if( !current_user_can('editor') || !current_user_can('administrator') ) {
        echo '<style>
        #adminmenu{
            display: none;
        }
        </style>';
    }
}
add_action('admin_head', 'admin_custom_style');

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

/**
* Remove menus admininstrativos para usuarios assinantes
* 
*/
function login_message_title() {
    return '<h3>Para visualizar esse site é necessário realizar o login abaixo.</h3>';
}
add_filter( 'login_message', 'login_message_title' );

/**
* Adiciona permissões para gerir usuários ao perfil de "Editor"
* 
*/
function editor_add_cap() {
    $edit_editor = get_role('editor'); // Get the user role
    $edit_editor->add_cap('edit_users');
    $edit_editor->add_cap('list_users');
    $edit_editor->add_cap('promote_users');
    $edit_editor->add_cap('create_users');
    $edit_editor->add_cap('add_users');
    $edit_editor->add_cap('delete_users');
}
add_action( 'admin_init', 'editor_add_cap', 1 );