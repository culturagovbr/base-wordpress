<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Identidade_Digital_do_Governo_-_WordPress
 */

get_header();
?>

	<main id="main" class="site-main">
		<div class="container">
			<div class="row">
				<?php the_breadcrumb (); ?>
			</div>

			<div class="row">
				<div class="col-12">
					<?php if ( have_posts() ) : ?>

						<header class="page-header">
							<h1 class="page-title text-center">
								<?php
								printf( esc_html__( 'Busca: %s', 'idg-wp' ), '<span>' . get_search_query() . '</span>' );
								?>
							</h1>
						</header>

						<?php

						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/content', 'search' );
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
get_footer();
