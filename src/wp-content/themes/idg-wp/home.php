<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Identidade_Digital_do_Governo_-_WordPress
 */

get_header();
?>

		<main id="main" class="site-main">

				<section class="carousel-wrapper">
					<div id="jumbotron-carousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="5000">
						<div class="carousel-inner">
							<div class="carousel-item active">
								<img class="d-block w-100" src="<?php echo get_template_directory_uri(); ?>/assets/img/teste-patrimonio.jpg" alt="First slide">
								<div class="carousel-caption d-none d-md-block">
									<div class="container">
										<a href="#">
											<h2>Título da notícia #1</h2>
											<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
										</a>
									</div>
								</div>
							</div>
							<div class="carousel-item">
								<img class="d-block w-100" src="<?php echo get_template_directory_uri(); ?>/assets/img/teste-personagem.jpg" alt="Second slide">
								<div class="carousel-caption d-none d-md-block">
									<div class="container">
										<a href="#">
											<h2>Título da notícia #2</h2>
											<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
										</a>
									</div>
								</div>
							</div>
							<div class="carousel-item">
								<img class="d-block w-100" src="<?php echo get_template_directory_uri(); ?>/assets/img/teste-reuniao.jpg" alt="Third slide">
								<div class="carousel-caption d-none d-md-block">
									<div class="container">
										<a href="#">
											<h2>Título da notícia #3</h2>
											<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
										</a>
									</div>
								</div>
							</div>
						</div>
						<a class="carousel-control-prev" href="#jumbotron-carousel" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#jumbotron-carousel" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
						<ol class="carousel-indicators">
							<li data-target="#jumbotron-carousel" data-slide-to="0" class="active"></li>
							<li data-target="#jumbotron-carousel" data-slide-to="1"></li>
							<li data-target="#jumbotron-carousel" data-slide-to="2"></li>
						</ol>
					</div>
				</section>

				<section class="services mt-5 mb-5">
						<div class="container">
								<div class="row">
										<div class="col-lg-12">
											<h2 class="section-title mb-5 text-center">Serviços</h2>
										</div>
									<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
										<div class="feature-card static-height">
											<div class="align">
												<div class="icon icon-lei-rouanet"></div>
												<h3 class="card-title">Lei Rouanet</h3>
											</div>
<!-- 											<p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
											<a class="card-btn btn" href="#">Acesse</a> -->
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
										<div class="feature-card static-height">
											<div class="align">
												<div class="icon icon-editais"></div>
												<h3 class="card-title">Editais</h3>
											</div>
<!-- 											<p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
											<a class="card-btn btn" href="#">Acesse</a> -->
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
										<div class="feature-card static-height">
											<div class="align">
												<div class="icon icon-apoio"></div>
												<h3 class="card-title">Apoio à projetos</h3>
											</div>
<!-- 											<p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
											<a class="card-btn btn" href="#">Acesse</a> -->
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
										<div class="feature-card static-height">
											<div class="align">
												<div class="icon icon-patrimonio"></div>
												<h3 class="card-title">Patrimônio</h3>
											</div>
<!-- 											<p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
											<a class="card-btn btn" href="#">Acesse</a> -->
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
										<div class="feature-card static-height">
											<div class="align">
												<div class="icon icon-centros-culturais"></div>
												<h3 class="card-title">Centros culturais</h3>
											</div>
<!-- 											<p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
											<a class="card-btn btn" href="#">Acesse</a> -->
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
										<div class="feature-card static-height">
											<div class="align">
												<div class="icon icon-pontos-cultura"></div>
												<h3 class="card-title">Pontos de cultura</h3>
											</div>
<!-- 											<p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
											<a class="card-btn btn" href="#">Acesse</a> -->
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
										<div class="feature-card static-height">
											<div class="align">
												<div class="icon icon-snc"></div>
												<h3 class="card-title">SNC</h3>
											</div>
<!-- 											<p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
											<a class="card-btn btn" href="#">Acesse</a> -->
										</div>
									</div>
									<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
										<div class="feature-card static-height">
											<div class="align">
												<div class="icon icon-economia-criativa"></div>
												<h3 class="card-title">Economia Criativa</h3>
											</div>
