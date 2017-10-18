<?php
get_header(); 
?>
<main class="container">
	<div class="row">
		<div class="col-md-8 content-area">
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
									<?php
									if( have_rows('link_para_as_bandeiras', 'options') ):
										$coords = array(
											"10,12,11",
											"36,11,9",
											"61,10,9",
											"87,11,10",
											"112,12,10",
											"136,12,9",
											"161,11,10",
											"186,11,11",
											"211,11,11",
											"236,11,11"
										);
										echo '<map name="image-map">';
										$i = 0; while( have_rows('link_para_as_bandeiras', 'options') ): the_row(); ?>

											<area target="_blank" alt="<?php the_sub_field('titulo'); ?>" title="<?php the_sub_field('titulo'); ?>" href="<?php the_sub_field('link'); ?>" coords="<?php echo $coords[$i]; ?>" shape="circle">

										<?php $i++; endwhile;
										echo '</map>';
									endif; ?>
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
		<div class="col-md-4">
			<div class="micsul-logo">
				<a href="<?php echo home_url(); ?>">
					<img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" title="MICSUL">
				</a>
			</div>
			<div class="micsul-map sidebar">
				<img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/images/mapa.png">
				<div class="mapa-content">
					<?php
					$cur_lang = pll_current_language();
					if( $cur_lang !== 'pt' ){
						$map_title = get_field('titulo_para_o_mapa_' . $cur_lang, 'options');
					} else {
						$map_title = get_field('titulo_para_o_mapa', 'options');
					}
					?>
				    <h1><?php echo $map_title; ?></h1>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>