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
							<?php the_archive_title( '<h1 class="page-title text-center mt-1">', '</h1>' ); ?>
						</header>

						<?php
						while ( have_posts() ) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<header class="entry-header">
									<?php the_title( '<h2><a href="'. get_the_permalink() .'">', '</a></h2>' ); ?>
								</header>

								<div class="entry-content">
									<?php the_excerpt(); ?>
								</div>

								<footer class="entry-footer">
									<?php idg_wp_entry_footer(); ?>
									<div class="date-box mb-4">
										<span>publicado: <?php the_date('d/m/Y'); ?> <?php the_time('H'); ?>h<?php the_time('i'); ?>, última modificação: <?php the_modified_date('d/m/Y'); ?> <?php the_modified_time('H'); ?>h<?php the_modified_time('i'); ?></span>
									</div>
								</footer>
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
