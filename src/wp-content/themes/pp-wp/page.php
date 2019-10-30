<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Portal_Padrão_WP
 */

get_header (); ?>

    <main id="main" class="site-main">
        <div class="container">

            <div class="row">
				<?php the_breadcrumb (); ?>
            </div>

            <div class="row">

				<?php get_sidebar (); ?>

                <div class="col-lg-9">
					<?php
					while (have_posts ()) : the_post ();

						get_template_part ('template-parts/content', 'page');

						if (comments_open () || get_comments_number ()) :
							comments_template ();
						endif;

					endwhile;
					?>
                </div>
            </div>
        </div>

    </main>

<?php
get_footer ();
