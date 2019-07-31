<?php

if (!defined('WPINC'))
    die();

class SNC_Oficinas_Shortcode_Formulario_Inscricao
{
    public function __construct()
    {
        if (!is_admin()) {
            add_action('get_header', array($this, 'add_acf_form_head'), 0);
            add_shortcode('snc-subscription-form', array($this, 'snc_minc_subscription_form_shortcode')); // Inscrição
            add_action('acf/pre_save_post', array($this, 'preprocess_main_form'));
            add_action('acf/save_post', array($this, 'postprocess_main_form'));
        }

        add_action('acf/validate_save_post', array($this, 'snc_acf_validate_save_post'), 10);
        add_filter('acf/fields/post_object/query/name=inscricao_oficina_uf', array($this, 'snc_filter_workshops'), 10, 3);
        add_filter('acf/fields/post_object/result/name=inscricao_oficina_uf', array($this, 'snc_filter_workshops_object_result'), 10, 4);

    }

    function snc_acf_validate_save_post()
    {
        if ($_POST['post_id'] != SNC_POST_TYPE_INSCRICOES) {
            return true;
        }

        // check if user is an administrator
        if (current_user_can('manage_options')) {
            // clear all errors
            acf_reset_validation_errors();
        }

        $acf = $_POST["acf"];

        $array_interesses = [
            'field_5d2f47cbbb0b3' => $acf['field_5d2f47cbbb0b3'], // interesse 1
            'field_5d2f48febb0b4' => $acf['field_5d2f48febb0b4'],  // interesse 2
            'field_5d2f490cbb0b5' => $acf['field_5d2f490cbb0b5'],  // interesse 3
            'field_5d2f4919bb0b6' => $acf['field_5d2f4919bb0b6'],  // interesse 4
            'field_5d2f4929bb0b7' => $acf['field_5d2f4929bb0b7']   // interesse 5
        ];

        $tem_duplicado = array_unique($array_interesses) != $array_interesses;
        if (!empty($acf['field_5d2f47cbbb0b3']) && $tem_duplicado) {

            $duplicado = key(array_diff_assoc($array_interesses, array_unique($array_interesses)));
            acf_add_validation_error("acf[{$duplicado}]", 'Item duplicado! Selecione diferentes interesses por ordem de prioridade');
        }

        $inscrito = $this->get_subscription_in_workshop();
        if (!empty($inscrito)) {
            acf_add_validation_error('acf[field_5d125ee09caf3]', 'Você já possui uma inscrição');
        }
    }

    /**
     * Shortcode to show ACF form
     *
     * @param $atts
     * @return string
     */
    public function snc_minc_subscription_form_shortcode($atts)
    {

        $atts = shortcode_atts(array(
            'form-group-id' => '',
            'return' => home_url('/inscricao/?sent=true#message')
        ), $atts);

        $settings = array(
            'field_groups' => array($atts['form-group-id']),
            'id' => 'snc-main-form',
            'post_id' => SNC_POST_TYPE_INSCRICOES,
            'new_post' => array(
                'post_type' => SNC_POST_TYPE_INSCRICOES,
                'post_status' => 'pending'
            ),
            'updated_message' => 'Inscrição enviada com sucesso.',
            'return' => home_url('/inscricao/?status=updated'),
            'uploader' => 'basic',
            'submit_value' => 'Finalizar inscrição'
        );

        $status = $_GET['status'];
        ob_start();
        $subscription = $this->get_subscription_in_workshop();
        if (count($subscription) == 0) {
            $this->get_message_register_success();
            acf_form($settings);
        } else if ($status == 'updated') {
            $this->get_message_subscription_pending(current($subscription));
        } else {
            $this->get_message_user_registered();
        }

        return ob_get_clean();
    }

    private function get_message_header()
    {
        ?>
        <p>
            Tendo em vista que as vagas são limitadas, solicitamos que você faça sua inscrição somente se
            tiver disponibilidade e interesse. Então, antes de se inscrever, confira as datas e horários na
            programação disponibilizadas no <a
                    href="http://portalsnc.cultura.gov.br/">http://portalsnc.cultura.gov.br</a>.
        </p>
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

    private function get_message_user_registered()
    {
        ?>
        <div class="alert alert-danger" role="alert">
            Você já possui uma inscrição!
        </div>

        Se desejar,
        <a href="<?= home_url('/inscricoes/') ?>">clique aqui para acompanhar o status da inscrição</a>
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
                'post_type' => SNC_POST_TYPE_INSCRICOES,
                'post_status' => array('publish', 'pending', 'canceled', 'waiting_list'),
                'posts_per_page' => 1
            ]);

            return $post;
        }

        return false;
    }

    public function add_acf_form_head()
    {
        if (shortcode_exists('snc-subscription-form')) {
            acf_form_head();
        }
    }

    /**
     * Notify the monitors about a new subscription
     *
     * @param $post_id
     */
    public function postprocess_main_form($post_id)
    {
        $update = get_post_meta($post_id, '_inscription_validated', true);

        if ($update) {
            return;
        }

        $oficinasEmail = new SNC_Oficinas_Email($post_id, 'snc_email_confirm_subscription');
        $oficinasEmail->snc_send_mail_user();

        add_post_meta($post_id, '_inscription_validated', true, true);
    }

    /**
     * Process data before save indication post
     *
     * @param $post_id
     * @return int|void|WP_Error
     */
    public function preprocess_main_form($post_id)
    {
        if ($post_id != SNC_POST_TYPE_INSCRICOES) {
            return $post_id;
        }

        if (is_admin()) {
            return;
        }

        $post = array('post_type' => SNC_POST_TYPE_INSCRICOES, 'post_status' => 'pending');
        $post_id = wp_insert_post($post);

        $token = SNC_Oficinas_Utils::generate_token();
        add_post_meta($post_id, 'token_ativacao_inscricao', $token, true);

        $inscricao = array('ID' => $post_id, 'post_title' => 'Inscrição - (ID #' . $post_id . ')');
        wp_update_post($inscricao);

        return $post_id;
    }

    function snc_filter_workshops($args)
    {
        $args['post_status'] = 'publish';
        return $args;
    }

    function snc_filter_workshops_object_result($title, $post, $field, $post_id)
    {
        $fields = get_fields($post->ID, true);
        $data_inicio = $fields['oficina_data_inicio'];
        $data_final = $fields['oficina_data_final'];
        $local = $fields['oficina_loca_de_realizacao'];
        $title = "<b>$title</b> Data:  $data_inicio a $data_final Local $local ";

        return $title;
    }

}