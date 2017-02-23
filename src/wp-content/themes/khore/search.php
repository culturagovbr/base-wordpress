<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Khore
 * @since Khore
 */

get_header(); ?>
<style>
body {
    overflow-y: scroll;
}
</style>
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		  <div class="search-form">
		    <form role="search" action="<?php echo site_url('/'); ?>" method="get" id="searchform">     
		      <input type="text" value="<?php print get_search_query(); ?>" name="s" id="s" />
		      <button id="searchsubmit" class="btn"><?php _e('Search', 'khore'); ?></button>
		    </form>
		  </div>
    
		<?php if ( have_posts() ) : ?>
    
			<header class="page-header">
				<h3 class="page-title"><?php printf( __( 'Search Results for: %s', 'khore' ), get_search_query() ); ?></h3>
			</header><!-- .page-header -->

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post(); ?>

				<?php
				/*
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'content', 'search' );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'khore' ),
				'next_text'          => __( 'Next page', 'khore' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'khore' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
