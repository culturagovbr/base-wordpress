<?php

class SNC_Oficinas_Formulario_Inscricao_Shortcode
{
	public function __construct()
	{
		if( !is_admin() ){
            add_shortcode('snc-subscription-form', array($this, 'snc_minc_subscription_form_shortcode')); // Inscrição
		}

        add_action('get_header', array($this, 'add_acf_form_head'), 0);

//		add_action('acf/pre_save_post', array($this, 'preprocess_main_form'));
//		add_action('acf/save_post', array($this, 'postprocess_main_form'));
        add_action('acf/validate_save_post', array($this, 'snc_acf_validate_save_post'), 10, 0);
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
			'return' =>  home_url('/inscricao/?updated=true'),
            'uploader' => 'basic',
            'submit_value' => 'Finalizar inscrição'
        );

        ob_start();
        $subscription = $this->is_user_registered_in_workshop();
        
        if (count($subscription) > 0) {
            $settings['post_id'] = current($subscription)->ID;
            $this->obter_mensagem_ja_cadastrado(current($subscription));
        } else {
            acf_form($settings);
        }

        return ob_get_clean();
    }

    private function obter_mensagem_ja_cadastrado($subscription)
    {
        ?>
        <div class="alert alert-success" role="alert">
            O seu cadastro foi realizado com sucesso!
            Em breve você receberá o e-mail para confirmar a partipação na oficina!
        </div>

        <div>
            <b>Status da inscrição:</b> <?= $this->get_status_label_subscription($subscription->post_status); ?>
        </div>
        <?php
    }

    private function is_user_registered_in_workshop()
    {
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $post = get_posts([
                'post_author'    =>  $user_id,
                'post_type'      => 'inscricao-oficina',
                'posts_per_page' => 1
            ]);

            return $post;
        }

        return false;
    }

    public function get_status_label_subscription($status)
    {
        return $status;
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
        if (current_user_can('manage_options')) {

            // clear all errors
            acf_reset_validation_errors();
        }
    }

    public function snc_acf_validate_value($valid, $value, $field, $input)
    {
        return 'aaaaaaa';
//        acf_add_validation_error( 'acf[field_5d12728b631d5]', 'Please check this input to proceed' );
//        acf_add_validation_error( $input, $message );


//        return;
//        return 'Image must be at least 960px wide';

// echo 'aaaaaaa';
//        var_dump($valid, $value, $field, $input);  die;
        // bail early if value is already invalid
//        if( !$valid ) {
//
//            return $valid;
//
//        }
//
//        return 'Image must be at least 960px wide';


//        // load image data
//        $data = wp_get_attachment_image_src( $value, 'full' );
//        $width = $data[1];
//        $height = $data[2];
//
//        if( $width < 960 ) {
//
//            $valid = 'Image must be at least 960px wide';
//
//        }


        // return
//        return $valid;


    }



    /**
     * Notify the monitors about a new indication
     *
     * @param $post_id
     */
    public function postprocess_main_form($post_id)
    {
        $update = get_post_meta($post_id, '_inscription_validated', true);

        echo 'apos submissao e depois de salvar';
        var_dump($update);
        die;
        if ($update) {
            return;
        }

        $user = wp_get_current_user();
        $user_cnpj = get_user_meta($user->ID, '_user_cnpj', true);
        $oscar_minc_options = get_option('oscar_minc_options');
        $monitoring_emails = explode(',', $oscar_minc_options['oscar_minc_monitoring_emails']);
        // $to = array_map('trim', $monitoring_emails);
        $to = 'rickmanu@gmail.com';
        $headers[] = 'From: ' . bloginfo('name') . ' <automatico@cultura.gov.br>';
        // $headers[] = 'Reply-To: ' . $oscar_minc_options['oscar_minc_email_from_name'] . ' <' . $oscar_minc_options['oscar_minc_email_from'] . '>';
//		$headers[] = 'Reply-To: Galdar Tec <contato@galdar.com.br>';
        $subject = 'Nova inscrição ao SNC.';

        $msg = 'Uma nova inscrição foi recebida em Oscar.<br>';
        $msg .= 'Proponente: <b>' . $user->display_name . '</b><br>';
        // $msg .= 'CNPJ: <b>' . $this->mask($user_cnpj, '##.###.###/####-##') . '</b><br>';
        $msg .= 'Filme: <b>' . get_field('titulo_do_filme', $post_id) . '</b>';
        $msg .= '<br>Para visualiza-la, clique <a href="' . admin_url('post.php?post=' . $post_id . '&action=edit') . '" style="color: rgb(206, 188, 114); text-decoration: none">aqui</a>.';
        $body = $this->get_email_template('admin', $msg);

        if (!wp_mail($to, $subject, $body, $headers)) {
            error_log("ERRO: O envio de email de monitoramento para: " . $to . ', Falhou!', 0);
        }

        // add_post_meta($post_id, '_inscription_validated', true, true);

        // Notify the user about its subscription sent
        $to = $user->user_email;
        $subject = 'Sua inscrição foi recebida.';

        // $body = $this->get_email_template('user', $oscar_minc_options['oscar_minc_email_body']);
        $body = $this->get_email_template('user', 'Some clever message here!!!');

        if (!wp_mail($to, $subject, $body, $headers)) {
            error_log("ERRO: O envio de email de monitoramento para: " . $to . ', Falhou!', 0);
        }

    }

    private function get_email_template($user_type = 'user', $message)
    {
        $user = wp_get_current_user();
        ob_start();
        if ($user_type === 'user') {
            require dirname(__FILE__) . '/email-templates/user-template.php';
        } else {
            require dirname(__FILE__) . '/email-templates/admin-template.php';
        }
        return ob_get_clean();
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

        $post = get_post($post_id);
        $user = wp_get_current_user();
        echo 'apos submissao e antes de salvar';
        var_dump($user);
        var_dump($post);
        die;
        $post = array('post_type' => 'inscricao-oficina', 'post_status' => 'publish');
        $post_id = wp_insert_post($post);

        $inscricao = array('ID' => $post_id, 'post_title' => 'Inscrição - (ID #' . $post_id . ')');
        wp_update_post($inscricao);

        // Return the new ID
        return $post_id;
    }
}
new SNC_Oficinas_Formulario_Inscricao_Shortcode();