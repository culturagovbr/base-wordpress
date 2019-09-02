<?php 

if ( ! is_admin() ) {
	add_action( 'wp_head' , 'style_switcher_enqueue_scripts' );
	add_action( 'init' , 'style_switcher_display' );
}


function style_switcher_enqueue_scripts() {
	wp_enqueue_style( 'css-style-switcher', EF_LIB_URL . '/style-switcher/style-switcher.css' );
	
	wp_enqueue_script( 'js-style-switcher', EF_LIB_URL . '/style-switcher/style-switcher.js', array( 'jquery' ) );
}

function style_switcher_display() {
	$ef_options = EF_Event_Options::get_theme_options();
	$color_scheme = empty( $ef_options['ef_color_palette'] ) ? 'basic' : $ef_options['ef_color_palette'];
	
	$khore_session_expired = 5;
	
	if ( isset( $_SESSION['dxef_last_skin_activity'] ) && ( ( time() - $_SESSION['dxef_last_skin_activity'] ) > $khore_session_expired ) ) {
		
		unset( $_SESSION['dxef_last_skin_activity'] );
		unset( $_SESSION['dxef_selected_skin'] );
			
	} else {
		$_SESSION['dxef_last_skin_activity'] = time();
		
		if ( ! empty( $_POST['selected-skin'] ) ) {
			$_SESSION['dxef_selected_skin'] = $_POST['selected-skin'];
		}
	
		if ( ! empty( $_SESSION['dxef_selected_skin'] ) ) {
			$color_scheme = $_SESSION['dxef_selected_skin'];
		}
		
		if ( isset( $color_scheme ) && $color_scheme != 'basic' ) {
			wp_enqueue_style( $color_scheme . '-scheme', get_template_directory_uri() . '/css/schemes/' . $color_scheme . '/layout.css', true );
			?>
			<script>
				var poi_marker = '<?php echo get_template_directory_uri(); ?>/images/schemes/<?php echo $color_scheme; ?>/icon-map-pointer.png';
			</script>
			<?php 
		}
	}
	?>
	
	<div id="dxef-style-switcher">
		<div id="dxef-style-switcher-close"></div>
		<h3><?php _e( 'Select Skin', 'khore' ); ?></h3>
		<form id="selected-skin-form" method="post">
			<select id="selected-skin" name="selected-skin">
				<option>Select Skin</option>
				<option value="basic">Basic</option>
				<option value="bangkok">Bangkok</option>
				<option value="barcelona">Barcelona</option>
				<option value="helsinki">Helsinki</option>
				<option value="istanbul">Istanbul</option>
				<option value="london">London</option>
				<option value="melbourne">Melbourne</option>
				<option value="mexico-city">Mmexico City</option>
				<option value="new-orleans">New Orleans</option>
				<option value="oslo">Oslo</option>
				<option value="paris">Paris</option>
				<option value="san-diego">San Diego</option>
				<option value="santa-monica">Santa Monica</option>
				<option value="shangai">Shangai</option>
				<option value="tokyo">Tokyo</option>
			</select>
		</form>
	</div>

<?php
}