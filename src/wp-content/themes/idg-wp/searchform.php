<?php
/**
 * Template for displaying search forms
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<form role="search" method="get" id="main-search" action="<?php echo esc_url( home_url( '/' ) );?>">
	<div class="input-group">
		<input type="search" class="form-control" placeholder="Buscar no portal" value="<?php echo get_search_query(); ?>" name="s" >
		<div class="input-group-append">
			<button class="btn" type="submit">
				<span class="sr-only">Buscar no portal</span>
			</button>
		</div>
	</div>
</form>