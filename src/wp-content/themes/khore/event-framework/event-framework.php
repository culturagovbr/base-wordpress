<?php

require_once 'lib/facebook/facebook.php';
require_once 'lib/twitter.php';
require_once 'lib/instagram.php';
require_once 'lib/geocode.php';
require_once 'lib/recaptchalib.php';
require_once 'lib/api/functions.php';
require_once 'defines.php';

/**
 * The main Event Framework class
 * 
 * Manages the rest of the component base
 *
 */
class DX_Event_Framework {

    /**
     * Current framework version 
     */
    public $version;

    public function __construct() {
        $this->version = '1.0';

        // Adding the theme support
        //add_theme_support( 'multievent' );
        // Multievent functionality added
        include_once( EF_PARENT_DIR . 'multievent/multievent.php' );

        add_action('after_setup_theme', array($this, 'ef_load_textdomain'));
        add_action('init', array($this, 'require_core'), 1);
        add_action('init', array($this, 'fetch_theme_options'));
        add_action('admin_menu', array($this, 'add_theme_options_page'));

        // Load CPT, taxonomies and relevant data
        add_action('init', array($this, 'setup_post_types'));
        add_action('init', array($this, 'setup_taxonomies'));

        // Add metaboxes with custom fields for CPTs
        //add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        $this->add_meta_boxes();

        // Enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_styles_scripts'));

        // Load widgets
        add_action('widgets_init', array($this, 'setup_widgets'));

        add_filter('template_include', array($this, 'fallback_templates'));

        add_action('admin_enqueue_scripts', array($this, 'dx_multievent_enqueue_script_stype'));

        // Add search to widget page
        add_action('widgets_admin_page', array($this, 'dx_multievent_widget_html'));

        //load theme shortcodes
        $this->setup_shortcodes();
    }

    /**
     * Register the theme options page
     */
    public function add_theme_options_page() {
        add_menu_page(__('Theme Options', 'dxef'), __('Theme Options', 'dxef'), 'manage_options', 'ef-options', array($this, 'theme_options_callback'));
        add_theme_page(__('Other Themes', 'dxef'), __('More Themes', 'dxef'), 'manage_options', 'ef-other-themes', array($this, 'theme_otherthemes_callback'));
    }

    /**
     * Include the code for the theme options page
     */
    public function theme_otherthemes_callback() {
        $current_theme = wp_get_theme();
        include_once( EF_INC_DIR . 'event-other-themes.php' );
    }
    
    /**
     * Include the code for the theme options page
     */
    public function theme_options_callback() {
        $current_theme = wp_get_theme();

        include_once( EF_INC_DIR . 'event-admin.php' );
    }

    /**
     * Setup all Custom Post Types
     */
    public function setup_post_types() {
        include_once ( EF_INC_DIR . 'cpts.php' );
    }

    /**
     * Setup all Taxonomies
     */
    public function setup_taxonomies() {
        include_once ( EF_INC_DIR . 'taxonomies.php' );
        // Include libraries
        include_once( EF_PARENT_DIR . 'lib/taxonomy-meta.php' );
        include_once( EF_INC_DIR . 'taxonomy-meta.php' );
    }

    /**
     * Include all shortscodes
     */
    public function setup_shortcodes() {
        include_once ( EF_INC_DIR . 'shortcodes.php' );
    }

    /**
     * Include all widgets
     */
    public function setup_widgets() {
        include_once ( EF_INC_DIR . 'widgets.php' );
    }

    /**
     * Register all post type metaboxes
     */
    public function add_meta_boxes() {
        include_once ( EF_INC_DIR . 'metaboxes.php' );
    }

