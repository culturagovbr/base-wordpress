<?php

class HappyForms_WP_Customize_Form_Manager {

	/**
	 * A reference to the $wp_customize object.
	 *
	 * @var WP_Customize_Manager
	 */
	private $manager;

	/**
	 * WP_Customize_Form_Manager constructor.
	 *
	 * @since  1.0
	 */
	public function __construct() {
		require_once( happyforms_get_core_folder() . '/helpers/helper-validation.php' );

		/*
		 * Note the customize_register action is triggered in
		 * WP_Customize_Manager::wp_loaded() which is itself the
		 * callback for the wp_loaded action at priority 10. So
		 * this wp_loaded action just has to be added at a
		 * priority less than 10.
		 */
		add_action( 'wp_loaded', array( $this, 'wp_loaded' ), 1 );

		// Ajax callbacks.
		add_action( 'wp_ajax_happyforms-update-form', array( $this, 'ajax_update_form' ) );
		add_action( 'wp_ajax_happyforms-form-part-add', array( $this, 'ajax_form_part_add' ) );
		add_action( 'wp_ajax_happyforms-form-fetch-partial-html', array( $this, 'ajax_fetch_partial' ) );
	}

	/**
	 * Get the form from the current request `form_id` parameter.
	 *
	 * @since  1.0
	 *
	 * @return array The current form data.
	 */
	public function get_current_form() {
		$form_id = intval( $_REQUEST['form_id'] );
		$form = happyforms_get_form_controller()->get( $form_id );

		if ( is_wp_error( $form ) ) {
			wp_die( $form->get_error_message() );
			exit;
		}

		$form = apply_filters( 'happyforms_customize_get_current_form', $form );

		return $form;
	}

	/**
	 * Action: reset Customize hooks and
	 * inject HappyForms logic and scripts.
	 *
	 * @since  1.0
	 *
	 * @hooked action wp_loaded
	 *
	 * @return void
	 */
	public function wp_loaded() {
		$this->get_current_form();

		global $wp_customize;

		remove_all_actions( 'customize_register' );
		remove_all_actions( 'customize_controls_enqueue_scripts' );

		$this->manager = $wp_customize;
		$this->library = happyforms_get_part_library();

		// Carry the happyforms customize query parameter over to preview screen
		add_action( 'customize_controls_init', array( $this, 'preview_screen_preserve_query_arg' ) );
		// Register a custom nonce
		add_filter( 'customize_refresh_nonces', array( $this, 'add_customize_nonce' ) );
		// Output header styles
		add_filter( 'customize_controls_print_scripts', array( $this, 'customize_controls_print_styles' ) );
		// Enqueue dynamic controls for the Customizer
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts_customizer' ) );
		// Print templates
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_controls_print_footer_scripts' ) );
	}

	/**
	 * Action: preserve the `happyforms` GET parameter
	 * across Customize preview sessions.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_controls_init
	 *
	 * @return void
	 */
	public function preview_screen_preserve_query_arg() {
		$this->manager->set_preview_url(
			add_query_arg(
				array( 'happyforms' => '1' ),
				$this->manager->get_preview_url()
			)
		);
	}

	/**
	 * Action: inject the HappyForms nonce in a Customize session.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_refresh_nonces
	 *
	 * @return array
	 */
	public function add_customize_nonce( $nonces ) {
		$nonces['happyforms'] = wp_create_nonce( 'happyforms' );
		return $nonces;
	}

