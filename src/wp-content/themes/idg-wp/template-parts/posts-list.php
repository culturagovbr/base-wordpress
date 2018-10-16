<?php
/**
 * Template part for listing posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Identidade_Digital_do_Governo_-_WordPress
 */

?>

<?php $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; ?>

<?php if ( have_posts() ) : ?>
  <ul id="posts-list">

    <?php while ( have_posts() ) : ?>
      <?php the_post(); ?>

      <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="categories"><?php the_category(', '); ?></div>

        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php if ( has_excerpt() ) : ?>
          <?php the_excerpt(); ?>
        <?php endif; ?>

        <?php if ( get_the_tags() ) : ?>
          <div class="tags-list">
            <?php the_tags('<span>tags:</span>', ''); ?>
          </div>
        <?php endif; ?>

        <span class="details">
          por <?php the_author_posts_link(); ?> última modificação em <?php the_modified_date('d/m/Y'); ?> <?php the_modified_time('H'); ?>h<?php the_modified_time('i'); ?>
        </span>
      </li>

    <?php endwhile; ?>

    <?php the_posts_navigation(); ?>

  </ul>

<?php else : ?>

  <?php get_template_part( 'template-parts/content', 'none' ); ?>

<?php endif; ?>
