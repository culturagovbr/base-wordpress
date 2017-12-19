<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Portal_Padrão_WP
 */

?>

<article id="post-<?php the_ID (); ?>" <?php post_class (); ?>>
    <header class="entry-header">
		<?php
		if (is_singular ()) :
			the_title ('<h1 class="entry-title">', '</h1>');
		else :
			the_title ('<h2 class="entry-title"><a href="' . esc_url (get_permalink ()) . '" rel="bookmark">', '</a></h2>');
		endif;

		if ('post' === get_post_type ()) : ?>
            <div class="entry-meta">
				<?php pp_wp_posted_on (); ?>
            </div>
			<?php
		endif; ?>
    </header>

    <div class="entry-content">
		<?php
		if (is_singular ()) {
			the_content (sprintf (wp_kses (// translators: %s: Name of current post. Only visible to screen readers
				__ ('Continue reading<span class="screen-reader-text"> "%s"</span>', 'pp-wp'), array('span' => array('class' => array(),),)), get_the_title ()));
		} else {
			the_excerpt ();
		}
		?>
    </div>

    <footer class="entry-footer">
		<?php pp_wp_entry_footer (); ?>
    </footer>
</article>
