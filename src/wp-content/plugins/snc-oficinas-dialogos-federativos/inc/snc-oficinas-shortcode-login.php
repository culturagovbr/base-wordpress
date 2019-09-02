<?php

class SNC_Oficinas_Shortcode_Login
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-login', array($this, 'snc_minc_login_form')); // Login
        }
    }

    /**
     * Login form
     *
     */
    public function snc_minc_login_form()
    { ?>

        <div class="text-right">
            <p>Ainda não possui cadastro? Faça o seu <b><a href="<?php echo home_url('/registro'); ?>">aqui</a>.</b></p>
        </div>

        <?php if (isset($_GET['login']) && $_GET['login'] === 'failed') : ?>
            <div class="alert alert-danger" role="alert">
                Erro ao realizar o login. Por favor, verifique as informações e tente novamente
            </div>
        <?php endif;

        if (isset($_GET['checkemail']) && $_GET['checkemail'] === 'confirm') : ?>
            <div class="alert alert-success" role="alert">
                Cheque seu email para recuperar sua senha.
            </div>
        <?php endif;

        wp_login_form(
            array(
                'redirect' => site_url( '/inscricao/ '),
                'form_id' => 'snc-login-form',
                'label_username' => __('Endereço de e-mail'),
                'value_username' => isset($_COOKIE['log']) ? $_COOKIE['log'] : null
            )
        ); ?>

        <!--<p><a href="<?php /*echo wp_lostpassword_url( home_url() ); */
        ?>" class="forget-password-link" title="Esqueceu a senha?">Esqueceu a senha?</a></p>-->

        <?php
    }

}