	/**
	 * Action: update a form with data coming from the Customize screen.
	 *
	 * @since  1.0
	 *
	 * @hooked action wp_ajax_happyforms-update-form
	 *
	 * @return void
	 */
	public function ajax_update_form() {
		if ( ! check_ajax_referer( 'happyforms', 'happyforms-nonce', false ) ) {
			status_header( 400 );
			wp_send_json_error( 'bad_nonce' );
		}

		if ( ! current_user_can( 'customize' ) ) {
			status_header( 403 );
			wp_send_json_error( 'customize_not_allowed' );
		}

		if ( ! isset( $_POST['form'] ) || empty( $_POST['form'] ) ) {
			status_header( 403 );
			wp_send_json_error( 'empty form data' );
		}

		$form_data = json_decode( wp_unslash( $_POST['form'] ), true );
		$result = happyforms_get_form_controller()->update( $form_data );
		$data = array();

		if ( is_wp_error( $result ) ) {
			$data['message'] = $result->get_error_message();
			wp_send_json_error( $data );
		}

		$admin_notices = happyforms_get_admin_notices();

		$dismissed_notices = $admin_notices->get_dismissed_notices( get_current_user_id() );
		if ( ! in_array( 'happyforms_form_saved_guide', $dismissed_notices ) ) {
			$notice_name = 'happyforms_form_saved_guide';
			$notice_type = 'custom';
			$notice_dismissible = true;
			$notice_onetime = false;
			$notice_content = '';
			$notice_content .= '<h3>' . __( 'Form saved 👏', 'happyforms' ) . '</h3>';
			$notice_content .= '<p>' . __( 'There are two ways to embed your form. Here goes…', 'happyforms' ) . '</p>';
			$notice_content .= '<h4>' . __( 'Add HappyForms to your page or post', 'happyforms' ) . '</h4>';
			$notice_content .= '<ol><li>' . __( 'In your Edit Post / Edit Page screen, click Add Block.', 'happyforms' ) . '</li><li>' . __( 'Select the HappyForms content block.', 'happyforms' ) . '</li><li>' . __( 'Select a form in the Form dropdown.', 'happyforms' ) . '</li><li> ' . __( 'That\'s it! You\'ll see a basic preview of your form in the editor.', 'happyforms' ) . '</li></ol>';
			$notice_content .= '<h4>' . __( 'Use HappyForms in a widget area', 'happyforms' ) . '</h4>';
			$notice_content .= '<ol>';
			$notice_content .= '<li>' . sprintf( __( 'Head over to Appearance &rarr; <a href="%s">Widgets</a> screen.', 'happyforms' ), get_site_url( NULL, 'wp-admin/widgets.php' ) ) . '</li>';
			$notice_content .= '<li>' . __( 'Drag the HappyForms widget to your sidebar.', 'happyforms' ) .'</li>';
			$notice_content .= '<li>' . __( 'Select a form in the Form dropdown.', 'happyforms' ) . '</li>';
			$notice_content .= '<li>' . __( 'All done!', 'happyforms' ) . '</li>';
			$notice_content .= '</ol>';
			$notice_content .= '<p>' . sprintf( __( 'Still have questions? Head over to our <a href="%s" target="_blank">help guide</a>.', 'happyforms' ), 'https://happyforms.me/help-guide' ) . '</p>';
		} else {
			$notice_name = 'happyforms_form_saved';
			$notice_type = 'success';
			$notice_dismissible = false;
			$notice_onetime = true;
			$notice_content = sprintf(
				__( 'Form saved. You can add this form to any Page, Post and Widget area. Have questions? <a href="%s" target="_blank">Ask for help in our support forums</a>.', 'happyforms' ),
				'https://wordpress.org/support/plugin/happyforms'
			);
		}

		$admin_notices->register(
			$notice_name,
			$notice_content,
			array(
				'type' => $notice_type,
				'dismissible' => $notice_dismissible,
				'screen' => array( 'edit-happyform' ),
				'one-time' => $notice_onetime
			)
		);

		wp_send_json_success( $result );
	}

	/**
	 * Action: return part metadata after it has been added
	 * to the form in a format the Customize preview can
	 * handle.
	 *
	 * @since  1.0
	 *
	 * @hooked action wp_ajax_happyforms-form-part-added
	 *
	 * @return void
	 */
	public function ajax_form_part_add() {
		if ( ! check_ajax_referer( 'happyforms', 'happyforms-nonce', false ) ) {
			status_header( 400 );
			wp_send_json_error( 'bad_nonce' );
		}

		if ( ! current_user_can( 'customize' ) ) {
			status_header( 403 );
			wp_send_json_error( 'customize_not_allowed' );
		}

		if ( ! isset( $_POST['form_id'] ) ||
			! isset( $_POST['part'] ) || empty( $_POST['part'] ) ) {
			status_header( 403 );
			wp_send_json_error( 'Missing data' );
		}

		$form_data = happyforms_get_form_controller()->get( intval( $_POST['form_id'] ) );
		$part_data = $_POST['part'];
		$template = happyforms_get_form_part( $part_data, $form_data );
		$template = stripslashes( $template );

		header( 'Content-type: text/html' );
		echo( $template );
		exit();
	}

