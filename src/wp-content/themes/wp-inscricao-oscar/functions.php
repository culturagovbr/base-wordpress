<?php
if ( ! function_exists( 'oscar_setup' ) ) :
    function oscar_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on oscar, use a find and replace
         * to change 'oscar' to the name of your theme in all the template files.
         */
        load_theme_textdomain( 'oscar', get_template_directory() . '/languages' );

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
        add_image_size('portfolio-thumb', 332, 200, true);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            'primary-menu' => esc_html__( 'Primary', 'oscar' ),
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

    }
    endif;
    add_action( 'after_setup_theme', 'oscar_setup' );


/**
 * Enqueue scripts and styles.
 */
function oscar_scripts() {
    wp_enqueue_style( 'google-fonts--open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400' );
    wp_enqueue_style( 'oscar-styles', get_template_directory_uri() . '/assets/css/dist/main.min.css' );

    wp_enqueue_script( 'oscar-scripts', get_template_directory_uri() . '/assets/js/dist/main.min.js', array('jquery'), false, true );
    
    wp_localize_script( 'oscar-scripts', 'oscarJS', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
        ));
}
add_action( 'wp_enqueue_scripts', 'oscar_scripts' );

/**
* Disable WordPress Admin Bar for all users but admins.
* 
*/
if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}

/**
* Create a custom post type to manage subscriptions
* 
*/
add_action( 'init', 'inscricao_cpt' );
function inscricao_cpt() {
    register_post_type( 'inscricao', array(
        'labels' => array(
            'name' => 'Inscrições 2018',
            'singular_name' => 'Inscrição',
            ),
        'description' => 'Inscrições Oscar 2018.',
        'public' => true,
        'menu_position' => 20,
        'supports' => array( 'title' ),
        'menu_icon' => 'dashicons-clipboard'
        ));
}

function add_inscricao_columns($columns) {
    unset($columns['author']);
    return array_merge($columns, 
        array(
            'responsible' => __('Proponente'),
            'user_cnpj' => __( 'CNPJ'),
            'movie' => __( 'Filme')
            )
        );
}
add_filter('manage_inscricao_posts_columns' , 'add_inscricao_columns');

function adding_custom_meta_boxes( $post ) {
    add_meta_box( 
        'oscar-video-post',
        'Filme',
        'oscar_video_post_meta_box',
        'inscricao',
        'side',
        'default'
        );
}
add_action( 'add_meta_boxes_inscricao', 'adding_custom_meta_boxes' );

function oscar_video_post_meta_box( $post ) {
    $oscar_movie_name = get_user_meta( $post->post_author, '_oscar_movie_name', true );
    $oscar_movie_path = get_user_meta( $post->post_author, '_oscar_movie_path', true );
    echo '<a href="'. $oscar_movie_path .'" target="_blank">' . $oscar_movie_name . '</a>';
}

add_action( 'manage_posts_custom_column' , 'custom_columns', 10, 2 );
function custom_columns( $column, $post_id ) {
    $post_author_id = get_post_field( 'post_author', $post_id );
    $post_author = get_user_by('id', $post_author_id);

    switch ( $column ) {
        case 'responsible':
        echo $post_author->display_name;
        break;

        case 'user_cnpj':
        echo get_user_meta( $post_author_id, '_user_cnpj', true );
        break;

        case 'movie':
        $oscar_movie_name = get_user_meta( $post_author_id, '_oscar_movie_name', true );
        $oscar_movie_path = get_user_meta( $post_author_id, '_oscar_movie_path', true );
        echo '<a href="'. $oscar_movie_path .'" target="_blank">' . $oscar_movie_name . '</a>';
        break;
    }
}

/**
 * Manage sessions to retrieve user meta
 */
add_action('init', 'oscar_start_session', 1);
add_action('wp_logout', 'oscar_end_session');
add_action('wp_login', 'oscar_end_session');

// Init session
function oscar_start_session() {
    if(!session_id()) {
        session_start();
        $current_user = wp_get_current_user();
        $_SESSION['logged_user_id'] = $current_user->ID;

        $user_cnpj = get_user_meta( $current_user->ID, '_user_cnpj', true ); 
        $_SESSION['logged_user_cnpj'] = $user_cnpj ? $user_cnpj : $current_user->ID;
    }
}

