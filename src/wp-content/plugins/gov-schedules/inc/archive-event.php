<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Identidade_Digital_do_Governo_-_WordPress
 */

get_header();
?>

	<main id="events-archive" class="site-main">
		<div class="container">
			<div class="row">
				<?php the_breadcrumb(); ?>
			</div>
			<div class="row">
				<div class="col-12 pt-4 pb-4">
					<?php if ( have_posts() ) : ?>

						<header class="page-header">
							<h1 class="page-title text-center mt-1">Agenda</h1>
						</header>


						<div class="entry-content">
							<?php
							while ( have_posts() ) : the_post(); ?>

									<?php

										$locaction = get_post_meta( get_the_ID(), 'dados_do_evento_location', true );
										$date = get_post_meta( get_the_ID(), 'dados_do_evento_data-de-incio', true );
										$raw_date = explode(' ', $date );

										$dateObj = explode('-', $raw_date[0]);
										$day = $dateObj[2];
										$month = $dateObj[1];
										$year = $dateObj[0];

									?>

									<div class="event row">
										<div class="time">
											<span class="icon icon-clock"><?php echo $raw_date[1]; ?></span>
										</div>

										<div class="info">
											<?php the_title( '<h2>', '</h2>' ); ?>

											<div class="additional">
													<span class="location icon icon-location"><?php echo $locaction; ?></span>
													<a href="#">Adicionar ao meu calendário</a>
											</div>
										</div>

									</div>



							<?php
							endwhile;

								the_posts_navigation();

							else :

								get_template_part( 'template-parts/content', 'none' );

							endif;
						?>

					</div>
				</div>
			</div>
		</div>
	</main>

<?php
get_sidebar();
get_footer();
