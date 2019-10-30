<?php
/**
 * The right sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Portal_Padrão_WP
 */

if ( ! is_active_sidebar( 'sidebar-right' ) ) {
	return;
}
?>

<aside id="tertiary" class="widget-area col-lg-3">
    <?php dynamic_sidebar( 'sidebar-right' ); ?>
</aside>