// Clear all sessions
function oscar_end_session() {
    session_destroy();
}

/**
 * Includes - Shortcodes
 */
// Registration form
require get_template_directory() . '/inc/shortcodes/auth-form.php';
// Main form for subscription
require get_template_directory() . '/inc/shortcodes/oscar-main-form.php';
// Video upload form
require get_template_directory() . '/inc/shortcodes/video-upload-form.php';


/**
 * Includes - options page for subscriptions
 */
require get_template_directory() . '/inc/options-page.php';

/**
 * Function responsible for process video upload
 */
function upload_oscar_video() {
    // error_reporting(0);
    // @ini_set('display_errors',0);
    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

        if( get_user_meta( $_SESSION['logged_user_id'], '_oscar_video_sent', true ) ){
            echo 'Seu vídeo já foi enviado';
            exit;
        }

        $oscar_options = get_option('oscar_options');
        $uploads = wp_upload_dir();
        $path = $uploads['basedir'] . '/oscar-videos';

        if (!file_exists( $path )) {
            mkdir($path, 0777, true);
        }

        // Set the valid file extensions 
        // Example: array("jpg", "png", "gif", "bmp", "jpeg", "GIF", "JPG", "PNG", "doc", "txt", "docx", "pdf", "xls", "xlsx"); 
        $valid_formats =  $oscar_options['oscar_movie_extensions'] ? explode(',', $oscar_options['oscar_movie_extensions']) : array('mp4');

        $name = $_FILES['oscarVideo']['name']; // Get the name of the file   
        $nice_name = str_replace( ' ', '_', strtolower( $_FILES['oscarVideo']['name'] ) ); // Remove white-spaces that causes error during move_uploaded_file()
        $size = $_FILES['oscarVideo']['size']; // Get the size of the file

        if (strlen($name)) { // Check if the file is selected or cancelled after pressing the browse button.
            list($txt, $ext) = explode(".", $name); // Extract the name and extension of the file
            if (in_array($ext, $valid_formats)) { // If the file is valid go on.
                // if ($size < 5e+9) { //  Check if the file size is more than 5 Gb
                if ($size < intval($oscar_options['oscar_movie_max_size'])*pow(1024,3) ) { //  Check if the file size is more than 5 Gb
                    $file_name = $_FILES['oscarVideo']['name'];
                    $tmp = $_FILES['oscarVideo']['tmp_name'];

                    // Check if path folder exists and has correct permissions
                    if (!is_writeable( $path )) {
                        printf('"%s" o diretório não possuir permissão de escrita.', $path);
                        error_log("Impossível criar arquivo no destino: " . $path, 0);
                    } else {
                        $unique_folder_based_on_cnpj = str_replace('.', '',  str_replace('-', '', str_replace('/', '', $_SESSION['logged_user_cnpj']) ) );
                        // Creates a unique folder to upload files (based on user CNPJ)
                        if (!file_exists( $path . '/' . $unique_folder_based_on_cnpj )) {
                            mkdir($path . '/' . $unique_folder_based_on_cnpj, 0777, true);
                        }

                        // Check if it the file move successfully.
                        if (move_uploaded_file($tmp, $path . '/' . $unique_folder_based_on_cnpj .'/'. $name)) {
                            update_user_meta( $_SESSION['logged_user_id'], '_oscar_movie_name', $name );
                            update_user_meta( $_SESSION['logged_user_id'], '_oscar_movie_path', $uploads['baseurl'] . '/oscar-videos' . '/' . $unique_folder_based_on_cnpj .'/'. $name );
                            update_user_meta( $_SESSION['logged_user_id'], '_oscar_video_sent', true );
                            echo $oscar_options['oscar_movie_uploaded_message'];
                            oscar_video_sent_confirmation_email( $_SESSION['logged_user_id'] );
                        } else {
                            echo 'Falha ao mover arquivo para pasta destino';
                        }
                    }
                } else {
                    echo 'O tamanho do arquivo excede o limite de '. $oscar_options['oscar_movie_max_size'] .'Gb.';
                }
            } else {
                echo 'Formato de arquivo inválido.';
            }
        } else {
            echo 'Selecione um arquivo para realizar o upload';
        }
    }

    die;
}
add_action('wp_ajax_upload_oscar_video', 'upload_oscar_video');
add_action('wp_ajax_nopriv_upload_oscar_video', 'upload_oscar_video');

