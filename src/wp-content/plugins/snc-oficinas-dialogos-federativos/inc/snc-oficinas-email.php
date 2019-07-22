<?php

if (!defined('WPINC'))
    die();

class SNC_Oficinas_Email
{
    private $post_id;
    private $type_message;
    private $subscription;

    public function __construct($post_id, $type_message)
    {
        $this->post_id = $post_id;
        $this->type_message = $type_message;
        $this->subscription = get_post($this->post_id);

        add_filter('wp_mail_content_type', array($this, 'set_email_content_type'));
    }

    public function snc_send_mail_user()
    {
        try {

            if (empty($this->post_id)) {
                throw new Exception("Identificador não informado");
            }

            if (empty($this->subscription)) {
                throw new Exception("Inscrição não encontrada");
            }

            if (empty($this->type_message)) {
                throw new Exception("Tipo da mensagem não informada");
            }

            $options = get_option(SNC_ODF_SLUG . '_settings');

            $headers[] = 'From: ' . get_bloginfo('name') . ' <automatico@cultura.gov.br>';
            $headers[] = 'Reply-To: ' . $options['snc_email_from_name'] . ' <' . $options['snc_email_from'] . '>';
            $subject = 'Ministério da Cidadania - Oficinas dos Diálogos Federativos';

            $to = get_the_author_meta('user_email', $this->subscription->post_author);
            if (empty($to)) {
                throw new Exception("Destinatário não informado");
            }

            $body = $this->get_email_template('user');
            if (empty($body)) {
                throw new Exception("Corpo do email vazio");
            }

            if (!wp_mail($to, $subject, $body, $headers)) {
                throw new Exception("wp_mail falhou");
            }

            return true;
        } catch (Exception $e) {
            $mensagem = "ERRO: O envio de email para: {$to}, falhou! Tipo: " . $e->getMessage();
            error_log($mensagem, 0);
            return false;
        }
    }

    public function get_email_template($user_type = 'user')
    {

        if (empty($this->post_id)) {
            throw new Exception("Identificador não informado");
        }

        if (empty($this->subscription) || empty($this->type_message)) {
            throw new Exception("Oficina não encontrada ou tipo não informado");
        }

        $options = get_option(SNC_ODF_SLUG . '_settings');

        $message = $options[$this->type_message];

        if (empty($message)) {
            throw new Exception("Mensagem vazia");
        }

        $workshop_post = get_field('inscricao_oficina_uf', $this->post_id);

        if (empty($workshop_post)) {
            throw new Exception("Oficina não encontrada vazia");
        }

        $workshop_fields = get_fields($workshop_post->ID);
        if ($workshop_fields) {
            foreach ($workshop_fields as $key => $field) {
                $message = str_replace('{' . $key . '}', $field, $message);
            }
        }


        $message = str_replace('{confirmar_inscricao_button}', $this->get_button_activation(), $message);
        $message = str_replace('{cancelar_inscricao_button}', $this->get_button_unsubscribe(), $message);
        $message = preg_replace_callback('/\{link_login ?(text=[\'\"]([^\}]+)[\'\"])?\}/', function ($matches) {
            $url_login = home_url('/login');
            $text = !empty($matches[2]) ? $matches[2] : 'Clique aqui para efetuar o login';
            return $this->get_link_action($url_login, $text);
        }, $message);

        $user_name = get_the_author_meta('display_name', $this->subscription->post_author);
        ob_start();
        if ($user_type === 'user') {
            require SNC_ODF_PLUGIN_PATH . '/email-templates/user-template.php';
        } else {
            require SNC_ODF_PLUGIN_PATH . '/email-templates/admin-template.php';
        }
        return ob_get_clean();
    }

    private function get_button_activation()
    {
        $token = get_post_meta($this->post_id, 'token_ativacao_inscricao', true);

        if (empty($token)) {
            return false;
        }

        $url = home_url('/confirmar-inscricao/?token=' . $token . '&id=' . $this->post_id);
        return $this->get_button_action($url, 'Confirmar inscrição');
    }

    private function get_button_unsubscribe()
    {
        $token = get_post_meta($this->post_id, 'token_cancelar_inscricao', true);

        if (empty($token)) {
            return false;
        }

        $url = home_url('/cancelar-inscricao/?token=' . $token . '&id=' . $this->post_id);
        return $this->get_button_action($url, 'Cancelar inscrição');
    }

    private function get_button_action($url, $label)
    {
        ob_start();
        ?>
        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary"
               style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
            <tbody>
            <tr>
                <td align="left"
                    style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
                    <table border="0" cellpadding="0" cellspacing="0"
                           style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                        <tbody>
                        <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;">
                                <a href="<?= $url; ?>" target="_blank"
                                   style="display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;"><?= $label; ?></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
        return ob_get_clean();
    }

    private function get_link_action($url, $label)
    {
        ob_start(); ?>
        <a href="<?= $url; ?>" target="_blank" style="cursor: pointer;"><?= $label; ?></a>
        <?php
        return ob_get_clean();
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
}
