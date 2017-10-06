<?php
if ( ! function_exists( 'diretrizesemetas_setup' ) ) :
    function diretrizesemetas_setup() {
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
            'primary-menu' => esc_html__( 'Primary', 'diretrizesemetas' ),
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
add_action( 'after_setup_theme', 'diretrizesemetas_setup' );

/**
 * Enqueue scripts and styles.
 */
function diretrizesemetas_scripts() {
    wp_enqueue_style( 'open-sans-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' );
    wp_enqueue_style( 'diretrizesemetas-styles', get_template_directory_uri() . '/assets/css/dist/main.min.css' );

    wp_enqueue_script( 'diretrizesemetas-scripts', get_template_directory_uri() . '/assets/js/dist/main.min.js', array('jquery'), false, true );
    
    wp_localize_script( 'diretrizesemetas-scripts', 'diretrizesemetasJS', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));
}
add_action( 'wp_enqueue_scripts', 'diretrizesemetas_scripts' );

/**
* Create a custom post type to manage subscriptions
* 
*/
add_action( 'init', 'inscricao_cpt' );
function inscricao_cpt() {
    register_post_type( 'inscricao', array(
        'labels' => array(
            'name' => 'Diretrizes e Metas',
            'singular_name' => 'Diretrizes e Metas',
            ),
        'description' => 'Inscrições.',
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
        'updated_message' => __('Ação incluída com sucesso.', 'acf'),
        'return'        => home_url('/?updated=true#message'),
        'submit_value'  => 'Incluir nova ação',
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
 * Set the mail content to accept HTML
 */
add_filter( 'wp_mail_content_type', 'set_content_type' );
function set_content_type( $content_type ) {
    return 'text/html';
}

/**
 * * Set sender email
 */
add_filter('wp_mail_from','mapas_wp_mail_from');
function mapas_wp_mail_from($content_type) {
    $diretrizesemetas_options = get_option('diretrizesemetas_options');
    return $diretrizesemetas_options['diretrizesemetas_email_from'];
}

/**
 * Set sender name for emails
 */
add_filter('wp_mail_from_name','mapas_wp_mail_from_name');
function mapas_wp_mail_from_name($name) {
    $diretrizesemetas_options = get_option('diretrizesemetas_options');
    return $diretrizesemetas_options['diretrizesemetas_email_from_name'];
}

/**
 * Process the subscription form
 */
add_action('acf/pre_save_post', 'preprocess_main_form');
function preprocess_main_form( $post_id ) {
    $diretrizesemetas_options = get_option('diretrizesemetas_options');

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
        'post_title'   => 'Ação Estratégica - #' . $post_id
    );
    wp_update_post( $inscricao );
    
    /*
    $post = get_post( $post_id );
    $name = 'Inscrição Mapas Culturais';
    $to = $_POST['acf'][ $diretrizesemetas_options['acf_email_id_option'] ];
    $subject = $post->post_title;
    $body = $diretrizesemetas_options['diretrizesemetas_email_body'];
    $headers[] = 'Reply-To: '. $diretrizesemetas_options['diretrizesemetas_email_from_name'] .' <'. $diretrizesemetas_options['diretrizesemetas_email_from'] .'>';
    */
   
    /* if( !wp_mail($to, $subject, $body, $headers) ){
        error_log("ERRO: O email para: " . $to . ' com a confirmação do envio de formulário, falhou!', 0);
    }*/ 

    // send_notification_about_subscriptions_received($post_id);
    // Return the new ID
    return $post_id;
}

function postprocess_main_form( $post_id ) {
    $name = 'Nova ação estratégica recebida';
    $to = $diretrizesemetas_options['diretrizesemetas_monitoring_emails'];
    $subject = 'Nova ação estratégica recebida';
    $body = '<p>Uma nova ação estratégica acaba de ser recebida.</p>';
    
    $unidade = get_field('unidade', $post_id);
    $pilares = get_field('pilares', $post_id);
    $natureza_da_entrega = get_field('natureza_da_entrega', $post_id);
    $produto_entrega = get_field('produto_entrega', $post_id);
    $descricao = get_field('descricao', $post_id);
    $data_limite = get_field('data_limite', $post_id);
    $custo = get_field('custo', $post_id);
    $situacao = get_field('situacao', $post_id);
    $percentual_execucao = get_field('percentual_execucao', $post_id);

    $body .= "<p><b>ID:</b> $post_id</p>";
    $body .= "<p><b>Unidade:</b> $unidade</p>";
    $body .= "<p><b>Pilares:</b> $pilares</p>";
    $body .= "<p><b>Natureza da entrega:</b> $natureza_da_entrega</p>";
    $body .= "<p><b>Produto/Entrega:</b> $produto_entrega</p>";
    $body .= "<p><b>Descrição:</b> $descricao</p>";
    $body .= "<p><b>Data limite:</b> $data_limite</p>";
    $body .= "<p><b>Custo:</b> $custo</p>";
    $body .= "<p><b>Situação:</b> $situacao</p>";
    $body .= "<p><b>Percentual de execução:</b> $percentual_execucao</p>";

    if( have_rows('acoes_etapas', $post_id) ):
        $body .= "<p><b>Ações/Etapas necessárias à execução do Produto/Entrega:</b></p>";
        $body .= "<ul>";
        while ( have_rows('acoes_etapas', $post_id) ) : the_row();
            $body .= "<li>";
            $body .= "<b>Ação/Etapa:</b> ". get_sub_field('acao_etapas') . "<br>";
            $body .= "<b>Descrição:</b> ". get_sub_field('descricao') . "<br>";
            $body .= "<b>Prazo:</b> ". get_sub_field('prazo') . "<br>";
            $body .= "<b>Custo:</b> ". get_sub_field('custo') . "<br>";
            $body .= "<b>Percentual de execução:</b> ". get_sub_field('percentual_execucao') . "<br>";
            $body .= "</li>";
        endwhile;
        $body .= "</ul>";
    endif;


    $body .= '<p>Para visualiza-la, clique <a href="'. admin_url( 'post.php?post='. $post_id .'&action=edit' ) .'">aqui</a>.<p>';
    
    wp_die($body);
    if( !wp_mail($to, $subject, $body, $headers ) ){
        error_log("ERRO: O envio de email de monitormanento para: " . $to . ', Falhou!', 0);
    }
    
}

add_action('acf/save_post', 'postprocess_main_form', 20);

function send_notification_about_subscriptions_received($post_id) {
    $name = 'Nova ação estratégica recebida';
    $to = $diretrizesemetas_options['diretrizesemetas_monitoring_emails'];
    $subject = 'Nova ação estratégica recebida';
    $body = '<p>Uma nova ação estratégica acaba de ser recebida.</p>';
    /*$i = 0;
    $labels = array(
        'Estado ou UF/Município',
        'Nome',
        'Email',
        'Cargo',
        'Telefone',
        'À qual unidade você pertence?',
        'Outras?',
        'Qual seu nível de conhecimento do software Mapas Culturais?'
    );
    foreach ($_POST['acf'] as $key => $val) {
        $body .= '<p><b>'. $labels[$i] .':</b> '. $val .'</p>';
        $i++;
    }*/
    $post = get_post($post_id);
    $unidade = get_field('unidade', $post_id);
    $pilares = get_field('pilares', $post_id);
    $natureza_da_entrega = get_field('natureza_da_entrega', $post_id);
    $produto_entrega = get_field('produto_entrega', $post_id);
    $descricao = get_field('descricao', $post_id);
    $data_limite = get_field('data_limite', $post_id);
    $custo = get_field('custo', $post_id);
    $situacao = get_field('situacao', $post_id);
    $percentual_execucao = get_field('percentual_execucao', $post_id);

    $body .= "<p>ID: $post_id</p>";
    $body .= "<p>Unidade: $unidade</p>";
    $body .= "<p>Pilares: $pilares</p>";
    $body .= "<p>Natureza da entrega: $natureza_da_entrega</p>";
    $body .= "<p>Produto/Entrega: $produto_entrega</p>";
    $body .= "<p>Descrição: $descricao</p>";
    $body .= "<p>Data limite: $data_limite</p>";
    $body .= "<p>Custo: $custo</p>";
    $body .= "<p>Situação: $situacao</p>";
    $body .= "<p>Percentual de execução: $percentual_execucao</p>";


    $body .= '<p>Para visualiza-la, clique <a href="'. admin_url( 'post.php?post='. $post_id .'&action=edit' ) .'">aqui</a>.<p>';
    
    wp_die($body);
    if( !wp_mail($to, $subject, $body, $headers ) ){
        error_log("ERRO: O envio de email de monitormanento para: " . $to . ', Falhou!', 0);
    }
}

add_action( 'admin_notices', 'theme_plugin_dependencies' );
function theme_plugin_dependencies() {
    if( !class_exists('acf') ){
        echo '<div class="error"><p><b>Aviso:</b> Este tema necessita do plugin <a href="https://www.advancedcustomfields.com/" target="_blank">Advanced Custom Fields</a> para funcionar!</p></div>';
    }
}

/**
 * Add a custom JS to load on site
 */
add_action('wp_head', 'form_custom_js');
function form_custom_js() { 

    if( !isset($_GET['updated']) ){
        return;
    }
    ?>
    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                $('#modal-inscricao').modal('show');
            });
        })(jQuery);
    </script>
<?php }

/**
 * Debug email sent (but prevent from sending)
 */
function test_wp_mail($args)
{
    $debug = "<pre>" . var_export($args, true) . "</pre>";
    wp_die($debug);
}
add_filter('wp_mail', 'test_wp_mail');