<!-- 											<p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
											<a class="card-btn btn" href="#">Acesse</a> -->
										</div>
									</div>
									<div class="col-lg-12 text-center">
										<a href="#" class="btn text-uppercase mt-4">Acesse mais serviços</a>
									</div>
								</div>
						</div>
				</section>
				<section class="pb-5 pt-5" style="background-color: #EEE;">
						<div class="container">
								<div class="row">
									<div class="col-lg-12">
										<h2 class="section-title mb-5 text-center">Notícias</h2>
									</div>
									<div class="col-lg-4">
										<div class="highlight-box" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/fake-img.jpg')">
											<div class="box-body">
												<span class="cat">Agosto</span>
												<h3 class="box-title">
													<a href="#">Pomerode (SC) recebe projeto de requalificação do patrimônio</a>
												</h3>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="highlight-box" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/fake-img.jpg')">
											<div class="box-body">
												<span class="cat">Agosto</span>
												<h3 class="box-title">
													<a href="#">Pomerode (SC) recebe projeto de requalificação do patrimônio</a>
												</h3>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="highlight-box" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/fake-img.jpg')">
											<div class="box-body">
												<span class="cat">Agosto</span>
												<h3 class="box-title">
													<a href="#">Pomerode (SC) recebe projeto de requalificação do patrimônio</a>
												</h3>
											</div>
										</div>
									</div>
								</div>
							<div class="col-lg-12 text-center">
								<a href="#" class="btn text-uppercase mt-5">Mais notícias</a>
							</div>
						</div>
				</section>

				<section class="pt-5 pb-5 mb-5" style="background-color: #19224d;">
					<div class="container">
						<div class="row">
							<div class="col-lg-12">
								<?php echo do_shortcode('[gs-agenda]'); ?>
							</div>
						</div>
					</div>
				</section>

				<section class="pt-5 pb-5 mb-5">
						<div class="container">
							<div class="row">
								<div class="col-lg-12">
									<h2 class="section-title mb-5 text-center">Conheça o Ministério</h2>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
									<div class="feature-card static-height card-2">
										<div class="align">
											<div class="icon icon-ministro"></div>
											<h3 class="card-title"><a href="#">O Ministro</a></h3>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
									<div class="feature-card static-height card-2">
										<div class="align">
											<div class="icon icon-ministerio"></div>
											<h3 class="card-title"><a href="#">O Ministério</a></h3>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 text-center mb-4">
									<div class="feature-card static-height card-2">
										<div class="align">
											<div class="icon icon-secretarias"></div>
											<h3 class="card-title"><a href="#">Secretarias</a></h3>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 text-center">
									<div class="feature-card static-height card-2">
										<div class="align">
											<div class="icon icon-vinculadas"></div>
											<h3 class="card-title"><a href="#">Entidades Vinculadas</a></h3>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 text-center">
									<div class="feature-card static-height card-2">
										<div class="align">
											<div class="icon icon-internacional"></div>
											<h3 class="card-title"><a href="#">Internacional</a></h3>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 text-center">
									<div class="feature-card static-height card-2">
										<div class="align">
											<div class="icon icon-acoes-programadas"></div>
											<h3 class="card-title"><a href="#">Ações e Programas</a></h3>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 text-center">
									<div class="feature-card static-height card-2">
										<div class="align">
											<div class="icon icon-legislacao"></div>
											<h3 class="card-title"><a href="#">Legislação</a></h3>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-3 text-center">
									<div class="feature-card static-height card-2">
										<div class="align">
											<div class="icon icon-escritorios-regionais"></div>
											<h3 class="card-title"><a href="#">Escritórios Regionais</a></h3>
										</div>
									</div>
								</div>
							</div>
						</div>
				</section>

				<section class="pt-5 pb-5" id="section-content">
						<div class="container">
								<div class="row">
									<div class="col-lg-12">
										<h2 class="section-title mb-5 text-center">Conteúdos</h2>
									</div>
									<div class="col-md-6 col-lg-3 text-center">
										<div class="feature-card static-height active card-2">
											<div class="align">
												<div class="icon icon-assessoria"></div>
												<h3 class="card-title"><a href="#">Imprensa</a></h3>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-lg-3 text-center">
										<div class="feature-card static-height active card-2">
											<div class="align">
												<div class="icon icon-noticias"></div>
												<h3 class="card-title"><a href="#">Notícias</a></h3>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-lg-3 text-center">
										<div class="feature-card static-height active card-2">
											<div class="align">
												<div class="icon icon-publicacoes"></div>
												<h3 class="card-title"><a href="#">Publicações</a></h3>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-lg-3 text-center">
										<div class="feature-card static-height active card-2">
											<div class="align">
												<div class="icon icon-multimidia"></div>
												<h3 class="card-title"><a href="#">Multimídia</a></h3>
											</div>
										</div>
									</div>
								</div>
						</div>
				</section>

			<section class="mt-5 mb-5">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<h2 class="section-title mb-5 text-center">Participação Social</h2>
						</div>
						<div class="col-lg-3 mb-4 text-center">
							<div class="feature-card">
								<div class="align">
									<div class="icon icon-fale-conosco"></div>
									<h3 class="card-title">Fale conosco</h3>
									<p class="card-desc">Mande sua dúvida ou sugestão sobre o site do Ministério da Cultura</p>
									<a class="card-btn btn" href="#">Acesse</a>
								</div>
							</div>
						</div>
						<div class="col-lg-3 mb-4 text-center">
							<div class="feature-card">
								<div class="align">
									<div class="icon icon-consultas-publicas"></div>
									<h3 class="card-title">Consultas públicas</h3>
									<p class="card-desc">Espaço de construção conjunta de políticas públicas entre governo e sociedade</p>
									<a class="card-btn btn" href="#">Acesse</a>
								</div>
							</div>
						</div>
						<div class="col-lg-3 mb-4 text-center">
							<div class="feature-card">
								<div class="align">
									<div class="icon icon-ouvidoria"></div>
									<h3 class="card-title">Ouvidoria</h3>
									<p class="card-desc">Canal para envio de sugestões, elogios, solicitações e denúncias para o ministério</p>
									<a class="card-btn btn" href="#">Acesse</a>
								</div>
							</div>
						</div>
						<div class="col-lg-3 mb-4 text-center">
							<div class="feature-card">
								<div class="align">
									<div class="icon icon-dados-br"></div>
									<h3 class="card-title">Dados.br</h3>
									<p class="card-desc">Ferramenta para obtenção de dados e as informações públicas do ministério</p>
									<a class="card-btn btn" href="#">Acesse</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<section class="mt-5" id="multimidia">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<h2 class="section-title mb-5 text-center">Multimídia</h2>
						</div>

						<div class="video" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/video-thumb.png');"></div>

						<div class="col-lg-4">
							<div class="highlight-box" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/default.png')"></div>
						</div>
						<div class="col-lg-4">
							<div class="highlight-box" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/default.png')"></div>
						</div>
						<div class="col-lg-4">
							<div class="highlight-box" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/default.png')"></div>
						</div>
					</div>
				</div>
			</section>

		</main>

<?php
get_footer();
