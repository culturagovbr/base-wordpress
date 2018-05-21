<?php
/**
 * Plugin Name:       Editais Culturais - WP
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       @TODO
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

if( ! class_exists('EditaisCulturaisWP') ) :

	class EditaisCulturaisWP {

		// private static $api_url_editais_abertos = 'http://mapas.cultura.gov.br/api/opportunity/find/?&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl,num_sniic&@files=(avatar.avatarMedium):url&@order=name%20ASC&@limit=6&@page=';
		private static $api_url_todos_os_editais = 'http://mapas.cultura.gov.br/api/opportunity/find/?&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl,num_sniic&@files=(avatar.avatarMedium):url&@order=name%20ASC&@limit=6&@page=';
		private static $api_url_editais_abertos = 'http://mapas.cultura.gov.br/api/opportunity/find/?&registrationFrom=LTE(2018-04-02%2011:49)&registrationTo=GTE(2018-04-02%2011:49)&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl,num_sniic&@files=(avatar.avatarMedium):url&@order=name%20ASC&@limit=6&@page=';

		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'register_ecwp_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_ecwp_scripts' ) );

			add_shortcode( 'editais-culturais', array( $this, 'ecwp_shortcodes' ) );

		}

		// Register our public styles
		public function register_ecwp_styles() {
			wp_register_style( 'ecwp-styles', plugins_url( 'editais-culturais/assets/ecwp-styles.css' ) );
			wp_enqueue_style( 'ecwp-styles' );
		}

		// Register our public scripts
		public function register_ecwp_scripts() {

			wp_register_script( 'masonry', plugins_url( 'editais-culturais/assets/masonry.pkgd.min.js' ) );
			wp_enqueue_script( 'masonry' );

			wp_register_script( 'scroll-magic', plugins_url( 'editais-culturais/assets/ScrollMagic.min.js' ) );
			wp_enqueue_script( 'scroll-magic' );

			wp_register_script( 'ecwp-scripts', plugins_url( 'editais-culturais/assets/ecwp-scripts.js' ) );
			wp_enqueue_script( 'ecwp-scripts' );

			$ecwp = array(
				'api_url_todos_os_editais' => self::$api_url_todos_os_editais,
				'api_url_editais_abertos' => self::$api_url_editais_abertos
			);

			wp_localize_script( 'ecwp-scripts', 'ecwp', $ecwp );

		}

		// check the current post for the existence of a short code
		public function has_shortcode($shortcode = '') {
			$post_to_check = get_post(get_the_ID());
			// false because we have to search through the post content first
			$found = false;
			// if no short code was provided, return false
			if (!$shortcode) {
				return $found;
			}
			// check the post content for the short code
			if ( stripos($post_to_check->post_content, '[' . $shortcode) !== false ) {
				// we have found the short code
				$found = true;
			}

			// return our final results
			return $found;
		}

		public function limit_text( $text, $limit ) {
			$excerpt = explode(' ', $text, $limit);
			if (count($excerpt)>=$limit) {
				array_pop($excerpt);
				$excerpt = implode(" ",$excerpt).'...';
			} else {
				$excerpt = implode(" ",$excerpt);
			}
			$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
			return $excerpt;
		}

		public function ecwp_shortcodes() {
			$json = file_get_contents( self::$api_url_editais_abertos . '1' );
			$editais_arr = json_decode($json);

			ob_start(); ?>
            <div class="editais-abertos-wrapper">
                <input type="checkbox" id="editais-abertos-filter" checked>
                <label for="editais-abertos-filter">Inscrições Abertas</label>
            </div>

            <div id="editais-culturais" class="editais-culturais-abertos" data-page="1">
			<?php foreach ( $editais_arr as $i => $edital ){ ?>

				<div class="card-wrapper">
					<div id="card-<?php echo $edital->id; ?>" class="edital-card">
                        <a href="<?php echo $edital->singleUrl; ?>" target="_blank">
                            <div class="card-header">
                                <div class="card-header-text">
                                    <span class="headline"><?php echo ucfirst( strtolower( $edital->name ) ); ?></span>
                                    <span class="subhead"><?php echo ucfirst( strtolower( $this->limit_text($edital->shortDescription, 15 ) ) ); ?></span>
                                </div>
                                <div class="card-header-media">
                                    <img src="<?php echo $edital->{"@files:avatar.avatarMedium"}->url; ?>">
                                </div>
                            </div>
                            <div class="card-actions">
                                <!--<span>Tags:</span>-->
                                <?php if( !empty($edital->terms->tag) ) : ?>
                                <ul>
                                    <?php foreach( $edital->terms->tag as $tag ) : ?>
                                        <li><?php echo $tag; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </a>
					</div>
				</div>

			<?php } ?>
            </div>

            <div id="loader">
                <img src="http://scrollmagic.io/img/example_loading.gif">
                Carregando...
            </div>


			<?php return ob_get_clean();

		}

	}

	// Initialize our plugin
	$ecwp = new EditaisCulturaisWP();

endif; // For class_exists