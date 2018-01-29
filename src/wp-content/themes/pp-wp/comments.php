<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Portal_Padrão_WP
 */
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area">

	<?php
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comment_count = get_comments_number();
			if ( 1 === $comment_count ) {
				printf(
					esc_html_e( 'One thought on &ldquo;%1$s&rdquo;', 'pp-wp' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf(
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'pp-wp' ) ),
					number_format_i18n( $comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ul class="comment-list">
			<?php
				wp_list_comments( array(
					'style'         => 'ul',
					'avatar_size'   => 64,
					'short_ping'    => true,
					'reply_text'    => '<i class="fa fa-reply" aria-hidden="true"></i> Responder',
				) );
			?>
		</ul>

		<?php the_comments_navigation();

		if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'pp-wp' ); ?></p>
		<?php
		endif;

	endif;

	comment_form();
	?>

</section>
