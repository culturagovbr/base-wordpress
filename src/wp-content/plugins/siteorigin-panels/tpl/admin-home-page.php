<?php $settings = siteorigin_panels_setting(); ?>

<div class="wrap" id="panels-home-page">
	<form action="<?php echo add_query_arg('page', 'so_panels_home_page') ?>" class="hide-if-no-js" method="post" id="panels-home-page-form">
		<div id="icon-index" class="icon32"><br></div>
		<h2>
			<?php esc_html_e('Custom Home Page', 'siteorigin-panels') ?>

			<div id="panels-toggle-switch" class="<?php echo ( get_option('siteorigin_panels_home_page_id') && get_option('siteorigin_panels_home_page_id') == get_option('page_on_front') && get_option('show_on_front') == 'page' ) ? 'state-on' : 'state-off'; ?>">
				<div class="on-text"><?php _e('ON', 'siteorigin-panels') ?></div>
				<div class="off-text"><?php _e('OFF', 'siteorigin-panels') ?></div>
				<img src="<?php echo plugin_dir_url(SITEORIGIN_PANELS_BASE_FILE) ?>css/images/handle.png" class="handle" />
			</div>

			<?php if( get_option('siteorigin_panels_home_page_id') && ($the_page = get_post( get_option('siteorigin_panels_home_page_id') ) ) ) : ?>
				<div id="panels-view-as-page">
					<a href="<?php echo admin_url('post.php?post='.$the_page->ID.'&action=edit') ?>" class="add-new-h2">Edit As Page</a>
				</div>
			<?php endif; ?>
		</h2>

		<?php if(isset($_POST['_sopanels_home_nonce']) && wp_verify_nonce($_POST['_sopanels_home_nonce'], 'save')) : ?>
			<div id="message" class="updated">
				<p><?php printf( __('Home page updated. <a href="%s">View page</a>', 'siteorigin-panels'), get_home_url() ) ?></p>
			</div>
		<?php endif; ?>

		<div id="post-body-wrapper">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" style="position: relative">
					<a href="#" class="preview button" id="post-preview"><?php _e('Preview Changes', 'siteorigin-panels') ?></a>

					<?php wp_editor('', 'content') ?>
					<?php do_meta_boxes('appearance_page_so_panels_home_page', 'advanced', false) ?>

					<p><input type="submit" class="button button-primary" id="panels-save-home-page" value="<?php esc_attr_e('Save Home Page', 'siteorigin-panels') ?>" /></p>
				</div>
			</div>
		</div>

		<input type="hidden" id="panels-home-enabled" name="siteorigin_panels_home_enabled" value="<?php echo ( get_option('siteorigin_panels_home_page_id') && get_option('siteorigin_panels_home_page_id') == get_option('page_on_front') && get_option('show_on_front') == 'page' ) ? 'true' : 'false'; ?>" />
		<?php wp_nonce_field('save', '_sopanels_home_nonce') ?>
	</form>
	<noscript><p><?php _e('This interface requires Javascript', 'siteorigin-panels') ?></p></noscript>
</div> 