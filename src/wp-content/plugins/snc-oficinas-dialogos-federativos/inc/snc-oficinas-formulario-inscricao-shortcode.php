<?php

class SNC_Oficinas_Formulario_Inscricao_Shortcode
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-subscription-form', array($this, 'snc_minc_subscription_form_shortcode')); // Inscrição
        }

        add_action('get_header', array($this, 'add_acf_form_head'), 0);

        add_action('acf/pre_save_post', array($this, 'preprocess_main_form'));
//		add_action('acf/save_post', array($this, 'postprocess_main_form'));
//        add_action('acf/validate_save_post', array($this, 'snc_acf_validate_save_post'), 10, 0);


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
            'post_id' => 'inscricao-oficina',
            'new_post' => array(
                'post_type' => 'inscricao-oficina',
                'post_status' => 'pending'
            ),
            'updated_message' => 'Inscrição enviada com sucesso.',
            'return' => home_url('/inscricao/?updated=true'),
            'uploader' => 'basic',
            'submit_value' => 'Finalizar inscrição'
        );

        ob_start();
        $subscription = $this->get_subscription_in_workshop();
        if (count($subscription) > 0) {
//            $settings['post_id'] = current($subscription)->ID;
            $this->get_message_subscription_pending(current($subscription));
        } else {
            $this->get_message_register_success();
            acf_form($settings);
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
            Cadastro salvo com sucesso! Preencha os dados abaixo para finalizar sua inscrição
        </div>
        <?php
    }

    private function get_message_subscription_pending($subscription)
    {
        ?>
        <div style="margin-bottom: 100px">

            <div class="alert alert-success" role="alert">
                Inscrição cadastrada com sucesso!
            </div>

            <?php if ($subscription->post_status != 'publish') : ?>
                <div>

                    <p>Você receberá um e-mail solicitando a confirmação na oficina dentro do prazo
                        estipulado. A falta de confirmação automaticamente excluirá a sua inscrição, e sua vaga será
                        disponibilizada para a próxima pessoa.</p>
                    <p>Se no ato da sua inscrição não houver vagas disponíveis, uma vez que o número de inscritos
                        superou o número de vagas disponíveis, o seu pedido ficará em lista de espera. Caso alguém
                        desista e seja possível atendê-lo (a), a sua inscrição será efetivada. Você será notificado por
                        e-
                        mail. </p>
                </div>
            <?php endif; ?>

            <b>Status da inscrição:</b> <?= $this->get_status_label_subscription($subscription->post_status); ?>
        </div>
        <?php
    }

    private function get_subscription_in_workshop()
    {
        if (is_user_logged_in()) {
            $post = get_posts([
                'author' => get_current_user_id(),
                'post_type' => 'inscricao-oficina',
                'post_status' => array('publish', 'pending'),
                'posts_per_page' => 1
            ]);

            return $post;
        }

        return false;
    }

    public function get_status_label_subscription($status)
    {
        $label = '';
        switch ($status) {
            case 'pending':
                $label = 'Pendente';
                break;
            case 'publish':
                $label = 'Confirmada';
                break;
        }
        return $label;
    }

    public function add_acf_form_head()
    {
        if (shortcode_exists('snc-subscription-form')) {
            acf_form_head();
        }
    }

    function snc_acf_validate_save_post()
    {
        // check if user is an administrator
//        if (current_user_can('manage_options')) {
//            // clear all errors
//            acf_reset_validation_errors();
//        }

        $inscrito = $this->get_subscription_in_workshop();
        if (!empty($inscrito)) {
            acf_add_validation_error('acf[field_5d125ee09caf3]', 'Você já possui uma inscrição');
        }

    }

    public function snc_acf_validate_value($valid, $value, $field, $input)
    {

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
        if ($post_id != 'inscricao-oficina') {
            return $post_id;
        }

        if (is_admin()) {
            return;
        }

        $post = array('post_type' => 'inscricao-oficina', 'post_status' => 'pending');
        $post_id = wp_insert_post($post);

        $token = md5(uniqid(rand(), true));
        add_post_meta($post_id, 'token_ativacao_inscricao', $token, true);

        $inscricao = array('ID' => $post_id, 'post_title' => 'Inscrição - (ID #' . $post_id . ')');
        wp_update_post($inscricao);

        // Return the new ID
        return $post_id;
    }

}