    /**
     * Include all core files for the framework
     */
    public function require_core() {

        // Include helpers
        include_once( EF_HELPERS_DIR . 'framework-helper.php' );
        include_once( EF_HELPERS_DIR . 'theme-specific.php' );
        include_once( EF_HELPERS_DIR . 'cpt/query-manager.php' );
        include_once( EF_HELPERS_DIR . 'cpt/pois.php' );
        include_once( EF_HELPERS_DIR . 'cpt/sessions.php' );
        include_once( EF_HELPERS_DIR . 'cpt/speakers.php' );
        include_once( EF_HELPERS_DIR . 'taxonomy.php' );

        // Include fields
        include_once( EF_FIELDS_DIR . 'base.php' );
        include_once( EF_FIELDS_DIR . 'background-color.php' );
        include_once( EF_FIELDS_DIR . 'checkbox.php' );
        include_once( EF_FIELDS_DIR . 'color.php' );
        include_once( EF_FIELDS_DIR . 'content-generator.php' );
        include_once( EF_FIELDS_DIR . 'font.php' );
        include_once( EF_FIELDS_DIR . 'image.php' );
        include_once( EF_FIELDS_DIR . 'importer.php' );
        include_once( EF_FIELDS_DIR . 'radio.php' );
        include_once( EF_FIELDS_DIR . 'select.php' );
        include_once( EF_FIELDS_DIR . 'text.php' );
        include_once( EF_FIELDS_DIR . 'textarea.php' );
        include_once( EF_FIELDS_DIR . 'gallery.php' );
        include_once( EF_FIELDS_DIR . 'datapicker_text.php' );
        include_once( EF_FIELDS_DIR . 'separator.php' );

        // Include event components
        include_once( EF_INC_DIR . 'theme-options/options-tab.php' );
        include_once( EF_INC_DIR . 'theme-options/options-panel.php' );
        include_once( EF_INC_DIR . 'theme-options/panel-manager.php' );
        include_once( EF_INC_DIR . 'event-options.php' );

        include_once( EF_INC_DIR . 'comments.php' );

        // Libs
        // @TODO: Change EF_PARENT_DIR with EF_LIB_DIR
        include_once( EF_PARENT_DIR . 'lib/importer/parsers.php' );

        // Check if EF_STYLE_SWITCHER is set to true
        if (defined('EF_STYLE_SWITCHER') && EF_STYLE_SWITCHER == true) {
            include_once( EF_LIB_DIR . 'style-switcher/style-switcher.php' );
        }

        include_once(EF_CONTENT_BUILDER_DIR . 'content-builder.php');
    }

    /**
     * Generate the theme options configuration
     */
    public function fetch_theme_options() {
        EF_Theme_Specific_Helper::load_theme_options();
    }

    /**
     * Add admin styles and scripts
     * 
     * @param string $hook current screen
     */
    public function admin_styles_scripts($hook) {
        if ($hook === 'toplevel_page_ef-options') {
            wp_enqueue_script('jquery');
            wp_enqueue_script('easy-tabs', EF_ASSETS_URL . '/js/easyResponsiveTabs.js', array('jquery'));
            wp_enqueue_script('ef-admin', EF_ASSETS_URL . '/js/ef-admin.js', array('jquery'));

            wp_enqueue_style('easy-tabs-style', EF_ASSETS_URL . '/css/easy-responsive-tabs.css');
            wp_enqueue_style('font-awesome', EF_ASSETS_URL . '/css/font-awesome.min.css');
            wp_enqueue_style('ef-normalize', EF_ASSETS_URL . '/css/normalize.css');
            wp_enqueue_style('ef-theme-options', EF_ASSETS_URL . 'css/themeoptions.css');
        } else if ($hook === 'appearance_page_ef-other-themes') {
            wp_enqueue_style('ef-theme-other-themes', EF_ASSETS_URL . 'css/otherthemes.css');
        }
    }

    /**
     * Load Event Framework textdomain
     */
    public function ef_load_textdomain() {
        load_theme_textdomain('dxef', dirname(__FILE__) . '/languages/');
    }

    public function fallback_templates($template) {
        global $wp_query;

        if (is_singular('speaker') && false === strpos($template, 'single-speaker.php')) {
            return EF_COMPONENTS_DIR . 'templates/single-speaker.php';
        } else if (is_singular('session') && false === strpos($template, 'single-session.php')) {
            return EF_COMPONENTS_DIR . 'templates/single-session.php';
        }

        return $template;
    }

    /**
     * Handles Script Style functionality for admin
     * 
     * @package Event Framework
     * @since 1.0.0
     */
    function dx_multievent_enqueue_script_stype($hook) {

        $pages = array('widgets.php');

        if (in_array($hook, $pages)) {

            wp_enqueue_script('jquery');

            // Registring searching widget script
            wp_register_script('search-widget-script', EF_ASSETS_URL . 'js/search-widget.js', array('jquery'), false, false);
            wp_enqueue_script('search-widget-script');
        }
    }

    /**
     * Add search widget
     * 
     * Handle add search functionality
     * on admin side widget page
     * 
     * @package Event Framework
     * @since 1.0.0
     */
    function dx_multievent_widget_html() {

        $html = '';
        $html .= '<div style="margin: 10px 0px; text-align: right;">';
        $html .= '<label>' . __('Search Widget Area : ', 'dxef') . '</label><input type="text" value="" class="multievent-search-area" /> ';
        $html .= '<label>' . __('Search Widget : ', 'dxef') . '</label><input type="text" value="" class="multievent-search-inp" /> ';
        //$html .= '<input type="button" value="'.__('Search', 'dxef').'" class="button button-primary multievent-search-btn" />';
        $html .= '</div>';

        echo $html;
    }

}

// Init
new DX_Event_Framework();
