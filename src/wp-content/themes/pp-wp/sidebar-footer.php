<?php
	if ( ! is_active_sidebar( 'sidebar-2' ) && ! is_active_sidebar( 'sidebar-3' ) && ! is_active_sidebar( 'sidebar-4' ) && ! is_active_sidebar( 'sidebar-5' ) )
		return;

    $footer_sidebars = array( 'footer-widgets-area-1', 'footer-widgets-area-2', 'footer-widgets-area-3', 'footer-widgets-area-4' );

    foreach ( $footer_sidebars as $key => $footer_sidebar ) :
        if ( is_active_sidebar( $footer_sidebar ) ) :
            echo '<div class="footer-widget col' . ( 3 === $key ? ' last' : '' ) . '">';
            dynamic_sidebar( $footer_sidebar );
            echo '</div>';
        endif;
    endforeach;
?>