<?php
if ( ! function_exists( 'mapasculturais_setup' ) ) :
    function mapasculturais_setup() {
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
            'primary-menu' => esc_html__( 'Primary', 'mapasculturais' ),
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
add_action( 'after_setup_theme', 'mapasculturais_setup' );

/**
 * Enqueue scripts and styles.
 */
function mapasculturais_scripts() {
    wp_enqueue_style( 'google-fonts--open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400' );
    wp_enqueue_style( 'mapasculturais-styles', get_template_directory_uri() . '/assets/css/dist/main.min.css' );

    wp_enqueue_script( 'mapasculturais-scripts', get_template_directory_uri() . '/assets/js/dist/main.min.js', array('jquery'), false, true );
    
    wp_localize_script( 'mapasculturais-scripts', 'mapasculturaisJS', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));
}
add_action( 'wp_enqueue_scripts', 'mapasculturais_scripts' );

/**
* Create a custom post type to manage subscriptions
* 
*/
add_action( 'init', 'inscricao_cpt' );
function inscricao_cpt() {
    register_post_type( 'inscricao', array(
        'labels' => array(
            'name' => 'Inscrições Mapas Culturais',
            'singular_name' => 'Inscrição',
            ),
        'description' => 'Inscrições Mapas Culturais.',
        'public' => true,
        'menu_position' => 20,
        'supports' => array( 'title' ),
        'menu_icon' => 'dashicons-clipboard'
    ));
}

add_filter('manage_inscricao_posts_columns' , 'add_inscricao_columns');
function add_inscricao_columns($columns) {
    unset($columns['author']);
    return array_merge($columns, 
        array(
            'responsible' => __('Nome'),
            'estado_ou_municipio' => __( 'Estado ou UF/Município')
        )
    );
}

add_action( 'manage_posts_custom_column' , 'custom_columns', 10, 2 );
function custom_columns( $column, $post_id ) {
    $post_author_id = get_post_field( 'post_author', $post_id );
    $post_author = get_user_by('id', $post_author_id);

    switch ( $column ) {
        case 'responsible':
        echo get_field( 'nome', $post_id );
        break;

        case 'estado_ou_municipio':
        echo get_field( 'estado_ou_municipio', $post_id );
        break;
    }
}

add_filter( 'manage_edit-inscricao_sortable_columns', 'custom_sortable_column' );
function custom_sortable_column( $columns ) {
    $columns['responsible'] = 'responsible';
    $columns['estado_ou_municipio'] = 'estado_ou_municipio';
    return $columns;
}

/**
 * Includes - options page for subscriptions
 */
require get_template_directory() . '/inc/options-page.php';

function main_form($acf_group) {
    // if( $_GET )
    $_POST = array();
    $options = array(
        'field_groups' => array( $acf_group ),
        'id' => 'main-form',
        'post_id'       => 'new_inscricao',
        'new_post'      => array(
            'post_type'     => 'inscricao',
            'post_status'   => 'publish'
        ),
        'uploader' => 'basic',
        'updated_message' => __('Inscrição realizada.', 'acf'),
        'return'        => home_url('/?updated=true#message'),
        'submit_value'  => 'Enviar solicitação',
    );

    return acf_form( $options );
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
 * Process the subscription form
 */
// add_action('acf/pre_save_post', 'process_main_oscar_form');
add_action('acf/pre_save_post', 'process_main_oscar_form');
function process_main_oscar_form( $post_id ) {
    $mapasculturais_options = get_option('mapasculturais_options');

    if( $post_id != 'new_inscricao' ){
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
        'post_title'   => 'Inscrição #' . $post_id
    );
    wp_update_post( $inscricao );
    
    $post = get_post( $post_id );
    $name = 'Inscrição Mapas Culturais';
    $user_email = $_POST['acf'][ $mapasculturais_options['acf_email_id_option'] ];
    // $user_email = get_field('email', $post_id);
    // $user_email = get_post_meta($post_id, 'email', true);

    $to = $user_email;
    $subject = $post->post_title;
    $body = $mapasculturais_options['mapasculturais_email_body'];
    
    if( !wp_mail($to, $subject, $body ) ){
        error_log("O envio de email para: " . $to . ', Falhou!', 0);
    }

    $name = 'Nova inscrição realizada';
    $to = $mapasculturais_options['mapasculturais_monitoring_emails'];
    $subject = 'Nova inscrição (' . $post->post_title . ')';
    $body = 'Uma nova inscrição ao Mapas Culturais acaba de ser concluída. Para visualiza-la, clique <a href="'. admin_url( 'post.php?post='. $post_id .'&action=edit' ) .'">aqui</a>.';
    
    if( !wp_mail($to, $subject, $body ) ){
        error_log("O envio de email de monitormanento para: " . $to . ', Falhou!', 0);
    }
    // Return the new ID
    return $post_id;
}

add_action( 'admin_notices', 'theme_plugin_dependencies' );
function theme_plugin_dependencies() {
    // if( ! function_exists('plugin_function') )
    if( !class_exists('acf') ){
        echo '<div class="error"><p><b>Aviso:</b> Este tema necessita do plugin <a href="https://www.advancedcustomfields.com/" target="_blank">Advanced Custom Fields</a> para funcionar!</p></div>';
    }
}