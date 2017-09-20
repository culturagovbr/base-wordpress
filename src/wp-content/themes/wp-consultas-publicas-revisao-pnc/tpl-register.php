<?php
/*
	Template Name: Signup Page
*/

global $user_ID;

wp_enqueue_script('cadastro', get_stylesheet_directory_uri() . '/js/cadastro.js', array('jquery'));
wp_localize_script('cadastro', 'vars', array( 'ajaxurl' => admin_url('admin-ajax.php') ));

//Check whether the user is already logged in
if (!$user_ID) {

	if($_POST) {
		$register_errors = array();
		$data 			 = array();

		$username 			= sanitize_user($_POST['username']);
		$email 	  	= esc_sql($_POST['email']);
		$user_password 			= $_POST['user_password'];
		$user_password_confirm  = $_POST['user_password_confirm'];
		$estado 					= $_POST['estado'];
		$municipio 				= $_POST['municipio'];

		// username
		if(empty($username)) {
			$register_errors['username'] = "Nome de usuário não pode ser vazio.";
		}
		// email
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {
			$register_errors['email'] =  "Por favor, informe um email válido.";
		}

		// estado
		if(empty($estado)) {
			$register_errors['estado'] = "Estado é obrigatório.";
		}

		// município
		if(empty($municipio)) {
			$register_errors['municipio'] = "Município é obrigatório.";
		}

		// password
        if(strlen($user_password)==0 )
            $register_errors['pass'] = 'A senha é obrigatória para a inscrição no site.';
        
        if( $user_password != $user_password_confirm)
            $register_errors['pass_confirm'] = 'As senhas informadas não são iguais.';

        // check and register
		if(!sizeof($register_errors)>0) {

            $status = wp_create_user( $username, $user_password, $email );

			if ( is_wp_error($status) ) {
				$register_errors['create'] = $status->get_error_message();
			} else {

				//se criar o usuário wp_create_user retorna o id
				$user_id = $status;

				// salva os metadados
				add_user_meta($user_id, 'estado', $estado);
				add_user_meta($user_id, 'municipio', $municipio);

				// enviar um email com informações da conta
				$from = get_option('admin_email');
                $headers = 'From: '.$from . "\r\n";
                $subject = "Cadastro " . get_bloginfo('name');
                $msg = "Você foi cadastrado com sucesso na plataforma de revisão das metas do Plano Nacional de Cultura."
                 ."\nDetalhes do login"
                 ."\nNome de usuário: $username"
                 ."\nSenha: $user_password"
                 ."\nAcesse: ". get_bloginfo('url');

	            wp_mail( $email, $subject, $msg, $headers );
				
				if ( is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		            $secure_cookie = false;
		        else
		            $secure_cookie = '';

		        $user = wp_signon(array('user_login' => $username, 'user_password' => $user_password), $secure_cookie);

		        if ( !is_wp_error($user) && !$reauth ) {

					wp_safe_redirect($_SERVER['REQUEST_URI']);
					
		            exit();
		        }
			}
		}
	}

	get_header();
 ?>

	<section id="main-section" class="span-15 prepend-1 append-1">
		<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix');?>>	

				<header>					
					<h1><?php the_title();?></h1>					
				</header>

				<?php if( isset($register_errors) ) : ?>
					<?php if (is_array($register_errors) && sizeof($register_errors) > 0): ?>
						<div class='messages'>
							<?php foreach ($register_errors as $e): ?>
								<div class="error"><?php echo $e; ?></div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<form method="post" id="register">
					<div class="span-4">
						<label>Nome de usuário*</label>
					</div>
					<div class="span-10 append-bottom">
						<input type="text" required="required" name="username" class="text" value="<?php echo isset($username) ? $username : '';?>" />
					</div>

					<div class="span-4">
						<label>Email*</label>
					</div>
					<div class="span-10 append-bottom">
						<input type="text" required="required" name="email" class="text" value="<?php echo isset($email) ? $email : '';?>" /> 
					</div>

					<div class="span-4">
						<label>Estado*</label>
					</div>
					<div class="span-10 append-bottom">
						<select required="required" name="estado" id="estado">
                            <option value=""> Selecione </option>
                            <?php $states = consulta_get_states(); ?>
                            <?php foreach ($states as $s): ?>
                                <option value="<?php echo $s->sigla; ?>"  <?php if (isset($_POST['estado']) && $_POST['estado'] == $s->sigla) echo 'selected'; ?>  >
                                    <?php echo $s->nome; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
					</div>

					<div class="span-4">
						<label>Município*</label>
					</div>
					<div class="span-10 append-bottom">
						<select required="required" name="municipio" id="municipio">
                            <option value="">Selecione</option>
                        </select>
					</div>

					<div class="span-4">
						<label>Senha*</label>
					</div>
					<div class="span-10 append-bottom">
						<input id="user_password" required="required" type="password" name="user_password" />
					</div>

					<div class="span-4">
						<label>Confirme a senha*</label>ad
					</div>
					<div class="span-10 append-bottom">
						<input id="user_password_confirm" required="required" type="password" name="user_password_confirm" />
					</div>

					<div class="textright">
						<input type="submit" id="submitbtn" class="blue-button"  name="submit" value="Registrar" />
					</div>
				</form>
		</article>
	</section>
	
	<?php  
}
else { 

	get_header(); ?>
	<section id="main-section" class="span-15 prepend-1 append-1">
		<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix');?>>	
			<div class="success">Você foi cadastrado com sucesso! <br>Para participar basta acessar a <a href="<?php echo site_url('/metas/'); ?>" title="Página das metas">página das metas</a> e deixar as suas opiniões.</div>
		</article>
	</section>
<?php 
} ?>

<aside id="main-sidebar" class="span-6 append-1 last">
		<?php get_sidebar(); ?>
	</aside>
<?php
get_footer();
?>
