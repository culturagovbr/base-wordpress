<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Portal_Padrão_WP
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="container site-info">
            <div class="row">
				<?php get_sidebar('footer'); ?>
            </div>
		</div>
	</footer>
</div>

<?php wp_footer(); ?>
</body>
</html>
