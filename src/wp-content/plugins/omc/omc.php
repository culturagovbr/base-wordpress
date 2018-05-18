<?php
/**
 * Plugin Name:       Ordem do Mérito Cultural
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       Sistema para definição de indicados à Ordem do Mérito Cultural – OMC
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/darciro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('OMC')) :

    class OMC
    {

        public function __construct()
        {
            add_action('init', array($this, 'indicacao_cpt'));
            add_action ('acf/pre_save_post', array($this, 'preprocess_main_form'));
            add_action ('acf/save_post', array($this, 'postprocess_main_form'));
            add_shortcode('omc', array($this, 'omc_shortcode'));
            add_action( 'get_header', 'acf_form_head' );
            add_action( 'wp_enqueue_scripts', array( $this, 'register_omc_styles' ) );
            add_action ('admin_enqueue_scripts', array( $this, 'register_omc_scripts' ) );
			add_filter ('wp_mail_content_type', array( $this, 'set_email_content_type' ) );
			add_filter ('wp_mail_from', array($this, 'omc_wp_mail_from') );
			add_filter ('wp_mail_from_name', array($this, 'omc_wp_mail_from_name') );

            require_once dirname( __FILE__ ) . '/inc/options-page.php';
        }

        /**
         * Shortcode to show ACF form
         *
         * @param $atts
         * @return string
         */
        public function omc_shortcode($atts)
        {
            $atts = shortcode_atts(array(
                'form-group-id' => '',
                'return'        => home_url ('/?sent=true#message')
            ), $atts);

            ob_start();

            $settings = array(
                'field_groups'      => array($atts['form-group-id']),
                'id'                => 'omc-main-form',
                'post_id'           => 'new_indicacao',
                'new_post'          => array(
                    'post_type'         => 'indicacao',
                    'post_status'       => 'publish'
                ),
                'updated_message'   => 'Indicação enviada com sucesso.',
                'return'            => $atts['return'],
                'submit_value'      => 'Indicar'
            );
            acf_form($settings);

            return ob_get_clean();

        }

        /**
         * Create a custom post type to manage indications
         *
         */
        public function indicacao_cpt()
        {
            register_post_type('indicacao', array(
                'labels' => array(
                    'name' => 'Indicações',
                    'singular_name' => 'Indicação',
                ),
                'description' => 'Indicações OMC',
                'public' => true,
                'supports' => array('title'),
                'menu_icon' => 'dashicons-clipboard')
            );
        }

        /**
         * Process data before save indication post
         *
         * @param $post_id
         * @return int|void|WP_Error
         */
        public function preprocess_main_form ($post_id)
        {
            if ($post_id != 'new_indicacao') {
                return $post_id;
            }

            if (is_admin ()) {
                return;
            }

            $post = get_post ($post_id);
            $post = array('post_type' => 'indicacao', 'post_status' => 'publish');
            $post_id = wp_insert_post ($post);

            $inscricao = array('ID' => $post_id, 'post_title' => 'Indicação - (ID #' . $post_id . ')');
            wp_update_post ($inscricao);

            // Return the new ID
            return $post_id;
        }

		/**
		 * Notify the monitors about a new indication
		 *
		 * @param $post_id
		 */
		public function postprocess_main_form ($post_id)
		{
			$omc_options = get_option ('omc_options');
			$monitoring_emails = explode (',', $omc_options['omc_monitoring_emails']);
			$to = array_map ('trim', $monitoring_emails);
			$headers[] = 'From: '. bloginfo('name') .' <automatico@cultura.gov.br>';
			$headers[] = 'Reply-To: '. $omc_options['omc_email_from_name'] .' <'. $omc_options['omc_email_from'] .'>';
			$subject = 'Nova indicação recebida em Ordem do Mérito Cultural.';

			$body  = '<h1>Olá,</h1>';
			$body .= '<p>Uma nova indicação foi recebida em Ordem do Mérito Cultural.</p><br>';
			$body .= '<p>Segmentos Culturais: <b>'. get_field ('segmentos_culturais', $post_id) .'</b></p>';
			$body .= '<p>Nome do Indicado: <b>'. get_field ('nome_completo_do_indicado', $post_id) .'</b></p>';
			$body .= '<p>Nome Artístico do Indicado: <b>'. get_field ('nome_artistico_do_indicado', $post_id) .'</b></p>';
			$body .= '<p>Indicação In Memoriam: <b>'. get_field ('indicacao_in_memoriam', $post_id) .'</b></p>';
			$body .= '<p>Sexo: <b>'. get_field ('sexo', $post_id) .'</b></p>';
			$body .= '<p>Endereço do Indicado: <b>'. get_field ('endereco_do_indicado', $post_id) .'</b></p>';
			$body .= '<p>CEP do Indicado: <b>'. get_field ('cep_do_indicado', $post_id) .'</b></p>';
			$body .= '<p>Telefone Residencial do Indicado: <b>'. get_field ('telefone_residencial_do_indicado', $post_id) .'</b></p>';
			$body .= '<p>Telefone Celular do Indicado: <b>'. get_field ('telefone_celular_do_indicado', $post_id) .'</b></p>';
			$body .= '<p>E-mail do Indicado: <b>'. get_field ('e-mail_do_indicado', $post_id) .'</b></p>';
			$body .= '<p>Justificativa (Breve Currículo): <b>'. get_field ('justificativa_breve_curriculo', $post_id) .'</b></p>';
			$body .= '<p>Nome de quem Indicou: <b>'. get_field ('nome_completo_de_quem_indicou', $post_id) .'</b></p>';
			$body .= '<p>Sexo de quem Indicou: <b>'. get_field ('sexo_de_quem_indicou', $post_id) .'</b></p>';
			$body .= '<p>Endereço de quem Indicou: <b>'. get_field ('endereco_de_quem_indicou', $post_id) .'</b></p>';
			$body .= '<p>CEP de quem Indicou: <b>'. get_field ('cep_de_quem_indicou', $post_id) .'</b></p>';
			$body .= '<p>Telefone Residencial de quem Indicou: <b>'. get_field ('telefone_residencial_de_quem_indicouTexto', $post_id) .'</b></p>';
			$body .= '<p>Telefone Celular de quem Indicou: <b>'. get_field ('telefone_celular_de_quem_indicou', $post_id) .'</b></p>';
			$body .= '<p>E-mail de quem Indicou: <b>'. get_field ('e-mail_de_quem_indicou', $post_id) .'</b></p>';
			$body .= '<p><br>Para visualiza-la, clique <a href="' . admin_url ('post.php?post=' . $post_id . '&action=edit') . '">aqui</a>.<p>';
			$body .= '<br><br><p><small>Você recebeu este email pois está cadastrado para monitorar as indicações à Ordem do Mérito Cultural. Para deixar de monitorar, remova seu email das configurações, em: <a href="' . admin_url ('edit.php?post_type=indicacao&page=indicacao-options-page') . '">Configurações OMC</a></small><p>';

			if (!wp_mail ($to, $subject, $body, $headers)) {
				error_log ("ERRO: O envio de email de monitoramento para: " . $to . ', Falhou!', 0);
			}

		}

		/**
		 * Register stylesheet for our plugin
		 *
		 */
        public function register_omc_styles ()
        {
            wp_register_style( 'omc-styles', plugin_dir_url( __FILE__ ) . 'assets/omc.css' );
            wp_enqueue_style( 'omc-styles' );
        }

		/**
		 * Register JS for our plugin
		 *
		 */
        public function register_omc_scripts ()
        {
            wp_enqueue_script ('xlsx-core', plugin_dir_url( __FILE__ ) . 'assets/xlsx.core.min.js', false, false, true);
            wp_enqueue_script ('FileSaver', plugin_dir_url( __FILE__ ) . 'assets/FileSaver.min.js', false, false, true);
            wp_enqueue_script ('tableexport', plugin_dir_url( __FILE__ ) . 'assets/tableexport.js', false, false, true);
            wp_enqueue_script ('omc-admin', plugin_dir_url( __FILE__ ) . 'assets/admin.js', false, false, true);
        }

		/**
		 * Set the mail content to accept HTML
		 *
		 * @param $content_type
		 * @return string
		 */
		public function set_email_content_type ($content_type)
		{
			return 'text/html';
		}

		/**
		 * Set email sender
		 *
		 * @param $content_type
		 * @return mixed
		 */
		public function omc_wp_mail_from ($content_type)
		{
			$omc_options = get_option ('omc_options');
			return $omc_options['omc_email_from'];
		}

		/**
		 * Set sender name for emails
		 *
		 * @param $name
		 * @return mixed
		 */
		public function omc_wp_mail_from_name ($name) {
			$omc_options = get_option ('omc_options');
			return $omc_options['omc_email_from_name'];
		}

    }

    // Initialize our plugin
    $gewp = new OMC();

endif;
