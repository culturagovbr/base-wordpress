<?php
add_shortcode( 'oscar-auth-form', 'oscar_auth_form' );
function oscar_auth_form( $atts ){

    if( is_user_logged_in() ):

        echo 'Você está logado neste momento, para efetuar um novo registro será preciso fazer <b><a href="'. wp_logout_url() .'">logout</a></b>.';

    else:

        if ( $_POST['reg_submit'] ) {
            validation();
            registration();
        }

        ob_start(); ?>
        <div class="text-right">
            <p>Já possui cadastro? Faça login <b><a href="<?php echo home_url('/login'); ?>">aqui</a>.</b></p>
        </div>
        <form id="oscar-register-form" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
            <div class="login-form row">
                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-user" for="reg-name">Nome completo</label>
                    <input name="reg_name" type="text" class="form-control login-field"
                    value="<?php echo(isset($_POST['reg_name']) ? $_POST['reg_name'] : null); ?>"
                    placeholder="" id="reg-name" required/>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-mail" for="reg-email">Email</label>
                    <input name="reg_email" type="email" class="form-control login-field"
                    value="<?php echo(isset($_POST['reg_email']) ? $_POST['reg_email'] : null); ?>"
                    placeholder="" id="reg-email" required/>
                </div>

                <div class="form-group col-md-4">
                    <label class="login-field-icon fui-lock" for="reg-pass">CPF</label>
                    <input name="cpf" type="text" class="form-control login-field"
                    value="<?php echo(isset($_POST['cpf']) ? $_POST['cpf'] : null); ?>"
                    placeholder="000.000.000-00" id="reg-pass" required/>
                </div>

                <div class="form-group col-md-4">
                    <label class="login-field-icon fui-lock" for="reg-pass">Senha</label>
                    <input name="reg_password" type="password" class="form-control login-field"
                    value="<?php echo(isset($_POST['reg_password']) ? $_POST['reg_password'] : null); ?>"
                    placeholder="" id="reg-pass" required/>
                </div>

                <div class="form-group col-md-4">
                    <label class="login-field-icon fui-lock" for="reg-pass">Repita a senha</label>
                    <input name="reg_password_repeat" type="password" class="form-control login-field"
                    value="<?php echo(isset($_POST['reg_password_repeat']) ? $_POST['reg_password_repeat'] : null); ?>"
                    placeholder="" id="reg-pass" required/>
                </div>

                <div class="form-group col-md-12 text-right">
                    <input class="btn btn-default" type="submit" name="reg_submit" value="Cadastrar"/>
                </div>
            </div>
        </form>

        <?php return ob_get_clean();

    endif;
}

function validation() {
    $username = $_POST['reg_name'];
    $email = $_POST['reg_email'];
    $cpf = $_POST['cpf'];
    $password = $_POST['reg_password'];
    $reg_password_repeat = $_POST['reg_password_repeat'];

    if (empty($username) || empty($password) || empty($email) || empty($cpf) ) {
        return new WP_Error('field', 'Todos os campos são de preenchimento obrigatório.');
    }

    if (strlen($password) < 5) {
        return new WP_Error('password', 'A senha está muito curta.');
    }

    if (!is_email($email)) {
        return new WP_Error('email_invalid', 'O email parece ser inválido');
    }

    if (email_exists($email)) {
        return new WP_Error('email', 'Este email já está em uso.');
    }

    if ($password !== $reg_password_repeat) {
        return new WP_Error('password', 'As senhas inseridas são diferentes.');
    }

    if (strlen( str_replace('.', '',  str_replace('-', '', $cpf) ) ) !== 11) {
        return new WP_Error('cpf', 'O CPF é inválido.');
    }
}

function registration() {
    $username = $_POST['reg_name'];
    $email = $_POST['reg_email'];
    $cpf = $_POST['cpf'];
    $password = $_POST['reg_password'];
    $reg_password_repeat = $_POST['reg_password_repeat'];

    $userdata = array(
        'first_name' => esc_attr($username),
        'display_name' => esc_attr($username),
        'user_login' => esc_attr($email),
        'user_email' => esc_attr($email),
        'user_pass' => esc_attr($password)
    );

    $errors = validation();

    if ( is_wp_error( $errors ) ) {
        echo '<div class="alert alert-danger">';
        echo '<strong>' . $errors->get_error_message() . '</strong>';
        echo '</div>';
    } else {
        $register_user = wp_insert_user($userdata);
        if (!is_wp_error($register_user)) {
            add_user_meta( $register_user, '_user_cpf', esc_attr($cpf), true );
            echo '<div class="alert alert-success">';
            echo 'Cadastro realizado com sucesso. Faça o login <strong><a href="' . home_url('/login') . '">aqui</a></strong>!';
            echo '</div>';
            $_POST = array();
        } else {
            echo '<div class="alert alert-danger">';
            echo '<strong>' . $register_user->get_error_message() . '</strong>';
            echo '</div>';
        }
    }

}

add_shortcode( 'oscar-login-form', 'oscar_login_form' );
function oscar_login_form( $atts ){
    echo '<div class="text-right">
            <p>Ainda não possui cadastro? Faça o seu <b><a href="'. home_url('/registro') .'">aqui</a>.</b></p>
        </div>';

    wp_login_form(
        array(
            'redirect' => home_url(),
            'form_id' => 'oscar-login-form',
            'label_username' => __( 'Endereço de e-mail' )
        )
    );
}