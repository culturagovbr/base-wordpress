<?php
	if ( ! is_active_sidebar( 'footer-widgets-area-1' ) && ! is_active_sidebar( 'footer-widgets-area-2' ) && ! is_active_sidebar( 'footer-widgets-area-3' ) && ! is_active_sidebar( 'footer-widgets-area-4' ) )
		return;

    $footer_sidebars = array( 'footer-widgets-area-1', 'footer-widgets-area-2', 'footer-widgets-area-3', 'footer-widgets-area-4' );
	switch ( count($footer_sidebars) ){
		case 4:
			$col = 'col-sm-12 col-md-6 col-lg-3';
			break;
		case 3:
			$col = 'col-md-4';
			break;
		case 2:
			$col = 'col-md-6';
			break;
		default:
			$col = 'col';
			break;
	}

    foreach ( $footer_sidebars as $key => $footer_sidebar ) :
        if ( is_active_sidebar( $footer_sidebar ) ) :

            echo '<div class="footer-widget col ' . $key . ' ' . ( 3 === $key ? 'last ' : '' ) .'">';
            dynamic_sidebar( $footer_sidebar );
            echo '</div>';

        endif;
    endforeach;
?>