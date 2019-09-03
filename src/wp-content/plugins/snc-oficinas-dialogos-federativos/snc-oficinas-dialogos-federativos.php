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
define('SNC_UPLOAD', '/wp-content/uploads/');

define('SNC_POST_TYPE_INSCRICOES', 'inscricao-oficina');
define('SNC_POST_TYPE_OFICINA', 'oficinas');
define('SNC_POST_TYPE_PARTICIPACAO', 'participacao-oficina');

class SNC_Oficinas_Dialogos_Federativos
{
    public function __construct()
    {
        register_activation_hook(__FILE__, array($this, 'activate_hook'));

        // Register the autoloader
        spl_autoload_register(array($this, 'autoloader'));

        add_action('init', array($this, 'inscricao_oficina_settings'));
        add_action('init', array($this, 'inscricao_oficina_cpt'));
        add_action('init', array($this, 'custom_post_status'), 0);
        add_action('init', array($this, 'set_shortcodes'));

        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'load_custom_wp_admin_scripts'));

        add_action('template_redirect', array($this, 'redirect_to_auth'));
        add_action('login_redirect', array($this, 'login_redirect'), 10, 3);
        add_action('manage_posts_custom_column', array($this, 'fill_custom_columns'), 10, 2);
        add_filter('page_template', array($this, 'snc_oficinas_page_template'));

        add_filter('manage_edit-inscricao-oficina_columns', array($this, 'add_custom_columns'));

        add_action('get_footer', array($this, 'debug_plugin'));


        add_action('admin_menu', array($this, 'snc_relatorio_menu'));
        add_action('plugins_loaded', array($this, 'snc_ofinas_relatorio_concluidos'));

        register_activation_hook(__FILE__, array(__CLASS__, 'activate'));

        // shortcodes
        new SNC_Oficinas_Shortcode_Formulario_Usuario();
    }

    static function activate()
    {
        $role = get_role('administrator');
        $role->add_cap('download_csv');
    }

    public function snc_relatorio_menu()
    {
        add_menu_page('Oficinas - Relatórios', 'Oficinas - Relatórios', 'manage_options', 'oficinas-relatorios-concluidos', array($this, 'snc_ofinas_relatorio_concluidos'), '', 29);
//        add_submenu_page('oficinas-relatorios', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'oficinas-relatorios-rel1');
//        add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2');
    }

    public function snc_ofinas_relatorio_concluidos()
    {
        global $pagenow;
        if ($pagenow == 'admin.php' && $_GET['page'] == 'oficinas-relatorios-concluidos') {

            $filename = SNC_Oficinas_Service::generate_relatorio_inscritos_csv();

            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=relatorio_concluidos.csv');

            readfile($filename);

            exit();
        }
    }

    public function activate_hook()
    {
        if (!is_plugin_active('advanced-custom-fields-pro/acf.php') && !is_plugin_active('advanced-custom-fields/acf.php')) {
            echo 'Para que este plugin funcione corretamente, é necessário a instalação e ativação do plugin ACF - 
            <a href="http://advancedcustomfields.com/" target="_blank">Advanced custom fields</a>.';
            die;
        }
    }

    /**
     * Autoload specific classses.
     *
     * @param $class
     */
    public static function autoloader($class)
    {
        $filename = false;
        if (strpos($class, 'SNC_Oficinas_') === 0) {
            $filename = str_replace('_', '-', $class);
            $filename = plugin_dir_path(__FILE__) . 'inc/' . strtolower($filename) . '.php';
        }

        if (!empty($filename) && file_exists($filename)) {
            include $filename;
        }
    }

    public function inscricao_oficina_settings()
    {
        new SNC_Oficinas_Settings();
    }

    public function inscricao_oficina_cpt()
    {
        register_post_type(SNC_POST_TYPE_OFICINA, array(
                'labels' => array(
                    'name' => 'Oficinas',
                    'singular_name' => 'Oficinas',
                    'add_new' => 'Nova oficina',
                    'add_new_item' => 'Nova oficina',
                    'search_items' => 'Procurar oficina',
                    'not_found' => 'Nenhuma oficina encontrada',
                ),
                'description' => 'Cadastro de Oficinas dos Diálogos Federativos',
                'public' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => false,
                'supports' => array('title'),
                'menu_icon' => 'dashicons-groups'
            )
        );

        register_post_type(SNC_POST_TYPE_INSCRICOES, array(
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

        register_post_type(SNC_POST_TYPE_PARTICIPACAO, array(
                'labels' => array(
                    'name' => 'Questionário para Pós-Oficinas',
                    'singular_name' => 'Questionário para Pós-Oficinas',
                    'add_new' => 'Novo questionário',
                    'add_new_item' => 'Novo questionário',
                    'search_items' => 'Procurar questionário',
                    'not_found' => 'Nenhuma questionário encontrada',
                ),
                'description' => 'Questionário das Oficinas dos Diálogos Federativos',
                'public' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => false,
                'supports' => array('title'),
                'menu_icon' => 'dashicons-clipboard'
            )
        );
    }

    function custom_post_status()
    {
        register_post_status('confirmed', array(
            'label' => _x('Confirmado', 'Status Confirmado', 'text_domain'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Confirmado (%s)', 'Confirmado  (%s)'),
        ));

        register_post_status('waiting_list', array(
            'label' => _x('Lista de espera', 'Status Lista de espera', 'text_domain'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Lista de espera (%s)', 'Lista de espera (%s)'),
        ));

        register_post_status('canceled', array(
            'label' => _x('Cancelado', 'Status Cancelado', 'text_domain'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Cancelado (%s)', 'Cancelado (%s)'),
        ));

        register_post_status('waiting_presence', array(
            'label' => _x('Aguardando Presença', 'Aguardando Presença', 'text_domain'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Aguardando Presença (%s)', 'Aguardando Presença (%s)'),
        ));

        register_post_status('waiting_questions', array(
            'label' => _x('Aguardando Questionário', 'Aguardando Questionário', 'text_domain'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Aguardando Questionário (%s)', 'Aguardando Questionário (%s)'),
        ));

        register_post_status('finish', array(
            'label' => _x('Concluído', 'Status Concluído', 'text_domain'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Concluído (%s)', 'Concluído (%s)'),
        ));
    }

    /**
     * Shortcode to show ACF form
     *
     * @param $atts
     * @return string
     */
    public function set_shortcodes()
    {
        new SNC_Oficinas_Shortcode_Inscricoes();
        new SNC_Oficinas_Shortcode_Formulario_Inscricao();
        new SNC_Oficinas_Shortcode_Login();
        new SNC_Oficinas_Shortcode_Confirmacao_Inscricao();
        new SNC_Oficinas_Shortcode_Visualizar_Email();
        new SNC_Oficinas_Shortcode_Formulario_Participacao();
        new SNC_Oficinas_Shortcode_Certificado();
    }

    /**
     * Register stylesheet for our plugin
     *
     */
    public function register_plugin_styles()
    {
        global $wp_scripts;

        wp_enqueue_style('jquery-ui-dialog-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');


        wp_register_style(SNC_ODF_SLUG . '-style', SNC_ODF_PLUGIN_URL . 'assets/' . SNC_ODF_SLUG . '-style.css');
        wp_enqueue_style(SNC_ODF_SLUG . '-style');
    }

    /**
     * Register stylesheet for our plugin
     *
     */
    public function register_plugin_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');

        wp_enqueue_script('jquery-mask', SNC_ODF_PLUGIN_URL . 'assets/jquery.mask.min.js', array('jquery'), false, true);
        wp_enqueue_script(SNC_ODF_SLUG . '-script', SNC_ODF_PLUGIN_URL . 'assets/' . SNC_ODF_SLUG . '-script.js', array('jquery'), false, true);
        wp_localize_script(SNC_ODF_SLUG . '-script', 'vars', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    function load_custom_wp_admin_scripts($hook)
    {
        if (get_post_type() != 'oficinas') {
            return;
        }

        wp_enqueue_script(SNC_ODF_SLUG . '-admin', SNC_ODF_PLUGIN_URL . 'assets/' . SNC_ODF_SLUG . '-admin.js', array('jquery'), false, true);
    }

    /**
     * Redirect users to auth page on specific pages
     *
     */
    public function redirect_to_auth()
    {
        if (
            !is_user_logged_in() && is_page('perfil') ||
            !is_user_logged_in() && is_page('inscricoes') ||
            !is_user_logged_in() && is_page('inscricao')
        ) {
            wp_redirect(home_url('/login'));
            exit;
        }

        if (is_user_logged_in() && is_page('login')) {
            wp_redirect(home_url('/inscricoes'));
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
        if (isset($user->roles) && is_array($user->roles)) {
            if (in_array('administrator', $user->roles)) {
                return admin_url();
            } elseif (in_array('editor', $user->roles)) {
                return admin_url('edit.php?post_type=inscricao');
            } else {
                return home_url('/inscricoes');
            }
        } else {
            return $redirect_to;
        }
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

    public function snc_oficinas_page_template($page_template)
    {
        if (is_page('visualizar-email')) {
            $page_template = SNC_ODF_PLUGIN_PATH . '/pages/custom-page-template.php';
        }
        return $page_template;
    }

    public static function snc_relatorio($type = "inscritos")
    {
        $oficinasEmail = new SNC_Oficinas_Email(null, null);
        $oficinasEmail->snc_send_mail_relatorios($type);

        echo "Relatório enviado com sucesso!", PHP_EOL;
    }

    public static function snc_proximas_oficinas($numDiasAntes = 1)
    {
        $objects = SNC_Oficinas_Service::snc_next_oficinas($numDiasAntes);
        $success = [];

        foreach ((array)$objects as $object) {
            $oficinasEmail = new SNC_Oficinas_Email($object->post_id, 'snc_email_reminder_workshop');

            if (!$oficinasEmail->snc_send_mail_user()) {
                $success[] = "Houve falha ao executar para o 'ID => {$object->post_id}'!";
                break;
            }

            $success[] = "Rotina próximo executado sucesso para o 'ID => {$object->post_id}!'";
        }

        echo implode("<br />", $success), PHP_EOL;
    }
}

// Instantiate our plugin
new SNC_Oficinas_Dialogos_Federativos();

// Cron - Relatório de Inscritos
add_filter('cron_schedules', 'add_custom_cron_schedule');

function add_custom_cron_schedule($schedules)
{
    $schedules['minute'] = array(
        'interval' => 60 * 5,
        'display' => ('20 minutos')
    );
    return $schedules;
}

function snc_relatorio_inscritos_cron()
{
    SNC_Oficinas_Dialogos_Federativos::snc_relatorio();
    SNC_Oficinas_Dialogos_Federativos::snc_relatorio("concluidos");
//    SNC_Oficinas_Service::trigger_change_finish_offices();
}

function snc_proximas_oficinas_cron()
{
    SNC_Oficinas_Dialogos_Federativos::snc_proximas_oficinas(1);
}

// Cron - Relatório de Inscritos
if (!wp_next_scheduled('snc_relatorio_inscritos_cron') || wp_next_scheduled('snc_relatorio_inscritos_cron') + 120 < time()) {
    wp_clear_scheduled_hook('snc_relatorio_inscritos_cron');
    wp_schedule_event(time(), 'minute', 'snc_relatorio_inscritos_cron');
}

add_action('snc_relatorio_inscritos_cron', 'snc_relatorio_inscritos_cron');

// Cron - Próximas Oficinas
if (!wp_next_scheduled('snc_proximas_oficinas_cron') || wp_next_scheduled('snc_proximas_oficinas_cron') + 120 < time()) {
    wp_clear_scheduled_hook('snc_proximas_oficinas_cron');
    wp_schedule_event(time(), 'minute', 'snc_proximas_oficinas_cron');
}

add_action('snc_proximas_oficinas_cron', 'snc_proximas_oficinas_cron');