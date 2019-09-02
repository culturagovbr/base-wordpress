<?php

/**
 * Panel manager class
 * 
 * Hosts the available panels that keep the option tabs
 * 
 * Currently only the Theme Options panel is expected, but 
 * another instance might be needed later as well.
 * 
 * @author nofearinc
 *
 */
class EF_Panel_Manager {
	
	/**
	 * Array with panels for fields 
	 * @var array panels
	 */
	private $panels = array();
	
	public function __construct() {
		$theme_panel = new EF_Options_Panel();
		
		$this->panels[ 'theme_options' ] = $theme_panel;
	}
	
	/**
	 * Add a new panel 
	 * 
	 * @param string $panel_name
	 * @param EF_Options_Panel $panel
	 */
	public function add_panel( $panel_name, $panel ) {
		$this->panels[ $panel_name ] = $panel;
	}
	
	/**
	 * Get a single panel
	 * 
	 * @param string $panel_name
	 * @return EF_Options_Panel
	 */
	public function get_panel( $panel_name ) {
		if( ! isset( $this->panels[ $panel_name ] ) ) {
			return new EF_Options_Panel();
		}
		
		return $this->panels[ $panel_name ];
	}
}

global $ef_panel_manager;
$ef_panel_manager = new EF_Panel_Manager();