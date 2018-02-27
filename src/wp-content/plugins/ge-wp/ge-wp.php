<?php
/**
 * Plugin Name:       Gestão Estratégica - WP
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

if( ! class_exists('GestaoEstrategicaWP') ) :

	class GestaoEstrategicaWP{

		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'register_gewp_styles' ) );
			// add_action( 'wp_enqueue_scripts', array( $this, 'register_gewp_scripts' ) );
			add_shortcode( 'gestao-estrategica-acoes', array( $this, 'gewp_shortcodes' ) );

		}

		// Register our public styles
		public function register_gewp_styles() {
			wp_register_style( 'gewp-styles', plugins_url( 'ge-wp/assets/gewp-styles.css' ) );
			wp_enqueue_style( 'gewp-styles' );
		}

		// Register our public scripts
		public function register_gewp_scripts() {

			wp_register_script( 'gewp-scripts', plugins_url( 'ge-wp/assets/gewp-scripts.js' ) );
			wp_enqueue_script( 'gewp-scripts' );

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

		public function gewp_shortcodes() {
			ob_start();

			$db_config = include plugin_dir_path( __FILE__ ) . 'inc/db-config.php';
			if( !@$db_config ){
			    echo 'Ops...houve um erro durante o carregamento dos dados de configuração com o banco de dados.';
			    return;
            }
			$conn_str  = 'host='. $db_config['host'] .' ';
			$conn_str .= 'port='. $db_config['port'] .' ';
			$conn_str .= 'dbname='. $db_config['dbname'] .' ';
			$conn_str .= 'user='. $db_config['user'] .' ';
			$conn_str .= 'password='. $db_config['password'] .'';

			$conn = pg_connect($conn_str);
			$sql = $db_config['query'];

			$result = pg_query($conn, $sql);
			$raw_data = pg_fetch_all($result);
			$raw_data = $raw_data ? $raw_data : [];

			$ge_data = [];
			foreach($raw_data as $dado){
				$ge_data[$dado['nome_eixo']][] = $dado;
			}

			function render_cards_by_axis ( $axis, $name ) {
                $icon = '';
				switch ($name) {
					case 'Gestão':
						$icon = 'strategy.png';
						break;
					case 'Formulação':
						$icon = 'brainstorming.png';
						break;
					case 'Realização':
						$icon = 'achievement.png';
						break;
				}

				foreach ( $axis as $i => $data ): ?>
                    <div id="card-<?php echo $i; ?>" class="ge-card">
                        <a href="#<?php echo $data['id_acao']; ?>">
                            <div class="card-header">
									<span class="headline">
										<span class="img">
											<img src="<?php echo plugins_url( 'ge-wp/assets/' . $icon ); ?>">
										</span>
										<?php echo ( !empty( $data['nome_fonte_recurso'] ) ) ? $data['nome_fonte_recurso'] : ''; ?>
                                    </span>
                                <?php echo ( !empty( $data['orcamento'] ) ) ? '<span class="meta">R$ '. number_format($data['orcamento'], 2, ',', '.') .'</span>' : ''; ?>
                            </div>
                            <div class="card-desc">
                                <div class="text">
                                    <h4><?php echo $data['nome_acao']; ?></h4>
                                    <p><b>Objetivo:</b> <?php echo $data['nome_objetivo']; ?></p>
                                </div>
                                <div class="card-media">
                                    <span><?php echo $data['nome_secretaria']; ?></span>
                                </div>
                            </div>
                            <div class="card-actions">
                                <?php if ( !empty( $data['data'] ) ): ?>
                                <ul>
                                    <li><b>Início:</b> 03/18</li>
                                    <li><b>Fim:</b> 03/19</li>
                                </ul>
						        <?php endif; ?>
                            </div>
                        </a>
                    </div>
				<?php endforeach;
            } ?>



			<div id="acoes-estrategicas" class="row">

				<div class="col-md-4">
					<div class="acao">
						<h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/strategy.png' ); ?>">Gestão</h3>
                        <div class="card-wrapper">
	                        <?php render_cards_by_axis( $ge_data['Gestão'], 'Gestão' ); ?>
                        </div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="acao">
						<h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/brainstorming.png' ); ?>">Formulação</h3>
                        <div class="card-wrapper">
						    <?php render_cards_by_axis( $ge_data['Formulação'], 'Formulação' ); ?>
                        </div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="acao">
						<h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/achievement.png' ); ?>">Realização</h3>
                        <div class="card-wrapper">
						    <?php render_cards_by_axis( $ge_data['Realização'], 'Realização' ); ?>
                        </div>
					</div>
				</div>

			</div>


			<?php return ob_get_clean();

		}

	}

	// Initialize our plugin
	$gewp = new GestaoEstrategicaWP();

endif;