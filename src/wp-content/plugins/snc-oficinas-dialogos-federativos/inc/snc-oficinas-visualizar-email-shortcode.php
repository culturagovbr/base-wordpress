<?php

class SNC_Oficinas_Visualizar_Email_Shortcode
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-oficinas-visualizar-email', array($this, 'snc_email_subscription'));
        }
    }

    public function snc_email_subscription()
    {
        $inscricao = current($this->get_subscription_in_workshop());

//        $oficina = get_field_object('inscricao_uf_oficina', $inscricao->ID);
        $oficina_id = get_field('inscricao_uf_oficina', $inscricao->ID)->ID;

        $oficina = get_fields($oficina_id);

        $body = "<p>É com satisfação que recebemos sua solicitação de inscrição no evento <b>Diálogos Federativos: Cultura de Ponto à Ponta</b> no período de {{periodo_oficina}}, no horário de {{horario_oficina}}, a ser realizado no estado de {{estado_oficina}}, no {{local_oficina}}.</p>";
        $body .= "<p>Clique no botão abaixo para confirmar o seu e-mail e finalizar o processo de inscrição.</p>";
        $body .= "<p><a href='#link'>Confirmar inscrição</a></p>";
        $body .= "<p>Pŕoximo ao evento, encaminharemos o e-mail com lembrete de participação</p>";
        echo $this->get_email_template('user', $body);
    }

    private function get_email_template($user_type = 'user', $message)
    {
        $user = wp_get_current_user();
        ob_start();
        if ($user_type === 'user') {
            require SNC_ODF_PLUGIN_PATH . '/email-templates/user-template.php';
        } else {
            require SNC_ODF_PLUGIN_PATH . '/email-templates/admin-template.php';
        }
        return ob_get_clean();
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
}
