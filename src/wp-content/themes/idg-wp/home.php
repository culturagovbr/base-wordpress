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

        <section class="mb-5">
	        <div id="jumbotron-carousel" class="carousel slide" data-ride="carousel">
		        <div class="carousel-inner">
			        <div class="carousel-item active">
				        <img class="d-block w-100" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg.jpeg" alt="First slide">
				        <div class="carousel-caption d-none d-md-block">
					        <a href="#">
					            <h2>Título da notícia #1</h2>
					            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
					        </a>
				        </div>
			        </div>
			        <div class="carousel-item">
				        <img class="d-block w-100" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg.jpeg" alt="Second slide">
				        <div class="carousel-caption d-none d-md-block">
					        <a href="#">
						        <h2>Título da notícia #2</h2>
						        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
					        </a>
				        </div>
			        </div>
			        <div class="carousel-item">
				        <img class="d-block w-100" src="<?php echo get_template_directory_uri(); ?>/assets/img/bg.jpeg" alt="Third slide">
				        <div class="carousel-caption d-none d-md-block">
					        <a href="#">
						        <h2>Título da notícia #3</h2>
						        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
					        </a>
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

        <section class="services mb-5 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
	                    <h2 class="section-title text-center">Serviços</h2>
                    </div>
	                <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card icon-law">
							<h3 class="card-title">Lei Rouanet</h3>
			                <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
			                <a class="card-btn btn" href="#">Acesse</a>
		                </div>
	                </div>
	                <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card icon-edicts">
			                <h3 class="card-title">Editais</h3>
			                <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
			                <a class="card-btn btn" href="#">Acesse</a>
		                </div>
	                </div>
	                <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card icon-rocket">
			                <h3 class="card-title">Apoio à projetos</h3>
			                <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
			                <a class="card-btn btn" href="#">Acesse</a>
		                </div>
	                </div>
	                <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card icon-patrimony">
			                <h3 class="card-title">Patrimônio</h3>
			                <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
			                <a class="card-btn btn" href="#">Acesse</a>
		                </div>
	                </div>
	                <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card icon-culture-center">
			                <h3 class="card-title">Centros culturais</h3>
			                <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
			                <a class="card-btn btn" href="#">Acesse</a>
		                </div>
	                </div>
	                <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card icon-culture-points">
			                <h3 class="card-title">Pontos de cultura</h3>
			                <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
			                <a class="card-btn btn" href="#">Acesse</a>
		                </div>
	                </div>
	                <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card icon-flag">
			                <h3 class="card-title">SNC</h3>
			                <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
			                <a class="card-btn btn" href="#">Acesse</a>
		                </div>
	                </div>
	                <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card icon-creative">
			                <h3 class="card-title">Economia Criativa</h3>
			                <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
			                <a class="card-btn btn" href="#">Acesse</a>
		                </div>
	                </div>
	                <div class="col-lg-12 text-center">
		                <a href="#" class="btn text-uppercase mt-4">Acesse mais serviços</a>
	                </div>
                </div>
            </div>
        </section>

        <section class="mt-5 mb-5">
            <div class="container">
                <div class="row">
	                <div class="col-lg-12">
		                <h2 class="section-title text-center">Notícas</h2>
	                </div>
	                <div class="col-lg-4">
		                <div class="highlight-box">
			                <div class="box-body">
				                <span class="cat">Agosto</span>
				                <h3 class="box-title">
					                <a href="#">Pomerode (SC) recebe projeto de requalificação do patrimônio</a>
				                </h3>
			                </div>
			                <img class="box-image" src="<?php echo get_template_directory_uri(); ?>/assets/img/fake-img.jpg">
		                </div>
	                </div>
	                <div class="col-lg-4">
		                <div class="highlight-box">
			                <div class="box-body">
				                <span class="cat">Agosto</span>
				                <h3 class="box-title">
					                <a href="#">Pomerode (SC) recebe projeto de requalificação do patrimônio</a>
				                </h3>
			                </div>
			                <img class="box-image" src="<?php echo get_template_directory_uri(); ?>/assets/img/fake-img.jpg">
		                </div>
	                </div>
	                <div class="col-lg-4">
		                <div class="highlight-box">
			                <div class="box-body">
				                <span class="cat">Agosto</span>
				                <h3 class="box-title">
					                <a href="#">Pomerode (SC) recebe projeto de requalificação do patrimônio</a>
				                </h3>
			                </div>
			                <img class="box-image" src="<?php echo get_template_directory_uri(); ?>/assets/img/fake-img.jpg">
		                </div>
	                </div>
                </div>
	            <div class="col-lg-12 text-center">
		            <a href="#" class="btn text-uppercase mt-5">Mais notícias</a>
	            </div>
            </div>
        </section>

        <section style="height: 480px; background-color: #18214a; padding: 45px 0;">
            <div class="container">
                <div class="row">
	                <div class="col-lg-12">
		                <h2 class="section-title text-center text-uppercase" style="color: #fff; font-size: 24px">Agenda Cultural</h2>
	                </div>
                </div>
            </div>
        </section>

        <section class="mb-4" style="background: url(<?php echo get_template_directory_uri() . '/assets/img/blue-circles.png'; ?>) #D1E6FF no-repeat left bottom; padding: 30px 0">
            <div class="container">
	            <div class="row">
		            <div class="col-lg-12">
			            <h2 class="section-title text-center" style="color: #2D5A92">Conheça o ministério</h2>
		            </div>
		            <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
			            <div class="feature-card card-2 icon-minister">
				            <h3 class="card-title"><a href="#">O Ministro</a></h3>
			            </div>
		            </div>
		            <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
			            <div class="feature-card card-2 icon-ministry">
				            <h3 class="card-title"><a href="#">O Ministério</a></h3>
			            </div>
		            </div>
		            <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
			            <div class="feature-card card-2 icon-secretaries">
				            <h3 class="card-title"><a href="#">Secretarias</a></h3>
			            </div>
		            </div>
		            <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
			            <div class="feature-card card-2 icon-linkeds">
				            <h3 class="card-title"><a href="#">Vinculadas</a></h3>
			            </div>
		            </div>
		            <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
			            <div class="feature-card card-2 icon-internacional">
				            <h3 class="card-title"><a href="#">Internacional</a></h3>
			            </div>
		            </div>
		            <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
			            <div class="feature-card card-2 icon-actions">
				            <h3 class="card-title"><a href="#">Ações programas</a></h3>
			            </div>
		            </div>
		            <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
			            <div class="feature-card card-2 icon-legislation">
				            <h3 class="card-title"><a href="#">Legislação</a></h3>
			            </div>
		            </div>
		            <div class="col-sm-12 col-md-6 col-lg-3 mb-4 text-center">
			            <div class="feature-card card-2 icon-officers">
				            <h3 class="card-title"><a href="#">Escritórios regionais</a></h3>
			            </div>
		            </div>
	            </div>
            </div>
        </section>

        <section class="mt-5 mb-5">
            <div class="container">
                <div class="row">
	                <div class="col-lg-12">
		                <h2 class="section-title text-center">Conteúdos</h2>
	                </div>
	                <div class="col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card card-2 icon-advisory">
			                <h3 class="card-title"><a href="#">Assessoria</a></h3>
		                </div>
	                </div>
	                <div class="col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card card-2 icon-news">
			                <h3 class="card-title"><a href="#">Notícias</a></h3>
		                </div>
	                </div>
	                <div class="col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card card-2 icon-publications">
			                <h3 class="card-title"><a href="#">Publicações</a></h3>
		                </div>
	                </div>
	                <div class="col-md-6 col-lg-3 mb-4 text-center">
		                <div class="feature-card card-2 icon-multimedia">
			                <h3 class="card-title"><a href="#">Multimídia</a></h3>
		                </div>
	                </div>
                </div>
            </div>
        </section>

	    <section class="mt-5 mb-5">
		    <div class="container">
			    <div class="row">
				    <div class="col-lg-12">
					    <h2 class="section-title text-center">Participação Social</h2>
				    </div>
				    <div class="col-lg-3 mb-4 text-center">
					    <div class="feature-card icon-talk-to-us">
						    <h3 class="card-title">Fale conosco</h3>
						    <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
						    <a class="card-btn btn" href="#">Acesse</a>
					    </div>
				    </div>
				    <div class="col-lg-3 mb-4 text-center">
					    <div class="feature-card icon-consultations">
						    <h3 class="card-title">Consultas públicas</h3>
						    <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
						    <a class="card-btn btn" href="#">Acesse</a>
					    </div>
				    </div>
				    <div class="col-lg-3 mb-4 text-center">
					    <div class="feature-card icon-ombudsman">
						    <h3 class="card-title">Ouvidoria</h3>
						    <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
						    <a class="card-btn btn" href="#">Acesse</a>
					    </div>
				    </div>
				    <div class="col-lg-3 mb-4 text-center">
					    <div class="feature-card icon-data">
						    <h3 class="card-title">Dados.br</h3>
						    <p class="card-desc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr</p>
						    <a class="card-btn btn" href="#">Acesse</a>
					    </div>
				    </div>
			    </div>
		    </div>
	    </section>

    </main>

<?php
get_footer();
