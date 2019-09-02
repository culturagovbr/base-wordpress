<?php
if( is_front_page() ){
	acf_form_head();
}
get_header(); 
?>
<main>
	<div id="main-jumbotron" class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6 text-center">
					<h2>Inclusão de novas ações para alcançar resultados estratégicos</h2>
					<a href="#main-form-wrapper" class="btn">Incluir nova ação</a>
				</div>
			</div>
		</div>
	</div>
	<div id="content-wrap" class="container">
		<div class="row">
			<aside class="col-sm-3"></aside>
			<div class="col-sm-8">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
								<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
									<div class="entry-thumbnail">
										<?php the_post_thumbnail(); ?>
									</div>
								<?php endif; ?>
								<h1 class="entry-title"><?php the_title(); ?></h1>
							</header>

							<div class="entry-content">
								<?php
								the_content( sprintf(
									__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'inscricao-oscar' ),
									the_title( '<span class="screen-reader-text">', '</span>', false )
									) );
								?>
							</div>
						</article>

					<?php endwhile; ?>
				<?php else: ?>
					<h1>Erro 404</h1>
					<p>Nada encontrado.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div id="main-form-wrapper">
		<div class="main-form-title">
			<div class="container">
				<div class="row">
					<div class="col-sm-3"></div>
					<div class="col-sm-9">
						<h2>Descreva a ação estratégica</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-sm-3 aside"></div>
				<div class="col-sm-9 margin-left-25">
					<div class="row">
						<?php
							$diretrizesemetas_options = get_option('diretrizesemetas_options');
							main_form( $diretrizesemetas_options['acf_group_id_option'] );
						?>
					</div>
				</div>
			</div>
		</div>
		<div id="modal-inscricao" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<h3>Sua ação foi incluída com sucesso!</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>