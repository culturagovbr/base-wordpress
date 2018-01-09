<?php
/**
 * The template for displaying page with no sidebar and with 100% of width
 * Template Name: 100% of width
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Portal_Padrão_WP
 */

get_header (); ?>

    <main id="main" class="site-main">
        <div class="<?php echo ( !get_post_meta( get_the_ID (), 'remove-internal-padding', true ) ) ? 'container-fluid' : ''; ?>">

            <div class="row <?php echo ( get_post_meta( get_the_ID (), 'remove-internal-padding', true ) ) ? 'no-gutters' : ''; ?>">
				<?php the_breadcrumb (); ?>
            </div>

            <div class="row <?php echo ( get_post_meta( get_the_ID (), 'remove-internal-padding', true ) ) ? 'no-gutters' : ''; ?>">

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
