<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Portal_Padrão_WP
 */

get_header(); ?>

    <main id="main" class="site-main">
            <div class="container">

                <div class="row">
					<?php the_breadcrumb (); ?>
                </div>

                <div class="row">
                    <div class="col-lg-12">

						<?php
						while ( have_posts() ) : the_post(); ?>

                            <article id="post-<?php the_ID (); ?>" <?php post_class ('acao-estrategica'); ?>>

                                <header class="entry-header">
									<?php
									if (is_singular ()) :
										the_title ('<h1 class="entry-title">', '</h1>');
									else :
										the_title ('<h2 class="entry-title"><a href="' . esc_url (get_permalink ()) . '" rel="bookmark">', '</a></h2>');
									endif;

									if ('post' === get_post_type ()) : ?>
                                        <div class="entry-meta">
											<?php pp_wp_posted_on (); ?>
                                        </div>
									<?php
									endif; ?>
                                </header>

                                <div class="entry-content">

                                <?php if( empty( $_GET['acao'] ) ): ?>
                                    <h3 class="action-name">Selecione uma ação</h3>
                                <?php else:

	                                $db_config = include plugin_dir_path( __FILE__ ) . 'db-config.php';
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
	                                $sql = $db_config['query-single'] . $_GET['acao'];

	                                $result = pg_query($conn, $sql);
	                                $acao_data = pg_fetch_all($result);

	                                $sql = $db_config['query-subacoes'] . $_GET['acao'];

	                                $result = pg_query($conn, $sql);
	                                $subacoes_data = pg_fetch_all($result);

	                                $icon = '';
	                                switch ($acao_data[0]['nome_eixo']) {
		                                case 'Gestão':
			                                $icon = 'strategy.png';
			                                break;
		                                case 'Formulação':
			                                $icon = 'brainstorming.png';
			                                break;
		                                case 'Realização':
			                                $icon = 'achievement.png';
			                                break;
	                                } ?>

                                    <h3 class="action-name"><?php echo $acao_data[0]['nome_acao']; ?></h3>

                                    <div class="row action-meta">
                                        <div class="col-md-8">
                                            <h4>
                                                <span class="img">
                                                    <img src="<?php echo plugins_url( 'ge-wp/assets/' . $icon ); ?>">
                                                </span>
                                                <span class="text">
                                                    <?php echo $acao_data[0]['nome_eixo']; ?>
                                                </span>
								                <?php if ( !empty( $acao_data[0]['data'] ) ): ?>
                                                <span class="when">
                                                    <b>Início:</b> 02/03/2018 <b>Fim:</b>02/03/2019
                                                </span>
                                                <?php endif; ?>
                                            </h4>
                                        </div>
                                        <div class="col-md-4 text-right">
                                        <?php if ( !empty( $acao_data[0]['orcamento'] ) ): ?>
                                            <h5>R$ <?php echo number_format($acao_data[0]['orcamento'], 2, ',', '.'); ?></h5>
                                            <p>Orçamento</p>
                                        <?php endif; ?>
                                    </div>
                                    </div>

                                    <div class="row action-data">
                                        <div class="col-md-9">
                                            <p><b>Objetivo Estratégico:</b><br>
                                            <?php echo $acao_data[0]['nome_objetivo']; ?></p>

                                            <p><b>Fonte de recurso:</b><br>
                                            <?php echo $acao_data[0]['nome_fonte_recurso']; ?></p>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <div class="card-media">
	                                            <?php echo $acao_data[0]['nome_secretaria']; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <p><b>Descritivo:</b><br>
                                            <?php echo $acao_data[0]['descricao_acao']; ?></p>
                                        </div>
                                        <div class="col-md-12">
                                            <p><b>Produto:</b><br>
                                            <?php echo $acao_data[0]['acaproduto']; ?></p>
                                        </div>
                                        <div class="col-md-12">
                                            <p><b>Data de entrega da ação:</b><br>
						<?php echo $acao_data[0]['mesdsc']; ?> / <?php echo date('Y'); ?></p>
                                        </div>

                                    </div>

	                                <?php endif; ?>

                                    <?php if( !empty( $subacoes_data[0]['id_subacao'] ) ) :  ?>
                                    <h3 class="action-name subacao-title">Subações</h3>

                                    <div id="acoes-estrategicas" class="subacoes row">
                                        <?php foreach ( $subacoes_data as $subacao ) : ?>
                                        <div class="col-lg-4 col-md-6 col-sm-12 grid-item">
                                            <div id="card-<?php echo $subacao['id_subacao']; ?>" class="ge-card">
                                                <div class="card-desc">
                                                    <div class="text">
                                                        <h4><?php echo $subacao['nome_subacao']; ?></h4>
                                                        <span class="when">
                                                            <b>Início:</b> <?php $t = strtotime($subacao['data_inicio_subacao']); echo date('d/m/y',$t); ?> -
                                                            <b>Fim:</b> <?php $t = strtotime($subacao['data_fim_subacao']); echo date('d/m/y',$t); ?>
                                                        </span>
                                                        <p><?php echo $subacao['texto_subacao']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <?php endif; ?>
                                </div>

                                <footer class="entry-footer">
									<?php pp_wp_entry_footer (); ?>
                                </footer>
                            </article>

						<?php endwhile; ?>

                    </div>
                </div>
            </div>

		</main>

<?php
get_footer();
