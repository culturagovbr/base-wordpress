<?php

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
        }
    }

    public function snc_registrations_list()
    {
        if (!is_user_logged_in()) {
            echo "Autenticação é obrigatória para acessar este recurso";
            return false;
        }

        // inscricoes
        $registrations = get_posts([
            'author' => get_current_user_id(),
            'post_type' => SNC_POST_TYPE_INSCRICOES,
            'post_status' => array('publish', 'pending', 'canceled', 'waiting_list'),
        ]);

        if ($_GET['status'] == 'canceled') {
            $this->get_message_cancel_confirmation();
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
                        <?php if ($registration->post_status != 'canceled') : ?>
                            <td>
                                <button id="pid-<?= $registration->ID ?>"
                                        type="button" class="cancel-subscription btn btn-secondary btn-sm" role="button"
                                        aria-pressed="true"
                                        style="cursor: pointer"
                                        title="Cancelar inscrição">Cancelar
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div>Não encontramos nenhuma inscrição cadastrada. <a href="<?= home_url('/inscricao/') ?>">Você pode criar
                    uma nova inscrição aqui</a></div>
        <?php endif;
    }

    public function snc_cancel_subscription()
    {
        $post_id = sprintf("%d", $_POST['pid']);

        try {
            if (empty($post_id)) {
                throw new Exception("Oficina não informada!");
            }

            if (!$this->current_user_is_the_author($post_id)) {
                throw new Exception("Você não tem autorização realizar esta operação!");
            }

            $subscription = array('ID' => $post_id, 'post_status' => 'canceled');
            wp_update_post($subscription);
            wp_send_json('Alteração realizada com sucesso!', 201);
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

}