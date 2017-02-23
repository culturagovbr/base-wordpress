<?php
/**
 * Admin Menu Class
 *
 * @package Update API Manager/Admin
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.0
 *
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class EF_Activation_License_Menu {

    private $ef_activation_key;

    // Load admin menu
    public function __construct() {

        $this->ef_activation_key = new EF_Activation_Key();

        add_action('admin_menu', array($this, 'add_menu'), 11);
        add_action('admin_init', array($this, 'load_settings'));
    }

    // Add option page menu
    public function add_menu() {
        global $ef_activation_license_manager;

        $page = add_submenu_page('ef-options', __('Theme Activation', $ef_activation_license_manager->text_domain), __('Theme Activation', $ef_activation_license_manager->text_domain), 'edit_theme_options', 'ef-activation', array($this, 'config_page')
        );
        add_action('admin_print_styles-' . $page, array($this, 'css_scripts'));
    }

    // Draw option page
    public function config_page() {
        global $ef_activation_license_manager;

        $settings_tabs = array('ef_activation_dashboard' => __('License Activation', $ef_activation_license_manager->text_domain));
        $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'ef_activation_dashboard';
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'ef_activation_dashboard';
        ?>
        <div class='wrap'>
            <?php screen_icon(); ?>
            <h2><?php _e('Theme Activation', $ef_activation_license_manager->text_domain); ?></h2>
            <form action='options.php' method='post'>
                <div class="main">
                    <?php
                    if ($tab == 'ef_activation_dashboard') {
                        settings_fields('ef_activation_license_options');
                        do_settings_sections('ef_activation_dashboard');
                        $wc_am_save_changes = __('Save Changes', $ef_activation_license_manager->text_domain);
                        submit_button($wc_am_save_changes);
                    }
                }

                // Register settings
                public function load_settings() {
                    global $ef_activation_license_manager;

                    register_setting('ef_activation_license_options', 'ef_activation_license_options', array($this, 'validate_options'));

                    // API Key
                    add_settings_section('api_key', '', array($this, 'wc_am_api_key_text'), 'ef_activation_dashboard');
                    add_settings_field('api_key', __('License Key', $ef_activation_license_manager->text_domain), array($this, 'wc_am_api_key_field'), 'ef_activation_dashboard', 'api_key');
                    add_settings_field('api_email', __('License email', $ef_activation_license_manager->text_domain), array($this, 'wc_am_api_email_field'), 'ef_activation_dashboard', 'api_key');

                    // Activation settings
                    register_setting('ef_activation_deactivate_checkbox', 'ef_activation_deactivate_checkbox', array($this, 'wc_am_license_key_deactivation'));
                    add_settings_section('deactivate_button', __('Theme License Deactivation', $ef_activation_license_manager->text_domain), array($this, 'wc_am_deactivate_text'), 'ef_activation_deactivation');
                    add_settings_field('deactivate_button', __('Deactivate Theme License', $ef_activation_license_manager->text_domain), array($this, 'wc_am_deactivate_textarea'), 'ef_activation_deactivation', 'deactivate_button');
                }

                // Provides text for api key section
                public function wc_am_api_key_text() {
                    //
                }

                // Outputs API License text field
                public function wc_am_api_key_field() {
                    global $ef_activation_license_manager;

                    $options = get_option('ef_activation_license_options');
                    $api_key = $options['api_key'];
                    echo "<input id='api_key' name='ef_activation_license_options[api_key]' size='25' type='text' value='{$options['api_key']}' />";
                    if (!empty($options['api_key'])) {
                        echo "<span class='icon-pos'><img src='" . $ef_activation_license_manager->plugin_url() . "assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
                    } else {
                        echo "<span class='icon-pos'><img src='" . $ef_activation_license_manager->plugin_url() . "assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
                    }
                }

                // Outputs API License email text field
                public function wc_am_api_email_field() {
                    global $ef_activation_license_manager;

                    $options = get_option('ef_activation_license_options');
                    $activation_email = $options['activation_email'];
                    echo "<input id='activation_email' name='ef_activation_license_options[activation_email]' size='25' type='text' value='{$options['activation_email']}' />";
                    if (!empty($options['activation_email'])) {
                        echo "<span class='icon-pos'><img src='" . $ef_activation_license_manager->plugin_url() . "assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
                    } else {
                        echo "<span class='icon-pos'><img src='" . $ef_activation_license_manager->plugin_url() . "assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
                    }
                }

                // Sanitizes and validates all input and output for Dashboard
                public function validate_options($input) {
                    global $ef_activation_license_manager;

                    // Load existing options, validate, and update with changes from input before returning
                    $options = get_option('ef_activation_license_options');

                    $options['api_key'] = trim($input['api_key']);
                    $options['activation_email'] = trim($input['activation_email']);

                    /**
                     * Plugin Activation
                     */
                    $api_email = trim($input['activation_email']);
                    $api_key = trim($input['api_key']);

                    $activation_status = get_option('ef_activation_license_options_activated');
                    $checkbox_status = get_option('ef_activation_deactivate_checkbox');

                    $current_api_key = $this->get_key();

                    // Should match the settings_fields() value
                    if ($_REQUEST['option_page'] != 'ef_activation_deactivate_checkbox') {

                        if ($activation_status == 'Deactivated' || $activation_status == '' || $api_key == '' || $api_email == '' || $checkbox_status == 'on' || $current_api_key != $api_key) {

                            /**
                             * If this is a new key, and an existing key already exists in the database,
                             * deactivate the existing key before activating the new key.
                             */
                            if ($current_api_key != $api_key)
                                $this->replace_license_key($current_api_key);

                            $args = array(
                                'email' => $api_email,
                                'licence_key' => $api_key,
                            );

                            $activate_results = $this->ef_activation_key->activate($args);

                            $activate_results = json_decode($activate_results, true);

                            if ($activate_results['activated'] == true) {
                                add_settings_error('activate_text', 'activate_msg', __('Plugin activated.', $ef_activation_license_manager->text_domain) . "{$activate_results['message']}.", 'updated');
                                update_option('ef_activation_license_options_activated', 'Activated');
                                update_option('ef_activation_deactivate_checkbox', 'off');
                            }

                            if ($activate_results == false) {
                                add_settings_error('api_key_check_text', 'api_key_check_error', __('Connection failed to the License Key API server. Try again later.', $ef_activation_license_manager->text_domain), 'error');
                                $options['api_key'] = '';
                                $options['activation_email'] = '';
                                update_option('ef_activation_license_options_activated', 'Deactivated');
                            }

                            if (isset($activate_results['code'])) {

                                switch ($activate_results['code']) {
                                    case '100':
                                        add_settings_error('api_email_text', 'api_email_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                                        $options['activation_email'] = '';
                                        $options['api_key'] = '';
                                        update_option('ef_activation_license_options_activated', 'Deactivated');
                                        break;
                                    case '101':
                                        add_settings_error('api_key_text', 'api_key_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                                        $options['api_key'] = '';
                                        $options['activation_email'] = '';
                                        update_option('ef_activation_license_options_activated', 'Deactivated');
                                        break;
                                    case '102':
                                        add_settings_error('api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                                        $options['api_key'] = '';
                                        $options['activation_email'] = '';
                                        update_option('ef_activation_license_options_activated', 'Deactivated');
                                        break;
                                    case '103':
                                        add_settings_error('api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                                        $options['api_key'] = '';
                                        $options['activation_email'] = '';
                                        update_option('ef_activation_license_options_activated', 'Deactivated');
                                        break;
                                    case '104':
                                        add_settings_error('api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                                        $options['api_key'] = '';
                                        $options['activation_email'] = '';
                                        update_option('ef_activation_license_options_activated', 'Deactivated');
                                        break;
                                    case '105':
                                        add_settings_error('api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}", 'error');
                                        $options['api_key'] = '';
                                        $options['activation_email'] = '';
                                        update_option('ef_activation_license_options_activated', 'Deactivated');
                                        break;
                                    case '106':
                                        add_settings_error('sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$activate_results['additional info']}", 'error');
                                        $options['api_key'] = '';
                                        $options['activation_email'] = '';
                                        update_option('ef_activation_license_options_activated', 'Deactivated');
                                        break;
                                }
                            }
                        } // End Plugin Activation
                    }

                    return $options;
                }

                public function get_key() {
                    $wc_am_options = get_option('ef_activation_license_options');
                    $api_key = $wc_am_options['api_key'];

                    return $api_key;
                }

                // Deactivate the current license key before activating the new license key
                public function replace_license_key($current_api_key) {
                    global $ef_activation_license_manager;

                    $default_options = get_option('ef_activation_license_options');

                    $api_email = $default_options['activation_email'];

                    $args = array(
                        'email' => $api_email,
                        'licence_key' => $current_api_key,
                    );

                    $reset = $this->ef_activation_key->deactivate($args); // reset license key activation

                    if ($reset == true)
                        return true;

                    return add_settings_error('not_deactivated_text', 'not_deactivated_error', __('The license could not be deactivated. Use the License Deactivation tab to manually deactivate the license before activating a new license.', $ef_activation_license_manager->text_domain), 'updated');
                }

                // Deactivates the license key to allow key to be used on another blog
                public function wc_am_license_key_deactivation($input) {
                    global $ef_activation_license_manager;

                    $activation_status = get_option('ef_activation_license_options_activated');

                    $default_options = get_option('ef_activation_license_options');

                    $api_email = $default_options['activation_email'];
                    $api_key = $default_options['api_key'];

                    $args = array(
                        'email' => $api_email,
                        'licence_key' => $api_key,
                    );

                    $options = ( $input == 'on' ? 'on' : 'off' );

                    if ($options == 'on' && $activation_status == 'Activated' && $api_key != '' && $api_email != '') {
                        $reset = $this->ef_activation_key->deactivate($args); // reset license key activation

                        if ($reset == true) {
                            $update = array(
                                'api_key' => '',
                                'activation_email' => ''
                            );
                            $merge_options = array_merge($default_options, $update);

                            update_option('ef_activation_license_options', $merge_options);

                            add_settings_error('wc_am_deactivate_text', 'deactivate_msg', __('Theme license deactivated.', $ef_activation_license_manager->text_domain), 'updated');

                            return $options;
                        }
                    } else {

                        return $options;
                    }
                }

                public function wc_am_deactivate_text() {
                    
                }

                public function wc_am_deactivate_textarea() {
                    global $ef_activation_license_manager;

                    $activation_status = get_option('ef_activation_deactivate_checkbox');
                    ?>
                    <input type="checkbox" id="ef_activation_deactivate_checkbox" name="ef_activation_deactivate_checkbox" value="on" <?php checked($activation_status, 'on'); ?> />
                    <span class="description"><?php _e('Deactivates theme license so it can be used on another blog.', $ef_activation_license_manager->text_domain); ?></span>
                    <?php
                }

                // Loads admin style sheets
                public function css_scripts() {
                    global $ef_activation_license_manager;

                    $curr_ver = $ef_activation_license_manager->version;

                    wp_register_style('cc-tk-admin-css', $ef_activation_license_manager->plugin_url() . 'assets/css/admin-settings.css', array(), $curr_ver, 'all');
                    wp_enqueue_style('cc-tk-admin-css');
                }

            }

            $ef_activation_license_menu = new EF_Activation_License_Menu();
            