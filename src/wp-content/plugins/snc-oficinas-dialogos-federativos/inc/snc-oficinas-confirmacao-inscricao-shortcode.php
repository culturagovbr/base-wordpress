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
        $valid = false;
        if (!empty($current_token) && !empty($token) && $current_token == $token) {
            $inscricao = array('ID' => $post_id, 'post_status' => 'publish');

            wp_update_post($inscricao);
            delete_post_meta($post_id, 'token_ativacao_inscricao');

            $token = md5(uniqid(rand(), true));
            add_post_meta($post_id, 'token_cancelamento_inscricao', $token, true);
            $valid = true;
        }

        ob_start();
        if ($valid) {
            $this->get_message_subscription_confirmation();
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
                <p>Parabéns! Sua inscrição para participa na foi confirmada! </p>
            </div>
        </div>
        <?php
    }

    private function get_message_subscription_error()
    {
        ?>
        <div style="margin-bottom: 100px">

            <div class="alert alert-danger" role="alert">
                Erro! Os dados informados são inválidos
            </div>
        </div>
        <?php
    }

    public function snc_cancel_token_subscription()
    {
        $token = esc_attr($_GET['token']);
        $post_id = esc_attr($_GET['id']);

        $current_token = get_post_meta($post_id, 'token_cancelamento_inscricao');
        $valid = false;
        if (!empty($current_token) && !empty($token) && $current_token == $token) {
            $inscricao = array('ID' => $post_id, 'post_status' => 'publish');
            wp_update_post($inscricao);

            delete_post_meta($post_id, 'token_cancelamento_inscricao');
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
                Inscrição cancelada com sucesso!
            </div>
        </div>
        <?php
    }
}

new SNC_Oficinas_Confirmacao_Inscricao_Shortcode();