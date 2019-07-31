<?php

class SNC_Oficinas_Registro_Usuario_Shortcode
{
    private $errors = [];

    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-register', array($this, 'snc_minc_auth_form')); // Registro de usuário
        }

        add_action('init', array(&$this, 'registration'));

        add_action('wp_ajax_nopriv_snc_get_cities_options', array($this, 'snc_print_cities_options'));
        add_action('wp_ajax_snc_get_cities_options', array($this, 'snc_print_cities_options'));
        add_action('wp_ajax_snc_register_verify_field', array($this, 'snc_register_verify_field'));
        add_action('wp_ajax_nopriv_snc_register_verify_field', array($this, 'snc_register_verify_field'));
    }

    /**
     * Authentication form
     *
     * @param $atts
     * @return string
     */
    public function snc_minc_auth_form($atts)
    {
        $errors = [];
        if ($_POST['reg_submit']) {
            $errors = $this->snc_form_validation();
        }

        $page_status = $_GET['status'];

        $name = null;
        $email = null;
        $cnpj = null;
        $password = null;
        $state = $_POST['state'];
        $county = $_POST['county'];
        $schooling = $_POST['schooling'];
        $gender = $_POST['gender'];


        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $name = $current_user->display_name;
            $birthday = get_user_meta($current_user->ID, '_user_birthday', true);
            $schooling = get_user_meta($current_user->ID, '_user_schooling', true);
            $gender = get_user_meta($current_user->ID, '_user_gender', true);
            $cpf = get_user_meta($current_user->ID, '_user_cpf', true);
            $rg = get_user_meta($current_user->ID, '_user_rg', true);
            $address = get_user_meta($current_user->ID, '_user_address', true);
            $county = get_user_meta($current_user->ID, '_user_county', true);
            $state = get_user_meta($current_user->ID, '_user_state', true);
            $neighborhood = get_user_meta($current_user->ID, '_user_neighborhood', true);
            $number = get_user_meta($current_user->ID, '_user_number', true);
            $complement = get_user_meta($current_user->ID, '_user_complement', true);
            $zipcode = get_user_meta($current_user->ID, '_user_zipcode', true);
            $phone = get_user_meta($current_user->ID, '_user_phone', true);
            $celphone = get_user_meta($current_user->ID, '_user_celphone', true);
            $email = $current_user->user_email;
            $institutional_email = get_user_meta($current_user->ID, '_user_institutional-email', true);
            $webpage = get_user_meta($current_user->ID, '_user_webpage', true);
            $socials = get_user_meta($current_user->ID, '_user_socials', true);
        }

        $states = $this->get_states();
        $required = is_user_logged_in() ? '' : 'required';
        $disabled = is_user_logged_in() ? 'disabled' : '';
        $is_user_logged_in = is_user_logged_in();

        ob_start();
        if (!is_user_logged_in()) : ?>
            <div class="text-right">
                <p>Já possui cadastro? Faça login <b><a href="<?php echo home_url('/login'); ?>">aqui</a>.</b></p>
            </div>
            <div>
                <p><b>Inscrição para as Oficinas dos Diálogos Federativos - Cultura de Ponto à Ponta</b></p>
                <p>
                    Tendo em vista que as vagas são limitadas, solicitamos que você faça sua inscrição somente se
                    tiver disponibilidade e interesse. Então, antes de se inscrever, confira as datas e horários na
                    programação disponibilizadas no <a href="<?php echo home_url(); ?>">portalsnc.cultura.gov.br/</a>.
                </p>
            </div>
        <?php endif; ?>
        <?php if ($page_status == 'updated') : ?>
        <div class="alert alert-success" role="alert">
            Cadastro atualizado com sucesso! <b><a href="<?php echo home_url('/inscricao'); ?>">Ir para página da
                    inscrição</a></b>
        </div>
    <?php endif; ?>

        <?php if ($this->errors['general']) : ?>
        <div class="alert alert-danger">
            <strong><?= $this->errors['general']; ?></strong>
        </div>
    <?php endif; ?>

        <form id="snc-register-form" method="post"
              action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
            <div class="login-form row">
                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-user" for="fullname">Nome completo <span
                                style="color: red;">*</span></label>
                    <input name="fullname" type="text" maxlength="255"
                           class="form-control login-field <?= strlen($errors['fullname']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['fullname']) ? $_POST['fullname'] : $name); ?>"
                           id="fullname" <?= $required; ?>
                    />
                    <div class="invalid-feedback"><?= $errors['fullname']; ?></div>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon" for="birthday">Data de nascimento <span style="color: red;">*</span></label>
                    <input name="birthday" type="text"
                           class="form-control login-field <?= strlen($errors['birthday']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['birthday']) ? $_POST['birthday'] : $birthday); ?>"
                           placeholder="__/__/____" id="birthday" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['birthday']; ?></div>
                </div>

                <div class="form-group col-md-3">
                    <label class="login-field-icon" for="schooling">Escolaridade <span
                                style="color: red;">*</span></label>
                    <select id="schooling" name="schooling"
                            class="form-control login-field <?= strlen($errors['fullname']) > 1 ? 'is-invalid' : '' ?>">
                        <option value="">Selecione</option>
                        <?php foreach ($this->get_schooling() as $schooling_item) : ?>
                            <option <?= ($schooling === $schooling_item) ? 'selected="selected"' : ''; ?>
                                    value="<?= $schooling_item ?>"><?= $schooling_item ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $errors['schooling']; ?></div>
                </div>

                <div class="form-group col-md-3">
                    <label class="login-field-icon fui-user" for="gender">Gênero <span
                                style="color: red;">*</span></label>
                    <select id="gender" name="gender"
                            class="form-control login-field <?= strlen($errors['gender']) > 1 ? 'is-invalid' : '' ?>">
                        <option value="">Selecione</option>
                        <?php foreach ($this->get_genres() as $gender_name) : ?>
                            <option <?= $gender === $gender_name ? 'selected="selected"' : ''; ?>
                                    value="<?= $gender_name; ?>"><?= $gender_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $errors['gender']; ?></div>
                </div>

                <div class="form-group col-md-3">
                    <label class="login-field-icon fui-lock" for="cpf">CPF <span style="color: red;">*</span></label>
                    <input name="cpf" type="text"
                        <?= $disabled; ?>
                           class="form-control login-field <?= strlen($errors['cpf']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['cpf']) ? $_POST['cpf'] : $cpf); ?>"
                           placeholder="000.000.000-00" id="cpf" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['cpf']; ?></div>
                </div>

                <div class="form-group col-md-3">
                    <label class="login-field-icon fui-user" for="rg">RG <span style="color: red;">*</span></label>
                    <input name="rg" type="text" maxlength="10"
                           class="form-control login-field <?= strlen($errors['rg']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['rg']) ? $_POST['rg'] : $rg); ?>"
                           id="rg" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['rg']; ?></div>
                </div>

                <div class="form-group col-md-12">
                    <label class="login-field-icon fui-lock" for="address">Endereço <span
                                style="color: red;">*</span></label>
                    <input name="address" type="text" maxlength="255"
                           class="form-control login-field <?= strlen($errors['address']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['address']) ? $_POST['address'] : $address); ?>"
                           id="address" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['address']; ?></div>
                </div>

                <div class="form-group col-md-3">
                    <label class="login-field-icon fui-lock" for="state">UF <span style="color: red;">*</span></label>
                    <select id="snc_state"
                            class="form-control <?= strlen($errors['state']) > 1 ? 'is-invalid' : '' ?>"
                            name="state">
                        <option value="">Selecione</option>
                        <?php foreach ($states as $state_item) : ?>
                            <option <?= ($state === $state_item->uf) ? 'selected="selected"' : ''; ?>
                                    value=<?= $state_item->uf; ?>><?= "{$state_item->name} ({$state_item->uf})" ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $errors['state']; ?></div>
                </div>
                <div class="form-group col-md-3">
                    <label class="login-field-icon fui-lock" for="state">
                        Município <span style="color: red;">*</span></label>
                    <select id="snc_county"
                            class="form-control  <?= strlen($errors['county']) > 1 ? 'is-invalid' : '' ?>"
                            name="county">
                        <?= $this->snc_get_cities_options($state, $county); ?>
                    </select>
                    <div class="invalid-feedback"><?= $errors['county']; ?></div>
                </div>
                <div class="form-group col-md-3">
                    <label class="login-field-icon fui-user" for="neighborhood">Bairro <span
                                style="color: red;" >*</span></label>
                    <input name="neighborhood" type="text" maxlength="255"
                           class="form-control login-field <?= strlen($errors['neighborhood']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['neighborhood']) ? $_POST['neighborhood'] : $neighborhood); ?>"
                           id="neighborhood" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['neighborhood']; ?></div>
                </div>

                <div class="form-group col-md-3">
                    <label class="login-field-icon fui-user" for="number">Número <span
                                style="color: red;">*</span></label>
                    <input name="number" type="text" maxlength="100"
                           class="form-control login-field <?= strlen($errors['number']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['number']) ? $_POST['number'] : $number); ?>"
                           id="number" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['number']; ?></div>
                </div>

                <div class="form-group col-md-8">
                    <label class="login-field-icon fui-user" for="complement">Complemento</label>
                    <input name="complement" type="text" maxlength="255"
                           class="form-control login-field"
                           value="<?php echo(isset($_POST['complement']) ? $_POST['complement'] : $complement); ?>"
                           id="complement"/>
                </div>

                <div class="form-group col-md-4">
                    <label class="login-field-icon fui-user" for="zipcode">CEP <span
                                style="color: red;">*</span></label>
                    <input name="zipcode" type="text"
                           class="form-control login-field <?= strlen($errors['zipcode']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['zipcode']) ? $_POST['zipcode'] : $zipcode); ?>"
                           placeholder="00000-000" id="zipcode" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['zipcode']; ?></div>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-user" for="phone">DDD / Telefone <span
                                style="color: red;">*</span></label>
                    <input name="phone" type="text"
                           class="form-control login-field <?= strlen($errors['phone']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['phone']) ? $_POST['phone'] : $phone); ?>"
                           placeholder="(00) 0000-0000"
                           id="phone" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['phone']; ?></div>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-user" for="celphone">DDD / Celular <span
                                style="color: red;">*</span></label>
                    <input name="celphone" type="text"
                           class="form-control login-field <?= strlen($errors['celphone']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['celphone']) ? $_POST['celphone'] : $celphone); ?>"
                           placeholder="(00) 0000-0000"
                           id="celphone" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['celphone']; ?></div>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-mail" for="email">E-mail pessoal <span
                                style="color: red;">*</span></label>
                    <input name="email" type="email" maxlength="100"
                           class="form-control login-field <?= strlen($errors['email']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['email']) ? $_POST['email'] : $email); ?>"
                           id="email" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['email']; ?></div>
                </div>

                <?php if (!$is_user_logged_in) : ?>
                    <div class="form-group col-md-6">
                        <label class="login-field-icon fui-mail" for="email">Confirmar e-mail pessoal <span
                                    style="color: red;">*</span></label>
                        <input name="email-repeat" type="email" maxlength="100"
                               class="form-control login-field <?= strlen($errors['email-repeat']) > 1 ? 'is-invalid' : '' ?>"
                               value="<?php echo(isset($_POST['email-repeat']) ? $_POST['email-repeat'] : $email); ?>"
                               id="email" <?= $required; ?>/>
                        <div class="invalid-feedback"><?= $errors['email-repeat']; ?></div>
                    </div>
                <?php endif; ?>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-mail" for="institutional-email ">E-mail institucional (se
                        houver)</label>
                    <input name="institutional-email" type="email" maxlength="100"
                           class="form-control login-field"
                           value="<?php echo(isset($_POST['institutional-email']) ? $_POST['institutional-email'] : $institutional_email); ?>"
                           id="institutional-email"/>
                    <div class="invalid-feedback"><?= $errors['institutional-email']; ?></div>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-mail" for="webpage">Página da internet (se houver)</label>
                    <input name="webpage" type="text" maxlength="100"
                           class="form-control login-field"
                           value="<?php echo(isset($_POST['webpage']) ? $_POST['webpage'] : $webpage); ?>"
                           id="webpage"/>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-mail" for="socials">Indique outras ferramentas de comunicação
                        utilizadas</label>
                    <input name="socials" type="text" maxlength="255"
                           class="form-control login-field"
                           value="<?php echo(isset($_POST['socials']) ? $_POST['socials'] : $socials); ?>"
                           id="socials"/>
                            <div class="invalid-feedback"><?= $errors['socials']; ?></div>
                </div>
                <?php if (!$is_user_logged_in) : ?>
                    <div class="form-group col-md-6"></div>
                <?php endif; ?>
                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-lock"
                           for="password">Senha <?= $is_user_logged_in ? '' : '<span style="color: red;">*</span>'; ?></label>
                    <input name="password" type="password"
                           maxlength="20"
                           class="form-control login-field <?= strlen($errors['password']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['password']) ? $_POST['password'] : null); ?>"
                           placeholder="" id="password" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['password']; ?></div>
                </div>

                <div class="form-group col-md-6">
                    <label class="login-field-icon fui-lock" for="password-repeat">Confirmar a
                        senha <?php echo is_user_logged_in() ? '' : '<span style="color: red;">*</span>'; ?></label>
                    <input name="password-repeat" type="password"
                           maxlength="20"
                           class="form-control login-field <?= strlen($errors['password-repeat']) > 1 ? 'is-invalid' : '' ?>"
                           value="<?php echo(isset($_POST['password-repeat']) ? $_POST['password-repeat'] : null); ?>"
                           placeholder="" id="password-repeat" <?= $required; ?>/>
                    <div class="invalid-feedback"><?= $errors['password-repeat']; ?></div>
                </div>

                <div class="form-group col-md-12 text-right">
                    <input class="btn btn-default" type="submit" name="reg_submit"
                           value="<?php echo is_user_logged_in() ? 'Atualizar' : 'Cadastrar'; ?>"/>
                </div>
            </div>
            <?php if (is_user_logged_in()): ?>
                <input type="hidden" name="is-updating" value="1">
                <input type="hidden" name="user-id" value="<?php echo $current_user->ID; ?>">
            <?php endif; ?>
        </form>

        <p class="text-right">
            <small>Campos marcados com <span style="color: red;">*</span> são obrigatórios.</small>
        </p>
        <?php return ob_get_clean();
    }

    /**
     * Register validation
     *
     * @return Array
     */
    private function snc_form_validation()
    {
        $errors = $this->validation();
        $email = $_POST['email'];
        $email_repeat = $_POST['email-repeat'];
        $password = $_POST['password'];
        $password_repeat = $_POST['password-repeat'];
        $is_updating = isset($_POST['is-updating']) ? true : false;

        if (!$is_updating) {
            if (empty($password)) {
                $errors['password'] = 'Este campo não pode ser nulo.';
            }

            if (empty($password_repeat)) {
                $errors['password_repeat'] = 'Este campo não pode ser nulo.';
            }

            if (strlen($password) < 5) {
                $errors['password'] = 'A senha deve ter mais que 5(cinco) caracteres.';
            }

            if ($email !== $email_repeat) {
                $errors['email-repeat'] = 'Os e-mails informados devem ser iguais.';
            }
        }

        if ($password !== $password_repeat) {
            $errors['password-repeat'] = 'As senhas informadas devem ser iguais.';
        }


        return $errors;
    }

    /**
     * Register user
     *
     */
    public function registration()
    {
        if (!$_POST['reg_submit']) {
            return true;
        }

        $username = $_POST['fullname'];
        $birthday = $_POST['birthday'];
        $schooling = $_POST['schooling'];
        $gender = $_POST['gender'];
        $cpf = $_POST['cpf'];
        $rg = $_POST['rg'];
        $address = $_POST['address'];
        $county = $_POST['county'];
        $state = $_POST['state'];
        $neighborhood = $_POST['neighborhood'];
        $number = $_POST['number'];
        $complement = $_POST['complement'];
        $zipcode = $_POST['zipcode'];
        $phone = $_POST['phone'];
        $celphone = $_POST['celphone'];
        $email = $_POST['email'];
        $institutional_email = $_POST['institutional-email'];
        $webpage = $_POST['webpage'];
        $socials = $_POST['socials'];
        $password = $_POST['password'];
        $password_repeat = $_POST['password-repeat'];
        $user_id = $_POST['user-id'];
        $is_updating = isset($_POST['is-updating']) ? true : false;

        $userdata = array(
            'first_name' => esc_attr($username),
            'display_name' => esc_attr($username),
            'user_login' => esc_attr($email),
            'user_email' => esc_attr($email),
            'user_pass' => esc_attr($password)
        );

        try {

            $form_errors = $this->snc_form_validation();
            if (count($form_errors) > 0) {
                throw new Exception('Dados inválidos ou não informados');
            }

            if ($is_updating) {

                $userdata = array(
                    'ID' => $user_id,
                    'first_name' => esc_attr($username),
                    'display_name' => esc_attr($username),
                    'user_login' => esc_attr($email),
                    'user_email' => esc_attr($email),
                    'user_pass' => esc_attr($password)
                );

                $user_id = wp_update_user($userdata);

                if (is_wp_error($user_id)) {
                    throw new Exception($user_id->get_error_message());
                }


                update_user_meta($user_id, '_user_birthday', esc_attr($birthday));
                update_user_meta($user_id, '_user_schooling', esc_attr($schooling));
                update_user_meta($user_id, '_user_gender', esc_attr($gender));
//                update_user_meta($user_id, '_user_cpf', esc_attr($cpf));
                update_user_meta($user_id, '_user_rg', esc_attr($rg));
                update_user_meta($user_id, '_user_address', esc_attr($address));
                update_user_meta($user_id, '_user_county', esc_attr($county));
                update_user_meta($user_id, '_user_state', esc_attr($state));
                update_user_meta($user_id, '_user_neighborhood', esc_attr($neighborhood));
                update_user_meta($user_id, '_user_number', esc_attr($number));
                update_user_meta($user_id, '_user_complement', esc_attr($complement));
                update_user_meta($user_id, '_user_zipcode', esc_attr($zipcode));
                update_user_meta($user_id, '_user_phone', esc_attr($phone));
                update_user_meta($user_id, '_user_celphone', esc_attr($celphone));
                update_user_meta($user_id, '_user_institutional-email', esc_attr($institutional_email));
                update_user_meta($user_id, '_user_webpage', esc_attr($webpage));
                update_user_meta($user_id, '_user_socials', esc_attr($socials));

                wp_safe_redirect(get_permalink() . '?status=updated');
                exit;
            }

            // insert
            $register_user = wp_insert_user($userdata);
            if (is_wp_error($register_user)) {
                throw new Exception($register_user->get_error_message());
            }

            add_user_meta($register_user, '_user_birthday', esc_attr($birthday), true);
            add_user_meta($register_user, '_user_schooling', esc_attr($schooling), true);
            add_user_meta($register_user, '_user_gender', esc_attr($gender), true);
            add_user_meta($register_user, '_user_cpf', esc_attr($cpf), true);
            add_user_meta($register_user, '_user_rg', esc_attr($rg), true);
            add_user_meta($register_user, '_user_address', esc_attr($address), true);
            add_user_meta($register_user, '_user_county', esc_attr($county), true);
            add_user_meta($register_user, '_user_state', esc_attr($state), true);
            add_user_meta($register_user, '_user_neighborhood', esc_attr($neighborhood), true);
            add_user_meta($register_user, '_user_number', esc_attr($number), true);
            add_user_meta($register_user, '_user_complement', esc_attr($complement), true);
            add_user_meta($register_user, '_user_zipcode', esc_attr($zipcode), true);
            add_user_meta($register_user, '_user_phone', esc_attr($phone), true);
            add_user_meta($register_user, '_user_celphone', esc_attr($celphone), true);
            add_user_meta($register_user, '_user_institutional-email', esc_attr($institutional_email), true);
            add_user_meta($register_user, '_user_webpage', esc_attr($webpage), true);
            add_user_meta($register_user, '_user_socials', esc_attr($socials), true);

            wp_new_user_notification($register_user, null, 'user');

            $creds = array(
                'user_login' => $userdata['user_email'],
                'user_password' => $userdata['user_pass'],
                'remember' => true
            );

            $user = wp_signon($creds, false);

            if (is_wp_error($user)) {
                throw new Exception("Aconteceu um erro inesperado ao salvar: " . $user->get_error_message());
            }

            $redirect_to = home_url('/inscricao/?registered=true');
            wp_safe_redirect($redirect_to);
            exit;

        } catch (Exception $e) {
            $this->errors['general'] = $e->getMessage();
        }
    }

    private function get_genres()
    {
        return [
            'Feminino',
            'Masculino',
            'Outros'
        ];
    }

    private function get_schooling()
    {
        return [
            'Sem escolaridade',
            'Fundamental',
            'Médio',
            'Superior',
            'Especialização',
            'Mestrado',
            'Doutorado'
        ];
    }

    public function get_states()
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * from states ORDER BY name");
    }

    private function snc_get_cities_options($uf, $selected = '')
    {
        global $wpdb;

        $uf_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM states WHERE uf = %s", $uf));

        if (!$uf_id) {
            return "<option value=''>Selecione a UF</option>";
        }

        $cidades = $wpdb->get_results($wpdb->prepare("SELECT * FROM cities WHERE state_id = %d order by name", $uf_id));

        $options = '';
        if (is_array($cidades) && count($cidades) > 0) {

            foreach ($cidades as $cidade) {
                $sel = $cidade->name == $selected ? 'selected' : '';
                $options .= "<option value='{$cidade->name}' $sel>{$cidade->name}</option>";
            }

        }
        return $options;
    }

    public function snc_print_cities_options()
    {
        echo $this->snc_get_cities_options($_POST['uf'], $_POST['selected']);
        die;
    }

    private function validation()
    {
        $errors = array();
//        $filter = new SNC_Oficinas_Filter();
        $validator = new SNC_Oficinas_Validator();

        $type_user = isset($_POST['user_type']) ? $_POST['user_type'] : "";
        $fields_rules = is_user_logged_in() ? 'update' : 'register';

        foreach ($_POST as $stepfield => $value) {

            if ($stepfield != 'action' && $stepfield != 'user_type') {

                $field = $stepfield;

//                $filter->apply($fields_rules, $field, $value);

                $response = $validator->validate_field($fields_rules, $field, $value, $type_user);

                if (strlen($response) > 1) {
                    $errors[$field] = $response;
                }
            }
        }

        return $errors;
    }

    public function snc_register_verify_field()
    {
        $response = $this->validation();

        print json_encode($response);
        die; // or wordpress will print 0
    }
}