function oscar_video_sent_confirmation_email ( $user_id ) {
    $oscar_options = get_option('oscar_options');
    $user = get_user_by( 'ID', $user_id );
    $name = 'Inscrições Oscar 2018 - Vídeo Recebido';
    
    $to = $user->user_email;
    $subject = 'Confirmação de vídeo recebido';
    $body = $oscar_options['oscar_email_body_video_received'];
    
    // Send email
    if( !wp_mail($to, $subject, $body ) ){
        error_log("O envio de email para: " . $to . ', Falhou!', 0);
    }
}

// add_filter( 'wp_mail_content_type', 'set_html_content_type' );
function set_html_content_type() {
    return 'text/html';
}

/**
 * Process the subscription form
 */
add_action('acf/pre_save_post', 'process_main_oscar_form');
function process_main_oscar_form( $post_id ) {
    $oscar_options = get_option('oscar_options');

    if( $post_id != 'new_inscricao' ){

        $post = get_post( $post_id );

        if( $post->post_type !== 'inscricao' ){
            return $post_id;
        }

        $name = 'Atualização de cadastro para o Oscar 2018';
        $oscar_monitoring_emails = explode(',', $oscar_options['oscar_monitoring_emails']);
        $oscar_monitoring_emails = array_map('trim', $oscar_monitoring_emails);

        $to = $oscar_monitoring_emails;
        $subject = 'Atualização ' . $post->post_title;
        $body = 'Uma inscrição ao Oscar 2018 acaba de ser editada. Para visualiza-la, clique <a href="'. admin_url( 'post.php?post='. $post_id .'&action=edit' ) .'">aqui</a>.';
        
        if( !wp_mail($to, $subject, $body ) ){
            error_log("O envio de email para: " . $to . ', Falhou!', 0);
        }

        return $post_id;
    }

    if( is_admin() ) {
        return;
    }

    $post = get_post( $post_id );

    $post = array(
        'post_type' => 'inscricao',
        'post_status' => 'publish'
        );
    $post_id = wp_insert_post( $post );

    $inscricao = array(
        'ID'           => $post_id,
        'post_title'   => 'Oscar 2018 (Inscrição #' . $post_id . ')'
        );
    wp_update_post( $inscricao );

    $current_user = wp_get_current_user();
    add_user_meta( $current_user->ID, '_inscricao_id', $post_id, true );
    
    $post = get_post( $post_id );
    $name = 'Inscrições Oscar 2018';
    
    $to = $current_user->user_email;
    $subject = $post->post_title;
    $body = $oscar_options['oscar_email_body'];
    
    if( !wp_mail($to, $subject, $body ) ){
        error_log("O envio de email para: " . $to . ', Falhou!', 0);
    }

    $name = 'Novo cadastro para o Oscar 2018';
    $to = $oscar_options['oscar_monitoring_emails'];
    $subject = 'Novo cadastro ' . $post->post_title;
    $body = 'Uma nova inscrição ao Oscar 2018 acaba de ser concluída. Para visualiza-la, clique <a href="'. admin_url( 'post.php?post='. $post_id .'&action=edit' ) .'">aqui</a>.';
    
    if( !wp_mail($to, $subject, $body ) ){
        error_log("O envio de email para: " . $to . ', Falhou!', 0);
    }

    // Return the new ID
    return $post_id;
}

/**
 * Disable acf css on front-end acf forms
 */
add_action( 'wp_print_styles', 'my_deregister_styles', 100 );
function my_deregister_styles() {
    wp_deregister_style( 'wp-admin' );
    wp_deregister_style( 'acf' );
    wp_deregister_style( 'acf-field-group' );
    wp_deregister_style( 'acf-global' );
    wp_deregister_style( 'acf-input' );
    wp_deregister_style( 'acf-datepicker' );
}
remove_filter( 'wp_signup_location', 'custom_register_redirect' );

/**
 * Login user, after registration
 */
function auto_login_new_user( $user_id ) {
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    wp_redirect( home_url() );
    exit;
}
// add_action( 'user_register', 'auto_login_new_user' );

