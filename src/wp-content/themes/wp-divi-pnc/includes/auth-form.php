<?php
add_shortcode( 'pnc-auth-form', 'pnc_auth_form' );
function pnc_auth_form( $atts ){

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
        <form id="pnc-register-form" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
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

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-cidade" for="reg-ecidade">Cidade</label>
                    <input name="reg_cidade" type="text" class="form-control login-field"
                    value="<?php echo(isset($_POST['reg_cidade']) ? $_POST['reg_cidade'] : null); ?>"
                    placeholder="" id="reg-email" required/>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-estado" for="reg-eestado">Estado</label>
                    <select id="reg_estado" name="reg_estado" class="form-control login-field">
                        <option value="AC" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'AC' ? 'selected="selected"' : ''; ?>>Acre</option>
                        <option value="AL" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'AL' ? 'selected="selected"' : ''; ?>>Alagoas</option>
                        <option value="AP" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'AP' ? 'selected="selected"' : ''; ?>>Amapá</option>
                        <option value="AM" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'AM' ? 'selected="selected"' : ''; ?>>Amazonas</option>
                        <option value="BA" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'BA' ? 'selected="selected"' : ''; ?>>Bahia</option>
                        <option value="CE" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'CE' ? 'selected="selected"' : ''; ?>>Ceará</option>
                        <option value="DF" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'DF' ? 'selected="selected"' : ''; ?>>Distrito Federal</option>
                        <option value="ES" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'ES' ? 'selected="selected"' : ''; ?>>Espírito Santo</option>
                        <option value="GO" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'GO' ? 'selected="selected"' : ''; ?>>Goiás</option>
                        <option value="MA" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'MA' ? 'selected="selected"' : ''; ?>>Maranhão</option>
                        <option value="MT" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'MT' ? 'selected="selected"' : ''; ?>>Mato Grosso</option>
                        <option value="MS" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'MS' ? 'selected="selected"' : ''; ?>>Mato Grosso do Sul</option>
                        <option value="MG" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'MG' ? 'selected="selected"' : ''; ?>>Minas Gerais</option>
                        <option value="PA" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'PA' ? 'selected="selected"' : ''; ?>> Pará</option>
                        <option value="PB" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'PB' ? 'selected="selected"' : ''; ?>>Paraíba</option>
                        <option value="PR" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'PR' ? 'selected="selected"' : ''; ?>>Paraná</option>
                        <option value="PE" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'PE' ? 'selected="selected"' : ''; ?>>Pernambuco</option>
                        <option value="PI" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'PI' ? 'selected="selected"' : ''; ?>>Piauí</option>
                        <option value="RJ" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'RJ' ? 'selected="selected"' : ''; ?>>Rio de Janeiro</option>
                        <option value="RN" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'RN' ? 'selected="selected"' : ''; ?>>Rio Grande do Norte</option>
                        <option value="RS" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'RS' ? 'selected="selected"' : ''; ?>>Rio Grande do Sul</option>
                        <option value="RO" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'RO' ? 'selected="selected"' : ''; ?>>Rondônia</option>
                        <option value="RR" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'RR' ? 'selected="selected"' : ''; ?>>Roraima</option>
                        <option value="SC" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'SC' ? 'selected="selected"' : ''; ?>>Santa Catarina</option>
                        <option value="SP" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'SP' ? 'selected="selected"' : ''; ?>>São Paulo</option>
                        <option value="SE" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'SE' ? 'selected="selected"' : ''; ?>>Sergipe</option>
                        <option value="TO" <?php echo isset($_POST['reg_estado']) && $_POST['reg_estado'] == 'TO' ? 'selected="selected"' : ''; ?>>Tocantins</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label class="login-field-icon fui-lock" for="reg-pass">Senha</label>
                    <input name="reg_password" type="password" class="form-control login-field"
                    value="<?php echo(isset($_POST['reg_password']) ? $_POST['reg_password'] : null); ?>"
                    placeholder="" id="reg-pass" required/>
                </div>

                <div class="form-group col-md-4">
                    <label class="login-field-icon fui-lock" for="reg-pass-repeat">Repita a senha</label>
                    <input name="reg_password_repeat" type="password" class="form-control login-field"
                    value="<?php echo(isset($_POST['reg_password_repeat']) ? $_POST['reg_password_repeat'] : null); ?>"
                    placeholder="" id="reg-pass-repeat" required/>
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
    $cidade = $_POST['reg_cidade'];
    $estado = $_POST['reg_estado'];
    $password = $_POST['reg_password'];
    $reg_password_repeat = $_POST['reg_password_repeat'];

    if (empty($username) || empty($password) || empty($email) || empty($cidade) ) {
        return new WP_Error('field', 'Todos os campos são de preenchimento obrigatório.');
    }

    if (strlen($password) < 5) {
        return new WP_Error('password', 'A senha está muito curta.');
    }

    if (!is_email($email)) {
        return new WP_Error('email_invalid', 'O email parece ser inválido');
    }

    if (email_exists($email)) {
        return new WP_Error('email', 'Este email já sendo utilizado, por favor utilize outro email.');
    }

    if ($password !== $reg_password_repeat) {
        return new WP_Error('password', 'As senhas inseridas são diferentes.');
    }
}

function registration() {
    $username = $_POST['reg_name'];
    $email = $_POST['reg_email'];
    $cidade = $_POST['reg_cidade'];
    $estado = $_POST['reg_estado'];
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
            add_user_meta( $register_user, '_user_cidade', esc_attr($cidade), true );
            add_user_meta( $register_user, '_user_estado', esc_attr($estado), true );
            echo '<div class="alert alert-success">';
            echo 'Cadastro realizado com sucesso. Você será redirionado para a tela de login, caso isso não ocorra automaticamente, clique <strong><a href="' . home_url('/login') . '">aqui</a></strong>!';
            echo '</div>';
            $_POST = array(); ?>
            <script type="text/javascript">
                window.setTimeout( function(){
                    window.location = '<?php echo home_url("/login"); ?>';
                }, 3000);
            </script>
        <?php } else {
            echo '<div class="alert alert-danger">';
            echo '<strong>' . $register_user->get_error_message() . '</strong>';
            echo '</div>';
        }
    }

}

add_shortcode( 'pnc-login-form', 'pnc_login_form' );
function pnc_login_form( $atts ){
    if( is_user_logged_in() ):

        echo 'Você está logado neste momento, realizar <b><a href="'. wp_logout_url() .'">logout</a></b>.';

    else:
        echo '<div class="text-right">
                <p>Ainda não possui cadastro? Faça o seu <b><a href="'. home_url('/registro') .'">aqui</a>.</b></p>
            </div>';

        wp_login_form(
            array(
                'redirect' => home_url(),
                'form_id' => 'pnc-login-form',
                'label_username' => __( 'Endereço de e-mail' )
            )
        );
    endif;
}