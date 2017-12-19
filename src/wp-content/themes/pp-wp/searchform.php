<?php
/**
 * Template for displaying search forms
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<form role="search" method="get" id="main-search" class="search-form" action="<?php echo esc_url( home_url( '/' ) );?>">
	<div class="input-group">
		<input type="search" class="search-field form-control" placeholder="Pesquisar por..." value="<?php echo get_search_query(); ?>" name="s" />
        <span class="input-group-btn">
            <button class="btn" type="submit">
            	<i class="fa fa-search" aria-hidden="true"></i>
            	<span class="sr-only">Pesquisar</span>
        	</button>
        </span>
	</div>
</form>