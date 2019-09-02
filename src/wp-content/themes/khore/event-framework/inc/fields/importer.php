<?php

class EF_Importer_Field extends EF_Field_Base {
	
	public function __construct( $id, $name, $description = '' ) {
		parent::__construct( $id, $name, $description );
		add_action( 'wp_ajax_ef_dummy_import', array( $this, 'dummy_import' ) );
		add_action( 'wp_ajax_nopriv_ef_dummy_import', array( $this, 'dummy_import' ) );
	}
	
	public $type = 'importer';
	
	public function display() {
		$defaults = array(
			'section_prefix' => 'section_prefix_',
			'class' => 'ef-section ef-importer',
			'style' => '',
			'id_prefix' => 'id_prefix',
			'selector' => '',
			'button_text' => 'Import'
		);
		
		$args = wp_parse_args( $this->args, $defaults );
		extract( $args );
		
		?>
		<div id="ef-importer-<?php echo $this->id; ?>"></div>
		<section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
			<label for="<?php echo $this->id_prefix . $this->id ?>"><?php echo $this->name; ?></label>
			<div class="ajax-loader"></div>	
			<input class="ef-importer" id="importer-submit-<?php echo $this->id; ?>" type="submit" value="<?php echo $button_text; ?>" />
		</section>
		<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('#importer-submit-<?php echo $this->id; ?>').click(function(e) {
						e.preventDefault();

						$('.ajax-loader').show();
						
						$.post(ajaxurl, {
					        	action: 'ef_dummy_import'
							 }, function(status) {
								 	 $('#ef-importer-<?php echo $this->id; ?>').html('Demo data imported successfully');
								 	 $('.ajax-loader').hide();
					           }
						);
						
					});
				});
		</script>
		<?php
	}
	
	public function dummy_import() {
		if ( ! defined( 'WP_LOAD_IMPORTERS' ) )
			define( 'WP_LOAD_IMPORTERS', true );
		// Load the parser
		include EF_PARENT_DIR . 'lib/importer/importer.php';
		
		// Pass the path to file
		//$import_file = EF_PARENT_DIR . 'lib/importer/dummy.xml';
                $import_file = get_template_directory() . '/dummy.xml';
		
		$importer = new EF_Import();
		
		$importer->import_start( $import_file );
		
		$importer->get_author_mapping();
		
		wp_suspend_cache_invalidation( true );
                $importer->process_options();
		$importer->process_categories();
		$importer->process_tags();
		$importer->process_terms();
		$importer->process_posts();
		wp_suspend_cache_invalidation( false );
		
		// update incorrect/missing information in the DB
		$importer->backfill_parents();
		$importer->backfill_attachment_urls();
		$importer->remap_featured_images();
				
		wp_cache_flush();
		foreach ( get_taxonomies() as $tax ) {
			delete_option( "{$tax}_children" );
			_get_term_hierarchy( $tax );
		}
		
		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );
		
		die();
	}
}