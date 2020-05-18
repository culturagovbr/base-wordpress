<?php

class HappyForms_Block {

	/**
	 * The singleton instance.
	 *
	 * @var HappyForms_Block
	 */
	private static $instance;

	/**
	 * The singleton constructor.
	 *
	 * @return HappyForms_Block
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_scripts' ) );
	}

	private function get_attributes() {
		$attributes = array(
			'id' => array(
				'type' => 'int',
			)
		);

		return $attributes;
	}

	private function get_properties() {
		$properties = array(
			'title' => __( 'HappyForms', 'happyforms' ),
			'description' => __( 'Contact form to manage and respond to conversations with customers.', 'happyforms' ),
			'category' => 'widgets',
			'icon' => 'format-status',
			'keywords' => array(
				'form', 'contact', 'email',
			),
		);

		return $properties;
	}

	public function register() {
		register_block_type( 'thethemefoundry/happyforms', array(
			'attributes' => $this->get_attributes(),
			'editor_script' => 'happyforms-block',
			'render_callback' => array( $this, 'render' ),
		) );
	}

	public function render( $attrs ) {
		return HappyForms()->handle_shortcode( $attrs );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'happyforms-block',
			happyforms_get_plugin_url() . 'core/assets/js/admin/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'jquery' )
		);

		$user_id = get_current_user_id();
		$admin_notices = happyforms_get_admin_notices();
		$forms = happyforms_get_form_controller()->get();
		$forms = wp_list_pluck( $forms, 'post_title', 'ID' );

		$upgrade_notice_nonces = array();

		foreach( $forms as $ID => $form ) {
			$upgrade_notice_nonces[$ID] = wp_create_nonce( 'happyforms_dismiss_' . 'happyforms_gutenberg_' . $ID . '_upgrade' );
		}

		$edit_link = admin_url( happyforms_get_form_edit_link( 'ID', 'URL' ) );
		$block_properties = $this->get_properties();
		$data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'forms' => $forms,
			'editLink' => $edit_link,
			'block' => $block_properties,
			'upgrade_notice' => array(
				'show' => ( ! defined( 'HAPPYFORMS_UPGRADE_VERSION' ) ) ? true : false,
				'i18n' => array(
					'text' => __( 'Do you want access to HappyForms’ most powerful features?', 'happyforms' ),
					'link_text' => __( 'Discover HappyForms Upgrade', 'happyforms' ),
				),
				'nonces' => $upgrade_notice_nonces
			),
			'i18n' => array(
				'select_default' => __( 'Choose', 'happyforms' ),
				'placeholder_text' => __( 'Which form would you like to add here?', 'happyforms' ),
				'settings_title' => __( 'HappyForms Settings', 'happyforms' ),
				'edit_form' => __( 'Edit Form', 'happyforms' )
			),
			'dismissed_notices' => $admin_notices->get_dismissed_notices( $user_id )
		);

		wp_localize_script( 'happyforms-block', '_happyFormsBlockSettings', $data );
	}

}

if ( ! function_exists( 'happyforms_get_block' ) ):
/**
 * Get the HappyForms_Block class instance.
 *
 * @return HappyForms_Block
 */
function happyforms_get_block() {
	return HappyForms_Block::instance();
}

endif;

/**
 * Initialize the HappyForms_Block class immediately.
 */
happyforms_get_block();
