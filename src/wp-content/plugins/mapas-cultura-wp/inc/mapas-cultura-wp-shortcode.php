<?php
function software_catalog_main_area( $atts ){
	$atts = shortcode_atts( array(
		'field_group' => '',
	), $atts, 'software-catalog' );

	$software_catalog_options = get_option('software_catalog_options');

	ob_start(); ?>

	<div id="software-catalog-repos" class="<?php echo $software_catalog_options['layout_columns'] ? 'layout-columns-' . $software_catalog_options['layout_columns'] : ''; ?>">
		<?php if( $software_catalog_options['upper_title'] ): ?>
		<h2 class="title">
			<?php echo $software_catalog_options['upper_title']; ?>
		</h2>
		<?php endif; ?>
		<div class="repos row" style="text-align: center;">
			<div style="margin: 30px 0;">
				<img src="<?php echo plugins_url( 'assets/img/loading.gif', dirname(__FILE__) ); ?>" alt="" class="loading">
			</div>
		</div>
		<?php if( $software_catalog_options['lower_title'] ): ?>
		<h2 class="title">
			<?php echo $software_catalog_options['lower_title']; ?>
		</h2>
		<?php endif; ?>

		<div class="row">
			<ul class="thumbnails members" id="members">
				<div style="margin: 30px 0;">
					<img src="<?php echo plugins_url( 'assets/img/loading.gif', dirname(__FILE__) ); ?>" alt="" class="loading">
				</div>
			</ul>
		</div>
	</div>

	<?php return ob_get_clean();
}
add_shortcode( 'software-catalog', 'software_catalog_main_area' );