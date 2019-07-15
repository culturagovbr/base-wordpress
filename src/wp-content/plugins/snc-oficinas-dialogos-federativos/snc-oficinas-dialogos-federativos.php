<?php
/*
Plugin Name: SNC - Oficinas dos Diálogos Federativos
Plugin URI: https://github.com/Darciro/Piwik-WordPress
Description: Adiciona um formulário de cadastro e inscrição às Oficinas dos Diálogos Federativos realizadas pela SNC
Version: 1.0
Author: Ricardo Carvalho
Author URI: https://galdar.com.br
License: GNU GPLv3
*/

if ( !defined( 'WPINC' ) )
	die();

define( 'SNC_ODF_SLUG', 'snc-oficinas-dialogos-federativos' );
define( 'SNC_ODF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SNC_ODF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

class SNC_Oficinas_Dialogos_Federativos {

	public function __construct() {

		register_activation_hook(__FILE__, array($this, 'activate_hook'));

		add_action('init', array($this, 'inscricao_oficina_settings'));
		add_action('init', array($this, 'inscricao_oficina_cpt'));
		add_action('init', array($this, 'set_shortcodes'));
		add_action('get_header', array($this, 'add_acf_form_head'), 0);

//		add_action('acf/pre_save_post', array($this, 'preprocess_main_form'));
//		add_action('acf/save_post', array($this, 'postprocess_main_form'));
         add_action('acf/validate_save_post', array($this,'snc_acf_validate_save_post'), 10, 0);

//        add_filter('acf/validate_value', array($this, 'snc_acf_validate_value'), 10, 4);
//        add_action('acf/validate_save_post', array($this,'snc_acf_validate_save_post'));

		add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
		add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));
		add_action('template_redirect', array($this, 'redirect_to_auth'));
		add_action('login_redirect', array($this, 'login_redirect'), 10, 3);
		add_action('manage_posts_custom_column', array($this, 'fill_custom_columns'), 10, 2);

		add_filter('manage_edit-inscricao-oficina_columns', array($this, 'add_custom_columns'));

		// add_action('wp_mail_failed', array($this, 'action_wp_mail_failed'), 10, 1);
		add_action('get_footer', array($this, 'debug_plugin'));

	}

	public function activate_hook () {
		if (!is_plugin_active('advanced-custom-fields-pro/acf.php') && !is_plugin_active('advanced-custom-fields/acf.php')) {
			echo 'Para que este plugin funcione corretamente, é necessário a instalação e ativação do plugin ACF - <a href="http://advancedcustomfields.com/" target="_blank">Advanced custom fields</a>.';
			die;
		}
	}

	public function inscricao_oficina_settings()
	{
		require_once SNC_ODF_PLUGIN_PATH . 'inc/settings.php';
		new SNC_Oficinas_Dialogos_Federativos_Settings();
	}

	public function inscricao_oficina_cpt () {
		register_post_type('inscricao-oficina', array(
				'labels' => array(
					'name' => 'Inscrições para as Oficinas',
					'singular_name' => 'Inscrição para as Oficinas',
					'add_new' => 'Nova inscrição',
					'add_new_item' => 'Nova inscrição',
					'search_items' => 'Procurar inscrição',
					'not_found' => 'Nenhuma inscrição encontrada',
				),
				'description' => 'Inscrições Oficinas dos Diálogos Federativos',
				'public' => true,
				'exclude_from_search' => false,
				'publicly_queryable' => false,
				'supports' => array('title'),
				'menu_icon' => 'dashicons-clipboard'
			)
		);
	}

	/**
	 * Shortcode to show ACF form
	 *
	 * @param $atts
	 * @return string
	 */
	public function set_shortcodes()
	{
		require_once SNC_ODF_PLUGIN_PATH . 'inc/shortcodes.php';
		new SNC_Oficinas_Dialogos_Federativos_Shortcodes();

        require_once SNC_ODF_PLUGIN_PATH . 'inc/snc-oficinas-formulario-inscricao-shortcode.php';
        new SNC_Oficinas_Formulario_Inscricao_Shortcode();

		require_once SNC_ODF_PLUGIN_PATH . 'inc/snc-oficinas-visualizar-inscricao-shortcode.php';
		new SNC_Oficinas_Visualizar_Inscricao_Shortcode();
	}

	public function add_acf_form_head () {
		if ( shortcode_exists( 'snc-subscription-form' ) ) {
			acf_form_head();
		}
	}

    function snc_acf_validate_save_post() {

        // check if user is an administrator
        if( current_user_can('manage_options') ) {

            // clear all errors
            acf_reset_validation_errors();
        }
        // clear all errors
//        acf_reset_validation_errors();
//    echo 'asdsadasd';
//    var_dump($_POST); die;
        // check custom $_POST data
        if($_POST['acf[field_5d125f7b8e05f]']) {
//        $field = get_field_object($selector);
            acf_add_validation_error( 'acf[field_5d125f7b8e05f]', 'Please check this input to proceed' );
        }

    }

    public function snc_acf_validate_value( $valid, $value, $field, $input )
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
        var_dump($post); die;
		$post = array('post_type' => 'inscricao-oficina', 'post_status' => 'publish');
		$post_id = wp_insert_post($post);

		$inscricao = array('ID' => $post_id, 'post_title' => 'Inscrição - (ID #' . $post_id . ')');
		wp_update_post($inscricao);

		// Return the new ID
		return $post_id;
	}

	/**
	 * Notify the monitors about a new indication
	 *
	 * @param $post_id
	 */
	public function postprocess_main_form($post_id)
	{
		$update = get_post_meta( $post_id, '_inscription_validated', true );

		echo 'apos submissao e depois de salvar';
		var_dump($update); die;
		if ( $update ) {
			return;
		}

		$user = wp_get_current_user();
		$user_cnpj = get_user_meta( $user->ID, '_user_cnpj', true );
		$oscar_minc_options = get_option('oscar_minc_options');
		$monitoring_emails = explode(',', $oscar_minc_options['oscar_minc_monitoring_emails']);
		// $to = array_map('trim', $monitoring_emails);
		$to = 'rickmanu@gmail.com';
		$headers[] = 'From: ' . bloginfo('name') . ' <automatico@cultura.gov.br>';
		// $headers[] = 'Reply-To: ' . $oscar_minc_options['oscar_minc_email_from_name'] . ' <' . $oscar_minc_options['oscar_minc_email_from'] . '>';
//		$headers[] = 'Reply-To: Galdar Tec <contato@galdar.com.br>';
		$subject = 'Nova inscrição ao SNC.';

		$msg  = 'Uma nova inscrição foi recebida em Oscar.<br>';
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
		if( $user_type === 'user' ){
			require dirname( __FILE__ ) . '/email-templates/user-template.php';
		} else {
			require dirname( __FILE__ ) . '/email-templates/admin-template.php';
		}
		return ob_get_clean();
	}

	/**
	 * Register stylesheet for our plugin
	 *
	 */
	public function register_plugin_styles()
	{
		wp_register_style(SNC_ODF_SLUG . '-style', SNC_ODF_PLUGIN_URL . 'assets/'. SNC_ODF_SLUG . '-style.css');
		wp_enqueue_style(SNC_ODF_SLUG . '-style');
	}

	/**
	 * Register stylesheet for our plugin
	 *
	 */
	public function register_plugin_scripts()
	{
		wp_enqueue_script('jquery-mask', SNC_ODF_PLUGIN_URL . 'assets/jquery.mask.min.js', array('jquery'), false, true);
		wp_enqueue_script(SNC_ODF_SLUG . '-script', SNC_ODF_PLUGIN_URL . 'assets/'. SNC_ODF_SLUG . '-script.js', array('jquery'), false, true);
        wp_localize_script(SNC_ODF_SLUG . '-script', 'vars', array( 'ajaxurl' => admin_url('admin-ajax.php')));

	}

	/**
	 * Redirect users to auth page on specific pages
	 *
	 */
	public function redirect_to_auth()
	{
		if (
			!is_user_logged_in() && is_page('perfil') ||
			!is_user_logged_in() && is_page('inscricao')
		) {
			wp_redirect( home_url('/login') );
			exit;
		}

		if (is_user_logged_in() && is_page('login')  ) {
			wp_redirect( home_url('/perfil') );
			exit;
		}

		if (is_user_logged_in() && is_page('registro')  ) {
			wp_redirect( home_url('/perfil') );
			exit;
		}
	}

	/**
	 * Redirect user after successful login, based on it's role
	 *
	 */
	public function login_redirect( $redirect_to, $request, $user )
	{
		if ( isset( $user->roles ) && is_array( $user->roles ) ) :
			if ( in_array( 'administrator', $user->roles ) ) {
				return admin_url();
			} elseif ( in_array( 'editor', $user->roles ) ) {
				return admin_url('edit.php?post_type=inscricao');
			} else {
				return home_url('/inscricao');
			}
		else:
			return $redirect_to;
		endif;
	}

	public function action_wp_mail_failed ($wp_error)
	{
		return error_log(print_r($wp_error, true));
	}

	public function debug_plugin ($wp_error)
	{ ?>

		<script type="text/javascript" id="debug-script">
			jQuery('#snc-register-form input').each(function(i, e){
				jQuery(e).removeAttr('required');
			})
		</script>

		<?php
	}

	/**
	 * Add new columns to our custom post type
	 *
	 * @param $columns
	 * @return array
	 */
	public function add_custom_columns($columns)
	{
		unset($columns['author']);
		return array_merge($columns, array(
			'responsible' => 'Responsável',
			'cpf' => 'CPF',
			'county' => 'Município',
			'state' => 'Estado'
		));
	}

	/**
	 * Fill custom columns with data
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function fill_custom_columns($column, $post_id)
	{
		$post_author_id = get_post_field('post_author', $post_id);
		$post_author = get_user_by('id', $post_author_id);

		switch ($column) {
			case 'responsible':
				if( current_user_can('administrator') || current_user_can('editor') ){
					echo '<a href="'. admin_url('/user-edit.php?user_id=') . $post_author_id . '">' . $post_author->display_name . '</a>';
				} else {
					echo $post_author->display_name;
				}
				break;
			case 'cpf':
				echo get_user_meta($post_author_id, '_user_cpf', true);
				break;
			case 'county':
				echo get_user_meta($post_author_id, '_user_county', true);
				break;
			case 'state':
				echo get_user_meta($post_author_id, '_user_state', true);
				break;
		}
	}
}

// Instantiate our plugin
new SNC_Oficinas_Dialogos_Federativos();