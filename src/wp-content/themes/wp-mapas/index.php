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
				<div class="col-md-6">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/computador-imagem.png">
				</div>
				<div class="col-md-6 text-center">
					<h2>Promova a cultura no seu município com a plataforma livre Mapas Culturais</h2>
					<a href="#main-form-wrapper" class="btn">Solicitar instância</a>
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
						<h2>Formulário de inscrição</h2>
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
							$mapasculturais_options = get_option('mapasculturais_options');
							main_form( $mapasculturais_options['acf_group_id_option'] );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>