	/**
	 * Action: output styles for the Customize screen.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_controls_print_scripts
	 *
	 * @return void
	 */
	public function customize_controls_print_styles() {
		?>
		<style>
		#customize-save-button-wrapper,
		#customize-info,
		#customize-notifications-area {
			display: none !important;
		}
		</style>
		<?php
	}

	/**
	 * Action: enqueue HappyForms styles and scripts
	 * for the Customizer part.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_controls_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts_customizer() {
		wp_enqueue_style(
			'happyforms-customize',
			happyforms_get_plugin_url() . 'core/assets/css/customize.css',
			array( 'wp-color-picker', 'wp-pointer' ), HAPPYFORMS_VERSION
		);

		wp_enqueue_style( 'code-editor' );

		$customize_deps = apply_filters(
			'happyforms_customize_dependencies',
			array(
				'backbone',
				'underscore',
				'jquery',
				'jquery-ui-core',
				'jquery-effects-core',
				'jquery-ui-sortable',
				'jquery-ui-slider',
				'jquery-ui-button',
				'wp-color-picker',
				'wp-pointer',
				'customize-controls',
				'csslint',
				'code-editor'
			)
		);

		wp_register_script(
			'happyforms-customize',
			happyforms_get_plugin_url() . 'inc/assets/js/customize.js',
			$customize_deps, HAPPYFORMS_VERSION, true
		);

		$data = array(
			'form'      => $this->get_current_form(),
			'formParts' => $this->library->get_parts(),
			'baseUrl'   => get_site_url(),
		);

		wp_localize_script( 'happyforms-customize', '_happyFormsSettings', $data );
		wp_enqueue_script( 'happyforms-customize' );

		// Rich text editor
		if ( ! class_exists( '_WP_Editors', false ) ) {
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		}

		wp_enqueue_editor();

		do_action( 'happyforms_customize_enqueue_scripts' );
	}

	/**
	 * Action: return part metadata after it has been added
	 * to the form in a format the Customize preview can
	 * handle.
	 *
	 * @since  1.0
	 *
	 * @hooked action wp_ajax_happyforms-form-part-added
	 *
	 * @return void
	 */
	public function ajax_fetch_partial() {
		if ( ! check_ajax_referer( 'happyforms', 'happyforms-nonce', false ) ) {
			status_header( 400 );
			wp_send_json_error( 'bad_nonce' );
		}

		if ( ! current_user_can( 'customize' ) ) {
			status_header( 403 );
			wp_send_json_error( 'customize_not_allowed' );
		}

		if ( ! isset( $_POST['form'] ) ) {
			status_header( 403 );
			wp_send_json_error( 'Missing data' );
		}

		$form_data = json_decode( wp_unslash( $_POST['form'] ), true );
		$partial_name = sanitize_text_field( $_POST['partial_name'] );
		$template = happyforms_get_form_partial( $partial_name, $form_data );
		$template = stripslashes( $template );

		header( 'Content-type: text/html' );
		echo ( $template );
		exit();
	}

	/**
	 * Action: output Javascript templates for the form editing interface.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_controls_print_footer_scripts
	 *
	 * @return void
	 */
	public function customize_controls_print_footer_scripts() {
		global $wp_customize;

		require_once( happyforms_get_core_folder() . '/templates/customize-header-actions.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-sidebar.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-steps.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-item.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-setup.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-build.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-parts-drawer.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-style.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-email.php' );

		_WP_Editors::print_default_editor_scripts();
	}

}
