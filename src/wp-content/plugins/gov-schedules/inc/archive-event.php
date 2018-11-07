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

	<main id="main" class="site-main">
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

						<div class="agenda-archive">
							<div id="archive-datepicker"></div>

							<div id="agenda" class="gs-agenda-container mt-5">
								<div class="daypicker-wrapper">
									<ul class="daypicker">
										<?php
										setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
										date_default_timezone_set('America/Sao_Paulo');

										for($i = 3; $i >= 1; $i--) {
											$date = date_create();
											date_sub($date, date_interval_create_from_date_string( $i . ' days')); ?>

											<li>
												<a href="#" data-day="<?php echo date_format($date, 'Y-m-d'); ?>">
													<span><?php echo date_format($date, 'd'); ?></span>
													<small><?php echo strftime('%a', strtotime( date_format($date, 'Y-m-d') ) ); ?></small>
												</a>
											</li>

											<?php
										}

										for($i = 0; $i <= 3; $i++) {
											$date = date_create();
											date_add($date, date_interval_create_from_date_string($i . ' days')); ?>

											<li <?php echo $i === 0 ? 'class="selected"' : ''; ?>>
												<a href="#" data-day="<?php echo date_format($date, 'Y-m-d'); ?>">
													<span><?php echo date_format($date, 'd'); ?></span>
													<small><?php echo strftime('%a', strtotime( date_format($date, 'Y-m-d') ) ); ?></small>
												</a>
											</li>

											<?php
										} ?>
									</ul>
								</div>
								<div class="events"></div>
							</div>
						</div>

						<?php
						while ( have_posts() ) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('row mb-5'); ?>>

								<?php

								$locaction = get_post_meta( get_the_ID(), 'dados_do_evento_location', true );
								$date = get_post_meta( get_the_ID(), 'dados_do_evento_data-de-incio', true );
								$raw_date = explode(' ', $date );

								?>
								<div class="entry-content container">
									<div class="col-md-3">
										<span><?php echo $raw_date[1]; ?></span>
									</div>
									<div class="col-md-9">
										<header class="entry-header">
											<?php the_title( '<h2><a href="'. get_the_permalink() .'">', '</a></h2>' ); ?>
										</header>
										<div>
											<span><?php echo $locaction; ?></span>
											<a href="#">Adicionar ao meu calendário</a>
										</div>
									</div>
								</div>
							</article>

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
	</main>

<?php
get_sidebar();
get_footer();
