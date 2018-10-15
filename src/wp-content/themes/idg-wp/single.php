<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Identidade_Digital_do_Governo_-_WordPress
 */

get_header();
?>

  <main id="main-single" class="site-main">
    <div class="container">
      <div class="row">
        <?php the_breadcrumb (); ?>
      </div>

      <div class="row" id="content">
        <div class="col-12 pt-4 pb-4">
          <div class="align">
            <?php while ( have_posts() ) : ?>
              <span class="category-single text-center d-block mb-3 text-uppercase"><?php the_category(get_the_ID()) ?></span>

              <?php the_post(); ?>

              <?php get_template_part( 'template-parts/content', get_post_type() ); ?>

              <?php //the_post_navigation(); ?>

              <?php if ( comments_open() || get_comments_number() ) : ?>
                <?php //comments_template(); ?>
              <?php endif; ?>

            <?php endwhile; ?>

            <?php get_template_part( 'template-parts/copyright' ); ?>
          </div>
        </div>
      </div>
    </div>
  </main>

<?php
get_footer();