/**
 * Redirect user to home after successful login.
 * 
 */
function my_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        //check for admins
        if ( in_array( 'administrator', $user->roles ) ) {
            // redirect them to the default place
            return $redirect_to;
        } elseif( in_array( 'committee_manager', $user->roles )  ) {
            return admin_url('edit.php?post_type=inscricao');
        } else {
            return home_url();
        }
    } else {
        return $redirect_to;
    }
}
// add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );

/**
 * Redirect user when logged (or not-logged) to correct url
 *
 */
function redirect_logged_user_to_profile_page()
{
    if (is_user_logged_in() && is_page('login')) {
        wp_redirect(home_url());
        exit;
    }

    if ( !is_user_logged_in() && is_front_page() || !is_user_logged_in() && is_page('enviar-video') ) {
        wp_redirect(home_url('/login'));
        exit;
    }
}
add_action('template_redirect', 'redirect_logged_user_to_profile_page');

/**
 * Limit Access to WordPress Dashboard
 */
function block_users_from_access_dashboard() {
    if ( is_admin() && !current_user_can( 'level_7' ) && !current_user_can( 'committee_manager' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url() );
        exit;
    }
}
add_action( 'init', 'block_users_from_access_dashboard' );

// Hook the appropriate WordPress action
add_action('init', 'prevent_wp_login');

function prevent_wp_login() {
    global $pagenow;
    $action = (isset($_GET['action'])) ? $_GET['action'] : '';
    // if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass'))))) {
    if( $pagenow == 'wp-login.php' && !empty($_GET['loggedout']) ) { ?>

    <script type="text/javascript">
        window.setTimeout( function(){
            window.location = '<?php echo home_url(); ?>';
        }, 1000);
    </script>

        <?php // exit();
    }
}

/**
* Assessment committee role
* 
*/
function add_roles_on_plugin_activation() {
    // remove_role( 'committee_manager' );
    add_role( 'committee_manager', 'Comitê de avaliação', array(
        'read'         => true, 
        'edit_posts'   => true,
        'edit_published_posts'   => true,
        'edit_others_posts'   => true, 
        'delete_posts' => true,
        'delete_others_posts' => true
        ) 
    );
    // flush_rewrite_rules();
}
add_action( 'admin_init', 'add_roles_on_plugin_activation' );

/**
 * Remove itens desncessários da barra de administracao para usuários assinantes
 * 
 */
function remove_toolbar_nodes($wp_admin_bar) {
    $user = wp_get_current_user();
    if ( $user->roles[0] === 'committee_manager') {
        $wp_admin_bar->remove_node('my-sites');
        $wp_admin_bar->remove_node('menu-posts');
        $wp_admin_bar->remove_node('new-content');
        $wp_admin_bar->remove_node('comments');
    }
}
add_action('admin_bar_menu', 'remove_toolbar_nodes', 999);

/**
 * Remove a top level admin menu
 */
function remove_menus(){
    if ( is_admin() && !current_user_can( 'administrator' ) ){
        remove_menu_page( 'index.php' );
        remove_menu_page( 'jetpack' );
        remove_menu_page( 'edit.php' );
        remove_menu_page( 'edit.php?post_type=incsub_wiki' );
        remove_menu_page( 'edit.php?post_type=page' );
        remove_menu_page( 'upload.php' );
        remove_menu_page( 'edit-comments.php' );
        remove_menu_page( 'tools.php' );
        remove_menu_page( 'options-general.php' );
        remove_menu_page( 'admin' );
        remove_menu_page( 'admin.php?page=wpcf7' );
        remove_menu_page( 'admin.php?page=campaign_contact' );
    }
}
add_action( 'admin_menu', 'remove_menus', 999 );

/**
 * Apply custom css to admin area
 * Hiding itens for non admin users
 */
function oscar_admin_custom_style() {
    if ( is_admin() && !current_user_can( 'administrator' ) ) {
        echo '<style>
        #wtoplevel_page_wpcf7,
        #toplevel_page_wpcf7,
        #toplevel_page_campaign_contact,
        tr.type-inscricao .row-actions .view{
            display: none !important;
        }
        </style>';
    }
}
add_action('admin_head', 'oscar_admin_custom_style');
