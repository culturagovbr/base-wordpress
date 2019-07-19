<?php

class SNC_Oficinas_Confirmacao_Inscricao_Shortcode
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-oficina-confirmar-inscricao', array($this, 'snc_confirm_token_subscription'));
            add_shortcode('snc-oficina-cancelar-inscricao', array($this, 'snc_cancel_token_subscription'));
        }
    }

    public function snc_confirm_token_subscription()
    {
        $token = esc_attr($_GET['token']);
        $post_id = esc_attr($_GET['id']);

        $current_token = get_post_meta($post_id, 'token_ativacao_inscricao', true);
        $post_status = false;
        if (!empty($current_token) && !empty($token) && $current_token == $token) {

            $inscricao = array('ID' => $post_id, 'post_status' => 'publish');

            wp_update_post($inscricao);
            delete_post_meta($post_id, 'token_ativacao_inscricao');

            $token = md5(uniqid(rand(), true));
            add_post_meta($post_id, 'token_cancelar_inscricao', $token, true);

            $oficinasEmail = new SNC_Oficinas_Email($post_id, 'snc_email_effectiveness_subscription');
            $oficinasEmail->snc_send_mail_user();

            $post_status = 'publish';
        }

        ob_start();
        if ($post_status == 'publish') {
            $this->get_message_subscription_confirmation();
        } else if ($post_status == 'waiting_list') {
            $this->get_message_subscription_waiting_list();
        } else {
            $this->get_message_subscription_error();
        }
        return ob_get_clean();
    }


    private function get_message_subscription_confirmation()
    {
        ?>
        <div style="margin-bottom: 100px">

            <div class="alert alert-success" role="alert">
                Inscrição confirmada com sucesso!
            </div>

            <div>
                <p>Parabéns! Sua inscrição para participação na oficina foi confirmada!</p>
            </div>
        </div>
        <?php
    }

    private function get_message_subscription_error()
    {
        ?>
        <div style="margin-bottom: 100px">

            <div class="alert alert-danger" role="alert">
                Erro! Os dados informados são inválidos ou a participação já foi confirmada!
                <a href="<?= home_url('/inscricao/') ?>">Clique aqui para consultar o status da inscrição</a>
            </div>
        </div>
        <?php
    }

    private function get_message_subscription_waiting_list()
    {
        ?>
        <div style="margin-bottom: 100px">

            <div class="alert alert-danger" role="alert">
                Não foi possível confirmar atender ao seu pedido de inscrição, uma vez que o número de inscritos superou o
                número limite de vagas disponíveis.
            </div>
            <p>
                Seu pedido ficará em lista de espera. Caso alguém desista e seja possível atendê-lo(a), efetivaremos sua
                matrícula e entraremos em contato por e-mail. Se desejar
                <a href="<?= home_url('/inscricao/') ?>">clique aqui para acompanhar o status da inscrição</a>
            </p>
        </div>
        <?php
    }

    public function snc_cancel_token_subscription()
    {
        $token = esc_attr($_GET['token']);
        $post_id = esc_attr($_GET['id']);

        $current_token = get_post_meta($post_id, 'token_cancelar_inscricao', true);
        $valid = false;
        if (!empty($current_token) && !empty($token) && $current_token == $token) {
            $inscricao = array('ID' => $post_id, 'post_status' => 'canceled');
            wp_update_post($inscricao);

            delete_post_meta($post_id, 'token_cancelar_inscricao');
            $valid = true;
        }

        ob_start();
        if ($valid) {
            $this->get_message_cancel_confirmation();
        } else {
            $this->get_message_subscription_error();
        }

        return ob_get_clean();
    }

    private function get_message_cancel_confirmation()
    {
        ?>
        <div style="margin-bottom: 100px">

            <div class="alert alert-success" role="alert">
                Sua inscrição foi cancelada com sucesso!
            </div>
        </div>
        <?php
    }
}
