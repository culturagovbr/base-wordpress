<?php
/**
 * The template for displaying pages without a left sidebar
 * Template Name: No sidebar
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Portal_Padrão_WP
 */

// Translating the template name
__('No sidebar', 'pp-wp');

get_header (); ?>

    <main id="main" class="site-main">
        <div class="container">

            <div class="row">
				<?php the_breadcrumb (); ?>
            </div>

            <div class="row">

                <div class="col-lg-12">
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
