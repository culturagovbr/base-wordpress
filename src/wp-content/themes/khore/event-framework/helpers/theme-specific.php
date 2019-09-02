<?php

/**
 * Helper class for Theme Specific settings
 * 
 *  Fallback to default if not available
 * 
 * @author nofearinc
 *
 */
class EF_Theme_Specific_Helper {
	
	private static $available_widgets = array();
	
	/**
	 * Loader methods - read theme specific files
	 * or fallback to framework defaults
	 */
	
	public static function load_widgets() {
		// TODO: Try to load file from theme and init widgets
		if( file_exists( get_template_directory() . '/ef-widgets.php' ) ) {
			include_once get_template_directory() . '/ef-widgets.php';
		} else {
			self::load_default_widgets();
		}
	}
	
	public static function load_theme_options() {
		// TODO: Try to load file from theme and init options
		if( file_exists( get_template_directory() . '/ef-options.php' ) ) {
			include_once get_template_directory() . '/ef-options.php';
		} else {
			self::load_default_theme_options();
		}
	}
	
	/**
	 * Add methods - fill in collections from theme options
	 * or framework defaults 
	 */
	
	public static function add_widget( $widget_name ) {
		self::$available_widgets[] = $widget_name;
	}
	
	/**
	 * Read default widgets or options from framework
	 */
	
	private static function load_default_widgets() {
		if( file_exists( EF_DEFAULT_DIR . '/widgets.php' ) ) {
			include_once EF_DEFAULT_DIR . '/widgets.php';
		}
	}
	
	private static function load_default_theme_options() {
		if( file_exists( EF_DEFAULT_DIR . '/options.php' ) ) {
			include_once EF_DEFAULT_DIR . '/options.php';
		}	
	}
	
	/**
	 * Helper methods for fetching the collections
	 * from outside
	 */
	
	public static function get_available_widgets() {
		return self::$available_widgets;
	}
	
}