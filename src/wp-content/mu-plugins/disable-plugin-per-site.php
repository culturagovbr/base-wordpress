<?php
add_filter('site_option_active_sitewide_plugins', 'modify_sitewide_plugins');
function modify_sitewide_plugins($value) {
	global $current_blog;

	// Gestao estrategica
	if( $current_blog->blog_id == 33 ) {
		unset($value['wp-super-cache/wp-cache.php']);
	}

	return $value;
}