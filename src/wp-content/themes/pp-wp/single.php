<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Portal_Padrão_WP
 */

get_header(); ?>

    <main id="main" class="site-main">
            <div class="container">

                <div class="row">
					<?php the_breadcrumb (); ?>
                </div>

                <div class="row">

					<?php get_sidebar(); ?>

                    <div class="col-lg-6">

						<?php
						while ( have_posts() ) : the_post();

							get_template_part( 'template-parts/content', get_post_type() );

							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

						endwhile;
						?>

                    </div>

                    <div class="col-lg-3">
                        SIDEBAR II
                    </div>
                </div>
            </div>

		</main>

<?php
get_footer();
