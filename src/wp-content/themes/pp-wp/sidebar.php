<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Portal_Padrão_WP
 */

if ( ! is_active_sidebar( 'sidebar-left' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area col-md-3 col-lg-3">
    <?php dynamic_sidebar( 'sidebar-left' ); ?>
</aside>
