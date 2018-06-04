<?php

/**
 * Class Oscar_Minc_Shortcodes
 *
 */
class Oscar_Minc_Shortcodes
{

    public function __construct()
    {
        add_shortcode('oscar-minc', array($this, 'oscar_minc_subscription_form_shortcode'));
        add_shortcode('oscar-register', array($this, 'oscar_minc_auth_form'));
        add_shortcode('oscar-login', array($this, 'oscar_minc_login_form'));
        add_shortcode('oscar-subscriptions', array($this, 'oscar_minc_user_subscriptions'));
        add_shortcode('oscar-upload-video', array($this, 'oscar_minc_video_upload_form'));
    }

    /**
     * Shortcode to show ACF form
     *
     * @param $atts
     * @return string
     */
    public function oscar_minc_subscription_form_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'form-group-id' => '',
            'return' => home_url('/?sent=true#message')
        ), $atts);

        ob_start();
        $post_inscricao = empty($_GET['inscricao']) ? 'new_inscricao' : $_GET['inscricao'];

        $settings = array(
            'field_groups' => array($atts['form-group-id']),
            'id' => 'oscar-main-form',
            'post_id' => $post_inscricao,
            'new_post' => array(
                'post_type' => 'inscricao',
                'post_status' => 'publish'
            ),
            'updated_message' => 'Inscrição enviada com sucesso.',
            'return' => $atts['return'],
            'submit_value' => 'Salvar dados'
        );
        acf_form($settings);

        return ob_get_clean();
    }

    /**
     * Authentication form
     *
     * @param $atts
     * @return string
     */
    public function oscar_minc_auth_form($atts)
    {

        if (is_user_logged_in()):
            echo 'Você está logado neste momento, para efetuar um novo registro será preciso fazer <b><a href="' . wp_logout_url() . '">logout</a></b>.';
        else:

            if ($_POST['reg_submit']) {
                $this->validation();
                $this->registration();
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
                        <label class="login-field-icon fui-lock" for="reg-cnpj">CNPJ</label>
                        <input name="cnpj" type="text" class="form-control login-field"
                               value="<?php echo(isset($_POST['cnpj']) ? $_POST['cnpj'] : null); ?>"
                               placeholder="00.000.000/0000-00" id="reg-cnpj" required/>
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

    /**
     * Register validation
     *
     * @return WP_Error
     */
    private function validation()
    {
        $username = $_POST['reg_name'];
        $email = $_POST['reg_email'];
        $cnpj = $_POST['cnpj'];
        $password = $_POST['reg_password'];
        $reg_password_repeat = $_POST['reg_password_repeat'];

        if (empty($username) || empty($password) || empty($email) || empty($cnpj)) {
            return new WP_Error('field', 'Todos os campos são de preenchimento obrigatório.');
        }

        if (strlen($password) < 5) {
            return new WP_Error('password', 'A senha está muito curta.');
        }

        if (!is_email($email)) {
            return new WP_Error('email_invalid', 'O email parece ser inválido');
        }

        if (email_exists($email)) {
            return new WP_Error('email', 'Este email já sendo utilizado, para cadastrar um novo filme, por favor utilize outro email.');
        }

        if ($password !== $reg_password_repeat) {
            return new WP_Error('password', 'As senhas inseridas são diferentes.');
        }

        if (strlen(str_replace('.', '', str_replace('-', '', str_replace('/', '', $cnpj)))) !== 14) {
            return new WP_Error('cnpj', 'O CNPJ é inválido.');
        }
    }

    /**
     * Register user
     *
     */
    private function registration()
    {
        $username = $_POST['reg_name'];
        $email = $_POST['reg_email'];
        $cnpj = $_POST['cnpj'];
        $password = $_POST['reg_password'];
        $reg_password_repeat = $_POST['reg_password_repeat'];

        $userdata = array(
            'first_name' => esc_attr($username),
            'display_name' => esc_attr($username),
            'user_login' => esc_attr($email),
            'user_email' => esc_attr($email),
            'user_pass' => esc_attr($password)
        );

        $errors = $this->validation();

        if (is_wp_error($errors)) {
            echo '<div class="alert alert-danger">';
            echo '<strong>' . $errors->get_error_message() . '</strong>';
            echo '</div>';
        } else {
            $register_user = wp_insert_user($userdata);
            if (!is_wp_error($register_user)) {
                add_user_meta($register_user, '_user_cnpj', esc_attr($cnpj), true);
                echo '<div class="alert alert-success">';
                echo 'Cadastro realizado com sucesso. Você será redirionado para a tela de login, caso isso não ocorra automaticamente, clique <strong><a href="' . home_url('/login') . '">aqui</a></strong>!';
                echo '</div>';
                $_POST = array(); ?>
                <script type="text/javascript">
                    window.setTimeout(function () {
                        // window.location = '<?php echo home_url("/login"); ?>';
                    }, 3000);
                </script>
            <?php } else {
                echo '<div class="alert alert-danger">';
                echo '<strong>' . $register_user->get_error_message() . '</strong>';
                echo '</div>';
            }
        }

    }

    /**
     * Login form
     *
     */
    public function oscar_minc_login_form()
    {
        echo '<div class="text-right">
            <p>Ainda não possui cadastro? Faça o seu <b><a href="' . home_url('/registro') . '">aqui</a>.</b></p>
        </div>';

        wp_login_form(
            array(
                'redirect' => home_url(),
                'form_id' => 'oscar-login-form',
                'label_username' => __('Endereço de e-mail')
            )
        );
    }

    /**
     * Show users subscriptions
     *
     */
    public function oscar_minc_user_subscriptions()
    {
        $current_user = wp_get_current_user();
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'inscricao',
            'author' => $current_user->ID
        );
        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) { ?>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Data</th>
                    <th scope="col">Título do filme</th>
                    <th scope="col">Mês/Ano de finalização</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo get_the_date(); ?></td>
                        <td><?php echo get_field('titulo_do_filme') ? get_field('titulo_do_filme') : '-'; ?></td>
                        <td><?php echo get_field('mes_ano_de_finalizacao') ? get_field('mes_ano_de_finalizacao') : '-'; ?></td>
                        <td><b>Filme não enviado<b></td>
                        <td>
                            <a href="<?php echo home_url('/inscricao') . '?inscricao=' . get_the_ID(); ?>" class="btn btn-primary btn-sm" role="button" data-toggle="tooltip" data-placement="top" title="Editar inscrição">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                <?php $i++; endwhile; ?>
                </tbody>
            </table>
            <a href="<?php echo home_url('/inscricao'); ?>" class="btn btn-primary ">Realizar nova inscrição</a>
            <?php wp_reset_postdata();
        } else { ?>
            <a href="<?php echo home_url('/inscricao'); ?>" class="btn btn-primary ">Realizar inscrição</a>
        <?php }
    }

    public function oscar_minc_video_upload_form()
    {
        $oscar_options = get_option('oscar_options');
        ob_start(); ?>

        <p>Tamanho máximo para o arquivo de vídeo: <b>20Gb</b>. Velocidade de conexão mínima sugerida: <b>10Mb</b>.</p>
        <p>Resolução mínima <b>720p</b>. Formatos permitidos: <b><?php echo $oscar_options['oscar_movie_extensions'] ?></b>.</p>

        <p id="dbi-upload-progress">Please select a file and click "Upload" to continue.</p>

        <form id="oscar-video-form" method="post" action="<?php echo get_the_permalink() ?>">
            <div class="form-group text-center video-drag-area dropzone">
                <input type="file" id="oscar-video" name="oscar-video" class="inputfile" accept=".mp4, .avi">
                <label id="oscar-video-btn" for="oscar-video"><i class="fa fa-upload"></i> Selecione seu vídeo</label>
                <p id="oscar-video-name" class="help-block"></p>
            </div>
            <div id="upload-status" class="form-group hidden">
                <div class="progress">
                    <div class="progress-bar progress-bar-success progress-bar-striped myprogress" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="sr-only">40% Complete (success)</span>
                    </div>
                </div>
                <div class="panel panel-default msg"></div>
            </div>
            <div class="text-right">
                <button id="oscar-video-upload-btn" type="submit" class="btn btn-default">Enviar</button>
            </div>
        </form>

        <?php return ob_get_clean();
    }

}