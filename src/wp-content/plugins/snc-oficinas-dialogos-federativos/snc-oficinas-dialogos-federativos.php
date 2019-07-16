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

if (!defined('WPINC'))
    die();

define('SNC_ODF_SLUG', 'snc-oficinas-dialogos-federativos');
define('SNC_ODF_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SNC_ODF_PLUGIN_PATH', plugin_dir_path(__FILE__));

class SNC_Oficinas_Dialogos_Federativos
{

    public function __construct()
    {

        register_activation_hook(__FILE__, array($this, 'activate_hook'));

        add_action('init', array($this, 'inscricao_oficina_settings'));
        add_action('init', array($this, 'inscricao_oficina_cpt'));
        add_action('init', array($this, 'set_shortcodes'));

//        add_action('get_header', array($this, 'add_acf_form_head'), 0);

//		add_action('acf/pre_save_post', array($this, 'preprocess_main_form'));
//		add_action('acf/save_post', array($this, 'postprocess_main_form'));
//        add_action('acf/validate_save_post', array($this, 'snc_acf_validate_save_post'), 10, 0);

        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));
        add_action('template_redirect', array($this, 'redirect_to_auth'));
        add_action('login_redirect', array($this, 'login_redirect'), 10, 3);
        add_action('manage_posts_custom_column', array($this, 'fill_custom_columns'), 10, 2);

        add_filter('manage_edit-inscricao-oficina_columns', array($this, 'add_custom_columns'));

        // add_action('wp_mail_failed', array($this, 'action_wp_mail_failed'), 10, 1);
        add_action('get_footer', array($this, 'debug_plugin'));

        // extensions
        require( SNC_ODF_PLUGIN_PATH . 'inc/snc-oficinas-registro-usuario-shortcode.php' );
        require(SNC_ODF_PLUGIN_PATH . 'inc/snc-oficinas-formulario-inscricao-shortcode.php');

    }

    public function activate_hook()
    {
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

    public function inscricao_oficina_cpt()
    {
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
        require_once SNC_ODF_PLUGIN_PATH . 'inc/snc-oficinas-login-shortcode.php';
//        require_once SNC_ODF_PLUGIN_PATH . 'inc/snc-oficinas-visualizar-inscricao-shortcode.php';

    }

    /**
     * Register stylesheet for our plugin
     *
     */
    public function register_plugin_styles()
    {
        wp_register_style(SNC_ODF_SLUG . '-style', SNC_ODF_PLUGIN_URL . 'assets/' . SNC_ODF_SLUG . '-style.css');
        wp_enqueue_style(SNC_ODF_SLUG . '-style');
    }

    /**
     * Register stylesheet for our plugin
     *
     */
    public function register_plugin_scripts()
    {
        wp_enqueue_script('jquery-mask', SNC_ODF_PLUGIN_URL . 'assets/jquery.mask.min.js', array('jquery'), false, true);
        wp_enqueue_script(SNC_ODF_SLUG . '-script', SNC_ODF_PLUGIN_URL . 'assets/' . SNC_ODF_SLUG . '-script.js', array('jquery'), false, true);
        wp_localize_script(SNC_ODF_SLUG . '-script', 'vars', array('ajaxurl' => admin_url('admin-ajax.php')));

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
            wp_redirect(home_url('/login'));
            exit;
        }

        if (is_user_logged_in() && is_page('login')) {
            wp_redirect(home_url('/perfil'));
            exit;
        }

        if (is_user_logged_in() && is_page('registro')) {
            wp_redirect(home_url('/perfil'));
            exit;
        }
    }

    /**
     * Redirect user after successful login, based on it's role
     *
     */
    public function login_redirect($redirect_to, $request, $user)
    {
        if (isset($user->roles) && is_array($user->roles)) :
            if (in_array('administrator', $user->roles)) {
                return admin_url();
            } elseif (in_array('editor', $user->roles)) {
                return admin_url('edit.php?post_type=inscricao');
            } else {
                return home_url('/inscricao');
            }
        else:
            return $redirect_to;
        endif;
    }

    public function action_wp_mail_failed($wp_error)
    {
        return error_log(print_r($wp_error, true));
    }

    public function debug_plugin($wp_error)
    { ?>

        <script type="text/javascript" id="debug-script">
            jQuery('#snc-register-form input').each(function (i, e) {
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
                if (current_user_can('administrator') || current_user_can('editor')) {
                    echo '<a href="' . admin_url('/user-edit.php?user_id=') . $post_author_id . '">' . $post_author->display_name . '</a>';
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