<?php
/**
 * Redireciona os usuários para a página de login, caso não estejam devidamente logados
 * 
 */
function redirect_non_logged_users() {
	if( !is_user_logged_in() && ! preg_match('/sendpress/', $_SERVER['REQUEST_URI']) ){
    	wp_redirect(  home_url() . '/wp-login.php' ); 
		exit;
	}
}
add_action( 'template_redirect', 'redirect_non_logged_users' , 0);

/**
 * Remove itens desncessários da barra de administracao para usuários assinantes
 * 
 */
function remove_toolbar_nodes($wp_admin_bar) {
    $user = wp_get_current_user();
    if ( $user->roles[0] === 'jaiminho_manager') {
        $wp_admin_bar->remove_node('new-content');
        $wp_admin_bar->remove_node('site-name');
        $wp_admin_bar->remove_node('my-sites');
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
    if ( $user->roles[0] === 'jaiminho_manager' ){
        remove_menu_page( 'index.php' );
        remove_menu_page( 'admin.php?page=campaign_contact' );
        remove_menu_page( 'edit.php?post_type=event' );
        remove_menu_page( 'admin.php?page=campaign_contact' );
        remove_menu_page( 'edit.php' );
        remove_menu_page( 'upload.php' );
        remove_menu_page( 'themes.php' );
        remove_menu_page( 'plugins.php' );
        remove_menu_page( 'users.php' ); 
        remove_menu_page( 'tools.php' ); 
        remove_submenu_page( 'tools.php', 'ms-delete-site.php?page=domainmapping' ); 
        remove_menu_page( 'options-general.php' );
        remove_menu_page( 'ms-delete-site.php' );
        remove_menu_page( 'wpcf7' );
        remove_menu_page( 'contribua' );
        remove_menu_page( 'cptui_main_menu' );
        remove_menu_page( 'jaiminho-sms/wp-sms.php' );
        remove_menu_page( 'facebook-thumb-fixer' );
        remove_menu_page( 'et_divi_options' );
        remove_menu_page( 'platform-settings' );
        remove_menu_page( 'edit.php?post_type=incsub_wiki' );
        remove_menu_page( 'admin.php?page=incsub_wiki' );
    }
}
add_action( 'admin_init', 'remove_menus', 999 );

/**
 * Aplica CSS personalizado na área administrativa
 * 
 */
function admin_custom_style() {
    $user = wp_get_current_user();
    if ( $user->roles[0] === 'jaiminho_manager' ){ ?>
        <style type="text/css">
            #adminmenu{ margin-top: -18px; }
            #adminmenu li.toplevel_page_campaign_contact,
            #adminmenu li.wp-menu-separator{
                display: none !important;
            }
        </style>';
    <?php }
}
add_action('admin_head', 'admin_custom_style');

/**
 * Adiciona um visual diferente para a página de login
 * 
 */
function jaiminho_login_page_style() { ?>
    <style type="text/css">
    .login{
        background-image: url( <?php echo get_stylesheet_directory_uri(); ?>/jaiminho-background2.jpg);
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }

    #login{
        background: #59a9ff;
        padding: 20px !important;
    }

    #login h1 a{
        height: 282px;
        width: 269px;
        background: url( <?php echo get_stylesheet_directory_uri(); ?>/jaiminho-logo.png)
    }

    .login form{
        background: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    #nav a,
    .login label{
        color: #fff !important;;
    }

    #backtoblog {
        display: none;
    }

    #nav{
        text-align: center;
    }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'jaiminho_login_page_style' );

/**
* Adiciona um perfil para visualizacao do Jaiminho
* 
*/
function add_roles_on_plugin_activation() {
    // remove_role( 'jaiminho_manager' );
    add_role( 'jaiminho_manager', 'Gerenciador de Jaiminho', array( 'manage_options' => true) );
}
add_action( 'admin_init', 'add_roles_on_plugin_activation' );

/**
* Redireciona usuarios com o perfil "jaiminho_manager", para a tela principal do Jaiminho
* 
*/
function jaiminho_login_redirect( $redirect_to, $request, $user ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'jaiminho_manager', $user->roles ) ) {
            return admin_url('admin.php?page=sp-emails');
        } else {
            return admin_url();
        }

    }
}
add_filter( 'login_redirect', 'jaiminho_login_redirect', 10, 3 );

/**
 * Remove a permissão dos perfis em páginas de configuração do site
 *
 */
function remove_permissions_based_on_admin_page()
{
    $current_screen = get_current_screen();
    $user = wp_get_current_user();

    // Páginas de configuração para remover a permissão
    $options_pages = array(
        'options-general',
        'options-writing',
        'options-reading',
        'options-discussion',
        'options-media',
        'options-permalink',
        'ms-delete-site'
    );

    if (in_array($current_screen->id, $options_pages) && $user->roles[0] === 'jaiminho_manager') {
        echo '
        <style>
            html{
                background: #f1f1f1;
            }
            body {
                background: #fff;
                height: 98px;
                color: #444;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                margin: 2em auto;
                padding: 1em 2em;
                max-width: 700px;
                -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
                box-shadow: 0 1px 3px rgba(0,0,0,0.13);
            }
            #error-page {
                margin-top: 50px;
            }
            #error-page p {
                font-size: 14px;
                line-height: 1.5;
                margin: 25px 0 20px;
            }
        </style>
        <body id="error-page">
            <p>Sorry, you are not allowed to access this page.</p>
        </body>';
        exit();
        $role = get_role('jaiminho_manager');
        $role->remove_cap('manage_options');
    }

}

add_action('current_screen', 'remove_permissions_based_on_admin_page');