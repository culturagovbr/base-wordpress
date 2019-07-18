<?php
/**
 * Class for the settings page
 *
 */

// If this file is called directly, abort.
if (!defined('WPINC'))
    die();

class SNC_Oficinas_Settings
{

    private $options; // holds the values to be used in the fields callbacks

    public function __construct()
    {

        if (is_admin()) {
            add_action('admin_menu', array($this, 'register_sub_menu'));
            add_action('admin_init', array($this, 'register_settings'));
        }

    }

    /**
     * Add options page
     *
     */
    public function register_sub_menu()
    {

        add_submenu_page(
            'edit.php?post_type=inscricao-oficina',
            'Configurações',
            'Configurações',
            'manage_options',
            SNC_ODF_SLUG,
            array($this, 'settings_page_callback')
        );

    }

    public function settings_page_callback()
    {
        if (!current_user_can('manage_options')) {
            return;
        } ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                // output security fields for the registered setting
                settings_fields(SNC_ODF_SLUG);
                // output setting sections and their fields
                do_settings_sections(SNC_ODF_SLUG);
                // output save settings button
                submit_button(__('Save Settings', 'pwp_textdomain'));
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     *
     */
    public function register_settings()
    {
        register_setting(SNC_ODF_SLUG, SNC_ODF_SLUG . '_settings');

        // General settings section
        add_settings_section(
            'general_setting_section',
            '',
            '',
            SNC_ODF_SLUG
        );

        add_settings_field(
            'pwp_script',
            __('Piwik site ID: ', 'pwp_textdomain'),
            array($this, 'pwp_script_callback'),
            SNC_ODF_SLUG,
            'general_setting_section',
            [
                'label_for' => 'pwp_script',
                'class' => 'form-field',
            ]
        );

        add_settings_section(
            'snc_mail_confirmation_section',
            'Email de confirmação',
            '',
            SNC_ODF_SLUG
        );

        add_settings_field(
            'snc_monitoring_emails',
            'Emails para monitoramento',
            array($this, 'snc_monitoring_emails'),
            SNC_ODF_SLUG,
            'snc_mail_confirmation_section',
            ['label_for' => 'snc_monitoring_emails', 'class' => 'form-field',]
        );

        add_settings_field(
            'snc_email_from_name',
            'Remetente',
            array($this, 'snc_email_from_name'),
            SNC_ODF_SLUG,
            'snc_mail_confirmation_section',
            ['label_for' => 'snc_email_from_name', 'class' => 'form-field',]
        );

        add_settings_field(
            'snc_email_from',
            'Email de remetente',
            array($this, 'snc_email_from'),
            SNC_ODF_SLUG,
            'snc_mail_confirmation_section',
            ['label_for' => 'snc_email_from', 'class' => 'form-field',]
        );

        add_settings_field(
            'snc_email_body_confirm_subscription',
            'Texto Confirmação de cadastro ',
            array($this, 'snc_email_body_confirm_subscription'),
            SNC_ODF_SLUG,
            'snc_mail_confirmation_section',
            ['label_for' => 'snc_email_from', 'class' => 'form-field',]
        );

        register_setting(
            SNC_ODF_SLUG,
            SNC_ODF_SLUG . '_settings',
            array($this, 'input_validate_sanitize')
        );

    }

    /**
     * Sanitize settings fields
     *
     */
    public function input_validate_sanitize($input)
    {
        $output = array();

        if (isset($input['pwp_script'])) {
            // $output['pwp_script'] = stripslashes( wp_filter_post_kses( addslashes( $input['pwp_script'] ) ) );
            $output['pwp_script'] = $input['pwp_script'];
        }
        return $output;
    }

    /**
     * Input HTML
     *
     */
    function pwp_script_callback($args)
    {
        $options = get_option(SNC_ODF_SLUG . '_settings'); ?>
        <input id="<?php echo esc_attr($args['label_for']); ?>"
               name="<?php echo SNC_ODF_SLUG . '_settings'; ?>[<?php echo esc_attr($args['label_for']); ?>]"
               type="number" value="<?php echo $options['pwp_script']; ?>">
        <p class="description">
            <?php echo __('Define site Piwik unique identification.', 'pwp_textdomain'); ?>
        </p>
        <?php
    }


    function snc_email_from_name($args)
    {
        $options = get_option('snc_oficina_options'); ?>

        <input id="<?php echo esc_attr($args['label_for']); ?>"
               name="omc_options[<?php echo esc_attr($args['label_for']); ?>]" type="text"
               value="<?php echo $options['snc_email_from_name']; ?>">
        <p class="description">
            Nome do rementente do email, o padrão é: <b><?php echo bloginfo('name'); ?></b>
        </p>
        <?php
    }


    function snc_email_from($args)
    {
        $options = get_option('snc_oficina_options'); ?>

        <input id="<?php echo esc_attr($args['label_for']); ?>"
               name="omc_options[<?php echo esc_attr($args['label_for']); ?>]" type="text"
               value="<?php echo $options['snc_email_from']; ?>">
        <p class="description">
            Endereço de email do rementente, o padrão é: <b><?php echo get_option('admin_email'); ?></b>
        </p>
        <?php
    }


    function snc_monitoring_emails($args)
    {
        $options = get_option('snc_oficina_options'); ?>

        <input id="<?php echo esc_attr($args['label_for']); ?>"
               name="omc_options[<?php echo esc_attr($args['label_for']); ?>]" type="text"
               value="<?php echo $options['snc_monitoring_emails']; ?>">
        <p class="description">
            Estes emails receberão uma notificação sempre que for realizado uma indicação através do formulário. Separe
            múltiplos emails com vírgulas.
        </p>
        <?php
    }


    function snc_email_body_confirm_subscription($args)
    {
        $options = get_option('snc_oficina_options'); ?>
        <textarea id="<?php echo esc_attr($args['label_for']); ?>"
                  name="omc_options[<?php echo esc_attr($args['label_for']); ?>]"
                  rows="10"><?php echo $options['snc_email_body_confirm_subscription']; ?></textarea>
        <p class="description">
            Mensagem recebida pelo usuário após realizar inscrição.

            Exemplo: É com satisfação que recebemos sua solicitação de inscrição no evento Diálogos Federativos: Cultura
            de Ponto à Ponta no período de {{periodo_oficina}}, no horário de {{horario_oficina}}, a ser realizado no
            estado de {{estado_oficina}}, no {{local_oficina}}.
        </p>
        <?php
    }

}

?>