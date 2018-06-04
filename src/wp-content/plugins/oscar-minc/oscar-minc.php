<?php
/**
 * Plugin Name:       Oscar Minc
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       @TODO
 * Version:           1.1.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/darciro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('OscarMinC')) :

    class OscarMinC
    {
        public function __construct()
        {
            require_once dirname( __FILE__ ) . '/inc/options-page.php';

            register_activation_hook(__FILE__, array($this, 'activate_oscar_minc'));
            add_action('init', array($this, 'inscricao_cpt'));
            add_filter('manage_inscricao_posts_columns', array($this, 'add_inscricao_columns'));
            add_action('manage_posts_custom_column', array($this, 'inscricao_custom_columns'), 10, 2);
            add_action('init', array($this, 'oscar_shortcodes'));
            add_action('acf/pre_save_post', array($this, 'preprocess_main_form'));
            add_action('acf/save_post', array($this, 'postprocess_main_form'));
            add_action('get_header', 'acf_form_head');
            add_action('wp_enqueue_scripts', array($this, 'register_oscar_minc_styles'));
            add_action('wp_enqueue_scripts', array($this, 'register_oscar_minc_scripts'));
            add_filter('wp_mail_content_type', array($this, 'set_email_content_type'));
            add_filter('wp_mail_from', array($this, 'oscar_minc_wp_mail_from'));
            add_filter('wp_mail_from_name', array($this, 'oscar_minc_wp_mail_from_name'));
            add_action('wp_ajax_upload_oscar_video', array($this, 'upload_oscar_video'));
            add_action('wp_ajax_nopriv_upload_oscar_video', array($this, 'upload_oscar_video'));
        }

        /**
         * Fired during plugin activation, check for dependency
         *
         */
        public static function activate_oscar_minc()
        {
            if (!is_plugin_active('advanced-custom-fields-pro/acf.php') && !is_plugin_active('advanced-custom-fields/acf.php')) {
                echo 'Para que este plugin funcione corretamente, é necessário a instalação e ativação do plugin ACF - <a href="http://advancedcustomfields.com/" target="_blank">Advanced custom fields</a>.';
                die;
            }
        }

        /**
         * Create a custom post type to manage indications
         *
         */
        public function inscricao_cpt()
        {
            register_post_type('inscricao', array(
                    'labels' => array(
                        'name' => 'Inscrições Oscar',
                        'singular_name' => 'Inscrição',
                        'add_new' => 'Nova inscrição',
                        'add_new_item' => 'Nova inscrição',
                    ),
                    'description' => 'Inscrições OscarMinC',
                    'public' => true,
                    'exclude_from_search' => false,
                    'publicly_queryable' => false,
                    'supports' => array('title'),
                    'menu_icon' => 'dashicons-clipboard')
            );
        }

        /**
         * Add new columns to our custom post type
         *
         * @param $columns
         * @return array
         */
        public function add_inscricao_columns($columns)
        {
            unset($columns['author']);
            return array_merge($columns, array(
                'responsible' => 'Proponente',
                'user_cnpj' => 'CNPJ',
                'movie' => 'Filme'
            ));
        }

        /**
         * Fill custom columns with data
         *
         * @param $column
         * @param $post_id
         */
        public function inscricao_custom_columns($column, $post_id)
        {
            $post_author_id = get_post_field('post_author', $post_id);
            $post_author = get_user_by('id', $post_author_id);

            switch ($column) {
                case 'responsible':
                    echo $post_author->display_name;
                    break;
                case 'user_cnpj':
                    echo get_user_meta($post_author_id, '_user_cnpj', true);
                    break;
                case 'movie':
                    $oscar_movie_name = get_field('titulo_do_filme');
                    $oscar_movie_path = get_user_meta($post_author_id, '_oscar_movie_path', true);
                    echo '<a href="' . $oscar_movie_path . '" target="_blank">' . $oscar_movie_name . '</a>';
                    break;
            }
        }

        /**
         * Shortcode to show ACF form
         *
         * @param $atts
         * @return string
         */
        public function oscar_shortcodes($atts)
        {
            require_once plugin_dir_path( __FILE__ ) . 'inc/shortcodes.php';
            $oscar_minc_shortcodes = new Oscar_Minc_Shortcodes();
        }

        /**
         * Process data before save indication post
         *
         * @param $post_id
         * @return int|void|WP_Error
         */
        public function preprocess_main_form($post_id)
        {
            if ($post_id != 'new_inscricao') {
                return $post_id;
            }

            if (is_admin()) {
                return;
            }

            $post = get_post($post_id);
            $post = array('post_type' => 'inscricao', 'post_status' => 'publish');
            $post_id = wp_insert_post($post);

            $inscricao = array('ID' => $post_id, 'post_title' => 'Inscrição - (ID #' . $post_id . ')');
            wp_update_post($inscricao);

            // Return the new ID
            return $post_id;
        }

        /**
         * Notify the monitors about a new indication
         *
         * @param $post_id
         */
        public function postprocess_main_form($post_id)
        {
            $oscar_minc_options = get_option('oscar_minc_options');
            $monitoring_emails = explode(',', $oscar_minc_options['oscar_minc_monitoring_emails']);
            $to = array_map('trim', $monitoring_emails);
            $headers[] = 'From: ' . bloginfo('name') . ' <automatico@cultura.gov.br>';
            $headers[] = 'Reply-To: ' . $oscar_minc_options['oscar_minc_email_from_name'] . ' <' . $oscar_minc_options['oscar_minc_email_from'] . '>';
            $subject = 'Nova indicação recebida em Ordem do Mérito Cultural.';

            $body = '<h1>Olá,</h1>';
            $body .= '<p>Uma nova indicação foi recebida em Ordem do Mérito Cultural.</p><br>';
            $body .= '<p>Segmentos Culturais: <b>' . get_field('segmentos_culturais', $post_id) . '</b></p>';
            $body .= '<p>Nome do Indicado: <b>' . get_field('nome_completo_do_indicado', $post_id) . '</b></p>';
            $body .= '<p>Nome Artístico do Indicado: <b>' . get_field('nome_artistico_do_indicado', $post_id) . '</b></p>';
            $body .= '<p>Inscrição In Memoriam: <b>' . get_field('inscricao_in_memoriam', $post_id) . '</b></p>';
            $body .= '<p>Sexo: <b>' . get_field('sexo', $post_id) . '</b></p>';
            $body .= '<p>Endereço do Indicado: <b>' . get_field('endereco_do_indicado', $post_id) . '</b></p>';
            $body .= '<p>CEP do Indicado: <b>' . get_field('cep_do_indicado', $post_id) . '</b></p>';
            $body .= '<p>Telefone Residencial do Indicado: <b>' . get_field('telefone_residencial_do_indicado', $post_id) . '</b></p>';
            $body .= '<p>Telefone Celular do Indicado: <b>' . get_field('telefone_celular_do_indicado', $post_id) . '</b></p>';
            $body .= '<p>E-mail do Indicado: <b>' . get_field('e-mail_do_indicado', $post_id) . '</b></p>';
            $body .= '<p>Justificativa (Breve Currículo): <b>' . get_field('justificativa_breve_curriculo', $post_id) . '</b></p>';
            $body .= '<p>Nome de quem Indicou: <b>' . get_field('nome_completo_de_quem_indicou', $post_id) . '</b></p>';
            $body .= '<p>Sexo de quem Indicou: <b>' . get_field('sexo_de_quem_indicou', $post_id) . '</b></p>';
            $body .= '<p>Endereço de quem Indicou: <b>' . get_field('endereco_de_quem_indicou', $post_id) . '</b></p>';
            $body .= '<p>CEP de quem Indicou: <b>' . get_field('cep_de_quem_indicou', $post_id) . '</b></p>';
            $body .= '<p>Telefone Residencial de quem Indicou: <b>' . get_field('telefone_residencial_de_quem_indicouTexto', $post_id) . '</b></p>';
            $body .= '<p>Telefone Celular de quem Indicou: <b>' . get_field('telefone_celular_de_quem_indicou', $post_id) . '</b></p>';
            $body .= '<p>E-mail de quem Indicou: <b>' . get_field('e-mail_de_quem_indicou', $post_id) . '</b></p>';
            $body .= '<p><br>Para visualiza-la, clique <a href="' . admin_url('post.php?post=' . $post_id . '&action=edit') . '">aqui</a>.<p>';
            $body .= '<br><br><p><small>Você recebeu este email pois está cadastrado para monitorar as indicações à Ordem do Mérito Cultural. Para deixar de monitorar, remova seu email das configurações, em: <a href="' . admin_url('edit.php?post_type=inscricao&page=inscricao-options-page') . '">Configurações OscarMinC</a></small><p>';

            if (!wp_mail($to, $subject, $body, $headers)) {
                error_log("ERRO: O envio de email de monitoramento para: " . $to . ', Falhou!', 0);
            }

        }

        /**
         * Register stylesheet for our plugin
         *
         */
        public function register_oscar_minc_styles()
        {
            wp_register_style('oscar-minc-styles', plugin_dir_url(__FILE__) . 'assets/oscar-minc.css');
            wp_enqueue_style('oscar-minc-styles');
        }

        /**
         * Register JS for our plugin
         *
         */
        public function register_oscar_minc_scripts()
        {
            wp_enqueue_script('oscar-minc-scripts', plugin_dir_url(__FILE__) . 'assets/oscar-minc.js', array('jquery'), false, true);
            wp_localize_script( 'oscar-minc-scripts', 'oscar_minc_vars', array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'upload_file_nonce' => wp_create_nonce( 'oscar-video' ),
                )
            );
        }

        /**
         * Set the mail content to accept HTML
         *
         * @param $content_type
         * @return string
         */
        public function set_email_content_type($content_type)
        {
            return 'text/html';
        }

        /**
         * Set email sender
         *
         * @param $content_type
         * @return mixed
         */
        public function oscar_minc_wp_mail_from($content_type)
        {
            $oscar_minc_options = get_option('oscar_minc_options');
            return $oscar_minc_options['oscar_minc_email_from'];
        }

        /**
         * Set sender name for emails
         *
         * @param $name
         * @return mixed
         */
        public function oscar_minc_wp_mail_from_name($name)
        {
            $oscar_minc_options = get_option('oscar_minc_options');
            return $oscar_minc_options['oscar_minc_email_from_name'];
        }

        public function upload_oscar_video()
        {
            check_ajax_referer( 'oscar-video', 'nonce' );
            $wp_upload_dir = wp_upload_dir();
            $file_path     = trailingslashit( $wp_upload_dir['path'] ) . $_POST['file'];
            $file_data     = $this->decode_chunk( $_POST['file_data'] );
            if ( false === $file_data ) {
                wp_send_json_error();
            }
            file_put_contents( $file_path, $file_data, FILE_APPEND );
            wp_send_json_success();
        }

        public function decode_chunk( $data ) {
            $data = explode( ';base64,', $data );
            if ( ! is_array( $data ) || ! isset( $data[1] ) ) {
                return false;
            }
            $data = base64_decode( $data[1] );
            if ( ! $data ) {
                return false;
            }
            return $data;
        }

    }

    // Initialize our plugin
    $oscar_minc = new OscarMinC();

endif;
