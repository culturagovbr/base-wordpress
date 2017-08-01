<?php
if( is_front_page() ){
	acf_form_head();
}
get_header(); 
?>


<main class="container">
	<div class="row">
		<div class="col-sm-12">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
								<div class="entry-thumbnail">
									<?php the_post_thumbnail(); ?>
								</div>
							<?php endif; ?>

							<h1 class="entry-title"><?php the_title(); ?></h1>
							<?php if (current_user_can('manage_options') ): ?>
							<div class="entry-meta header">
								<?php edit_post_link( __( 'Edit', 'inscricao-oscar' ), '<span class="edit-link">', '</span>' ); ?>
							</div>
							<?php endif; ?>
						</header>

						<?php if ( is_search() ) : ?>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div>
						<?php else : ?>
							<div class="entry-content">
								<?php
								the_content( sprintf(
									__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'inscricao-oscar' ),
									the_title( '<span class="screen-reader-text">', '</span>', false )
									) );
								wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'inscricao-oscar' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
								?>
							</div>
						<?php endif; ?>

						<footer class="entry-meta">
							<?php if ( comments_open() && ! is_single() ) : ?>
								<div class="comments-link">
									<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'inscricao-oscar' ) . '</span>', __( 'One comment so far', 'inscricao-oscar' ), __( 'View all % comments', 'inscricao-oscar' ) ); ?>
								</div>
							<?php endif; ?>
						</footer>
					</article>

				<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>