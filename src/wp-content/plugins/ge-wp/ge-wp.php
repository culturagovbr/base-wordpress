<?php
/**
 * Plugin Name:       Gestão Estratégica - WP
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       Plugin de integração do Portal estratégico do MinC com o SIMINC - Sistema de Informações do Ministério da Cultura
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
			add_action( 'wp_enqueue_scripts', array( $this, 'register_gewp_scripts' ) );
			add_shortcode( 'gestao-estrategica-acoes', array( $this, 'gewp_shortcodes_acoes' ) );
			add_shortcode( 'gestao-estrategica-objetivos', array( $this, 'gewp_shortcodes_objetivos' ) );
			add_action( 'init', array( $this, 'cpt_acao_estrategica' ) );
			add_filter( 'single_template', array( $this, 'cpt_acao_estrategica_template' ) );
			add_action( 'init', array( $this, 'create_acao_estrategica_model' ) );
		}

		// Register our public styles
		public function register_gewp_styles() {
			wp_register_style( 'gewp-styles', plugins_url( 'ge-wp/assets/gewp-styles.css' ) );
			wp_enqueue_style( 'gewp-styles' );
		}

		// Register our public scripts
		public function register_gewp_scripts() {
			wp_register_script( 'gewp-masonry', plugins_url( 'ge-wp/assets/masonry.pkgd.min.js' ) );
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

		public function cpt_acao_estrategica() {
			register_post_type( 'acoes-estrategicas',
				array(
					'labels'              => array(
						'name'          => 'Ações estratégicas',
						'singular_name' => 'Ações estratégicas',
					),
					'public'              => true,
					'exclude_from_search' => true,
					'show_in_nav_menus'   => false,
					'show_ui'             => false,
				)
			);
        }

		public function cpt_acao_estrategica_template($single) {
			global $wp_query, $post;
			if ( $post->post_type == 'acoes-estrategicas' ) {
				if ( file_exists( plugin_dir_path( __FILE__ ) . 'inc/single-acao-estrategica.php' ) ) {
					return plugin_dir_path( __FILE__ ) . 'inc/single-acao-estrategica.php';
				}
			}

			return $single;
        }

		public function create_acao_estrategica_model() {
			$acao_estrategica_model = get_page_by_title('Ação estratégica', OBJECT, 'acoes-estrategicas' );

			if( !$acao_estrategica_model ){
				$acao_estrategica_model_post = array(
					'post_type' => 'acoes-estrategicas',
					'post_title' => 'Ação estratégica',
					'post_status' => 'publish'
				);
				$acao_estrategica_model_post_id = wp_insert_post( $acao_estrategica_model_post, true );
				if(is_wp_error($acao_estrategica_model_post_id)){
					wp_die('Ocorreu um erro durante a geração do post "Ações estratégicas".');
				}
            }
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

		public function gewp_shortcodes_acoes() {
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

			$sql = $db_config['query-objetivos'];

			$result = pg_query($conn, $sql);
			$objectives = pg_fetch_all($result);

			$sql = $db_config['query-diretrizes'];

			$result = pg_query($conn, $sql);
			$diretrizes = pg_fetch_all($result);

			function filters ($objectives, $diretrizes) { ?>

                <div class="filter-wrap col-md-12">
                    <div class="filter">
                        <span>Filtros</span>
                        <div class="dropdown d-inline">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="filter-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Eixos
                            </button>
                            <div class="dropdown-menu" aria-labelledby="filter-1">
                                <h6 class="dropdown-header">Selecione um eixo</h6>
                                <a class="dropdown-item" href="<?php echo home_url('/acoes-estrategicas/?eixo=Gestão'); ?>">Gestão</a>
                                <a class="dropdown-item" href="<?php echo home_url('/acoes-estrategicas/?eixo=Formulação'); ?>">Formulação</a>
                                <a class="dropdown-item" href="<?php echo home_url('/acoes-estrategicas/?eixo=Realização'); ?>">Realização</a>
                            </div>
                        </div>
                        <div class="dropdown d-inline">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="filter-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Diretrizes
                            </button>
                            <div class="dropdown-menu" aria-labelledby="filter-2">
                                <h6 class="dropdown-header">Selecione uma diretriz</h6>
	                            <?php
	                            foreach ( $diretrizes as $diretriz ) { ?>
                                    <a class="dropdown-item" href="<?php echo home_url('/acoes-estrategicas/?diretriz=') . $diretriz['id_diretriz']; ?>"><?php echo $diretriz['nome_diretriz']; ?></a>
	                            <?php } ?>
                            </div>
                        </div>
                        <div class="dropdown d-inline">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="filter-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Objetivos Estratégicos
                            </button>
                            <div class="dropdown-menu" aria-labelledby="filter-3">
                                <h6 class="dropdown-header">Selecione um objetivo</h6>
                                <?php
                                foreach ( $objectives as $objective ) { ?>
                                    <a class="dropdown-item" href="<?php echo home_url('/acoes-estrategicas/?objetivo=') . $objective['id_objetivo']; ?>"><?php echo $objective['nome_objetivo']; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if( !empty( $_GET['eixo'] ) || !empty( $_GET['objetivo'] ) || !empty( $_GET['diretriz'] ) ): ?>
                        <div class="clearfix actives">
                            <?php echo !empty( $_GET['eixo'] ) ? '<a href="'. home_url('/acoes-estrategicas/') .'" class="badge badge-secondary">Eixo: '. $_GET['eixo'] .' <i class="fa fa-close"></i></a>' : ''; ?>

                            <?php
                            $objective = array_search($_GET['objetivo'], array_column($objectives, 'id_objetivo'));
                            echo !empty( $objective ) ? '<a href="'. home_url('/acoes-estrategicas/') .'" class="badge badge-secondary">Objetivo Estratégico: '. $objectives[$objective]['nome_objetivo'] .' <i class="fa fa-close"></i></a>' : ''; ?>

                            <?php
                            $diretriz = array_search($_GET['diretriz'], array_column($diretrizes, 'id_diretriz'));
                            echo !empty( $_GET['diretriz'] ) ? '<a href="'. home_url('/acoes-estrategicas/') .'" class="badge badge-secondary">Diretriz: '. $diretrizes[$diretriz]['nome_diretriz'] .' <i class="fa fa-close"></i></a>' : ''; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php }

			function render_cards_by_axis ( $axis, $name, $card_size = 1 ) {
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

				if( $card_size == 3 ){
				    $card_size = 'col-md-4';
                } else if( $card_size == 2 ){
					$card_size = 'col-md-6';
                } else {
					$card_size = 'col-md-12';
                }

				foreach ( $axis as $i => $data ): ?>
                    <div class="<?php echo $card_size; ?>">
                        <div id="card-<?php echo $i; ?>" class="ge-card">
                            <a href="<?php echo home_url('/acoes-estrategicas/acao-estrategica/?acao=') . $data['id_acao']; ?>">
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
                                        <p><b>Objetivo Estratégico:</b> <?php echo $data['nome_objetivo']; ?></p>
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
                    </div>
				<?php endforeach;
            } ?>



			<div id="acoes-estrategicas" class="row">

                <?php
                    // Nenhum parametro informado
                    if( empty( $_GET['eixo'] ) && empty( $_GET['objetivo'] ) && empty( $_GET['diretriz'] ) ):
                ?>

                <?php echo filters ($objectives, $diretrizes); ?>

				<div class="col-md-4">
					<div class="acao">
                        <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/strategy.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Gestão'); ?>">Gestão</a></h3>
                        <div class="card-wrapper row">
	                        <?php render_cards_by_axis( $ge_data['Gestão'], 'Gestão' ); ?>
                        </div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="acao">
                        <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/brainstorming.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Formulação'); ?>">Formulação</a></h3>
                        <div class="card-wrapper row">
						    <?php render_cards_by_axis( $ge_data['Formulação'], 'Formulação' ); ?>
                        </div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="acao">
                        <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/achievement.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Realização'); ?>">Realização</a></h3>
                        <div class="card-wrapper row">
						    <?php render_cards_by_axis( $ge_data['Realização'], 'Realização' ); ?>
                        </div>
					</div>
				</div>

				<?php
                    // Apenas o eixo foi informado
                    elseif( !empty( $_GET['eixo'] ) && empty( $_GET['objetivo'] ) ):
                ?>

                <?php echo filters ($objectives, $diretrizes); ?>

                <div class="col-md-12">
                    <div class="acao">
                        <?php
                        switch ( $_GET['eixo'] ) {
                            case 'Gestão':
                                $name = 'Gestão';
                                $icon = 'strategy.png';
                                break;
                            case 'Formulação':
                                $name = 'Formulação';
                                $icon = 'brainstorming.png';
                                break;
                            case 'Realização':
                                $name = 'Realização';
                                $icon = 'achievement.png';
                                break;
                        }
                        ?>
                        <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/' . $icon ); ?>"><?php echo $name; ?></h3>
                        <div class="card-wrapper row">
                            <?php render_cards_by_axis( $ge_data[$name], $name, 2 ); ?>
                        </div>
                    </div>
                </div>

                <?php
                    // Apenas a diretriz foi informada
                    elseif( empty( $_GET['eixo'] ) && !empty( $_GET['diretriz'] ) ):
                ?>

                    <?php echo filters ($objectives, $diretrizes); ?>

                    <div class="col-md-12">
		                <?php

		                $sql = $db_config['query-by-diretriz'] . $_GET['diretriz'];
		                $result = pg_query($conn, $sql);
		                $raw_data = pg_fetch_all($result);
		                $raw_data = $raw_data ? $raw_data : [];
		                $ge_data = [];
		                foreach($raw_data as $dado){
			                $ge_data[$dado['nome_eixo']][] = $dado;
		                } ?>
                    </div>
                    <div class="col-md-4">
                        <div class="acao">
                            <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/strategy.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Gestão'); ?>">Gestão</a></h3>
                            <div class="card-wrapper row">
				                <?php render_cards_by_axis( $ge_data['Gestão'], 'Gestão' ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="acao">
                            <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/brainstorming.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Formulação'); ?>">Formulação</a></h3>
                            <div class="card-wrapper row">
				                <?php render_cards_by_axis( $ge_data['Formulação'], 'Formulação' ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="acao">
                            <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/achievement.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Realização'); ?>">Realização</a></h3>
                            <div class="card-wrapper row">
				                <?php render_cards_by_axis( $ge_data['Realização'], 'Realização' ); ?>
                            </div>
                        </div>
                    </div>

				<?php
                    // Filtro por objetivos
                    elseif( empty( $_GET['eixo'] ) && !empty( $_GET['objetivo'] ) ):

	                $sql = $db_config['query-by-objective'] . $_GET['objetivo'];
					$result = pg_query($conn, $sql);
					$raw_data = pg_fetch_all($result);
					$raw_data = $raw_data ? $raw_data : [];
					$ge_data = [];
					foreach($raw_data as $dado){
						$ge_data[$dado['nome_eixo']][] = $dado;
					} ?>

                <?php echo filters ($objectives, $diretrizes); ?>

                <div class="col-md-12">
                    <?php

                    $sql = $db_config['query-get-objective-by-id'] . $_GET['objetivo'];
                    $result = pg_query($conn, $sql);
                    $objective = pg_fetch_all($result); ?>
                </div>
                <div class="col-md-4">
                    <div class="acao">
                        <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/strategy.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Gestão'); ?>">Gestão</a></h3>
                        <div class="card-wrapper row">
                            <?php render_cards_by_axis( $ge_data['Gestão'], 'Gestão' ); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="acao">
                        <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/brainstorming.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Formulação'); ?>">Formulação</a></h3>
                        <div class="card-wrapper row">
                            <?php render_cards_by_axis( $ge_data['Formulação'], 'Formulação' ); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="acao">
                        <h3 class="col-title"><img src="<?php echo plugins_url( 'ge-wp/assets/achievement.png' ); ?>"><a href="<?php echo home_url('/acoes-estrategicas/?eixo=Realização'); ?>">Realização</a></h3>
                        <div class="card-wrapper row">
                            <?php render_cards_by_axis( $ge_data['Realização'], 'Realização' ); ?>
                        </div>
                    </div>
                </div>

			    <?php endif; ?>
			</div>


			<?php return ob_get_clean();

		}

		public function gewp_shortcodes_objetivos () {
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
			$sql = $db_config['query-objetivos'];

			$result = pg_query($conn, $sql);
			$objectives = pg_fetch_all($result);

			echo '<ul class="acoes-estrategicas-objetivos">';
			foreach ( $objectives as $objective ) {
                echo '<li><a href="'. home_url('/acoes-estrategicas/?objetivo=') . $objective['id_objetivo'] .'">'. $objective['nome_objetivo'] .'</a></li>';
			}
			echo '</ul>';

			?>



			<?php return ob_get_clean();
        }

	}

	// Initialize our plugin
	$gewp = new GestaoEstrategicaWP();

endif;