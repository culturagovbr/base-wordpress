<?php

if (!defined('WPINC'))
    die();

class SNC_Oficinas_Shortcode_Inscricoes
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-inscricoes', array($this, 'snc_registrations_list'));
        }

        if (is_user_logged_in()) {
            add_action('wp_ajax_nopriv_snc_cancel_subscription', array($this, 'snc_cancel_subscription'));
            add_action('wp_ajax_snc_cancel_subscription', array($this, 'snc_cancel_subscription'));

            add_action('wp_ajax_nopriv_snc_confirm_presence', array($this, 'snc_confirm_presence'));
            add_action('wp_ajax_snc_confirm_presence', array($this, 'snc_confirm_presence'));

            add_action('wp_ajax_nopriv_snc_question_response', array($this, 'snc_question_response'));
            add_action('wp_ajax_snc_question_response', array($this, 'snc_question_response'));
        }
    }

    public function snc_registrations_list()
    {
        if (!is_user_logged_in()) {
            echo "Autenticação é obrigatória para acessar este recurso";
            return false;
        }

        SNC_Oficinas_Service::trigger_change_waiting_presence();

        $options = get_option(SNC_ODF_SLUG . '_settings');

        // inscricoes
        $registrations = get_posts([
            'author' => get_current_user_id(),
            'post_type' => SNC_POST_TYPE_INSCRICOES,
            'post_status' => array('pending', 'waiting_list', 'confirmados', 'waiting_presence', 'waiting_questions', 'finish', 'canceled'),
        ]);

        if ($_GET['status'] == 'canceled') {
            $this->get_message_cancel_confirmation();
        }

        if ($_GET['status'] == 'confirm') {
            $this->get_message_finish_confirmation();
        }

        if ($registrations) : ?>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Inscrição</th>
                    <th scope="col">Oficina</th>
                    <th scope="col">Data</th>
                    <th scope="col">Local</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($registrations as $registration) :
                    $registration_fields = get_fields($registration->ID);
                    $workshop_fields = get_fields($registration_fields['inscricao_oficina_uf']->ID);
                    ?>
                    <tr>
                        <td scope="row"><?= $registration->post_title; ?></td>
                        <td><?= $registration_fields['inscricao_oficina_uf']->post_title; ?></td>
                        <td><?= $workshop_fields['oficina_data_inicio'] . ' a ' . $workshop_fields['oficina_data_final']; ?> </td>
                        <td><?= $workshop_fields['oficina_loca_de_realizacao']; ?> </td>
                        <td><?= SNC_Oficinas_Utils::get_status_label_subscription($registration->post_status); ?></td>
                        <td>
                            <?php if (!in_array($registration->post_status, array('waiting_presence', 'waiting_questions', 'finish', 'canceled'))) : ?>
                                <button id="pid-<?= $registration->ID ?>"
                                        type="button" class="cancel-subscription btn btn-secondary btn-sm" role="button"
                                        aria-pressed="true"
                                        style="cursor: pointer"
                                        title="Cancelar inscrição">Cancelar
                                </button>
                            <?php endif; ?>
                            <?php if ('waiting_presence' == $registration->post_status) : ?>
                                <button id="pid-<?= $registration->ID ?>"
                                        type="button" class="confirm-presence btn btn-secondary btn-sm" role="button"
                                        aria-pressed="true"
                                        data-id="<?= $registration->ID ?>"
                                        data-dt-inicio="<?= $workshop_fields['oficina_data_inicio'] ?>"
                                        data-hr-inicio="<?= $workshop_fields['oficina_horario_inicio'] ?>"
                                        data-dt-fim="<?= $workshop_fields['oficina_data_final'] ?>"
                                        data-hr-fim="<?= $workshop_fields['oficina_horario_termino'] ?>"
                                        style="cursor: pointer;"
                                        title="Confirmar Presença">Confimar
                                </button>
                            <?php endif; ?>
                            <?php if ('waiting_questions' == $registration->post_status) : ?>
                                <button id="pid-<?= $registration->ID ?>"
                                        type="button" class="question-response btn btn-secondary btn-sm" role="button"
                                        aria-pressed="true"
                                        data-id="<?= $registration->ID ?>"
                                        data-dt-inicio="<?= $workshop_fields['oficina_data_inicio'] ?>"
                                        data-hr-inicio="<?= $workshop_fields['oficina_horario_inicio'] ?>"
                                        data-dt-fim="<?= $workshop_fields['oficina_data_final'] ?>"
                                        data-hr-fim="<?= $workshop_fields['oficina_horario_termino'] ?>"
                                        style="cursor: pointer;"
                                        title="Responder Questionário">Responder
                                </button>
                            <?php endif; ?>
                            <?php if ('finish' == $registration->post_status) : ?>
                                <?php
                                $query = new WP_Query(array(
                                        'post_type' => SNC_POST_TYPE_PARTICIPACAO,
                                        'post_status' => 'publish',
                                        'post_parent' => $registration->ID)
                                );
                                $questionarios = $query->get_posts();

                                ?>
                                <a id="pid-<?= $registration->ID ?>"
                                   target="_blank"
                                   href="<?= home_url("certificado?idOficina={$registration->ID}&idQuestion={$questionarios[0]->ID}") ?>"
                                   type="button" class="impress-cert btn btn-success btn-sm" role="button"
                                   aria-pressed="true"
                                   data-id="<?= $registration->ID ?>"
                                   data-dt-inicio="<?= $workshop_fields['oficina_data_inicio'] ?>"
                                   data-hr-inicio="<?= $workshop_fields['oficina_horario_inicio'] ?>"
                                   data-dt-fim="<?= $workshop_fields['oficina_data_final'] ?>"
                                   data-hr-fim="<?= $workshop_fields['oficina_horario_termino'] ?>"
                                   style="cursor: pointer;"
                                   title="Imprimir Certificado">Imprimir
                                </a>
                                <?php unset($questionarios); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div id="dialog-snc" style="display: none;">
                <div id="dialog-snc-label-table"></div>
                <div id="dialog-snc-label">
                    <table style="font-size: 14px; border: 0;">
                        <tr>
                            <td style="text-align: justify; border: 0; font-style: italic; vertical-align: text-top;">
                                <p>
                                    <input name="confirmFinish" id="confirmFinish"
                                           class="checkValidatorSnc confirmFinish" type="checkbox"/>
                                    <?= $options['snc_legal_terms'] ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div>
                Não encontramos nenhuma inscrição cadastrada.
                <a href="<?= home_url('/inscricao/') ?>">Você pode criar uma nova inscrição aqui</a>
            </div>
        <?php endif;
    }

    public function snc_cancel_subscription()
    {
        $post_id = sprintf("%d", $_POST['pid']);

        try {
            if (empty($post_id)) {
                throw new Exception("Usuário não informada!");
            }

            if (!$this->current_user_is_the_author($post_id)) {
                throw new Exception("Você não tem autorização realizar esta operação!");
            }

            $oficina = SNC_Oficinas_Service::get_oficina_by_insc($post_id);

            $subscription = array('ID' => $post_id, 'post_status' => 'canceled');
            wp_update_post($subscription);

            SNC_Oficinas_Service::trigger_change_waiting_list($oficina->oficina_id);

            wp_send_json('Alteração realizada com sucesso!', 201);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 412);
        }
    }

    public function snc_confirm_presence()
    {
        $post_id = sprintf("%d", $_POST['pid']);

        try {
            if (empty($post_id)) {
                throw new Exception("Usuário não informada!");
            }

            if (!$this->current_user_is_the_author($post_id)) {
                throw new Exception("Você não tem autorização realizar esta operação!");
            }

            $subscription = array('ID' => $post_id, 'post_status' => 'waiting_questions');

            wp_update_post($subscription);

            $inscricaoPerfil = get_post_meta($post_id, 'inscricao_perfil', true);

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

            $token = SNC_Oficinas_Utils::generate_token($questionarioUrl);

            add_post_meta($post_id, 'token_responder_questionario', $token, true);

            $sncEmail = new SNC_Oficinas_Email($post_id, 'snc_email_impress_cert');
            $sncEmail->snc_send_mail_user();

            wp_send_json('Alteração realizada com sucesso!', 201);

        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 412);
        }
    }

    public function snc_question_response()
    {
        $post_id = sprintf("%d", $_POST['pid']);

        try {
            if (empty($post_id)) {
                throw new Exception("Usuário não informada!");
            }

            if (!$this->current_user_is_the_author($post_id)) {
                throw new Exception("Você não tem autorização realizar esta operação!");
            }

            $inscricaoPerfil = get_post_meta($post_id, 'inscricao_perfil', true);

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

            $token = SNC_Oficinas_Utils::generate_token($questionarioUrl);

            add_post_meta($post_id, 'token_responder_questionario', $token, true);

            $token = get_post_meta($post_id, 'token_responder_questionario', true);

            $url = home_url("/{$questionarioUrl}/?token={$token}&id={$post_id}");

            wp_send_json(array("url" => $url), 201);

        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 412);
        }
    }

    private function current_user_is_the_author($post_id)
    {
        $userID = get_post_field('post_author', $post_id);

        if (get_current_user_id() != $userID) {
            return false;
        }
        return true;
    }

    private function get_message_cancel_confirmation()
    {
        ?>
        <div class="container-fluid">
            <div class="alert alert-success" role="alert">
                Sua inscrição foi cancelada com sucesso!
            </div>
        </div>
        <?php
    }

    private function get_message_finish_confirmation()
    {
        ?>
        <div class="container-fluid">
            <div class="alert alert-success" role="alert">
                Suas presenças foram confirmadas com sucesso!
            </div>
        </div>
        <?php
    }
}