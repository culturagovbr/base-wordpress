<?php

if (!defined('WPINC'))
    die();

class SNC_Oficinas_Confirmacao_Inscricao_Shortcode
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-oficina-confirmar-inscricao', array($this, 'snc_confirm_token_subscription'));
        }
    }

    public function snc_confirm_token_subscription()
    {
        $token = esc_attr($_GET['token']);
        $subscription_id = esc_attr($_GET['id']);

        try {
            ob_start();

            if (!$this->is_token_valid($subscription_id, $token)) {
                throw new Exception("Erro! Os dados informados são inválidos.");
            }

            if ($this->exists_vacancy_in_the_workshop($subscription_id)) {
                $this->add_user_in_workshop($subscription_id);
                $this->get_message_subscription_confirmation();
            } else {
                $this->add_user_in_waiting_list_in_workshop($subscription_id);
                $this->get_message_subscription_waiting_list();
            }

            $this->get_infos_workshop($subscription_id);
        } catch (Exception $e) {
            $this->get_message_subscription_error($e->getMessage());
        } finally {
            return ob_get_clean();
        }
    }

    private function is_token_valid($id, $token, $type = 'token_ativacao_inscricao')
    {
        $current_token = get_post_meta($id, $type, true);

        if (empty($current_token) || empty($token)) {
            return false;
        }

        if ($current_token != $token) {
            return false;
        }

        return true;
    }

    private function exists_vacancy_in_the_workshop($subscription_id)
    {
        $workshop = get_field('inscricao_oficina_uf', $subscription_id);

        if (empty($workshop)) {
            return false;
        }

        $class_size = (int)get_field('oficina_numero_turma', $workshop->ID);
        $total_registered = $this->get_total_registered_in_workshop($workshop->ID);

        if ($total_registered >= $class_size) {
            return false;
        }
        return true;
    }

    private function get_total_registered_in_workshop($workshop_id)
    {
        $query = new WP_Query(array(
                'post_type' => SNC_POST_TYPE_INSCRICOES,
                'post_status' => 'publish',
                'meta_key' => 'inscricao_oficina_uf',
                'meta_value' => $workshop_id)
        );

        return $query->found_posts;
    }

    private function add_user_in_workshop($subscription_id)
    {
        $subscription = array('ID' => $subscription_id, 'post_status' => 'publish');
        wp_update_post($subscription);
        delete_post_meta($subscription_id, 'token_ativacao_inscricao');

        $oficinasEmail = new SNC_Oficinas_Email($subscription_id, 'snc_email_effectiveness_subscription');
        $oficinasEmail->snc_send_mail_user();
    }

    private function add_user_in_waiting_list_in_workshop($subscription_id)
    {
        $subscription = array('ID' => $subscription_id, 'post_status' => 'waiting_list');
        wp_update_post($subscription);
        delete_post_meta($subscription_id, 'token_ativacao_inscricao');

        $oficinasEmail = new SNC_Oficinas_Email($subscription_id, 'snc_email_waiting_list_subscription');
        $oficinasEmail->snc_send_mail_user();
    }

    private function get_infos_workshop($subscription_id)
    {
        $workshop = get_field('inscricao_oficina_uf', $subscription_id, true);
        $subscription = get_post($subscription_id);
        $user_name = get_the_author_meta('display_name', $subscription->post_author);

        $workshop_fields = get_fields($workshop->ID);
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <b>Nome:</b> <?= $user_name; ?>
                </div>
                <div class="col-sm-12">
                    <b>Oficina:</b> <?= $workshop->post_title; ?>
                </div>
                <div class="col-sm-12">
                    <b>Período:</b> <?= $workshop_fields['oficina_data_inicio']; ?>
                    a <?= $workshop_fields['oficina_data_final']; ?>
                </div>
                <div class="col-sm-12">
                    <b>Horário:</b> <?= $workshop_fields['oficina_horario_inicio']; ?>
                    a <?= $workshop_fields['oficina_horario_termino']; ?>
                </div>
                <div class="col-sm-12">
                    <b>Local:</b> <?= $workshop_fields['oficina_loca_de_realizacao']; ?>
                </div>
            </div>
        </div>
        <?php
    }


    private function get_message_subscription_confirmation()
    {
        ?>
        <div class="container-fluid">

            <div class="alert alert-success" role="alert">
                Inscrição confirmada com sucesso!
            </div>

            <div>
                <p>Parabéns! Sua inscrição para participação na oficina foi confirmada!</p>
            </div>
        </div>
        <?php
    }

    private function get_message_subscription_error($message)
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

    private function get_message_subscription_waiting_list()
    {
        ?>
        <div class="container-fluid">

            <div class="alert alert-danger" role="alert">
                Não foi possível atender ao seu pedido de inscrição, uma vez que o número de inscritos superou o
                limite de vagas disponíveis.
            </div>
            <p>
                Seu pedido ficará em lista de espera. Caso alguém desista e seja possível atendê-lo(a), efetivaremos sua
                matrícula e entraremos em contato por e-mail. Se desejar,
                <a href="<?= home_url('/inscricoes/') ?>">clique aqui para acompanhar o status da inscrição</a>
            </p>
        </div>
        <?php
    }
}
