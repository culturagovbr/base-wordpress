<?php
/**
 * Plugin Name:       e-Praças API
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       Widget de integração com a api do <a href="https://epracas.cultura.gov.br/" target="_blank">e-Praças</a> para busca de informações sobre as diferentes praças. Utilização: [e-pracas-api], Atributos opcionais: title, image-url, desc, input-text, btn-text.
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/culturagovbr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if( ! class_exists('EPracasAPI') ) :

    class EPracasAPI {

        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'register_epracas_styles' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'register_epracas_scripts' ) );
            add_shortcode( 'e-pracas-api', array( $this, 'epracas_shortcode' ) );

        }

        // Register our public styles
        public function register_epracas_styles() {
            wp_register_style( 'epracas-styles', plugin_dir_url( __FILE__ ) . 'assets/e-pracas-api.css' );
            wp_enqueue_style( 'epracas-styles' );
        }

        // Register our public scripts
        public function register_epracas_scripts() {
            wp_register_script( 'epracas-scripts', plugin_dir_url( __FILE__ ) . 'assets/e-pracas-api.js' );
            wp_enqueue_script( 'epracas-scripts' );
        }

        // Shortcode view
        public function epracas_shortcode( $atts ) {
            $atts = shortcode_atts( array(
                'title'         => 'e-Praças',
                'image-url'     => false,
                'desc'          => '<p>Confira o <a href="http://mapas.cultura.gov.br/busca/##(global:(enabled:(space:!t),filterEntity:space),space:(filters:(type:!(\'132\'))))" target="_blank" rel="noopener">estágio das obras</a> e a <a href="https://epracas.cultura.gov.br/eventos" target="_blank" class="programacao" rel="noopener">Programação das Praças</a></p>',
                'input-text'    => 'Procure por um estado ou cidade',
                'btn-text'      => 'Buscar'
            ), $atts );

            ob_start(); ?>

            <div class="modulo-epracas">
                <h3 class="text-center"><?php echo $atts['title']; ?></h3>
                <?php if( $atts['image-url'] ): ?>
                    <img class="imagem-pracas" src="<?php echo $atts['image-url']; ?>" alt="Desenho do e-pracas" />
                <?php endif; ?>
                <p><?php echo $atts['desc']; ?></p>
                <div class="busca-epracas">
                    <input id="busca-epracas" class="form-control" type="search" placeholder="<?php echo $atts['input-text']; ?>" />
                    <button class="icone icone-busca"><i class="fa fa-search" aria-hidden="true"></i><i class="fa fa-close hidden" aria-hidden="true"></i></button>
                    <button class="btn-busca-epracas" title="Buscar"><?php echo $atts['btn-text']; ?></button>
                </div>
            </div>

            <?php return ob_get_clean();

        }

    }

    // Initialize our plugin
    $epracas = new EPracasAPI();

endif; // For class_exists