<?php

/**
 * Class Oscar_Minc_Shortcodes
 *
 */
class SNC_Oficinas_Dialogos_Federativos_Shortcodes
{
	public function __construct()
	{
		if( !is_admin() ){
			add_shortcode('snc-subscription-form', array($this, 'snc_minc_subscription_form_shortcode')); // Inscrição
			add_shortcode('snc-register', array($this, 'snc_minc_auth_form')); // Registro de usuário
			add_shortcode('snc-login', array($this, 'snc_minc_login_form')); // Login
		}
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

		ob_start();

		$settings = array(
			'field_groups' => array($atts['form-group-id']),
			'id' => 'snc-main-form',
			'post_id' => 'inscricao-oficina',
			'new_post' => array(
				'post_type' => 'inscricao-oficina',
				'post_status' => 'publish'
			),
			'updated_message' => 'Inscrição enviada com sucesso.',
			// 'return' => $atts['return'],
			'return' =>  home_url('/inscricao'),
			'uploader' => 'basic',
			'submit_value' => 'Enviar inscrição'
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
	public function snc_minc_auth_form($atts)
	{

		if ($_POST['reg_submit']) {
			$errors = $this->validation();
			$this->registration();
		}

		$name = null;
		$email = null;
		$cnpj = null;
		$password = null;

		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$name = $current_user->display_name;
			$birthday = get_user_meta( $current_user->ID, '_user_birthday', true );
			$schooling = get_user_meta( $current_user->ID, '_user_schooling', true );
			$gender = get_user_meta( $current_user->ID, '_user_gender', true );
			$cpf = get_user_meta( $current_user->ID, '_user_cpf', true );
			$rg = get_user_meta( $current_user->ID, '_user_rg', true );
			$address = get_user_meta( $current_user->ID, '_user_address', true );
			$county = get_user_meta( $current_user->ID, '_user_county', true );
			$state = get_user_meta( $current_user->ID, '_user_state', true );
			$neighborhood = get_user_meta( $current_user->ID, '_user_neighborhood', true );
			$number = get_user_meta( $current_user->ID, '_user_number', true );
			$complement = get_user_meta( $current_user->ID, '_user_complement', true );
			$zipcode = get_user_meta( $current_user->ID, '_user_zipcode', true );
			$phone = get_user_meta( $current_user->ID, '_user_phone', true );
			$celphone = get_user_meta( $current_user->ID, '_user_celphone', true );
			$email = $current_user->user_email;
			$institutional_email = get_user_meta( $current_user->ID, '_user_institutional-email', true );
			$webpage = get_user_meta( $current_user->ID, '_user_webpage', true );
			$socials = get_user_meta( $current_user->ID, '_user_socials', true );
		}

		ob_start();
		if ( !is_user_logged_in() ) : ?>
			<div class="text-right">
				<p>Já possui cadastro? Faça login <b><a href="<?php echo home_url('/login'); ?>">aqui</a>.</b></p>
			</div>
		<?php endif; ?>
		<form id="snc-register-form" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
			<div class="login-form row">
				<div class="form-group col-md-6">
					<label class="login-field-icon fui-user" for="fullname">Nome completo <span style="color: red;">*</span></label>
					<input name="fullname" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['fullname']) ? $_POST['fullname'] : $name); ?>"
					       id="fullname" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon" for="birthday">Data de nascimento <span style="color: red;">*</span></label>
					<input name="birthday" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['birthday']) ? $_POST['birthday'] : $birthday); ?>"
					       placeholder="__/__/____" id="birthday" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-3">
					<label class="login-field-icon" for="schooling">Escolaridade <span style="color: red;">*</span></label>
					<select id="schooling" name="schooling" class="form-control login-field">
						<option <?php echo ( $_POST['schooling'] === 'Sem escolaridade' || $schooling === 'Sem escolaridade' ) ? 'selected="selected"' : ''; ?> value="Sem escolaridade">Sem escolaridade</option>
						<option <?php echo ( $_POST['schooling'] === 'Fundamental' || $schooling === 'Fundamental' ) ? 'selected="selected"' : ''; ?> value="Fundamental">Fundamental</option>
						<option <?php echo ( $_POST['schooling'] === 'Médio' || $schooling === 'Médio' ) ? 'selected="selected"' : ''; ?> value="Médio">Médio</option>
						<option <?php echo ( $_POST['schooling'] === 'Superior' || $schooling === 'Superior' ) ? 'selected="selected"' : ''; ?> value="Superior">Superior</option>
						<option <?php echo ( $_POST['schooling'] === 'Especialização' || $schooling === 'Especialização' ) ? 'selected="selected"' : ''; ?> value="Especialização">Especialização</option>
						<option <?php echo ( $_POST['schooling'] === 'Mestrado' || $schooling === 'Mestrado' ) ? 'selected="selected"' : ''; ?> value="Mestrado">Mestrado</option>
						<option <?php echo ( $_POST['schooling'] === 'Doutorado' || $schooling === 'Doutorado' ) ? 'selected="selected"' : ''; ?> value="Doutorado">Doutorado</option>
					</select>
				</div>

				<div class="form-group col-md-3">
					<label class="login-field-icon fui-user" for="gender">Gênero <span style="color: red;">*</span></label>
					<select id="gender" name="gender" class="form-control login-field">
						<option <?php echo ( $_POST['gender'] === 'Feminino' || $gender === 'Feminino' ) ? 'selected="selected"' : ''; ?> value="Feminino">Feminino</option>
						<option <?php echo ( $_POST['gender'] === 'Masculino' || $gender === 'Masculino' ) ? 'selected="selected"' : ''; ?> value="Masculino">Masculino</option>
						<option <?php echo ( $_POST['gender'] === 'Outros' || $gender === 'Outros' ) ? 'selected="selected"' : ''; ?> value="Outros">Outros</option>
					</select>
				</div>

				<div class="form-group col-md-3">
					<label class="login-field-icon fui-lock" for="cpf">CPF <span style="color: red;">*</span></label>
					<input name="cpf" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['cpf']) ? $_POST['cpf'] : $cpf); ?>"
					       placeholder="000.000.000-00" id="cpf" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-3">
					<label class="login-field-icon fui-user" for="rg">RG <span style="color: red;">*</span></label>
					<input name="rg" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['rg']) ? $_POST['rg'] : $rg); ?>"
					       id="rg" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-12">
					<label class="login-field-icon fui-lock" for="address">Endereço <span style="color: red;">*</span></label>
					<input name="address" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['address']) ? $_POST['address'] : $address); ?>"
					       id="address" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-3">
					<label class="login-field-icon fui-user" for="county">Município <span style="color: red;">*</span></label>
					<input name="county" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['county']) ? $_POST['county'] : $county); ?>"
					       id="county" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-3">
					<label class="login-field-icon fui-lock" for="state">UF <span style="color: red;">*</span></label>
					<select id="state" class="form-control" name="state" <?php echo is_user_logged_in() ? '' : 'required'; ?>>
						<option <?php echo ( $_POST['state'] === 'Acre (AC)' || $state === 'Acre (AC)' ) ? 'selected="selected"' : ''; ?> value="Acre (AC)">Acre (AC)</option>
						<option <?php echo ( $_POST['state'] === 'Alagoas (AL)' || $state === 'Alagoas (AL)' ) ? 'selected="selected"' : ''; ?> value="Alagoas (AL)">Alagoas (AL)</option>
						<option <?php echo ( $_POST['state'] === 'Amapá (AP)' || $state === 'Amapá (AP)' ) ? 'selected="selected"' : ''; ?> value="Amapá (AP)">Amapá (AP)</option>
						<option <?php echo ( $_POST['state'] === 'Amazonas (AM)' || $state === 'Amazonas (AM)' ) ? 'selected="selected"' : ''; ?> value="Amazonas (AM)">Amazonas (AM)</option>
						<option <?php echo ( $_POST['state'] === 'Bahia (BA)' || $state === 'Bahia (BA)' ) ? 'selected="selected"' : ''; ?> value="Bahia (BA)">Bahia (BA)</option>
						<option <?php echo ( $_POST['state'] === 'Ceará (CE)' || $state === 'Ceará (CE)' ) ? 'selected="selected"' : ''; ?> value="Ceará (CE)">Ceará (CE)</option>
						<option <?php echo ( $_POST['state'] === ')">Distrito Federal (DF)' || $state === 'Distrito Federal (DF)' ) ? 'selected="selected"' : ''; ?> value="Distrito Federal (DF)">Distrito Federal (DF)</option>
						<option <?php echo ( $_POST['state'] === ')">Espírito Santo (ES)' || $state === 'Espírito Santo (ES)' ) ? 'selected="selected"' : ''; ?> value="Espírito Santo (ES)">Espírito Santo (ES)</option>
						<option <?php echo ( $_POST['state'] === 'Goiás (GO)' || $state === 'Goiás (GO)' ) ? 'selected="selected"' : ''; ?> value="Goiás (GO)">Goiás (GO)</option>
						<option <?php echo ( $_POST['state'] === 'Maranhão (MA)' || $state === 'Maranhão (MA)' ) ? 'selected="selected"' : ''; ?> value="Maranhão (MA)">Maranhão (MA)</option>
						<option <?php echo ( $_POST['state'] === ')">Mato Grosso (MT)' || $state === 'Mato Grosso (MT)' ) ? 'selected="selected"' : ''; ?> value="Mato Grosso (MT)">Mato Grosso (MT)</option>
						<option <?php echo ( $_POST['state'] === 'o Grosso do Sul (MS)">Mato Grosso do Sul (MS)' || $state === 'Mato Grosso do Sul (MS)' ) ? 'selected="selected"' : ''; ?> value="Mato Grosso do Sul (MS)">Mato Grosso do Sul (MS)</option>
						<option <?php echo ( $_POST['state'] === ')">Minas Gerais (MG)' || $state === 'Minas Gerais (MG)' ) ? 'selected="selected"' : ''; ?> value="Minas Gerais (MG)">Minas Gerais (MG)</option>
						<option <?php echo ( $_POST['state'] === 'Pará (PA)' || $state === 'Pará (PA)' ) ? 'selected="selected"' : ''; ?> value="Pará (PA)">Pará (PA)</option>
						<option <?php echo ( $_POST['state'] === 'Paraíba (PB)' || $state === 'Paraíba (PB)' ) ? 'selected="selected"' : ''; ?> value="Paraíba (PB)">Paraíba (PB)</option>
						<option <?php echo ( $_POST['state'] === 'Paraná (PR)' || $state === 'Paraná (PR)' ) ? 'selected="selected"' : ''; ?> value="Paraná (PR)">Paraná (PR)</option>
						<option <?php echo ( $_POST['state'] === 'Pernambuco (PE)' || $state === 'Pernambuco (PE)' ) ? 'selected="selected"' : ''; ?> value="Pernambuco (PE)">Pernambuco (PE)</option>
						<option <?php echo ( $_POST['state'] === 'Piauí (PI)' || $state === 'Piauí (PI)' ) ? 'selected="selected"' : ''; ?> value="Piauí (PI)">Piauí (PI)</option>
						<option <?php echo ( $_POST['state'] === 'Janeiro (RJ)">Rio de Janeiro (RJ)' || $state === 'Rio de Janeiro (RJ)' ) ? 'selected="selected"' : ''; ?> value="Rio de Janeiro (RJ)">Rio de Janeiro (RJ)</option>
						<option <?php echo ( $_POST['state'] === ' Grande do Norte (RN)">Rio Grande do Norte (RN)' || $state === 'Rio Grande do Norte (RN)' ) ? 'selected="selected"' : ''; ?> value="Rio Grande do Norte (RN)">Rio Grande do Norte (RN)</option>
						<option <?php echo ( $_POST['state'] === ' Grande do Sul (RS)">Rio Grande do Sul (RS)' || $state === 'Rio Grande do Sul (RS)' ) ? 'selected="selected"' : ''; ?> value="Rio Grande do Sul (RS)">Rio Grande do Sul (RS)</option>
						<option <?php echo ( $_POST['state'] === 'Rondônia (RO)' || $state === 'Rondônia (RO)' ) ? 'selected="selected"' : ''; ?> value="Rondônia (RO)">Rondônia (RO)</option>
						<option <?php echo ( $_POST['state'] === 'Roraima (RR)' || $state === 'Roraima (RR)' ) ? 'selected="selected"' : ''; ?> value="Roraima (RR)">Roraima (RR)</option>
						<option <?php echo ( $_POST['state'] === ')">Santa Catarina (SC)' || $state === 'Santa Catarina (SC)' ) ? 'selected="selected"' : ''; ?> value="Santa Catarina (SC)">Santa Catarina (SC)</option>
						<option <?php echo ( $_POST['state'] === ')">São Paulo (SP)' || $state === 'São Paulo (SP)' ) ? 'selected="selected"' : ''; ?> value="São Paulo (SP)">São Paulo (SP)</option>
						<option <?php echo ( $_POST['state'] === 'Sergipe (SE)' || $state === 'Sergipe (SE)' ) ? 'selected="selected"' : ''; ?> value="Sergipe (SE)">Sergipe (SE)</option>
						<option <?php echo ( $_POST['state'] === 'Tocantins (TO)' || $state === 'Tocantins (TO)' ) ? 'selected="selected"' : ''; ?> value="Tocantins (TO)">Tocantins (TO)</option>
					</select>
				</div>

				<div class="form-group col-md-3">
					<label class="login-field-icon fui-user" for="neighborhood">Bairro <span style="color: red;">*</span></label>
					<input name="neighborhood" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['neighborhood']) ? $_POST['neighborhood'] : $neighborhood); ?>"
					       id="neighborhood" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-3">
					<label class="login-field-icon fui-user" for="number">Número <span style="color: red;">*</span></label>
					<input name="number" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['number']) ? $_POST['number'] : $number); ?>"
					       id="number" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-8">
					<label class="login-field-icon fui-user" for="complement">Complemento <span style="color: red;">*</span></label>
					<input name="complement" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['complement']) ? $_POST['complement'] : $complement); ?>"
					       id="complement" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-4">
					<label class="login-field-icon fui-user" for="zipcode">CEP <span style="color: red;">*</span></label>
					<input name="zipcode" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['zipcode']) ? $_POST['zipcode'] : $zipcode); ?>"
					       placeholder="00000-000" id="zipcode" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon fui-user" for="phone">DDD / Telefone <span style="color: red;">*</span></label>
					<input name="phone" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['phone']) ? $_POST['phone'] : $phone); ?>"
					       placeholder="(00) 0000-0000" id="phone" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon fui-user" for="celphone">DDD / Celular <span style="color: red;">*</span></label>
					<input name="celphone" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['celphone']) ? $_POST['celphone'] : $celphone); ?>"
					       placeholder="(00) 0000-0000" id="celphone" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon fui-mail" for="email">E-mail pessoal <span style="color: red;">*</span></label>
					<input name="email" type="email" class="form-control login-field"
					       value="<?php echo(isset($_POST['email']) ? $_POST['email'] : $email); ?>"
					       id="email" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon fui-mail" for="institutional-email">E-mail institucional (se houver)</label>
					<input name="institutional-email" type="email" class="form-control login-field"
					       value="<?php echo(isset($_POST['institutional-email']) ? $_POST['institutional-email'] : $institutional_email); ?>"
					       id="institutional-email"/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon fui-mail" for="webpage">Página da internet (se houver)</label>
					<input name="webpage" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['webpage']) ? $_POST['webpage'] : $webpage); ?>"
					       id="webpage"/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon fui-mail" for="socials">Indique outras ferramentas de comunicação utilizadas</label>
					<input name="socials" type="text" class="form-control login-field"
					       value="<?php echo(isset($_POST['socials']) ? $_POST['socials'] : $socials); ?>"
					       id="socials"/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon fui-lock" for="password">Senha <?php echo is_user_logged_in() ? '' : '<span style="color: red;">*</span>'; ?></label>
					<input name="password" type="password" class="form-control login-field"
					       value="<?php echo(isset($_POST['password']) ? $_POST['password'] : null); ?>"
					       placeholder="" id="password" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-6">
					<label class="login-field-icon fui-lock" for="password-repeat">Repita a senha <?php echo is_user_logged_in() ? '' : '<span style="color: red;">*</span>'; ?></label>
					<input name="password-repeat" type="password" class="form-control login-field"
					       value="<?php echo(isset($_POST['password-repeat']) ? $_POST['password-repeat'] : null); ?>"
					       placeholder="" id="password-repeat" <?php echo is_user_logged_in() ? '' : 'required'; ?>/>
				</div>

				<div class="form-group col-md-12 text-right">
					<input class="btn btn-default" type="submit" name="reg_submit" value="<?php echo is_user_logged_in() ? 'Atualizar' : 'Cadastrar'; ?>"/>
				</div>
			</div>
			<?php if( is_user_logged_in() ): ?>
				<input type="hidden" name="is-updating" value="1">
				<input type="hidden" name="user-id" value="<?php echo $current_user->ID; ?>">
			<?php endif; ?>
		</form>

		<p class="text-right"><small>Campos marcados com <span style="color: red;">*</span> são obrigatórios.</small></p>
		<?php return ob_get_clean();
	}

	/**
	 * Register validation
	 *
	 * @return WP_Error
	 */
	private function validation()
	{
		$name = $_POST['fullname'];
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
		$is_updating = isset( $_POST['is-updating'] ) ? true : false;

		if( !$is_updating ){
			if (
				empty($name) ||
				empty($birthday) ||
				empty($schooling) ||
				empty($gender) ||
				empty($cpf) ||
				empty($rg) ||
				empty($address) ||
				empty($county) ||
				empty($state) ||
				empty($neighborhood) ||
				empty($number) ||
				empty($complement) ||
				empty($zipcode) ||
				empty($phone) ||
				empty($celphone) ||
				empty($email) ||
				empty($password) ||
				empty($password_repeat)
			) {
				return new WP_Error('field', 'Existem campos obrigatórios não preenchidos.');
			}
		} else {
			if (
				empty($name) ||
				empty($birthday) ||
				empty($schooling) ||
				empty($gender) ||
				empty($cpf) ||
				empty($rg) ||
				empty($address) ||
				empty($county) ||
				empty($state) ||
				empty($neighborhood) ||
				empty($number) ||
				empty($complement) ||
				empty($zipcode) ||
				empty($phone) ||
				empty($celphone) ||
				empty($email)
			) {
				return new WP_Error('field', 'Existem campos obrigatórios não preenchidos.');
			}
		}

		if( !$is_updating ){
			if (strlen($password) < 5) {
				return new WP_Error('password', 'A senha está muito curta.');
			}
		} else {
			if ( !empty($password) && strlen($password) < 5) {
				return new WP_Error('password', 'A senha está muito curta.');
			}
		}

		if (!is_email($email)) {
			return new WP_Error('email_invalid', 'O email parece ser inválido');
		}

		if (email_exists($email) && !$is_updating) {
			return new WP_Error('email', 'Este email já sendo utilizado.');
		}

		if ($password !== $password_repeat) {
			return new WP_Error('password', 'As senhas inseridas são diferentes.');
		}

		if (strlen(str_replace('.', '', str_replace('-', '', str_replace('/', '', $cpf)))) !== 11) {
			return new WP_Error('cpf', 'O CPF é inválido.');
		}
	}

	/**
	 * Register user
	 *
	 */
	private function registration()
	{
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
		$is_updating = isset( $_POST['is-updating'] ) ? true : false;

		$userdata = array(
			'first_name' => esc_attr($username),
			'display_name' => esc_attr($username),
			'user_login' => esc_attr($email),
			'user_email' => esc_attr($email),
			'user_pass' => esc_attr($password)
		);

		$errors = $this->validation();

		if (is_wp_error($errors)) :
			echo '<div class="alert alert-danger">';
			echo '<strong>' . $errors->get_error_message() . '</strong>';
			echo '</div>';
		else :
			if ( $is_updating ) {

				$userdata = array(
					'ID' => $user_id,
					'first_name' => esc_attr($username),
					'display_name' => esc_attr($username),
					'user_login' => esc_attr($email),
					'user_email' => esc_attr($email),
					'user_pass' => esc_attr($password)
				);

				$user_id = wp_update_user($userdata);

				if ( is_wp_error( $user_id ) ) {
					echo '<div class="alert alert-danger">';
					echo '<strong>' . $user_id->get_error_message() . '</strong>';
					echo '</div>';
				} else {

					update_user_meta( $user_id, '_user_birthday', esc_attr($birthday) );
					update_user_meta( $user_id, '_user_schooling', esc_attr($schooling) );
					update_user_meta( $user_id, '_user_gender', esc_attr($gender) );
					update_user_meta( $user_id, '_user_cpf', esc_attr($cpf) );
					update_user_meta( $user_id, '_user_rg', esc_attr($rg) );
					update_user_meta( $user_id, '_user_address', esc_attr($address) );
					update_user_meta( $user_id, '_user_county', esc_attr($county) );
					update_user_meta( $user_id, '_user_state', esc_attr($state) );
					update_user_meta( $user_id, '_user_neighborhood', esc_attr($neighborhood) );
					update_user_meta( $user_id, '_user_number', esc_attr($number) );
					update_user_meta( $user_id, '_user_complement', esc_attr($complement) );
					update_user_meta( $user_id, '_user_zipcode', esc_attr($zipcode) );
					update_user_meta( $user_id, '_user_phone', esc_attr($phone) );
					update_user_meta( $user_id, '_user_celphone', esc_attr($celphone) );
					update_user_meta( $user_id, '_user_institutional-email', esc_attr($institutional_email) );
					update_user_meta( $user_id, '_user_webpage', esc_attr($webpage) );
					update_user_meta( $user_id, '_user_socials', esc_attr($socials) );

					echo '<div class="alert alert-success">';
					echo 'Cadastro atualizado com sucesso.';
					echo '</div>';
				}
			} else {
				$register_user = wp_insert_user($userdata);
				if (!is_wp_error($register_user)) {

					add_user_meta( $register_user, '_user_birthday', esc_attr($birthday), true );
					add_user_meta( $register_user, '_user_schooling', esc_attr($schooling), true );
					add_user_meta( $register_user, '_user_gender', esc_attr($gender), true );
					add_user_meta( $register_user, '_user_cpf', esc_attr($cpf), true );
					add_user_meta( $register_user, '_user_rg', esc_attr($rg), true );
					add_user_meta( $register_user, '_user_address', esc_attr($address), true );
					add_user_meta( $register_user, '_user_county', esc_attr($county), true );
					add_user_meta( $register_user, '_user_state', esc_attr($state), true );
					add_user_meta( $register_user, '_user_neighborhood', esc_attr($neighborhood), true );
					add_user_meta( $register_user, '_user_number', esc_attr($number), true );
					add_user_meta( $register_user, '_user_complement', esc_attr($complement), true );
					add_user_meta( $register_user, '_user_zipcode', esc_attr($zipcode), true );
					add_user_meta( $register_user, '_user_phone', esc_attr($phone), true );
					add_user_meta( $register_user, '_user_celphone', esc_attr($celphone), true );
					add_user_meta( $register_user, '_user_institutional-email', esc_attr($institutional_email), true );
					add_user_meta( $register_user, '_user_webpage', esc_attr($webpage), true );
					add_user_meta( $register_user, '_user_socials', esc_attr($socials), true );

					echo '<div class="alert alert-success">';
					echo 'Cadastro realizado com sucesso. Você será redirionado para a tela de login em <b class="time-before-redirect">5</b> segundos, caso isso não ocorra automaticamente, clique <strong><a href="' . home_url('/login') . '">aqui</a></strong>!';
					echo '</div>';
					$_POST = array(); ?>
					<script type="text/javascript">
						jQuery('#snc-register-form').hide();
						var counter = 5;
						var interval = setInterval(function() {
							counter--;
							jQuery('.time-before-redirect').text(counter);
							if (counter === 0) {
								clearInterval(interval);
								window.location = '<?php echo home_url("/login"); ?>';
							}
						}, 1000);
					</script>
				<?php } else {
					echo '<div class="alert alert-danger">';
					echo '<strong>' . $register_user->get_error_message() . '</strong>';
					echo '</div>';
				}
			}
		endif;

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

		<?php if ( isset( $_GET['login'] ) && $_GET['login'] === 'failed' ) : ?>
		<div class="alert alert-danger" role="alert">
			Erro ao realizar o login. Por favor, verifique as informações e tente novamente
		</div>
	<?php endif;

		if ( isset( $_GET['checkemail'] ) && $_GET['checkemail'] === 'confirm' ) : ?>
			<div class="alert alert-success" role="alert">
				Cheque seu email para recuperar sua senha.
			</div>
		<?php endif;

		wp_login_form(
			array(
				'redirect' => home_url(),
				'form_id' => 'snc-login-form',
				'label_username' => __('Endereço de e-mail'),
				'value_username' => isset( $_COOKIE['log'] ) ? $_COOKIE['log'] : null
			)
		); ?>

		<!--<p><a href="<?php /*echo wp_lostpassword_url( home_url() ); */?>" class="forget-password-link" title="Esqueceu a senha?">Esqueceu a senha?</a></p>-->

		<?php
	}

}