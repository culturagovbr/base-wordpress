<?php

class EF_Event_Options {
	
	public static $theme_options_group = 'event_framework'; 
	
	private static $theme_options = NULL;
	
	public function __construct() {
		add_action( 'wp_ajax_save_theme_options', array( $this, 'save_theme_options' ) );
		add_action( 'wp_head', array( __CLASS__, 'generate_css_rules' ), 88 );
	}
	
	public function save_theme_options() {
		if( isset( $_POST['data'] ) ) {
			$theme_options = apply_filters( 'ef_save_options_filter' , $_POST['data'] );
			$this->update_theme_options( $theme_options );
		}
		
		die();
	}
	
	private function update_theme_options( $theme_options ) {
		update_option( self::$theme_options_group , $theme_options );
	}
	
	public static function get_theme_options( $force = false ) {
		// Fetch DB only if the value isn't set or force is triggered
		if ( $force || is_null( self::$theme_options ) ) {
			// Read the DB options
			$db_options = get_option( self::$theme_options_group, array() );
			$theme_options = array();

			if ( empty( $db_options ) ) {
				return array();
			}
			// Convert the DB options to a more readable format
			foreach( $db_options['theme_options'] as $index => $data ) {
				if( false !== strpos( $data['name'], 'eventframework[' ) ) {
					// Strip the eventframework[...] wrapper
					$option_name = substr( $data['name'], 15, strlen( $data['name'] ) - 16 );
					
					// Regroup arrays if there is another level of depth
					if ( false !== strpos( $option_name , '][' ) ) {
						$option_fields = explode( '][' , $option_name );
						$theme_options[ $option_fields[0] ][ $option_fields[1] ] = $data['value'];
					} else {
						// Take care of simple fields, non-arrays
						$theme_options[ $option_name ] = $data['value'];
					}
					
				}
			}
			
			self::$theme_options = apply_filters( 'ef_get_theme_options_filter', $theme_options );
		} 
		
		return self::$theme_options;
	}
	
	public static function get_theme_option( $option_name ) {
		$theme_options = self::get_theme_options();
		
		if ( ! isset( $theme_options[ $option_name ] ) ) {
			return '';
		}
		
		return $theme_options[ $option_name ];
	}
	
	public static function generate_css_rules() {
		
		if ( is_admin() ) {
			return;
		}
		
		$theme_options = self::get_theme_options();
		
		$all_fields = self::get_all_registered_panel_fields();
		
		$css_rules = array();
		foreach ( $all_fields as $field ) {
			if ( ! is_null( $field->selector ) ) {
				$css = array();
				
				// Merge old rules assigned to the same selector if any
				if ( ! empty( $css_rules[ $field->selector ] ) ) {
					$css = $css_rules[ $field->selector ];	
				}
				
				$css_rules[ $field->selector ] = array_merge( $css, $field->get_css_rules() );
			} 
		}
		
		echo '<style type="text/css">';
		foreach ( $css_rules as $selector => $rules_list ) {
			$rules = '';
			foreach ( $rules_list as $rule => $value ) {
				$rules .= sprintf( '%s: %s;', $rule, $value );
			}
			printf( '%s { %s } ', $selector, $rules );
		}
		echo '</style>';
	}
	
	/**
	 * Get all registered fields in a panel, flat model
	 * 
	 * @param string $panel panel name
	 */
	public static function get_all_registered_panel_fields( $panel = 'theme_options' ) {
		global $ef_panel_manager;
		
		$all_fields = array();
		
		$theme_options = $ef_panel_manager->get_panel( $panel );
		
		$tabs = $theme_options->get_tabs();
		
		foreach( $tabs as $tab ) {
			$tab_fields = $tab->get_fields();
			$all_fields = array_merge( $all_fields, $tab_fields );
		}
		
		return apply_filters( 'ef_filter_all_fields_array', $all_fields );
	}
}

new EF_Event_Options();