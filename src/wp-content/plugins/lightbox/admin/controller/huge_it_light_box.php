<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( HUGEIT_LIGHTBOX_PLUGIN_DIR . "/admin/model/huge_it_light_box.php" );

/**
 * Class Hugeit_Lightbox_Controller
 */
class Hugeit_Lightbox_Controller {
	public $model;

	public function __construct() {
		$this->model = new Hugeit_Lightbox_Model();
	}

	public function invoke() {
		if ( isset($_GET['hugeit_task']) && $_GET['hugeit_task'] == 'save' ) {
			if (!isset($_REQUEST['hugeit_lightbox_save_settings_nonce']) || !wp_verify_nonce($_REQUEST['hugeit_lightbox_save_settings_nonce'], 'save_settings')) {
				wp_die('Security check failure');
			}
			$this->model->save();
		}

		include_once( HUGEIT_LIGHTBOX_PLUGIN_DIR . "/admin/view/huge_it_light_box.php" );
	}
}