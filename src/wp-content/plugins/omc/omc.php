<?php
/**
 * Plugin Name:       Ordem do Mérito Cultural
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       Sistema para definição de indicados à Ordem do Mérito Cultural – OMC
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/darciro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('OMC')) :

    class OMC
    {

        public function __construct()
        {
            add_action('init', array($this, 'indicacao_cpt'));
            add_action ('acf/pre_save_post', array($this, 'preprocess_main_form'));
            add_shortcode('omc', array($this, 'omc_shortcode'));
            add_action( 'get_header', 'acf_form_head' );
            add_action( 'wp_enqueue_scripts', array( $this, 'register_omc_styles' ) );
            add_action ('admin_enqueue_scripts', array( $this, 'register_omc_scripts' ) );
            add_action( 'admin_head', array( $this, 'omc_admin_scripts' ) );

            require_once dirname( __FILE__ ) . '/inc/options-page.php';
        }

        /**
         * Shortcode to show ACF form
         *
         * @param $atts
         * @return string
         */
        public function omc_shortcode($atts)
        {
            $atts = shortcode_atts(array(
                'form-group-id' => '',
                'return'        => home_url ('/?sent=true#message')
            ), $atts);

            ob_start();

            $settings = array(
                'field_groups'      => array($atts['form-group-id']),
                'id'                => 'omc-main-form',
                'post_id'           => 'new_indicacao',
                'new_post'          => array(
                    'post_type'         => 'indicacao',
                    'post_status'       => 'publish'
                ),
                'updated_message'   => 'Indicação enviada com sucesso.',
                'return'            => $atts['return'],
                'submit_value'      => 'Indicar'
            );
            acf_form($settings);

            return ob_get_clean();

        }

        /**
         * Create a custom post type to manage indications
         *
         */
        public function indicacao_cpt()
        {
            register_post_type('indicacao', array(
                'labels' => array(
                    'name' => 'Indicações',
                    'singular_name' => 'Indicação',
                ),
                'description' => 'Indicações OMC',
                'public' => true,
                'supports' => array('title'),
                'menu_icon' => 'dashicons-clipboard')
            );
        }

        /**
         * Process data before save indication post
         *
         * @param $post_id
         * @return int|void|WP_Error
         */
        public function preprocess_main_form ($post_id)
        {
            if ($post_id != 'new_indicacao') {
                return $post_id;
            }

            if (is_admin ()) {
                return;
            }

            $post = get_post ($post_id);
            $post = array('post_type' => 'indicacao', 'post_status' => 'publish');
            $post_id = wp_insert_post ($post);

            $inscricao = array('ID' => $post_id, 'post_title' => 'Indicação - (ID #' . $post_id . ')');
            wp_update_post ($inscricao);

            // Return the new ID
            return $post_id;
        }

        public function register_omc_styles ()
        {
            wp_register_style( 'omc-styles', plugin_dir_url( __FILE__ ) . 'assets/omc.css' );
            wp_enqueue_style( 'omc-styles' );
        }

        public function register_omc_scripts ()
        {
            wp_enqueue_script ('xlsx-core', plugin_dir_url( __FILE__ ) . 'assets/xlsx.core.min.js', false, false, true);
            wp_enqueue_script ('FileSaver', plugin_dir_url( __FILE__ ) . 'assets/FileSaver.min.js', false, false, true);
            wp_enqueue_script ('tableexport', plugin_dir_url( __FILE__ ) . 'assets/tableexport.js', false, false, true);
        }

        function omc_admin_scripts() {
            global $current_screen;
            $user = wp_get_current_user();
            $user_role = $user->roles[0];

            if ( $current_screen->id !== 'page' || $user_role === 'administrator' ) {
                // return;
            } ?>
            <script>
                (function($) {
                    $(document).ready(function() {
                        admin.init();
                    });

                    var admin = {
                        init: function() {
                            console.log('FOO');
                            $('div[data-name="pilares"]').find('input[type="checkbox"]').each(function(){
                                if( ! $(this).is(':checked') ){
                                    $(this).parent().parent().addClass('hide-for-print');
                                }
                            })

                            TableExport.prototype.defaultButton = "button button-primary";
                            TableExport.prototype.xlsx = {
                                defaultClass: "xlsx",
                                buttonContent: "Exportar para xlsx",
                                mimeType: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                                fileExtension: ".xlsx"
                            };

                            $("#omc-export-data").tableExport({
                                headers: true,                              // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
                                footers: true,                              // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
                                formats: ['xlsx'],                          // (String[]), filetype(s) for the export, (default: ['xls', 'csv', 'txt'])
                                filename: 'id',                             // (id, String), filename for the downloaded file, (default: 'id')
                                bootstrap: false,                           // (Boolean), style buttons using bootstrap, (default: true)
                                exportButtons: true,                        // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
                                position: 'bottom',                         // (top, bottom), position of the caption element relative to table, (default: 'bottom')
                                ignoreRows: null,                           // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
                                ignoreCols: null,                           // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
                                trimWhitespace: true                        // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
                            });

                            $('table caption button').on('click', function(e){
                                e.preventDefault();
                            })
                        }
                    };
                })(jQuery);
            </script>

        <?php }


    }

    // Initialize our plugin
    $gewp = new OMC();

endif;
