<?php

/**
 * 
 * Options Panel class
 * 
 * Storing tabs for a theme options page
 * 
 * @author nofearinc
 *
 */
class EF_Options_Panel {
	
	/**
	 * Array with tabs for the given page 
	 * 
	 * @var array $tabs
	 */
	private $tabs = array();

	public function __construct() { }

	/**
	 * Get all tabs
	 * 
	 * @return array EF_Options_Tab tabs:
	 */
	public function get_tabs() {
		return $this->tabs;
	}
	
	/**
	 * Get a single tab
	 * 
	 * @param string $tab_name
	 * @return EF_Options_Tab instance
	 */
	public function get_tab( $tab_name ) {
		if ( ! isset( $this->tabs[ $tab_name ] ) ) {
			return new EF_Options_Tab();
		}
		
		return $this->tabs[ $tab_name ];
	}
	
	/**
	 * Add a new tab to panel
	 * 
	 * @param string $tab_name
	 * @param EF_Options_Tab $tab
	 */
	public function add_tab( $tab_name, $tab ) {
		$this->tabs[ $tab_name ] = $tab;
	}
}