<?php

if (!defined('WPINC'))
    die();

class SNC_Oficinas_Shortcode_Formulario_Participacao
{
    public function __construct()
    {
        if (!is_admin()) {
            add_action('get_header', array($this, 'add_acf_form_head'), 0);
            add_shortcode('snc-oficina-response-questions', array($this, 'snc_response_questions_form'));
            add_action('acf/pre_save_post', array($this, 'preprocess_main_form'));
            add_action('acf/save_post', array($this, 'postprocess_main_form_update'));
        }
    }

    public function snc_response_questions_form($atts)
    {
        $token = esc_attr($_GET['token']);
        $subscription_id = esc_attr($_GET['id']);

        try {
            ob_start();

            if (!SNC_Oficinas_Validator::is_token_valid($subscription_id, $token, 'token_responder_questionario')) {
                throw new Exception("Erro! Os dados informados são inválidos.");
            }

            $inscricaoPerfil = get_post_meta($subscription_id, 'inscricao_perfil', true);

            $questionarioUrl = "questionario";

            switch ($inscricaoPerfil) {
                case 'Gestor de Cultura':
                    $questionarioUrl .= "-gestor";
                    break;
                case 'Conselheiro de Cultura':
                    $questionarioUrl .= "-conselheiro";
                    break;
                case 'Ponteiro de Cultura':
                    $questionarioUrl .= "-ponteiro";
                    break;
                default:
                    $questionarioUrl .= "";
                    break;
            }

            $url = home_url("/{$questionarioUrl}/?token={$token}&id={$subscription_id}&sent=true#message");

            $atts = shortcode_atts(array(
                'form-group-id' => '',
                'return' => $url
            ), $atts);

            $settings = array(
                'field_groups' => array($atts['form-group-id']),
                'id' => 'snc-question-form',
                'post_id' => SNC_POST_TYPE_PARTICIPACAO,
                'new_post' => array(
                    'post_type' => SNC_POST_TYPE_PARTICIPACAO,
                    'post_status' => 'publish'
                ),
                'updated_message' => 'Questionário enviado com sucesso.',
                'return' => home_url("/inscricoes/?status=finalizado&id={$subscription_id}"),
                'uploader' => 'basic',
                'submit_value' => 'Enviar'
            );

            $status = $_GET['status'];

            $subscription = $this->get_subscription_in_workshop();
            if (count($subscription) == 0) {
                acf_form($settings);
            } else {
                $this->get_message_user_registered();
            }
        } catch (Exception $e) {
            $this->_get_message_subscription_error($e->getMessage());
        } finally {
            return ob_get_clean();
        }
    }

    private function _get_message_subscription_error($message)
    {
        ?>
        <div class="container-fluid">
            <div class="alert alert-danger" role="alert">
                <?= $message; ?>
                <a href="<?= home_url('/inscricoes/') ?>">Clique aqui para consultar o status da inscrição</a>
            </div>
        </div>
        <?php
    }

    private function get_message_register_success()
    {
        ?>
        <div class="alert alert-success" role="alert">
            Usuário cadastrado com sucesso! Preencha os dados abaixo para finalizar a solicitação de inscrição na
            oficina!
        </div>
        <?php
    }

    private function get_message_subscription_pending($subscription)
    {
        ?>
        <div style="margin-bottom: 100px">
            <div class="alert alert-success" role="alert">
                Solicitação de inscrição enviada com sucesso. Confirme seu e-mail para prosseguir com a inscrição.
            </div>

            Se desejar,
            <a href="<?= home_url('/inscricoes/') ?>">clique aqui para acompanhar o status da inscrição</a>
        </div>
        <?php
    }

    private function get_subscription_in_workshop()
    {
        if (is_user_logged_in()) {
            $post = get_posts([
                'author' => get_current_user_id(),
                'post_type' => SNC_POST_TYPE_PARTICIPACAO,
                'post_status' => array('waiting_questions'),
                'posts_per_page' => 1
            ]);

            return $post;
        }

        return false;
    }

    public function add_acf_form_head()
    {
        if (shortcode_exists('snc-question-form')) {
            acf_form_head();
        }
    }

    /**
     * Notify the monitors about a new subscription
     *
     * @param $post_id
     */
    public function postprocess_main_form_update($post_id)
    {
        $subscription_id = esc_attr($_GET['id']);

        $subscription = array('ID' => $subscription_id, 'post_status' => 'finish');


        wp_update_post($subscription);

        delete_post_meta($subscription_id, 'token_responder_questionario');

        $sncEmail = new SNC_Oficinas_Email($subscription_id, 'snc_email_impress_cert');
        $sncEmail->snc_send_mail_user();
    }

    /**
     * Process data before save indication post
     *
     * @param $post_id
     * @return int|void|WP_Error
     */
    public function preprocess_main_form($post_id)
    {
        $subscription_id = esc_attr($_GET['id']);

        if ($post_id != SNC_POST_TYPE_PARTICIPACAO) {
            return $post_id;
        }

        if (is_admin()) {
            return;
        }

        $post = array('post_type' => SNC_POST_TYPE_PARTICIPACAO, 'post_status' => 'publish');
        $post_id = wp_insert_post($post);

        $questionario = array('ID' => $post_id,
            'post_title' => 'Questionário - (ID #' . $post_id . ')',
            'post_parent' => $subscription_id);

        wp_update_post($questionario);

        return $post_id;
    }
}