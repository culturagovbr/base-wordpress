<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Class
 *
 * Handles all public functionalities of plugin
 *
 * @package Event Framework
 * @since 1.0.0
 */
class Multi_Event_Renderer{
	
	public $script;
	public function __construct() {
		
		global $multi_event_scripts;
		
		$this->script	= $multi_event_scripts;
	}
	
	public function dx_multievent_display_events( $option_name, $option_value, $multievent_data = array() ) { ?>
		
		<em><?php echo __( 'Select Event', 'dxef' );?></em>
		<br />
		<select name="<?php echo $option_name;?>">
			<option value=""><?php echo __( 'Select Event', 'dxef' )?></option><?php
			
			foreach ( $multievent_data as $multievent ) {
				
				$selected = '';
				if( $multievent['ID'] == $option_value ) {
					
					$selected = ' selected="selected"';
				}?>
				
				<option value="<?php echo $multievent['ID'];?>" <?php echo $selected;?>><?php echo $multievent['post_title'];?></option><?php 
				
			}?>
		</select><?php 
	}
}