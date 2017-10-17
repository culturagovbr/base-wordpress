<?php
get_header(); 
?>
<main class="container">
	<div class="row">
		<div class="col-md-8">
			<div class="micsul-map">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/mapa.png">
				<?php
				$cur_lang = pll_current_language();
				if( $cur_lang !== 'pt' ){
					$repeater = 'pontos_do_mapa_' . $cur_lang;
				} else {
					$repeater = 'pontos_do_mapa';
				}
				if( have_rows($repeater, 'options') ):
					$i = 1; while( have_rows($repeater, 'options') ): the_row(); ?>

						<div class="micsul-map-item item-<?php echo $i; ?>">
							<a href="#"><?php the_sub_field('titulo'); ?></a>
							<div><?php the_sub_field('texto'); ?></div>
						</div>

					<?php $i++; endwhile;
				endif; ?>
				<div class="mapa-content">
				    <h1> A Cultura conecta os povos e gera bons negócios.</h1>
				</div>
			</div>
		</div>
		<div class="col-md-4 content-area">
			<div class="micsul-logo">
				<a href="<?php echo home_url(); ?>">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" title="MICSUL">
				</a>
			</div>
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
								<div class="entry-thumbnail">
									<?php the_post_thumbnail(); ?>
								</div>
							<?php endif; ?>

							<h1 class="entry-title">
								<?php if ( is_single() ) : ?>
									<?php the_title(); ?>
								<?php else : ?>
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								<?php endif; ?>
							</h1>
							<?php if (current_user_can('manage_options') ): ?>
							<div class="entry-meta header">
								<?php edit_post_link( __( 'Edit', 'inscricao-oscar' ), '<span class="edit-link">', '</span>' ); ?>
							</div>
							<?php endif; ?>
						</header>

						<?php if ( is_single() || is_page() ) : ?>
							<div class="entry-content">
								<?php the_content(); ?>
							</div>
						<?php else : ?>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>

						<footer class="entry-meta">
							<?php if ( is_front_page() ) : ?>
								<div class="entry-meta--front-page">
									<hr class="custom-hrow">
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/bandeiras.png" usemap="#image-map">
									<map name="image-map">
											<area target="_blank" alt="link" title="link" href="#link" coords="0,20,22,-1" shape="rect">
											<area target="_blank" alt="link" title="link" href="#link2" coords="48,1,27,20" shape="rect">
											<area target="_blank" alt="link" title="link" href="#link3" coords="54,2,74,19" shape="rect">
											<area target="_blank" alt="link" title="link" href="#link4" coords="86,11,10" shape="circle">
											<area target="_blank" alt="link" title="link" href="#link5" coords="110,9,12" shape="circle">
											<area target="_blank" alt="link" title="link" href="#link6" coords="124,-1,146,20" shape="rect">
											<area target="_blank" alt="link" title="link" href="#link7" coords="160,12,11" shape="circle">
											<area target="_blank" alt="link" title="link" href="#link8" coords="186,11,12" shape="circle">
											<area target="_blank" alt="link" title="link" href="#link9" coords="211,11,12" shape="circle">
											<area target="_blank" alt="link" title="link" href="#link10" coords="236,12,10" shape="circle">
									</map>
									<a href="http://www.cultura.gov.br/" class="gov">
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/gov.png">
									</a>
								</div>
							<?php endif; ?>
						</footer>
					</article>

				<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>