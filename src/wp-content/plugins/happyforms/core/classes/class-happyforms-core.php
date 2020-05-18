<?php

class HappyForms_Core {

	/**
	 * The parameter key used to connotate
	 * HappyForms Customize screen sessions.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	private $customize_mode = 'happyforms';

	/**
	 * The form shortcode name.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	private $shortcode = 'happyforms';

	/**
	 * URL of plugin landing page.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	private $landing_page_url = 'https://www.happyforms.me';

	/**
	 * List of forms found on current page.
	 *
	 * @since 1.3
	 *
	 * @var array
	 */
	private $current_forms = array();

	/**
	 * Whether or not frontend styles were loaded.
	 */
	private $frontend_styles = false;

	/**
	 * Whether or not frontend color styles were loaded.
	 */
	private $frontend_color_styles = false;

	/**
	 * Action: initialize admin and frontend logic.
	 *
	 * @since 1.0
	 *
	 * @hooked action plugins_loaded
	 *
	 * @return void
	 */
	public function initialize_plugin() {
		require_once( happyforms_get_core_folder() . '/helpers/helper-misc.php' );
		require_once( happyforms_get_core_folder() . '/helpers/helper-styles.php' );

		if ( is_admin() ) {
			require_once( happyforms_get_core_folder() . '/classes/class-admin-notices.php' );
		}

		require_once( happyforms_get_core_folder() . '/classes/class-tracking.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-controller.php' );
		require_once( happyforms_get_include_folder() . '/classes/class-message-controller.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-email-message.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-part-library.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-styles.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-setup.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-form-email.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-session.php' );
		require_once( happyforms_get_core_folder() . '/classes/class-happyforms-widget.php' );
		require_once( happyforms_get_core_folder() . '/helpers/helper-form-templates.php' );
		require_once( happyforms_get_include_folder() . '/classes/class-migrations.php' );
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		require_once( happyforms_get_core_folder() . '/classes/class-validation-messages.php' );

		// Gutenberg block
		if ( happyforms_is_gutenberg() ) {
			require_once( happyforms_get_core_folder() . '/classes/class-block.php' );
		}

		// Admin hooks
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'current_screen', array( $this, 'admin_screens' ) );
		add_action( 'media_buttons', array( $this, 'insert_editor_buttons' ) );
		add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );

		// Widget
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// Common hooks
		add_shortcode( $this->shortcode, array( $this, 'handle_shortcode' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'wp_print_footer_scripts' ), 0 );
		add_action( 'admin_print_footer_scripts', array( $this, 'wp_print_footer_scripts' ), 0 );
		add_action( 'admin_print_footer_scripts', array( $this, 'print_shortcode_template' ) );

		// Preview scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_preview' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_scripts_preview' ) );

		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
		add_action( 'current_screen', array( $this, 'show_help_tab' ) );
	}

	public function customize_preview_init() {
		require_once( happyforms_get_core_folder() . '/classes/class-admin-notices.php' );

		add_action( 'happyforms_form_before', array( happyforms_get_admin_notices(), 'display_preview_notices' ), 20 );
	}

	/**
	 * Action: initialize Customize screen logic.
	 *
	 * @since 1.0
	 *
	 * @hooked action customize_loaded_components
	 *
	 * @param array $components Array of standard Customize components.
	 *
	 * @return array
	 */
	public function initialize_customize_screen( $components ) {
		/*
		 * See "Resetting the Customizer to a Blank Slate"
		 * https://make.xwp.co/2016/09/11/resetting-the-customizer-to-a-blank-slate/
		 * https://github.com/xwp/wp-customizer-blank-slate
		 */

		// Initialize our customize screen if we're in HappyForms mode.
		if ( ! $this->is_customize_mode() ) {
			return $components;
		}

		require_once( happyforms_get_core_folder() . '/classes/class-wp-customize-form-manager.php' );

		$this->customize = new HappyForms_WP_Customize_Form_Manager();

		// Short-circuit widgets, nav-menus, etc from loading.
		return array();
	}

	/**
	 * Action: register admin menus.
	 *
	 * @since 1.0
	 *
	 * @hooked action admin_menu
	 *
	 * @return void
	 */
	public function admin_menu() {
		$form_controller = happyforms_get_form_controller();

		add_menu_page(
			__( 'HappyForms Index', 'happyforms' ),
			__( 'HappyForms', 'happyforms' ),
			$form_controller->capability,
			'happyforms',
			array( $this, 'happyforms_page_index' ),
			'dashicons-format-status',
			50
		);

		add_submenu_page(
			'happyforms',
			__( 'All Forms', 'happyforms' ),
			__( 'All Forms', 'happyforms' ),
			$form_controller->capability,
			'/edit.php?post_type=happyform'
		);

		add_submenu_page(
			'happyforms',
			__( 'Add New', 'happyforms' ),
			__( 'Add New', 'happyforms' ),
			$form_controller->capability,
			happyforms_get_form_edit_link( 0 )
		);

		add_submenu_page(
			'happyforms',
			__( 'Activity', 'happyforms' ),
			__( 'Activity', 'happyforms' ),
			apply_filters( 'happyforms_responses_page_capabilities', 'manage_options' ),
			apply_filters( 'happyforms_responses_page_url', '#responses' ),
			apply_filters( 'happyforms_responses_page_method', '' )
		);

		add_submenu_page(
			'happyforms',
			__( 'Settings', 'happyforms' ),
			__( 'Settings', 'happyforms' ) . apply_filters( 'happyforms_settings_page_menu_badge', '' ),
			apply_filters( 'happyforms_settings_page_capabilities', 'manage_options' ),
			apply_filters( 'happyforms_settings_page_url', '#settings' ),
			apply_filters( 'happyforms_settings_page_method', '' )
		);

		add_submenu_page(
			'happyforms',
			__( 'Welcome', 'happyforms' ),
			__( 'Welcome', 'happyforms' ),
			'manage_options',
			'happyforms-welcome',
			array( happyforms_get_tracking(), 'settings_page' )
		);

		// Remove first duplicate submenu link
		remove_submenu_page( 'happyforms', 'happyforms' );
	}

	/**
	 * Action: enqueue scripts and styles for the admin.
	 *
	 * @since 1.0
	 *
	 * @hooked action admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style(
			'happyforms-admin',
			happyforms_get_plugin_url() . 'core/assets/css/admin.css',
			array(), HAPPYFORMS_VERSION
		);

		wp_enqueue_style(
			'happyforms-notices',
			happyforms_get_plugin_url() . 'core/assets/css/notice.css',
			array(), HAPPYFORMS_VERSION
		);

		wp_register_script(
			'happyforms-admin',
			happyforms_get_plugin_url() . 'core/assets/js/admin/dashboard.js',
			array( 'jquery-color' ), HAPPYFORMS_VERSION, true
		);

		global $pagenow;

		$data = array(
			'editLink' => admin_url( happyforms_get_form_edit_link( 'ID', 'URL' ) ),
			'shortcode' => happyforms_get_shortcode(),
		);

		if ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) {
			$forms = happyforms_get_form_controller()->get();
			$fields = array( 'post_title' );
			$fields = apply_filters( 'happyforms_dashboard_form_fields', $fields );
			$fields = array_flip( $fields );
			$form_data = array();

			foreach( $forms as $form ) {
				$form_id = $form['ID'];
				$form_data[$form_id] = array_intersect_key( $form, $fields );
			}

			$data['forms'] = $form_data;
			$data = apply_filters( 'happyforms_dashboard_data', $data );
		}

		wp_localize_script( 'happyforms-admin', '_happyFormsAdmin', $data );
		wp_enqueue_script( 'happyforms-admin' );
	}

	/**
	 * Action: include custom admin screens
	 * for the Form and Message post types.
	 *
	 * @since 1.0
	 *
	 * @hooked action current_screen
	 *
	 * @return void
	 */
	public function admin_screens() {
		global $pagenow;

		$form_post_type = happyforms_get_form_controller()->post_type;
		$current_post_type = get_current_screen()->post_type;

		if ( in_array( $pagenow, array( 'edit.php', 'post.php' ) )
			&& ( $current_post_type === $form_post_type ) ) {

			require_once( happyforms_get_core_folder() . '/classes/class-form-admin.php' );
		}
	}

	/**
	 * Get basic info about the form being currently edited.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	private function get_form_data_array() {
		$forms = happyforms_get_form_controller()->get();
		$form_data = array();

		foreach ( $forms as $form ) {
			array_push( $form_data, array( 'id' => $form['ID'], 'title' => $form['post_title'] ) );
		}

		return $form_data;
	}

	/**
	 * Return whether or not we're running
	 * the Customize screen in HappyForms mode.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	private function is_customize_mode() {
		return (
			isset( $_REQUEST['happyforms'] )
			&& ! empty( $_REQUEST['happyforms'] )
			&& isset( $_REQUEST['form_id'] )
		);
	}

	/**
	 * Filter: register the form dropdown button
	 * for he post content editor toolbar.
	 *
	 * @since 1.0
	 *
	 * @hooked filter mce_buttons
	 *
	 * @param array $buttons The currently registered buttons.
	 *
	 * @return array
	 */
	public function tinymce_register_button( $buttons ) {
		$buttons[] = 'happyforms_form_picker';

		return $buttons;
	}

	/**
	 * Enqueue a form to load assets for.
	 *
	 * @since 1.0
	 *
	 * @param array $form The form to enqueue.
	 *
	 * @return void
	 */
	public function enqueue_form( $form ) {
		$this->current_forms[$form['ID']] = $form;
	}

	/**
	 * Render the HappyForms shortcode.
	 *
	 * @since 1.0
	 *
	 * @param array $attrs The shortcode attributes.
	 *
	 * @return string
	 */
	public function handle_shortcode( $attrs ) {
		if ( ! isset( $attrs['id'] ) ) {
			return;
		}

		$form_id = intval( $attrs['id'] );
		$form_controller = happyforms_get_form_controller();
		$form = $form_controller->get( $form_id );

		if ( empty( $form ) ) {
			return '';
		}

		if ( happyforms_get_form_property( $form, 'modal' ) ) {
			return '';
		}

		$output = $form_controller->render( $form, true );
		$this->enqueue_form( $form );

		return $output;
	}

	/**
	 * Action: output scripts and styles for the forms
	 * embedded into the current post.
	 *
	 * @since 1.0
	 *
	 * @hooked action wp_head
	 *
	 * @return void
	 */
	public function wp_head() {
		?>
		<!-- HappyForms global container -->
		<script type="text/javascript">HappyForms = {};</script>
		<!-- End of HappyForms global container -->
		<?php
	}

	/**
	 * Filter: Add HappyForms button markup to a markup above content editor, next to
	 * Add Media button.
	 *
	 * @since 1.1.0.
	 *
	 * @hooked filter media_buttons
	 *
	 * @param string $editor_id Editor ID.
	 *
	 * @return void
	 */
	public function insert_editor_buttons( $editor_id ) {
		global $pagenow;

		if ( 'post.php' !== $pagenow && 'post-new.php' !== $pagenow ) {
			return;
		}

		$button_html = '<a href="#" class="button happyforms-editor-button" data-title="' . __( 'Insert HappyForm', 'happyforms' ) . '"><span class="dashicons dashicons-format-status"></span><span>'. __( 'Add HappyForms', 'happyforms' ) .'</span></a>';

		add_action( 'admin_footer', array( $this, 'output_happyforms_modal' ) );

		echo ' ' . $button_html;
	}

	public function mce_external_plugins( $plugins ) {
		$plugins['happyforms_shortcode'] = happyforms_get_plugin_url() . 'core/assets/js/admin/shortcode.js';

		return $plugins;
	}

	/**
	 * Render HappyForms dialog in the footer of edit post / page screen. Also
	 * prints a script block for adding shortcode to visual editor.
	 *
	 * @since 1.3.0.
	 *
	 * @hooked action admin_footer
	 *
	 * @return void
	 */
	public function output_happyforms_modal() {
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

		require_once( happyforms_get_core_folder() . '/templates/admin-form-modal.php' );
	}

	public function print_frontend_styles( $form ) {
		$output = apply_filters( 'happyforms_enqueue_style', true );

		if ( $output && ! $this->frontend_styles ) {
			$this->frontend_styles = true;
			$url = happyforms_get_frontend_stylesheet_url( 'layout.css' );
			?>
			<link rel="stylesheet" property="stylesheet" href="<?php echo $url; ?>" />
			<?php
		}

		if ( $output && ! $this->frontend_color_styles ) {
			$this->frontend_color_styles = true;
			$color_url = happyforms_get_frontend_stylesheet_url( 'color.css' );
			?>
			<link rel="stylesheet" property="stylesheet" href="<?php echo $color_url; ?>" />
			<?php
		}

		do_action( 'happyforms_print_frontend_styles', $form );
	}

	/**
	 * Action: enqueue scripts and styles
	 * for the frontend part of the plugin.
	 *
	 * @since 1.0
	 *
	 * @hooked action wp_print_footer_scripts
	 *
	 * @return void
	 */
	public function wp_print_footer_scripts() {
		if ( happyforms_is_preview() ) {
			$form_controller = happyforms_get_form_controller();
			$form = $form_controller->get( get_the_ID() );
			$this->current_forms[] = $form;
		}

		// Return early if no current forms
		// are being displayed.
		if ( empty( $this->current_forms ) ) {
			return;
		}

		if ( is_admin() && happyforms_is_gutenberg() ) {
			return;
		}

		wp_register_script(
			'happyforms-select',
			happyforms_get_plugin_url() . 'core/assets/js/lib/happyforms-select.js',
			array( 'jquery' ), HAPPYFORMS_VERSION, true
		);

		$dependencies = apply_filters(
			'happyforms_frontend_dependencies',
			array( 'jquery' ), $this->current_forms
		);

		wp_enqueue_script(
			'happyforms-frontend',
			happyforms_get_plugin_url() . 'inc/assets/js/frontend.js',
			$dependencies, HAPPYFORMS_VERSION, true
		);

		/**
		 * Output form-specific scripts and styles.
		 *
		 * @since 1.1
		 *
		 * @param array $forms Array of forms found in page.
		 *
		 * @return void
		 */
		do_action( 'happyforms_footer', $this->current_forms );
	}

	public function print_shortcode_template() {
		require_once( happyforms_get_core_folder() . '/templates/admin-shortcode.php' );
	}

	public function enqueue_styles_preview() {
		if ( ! happyforms_is_preview() ) {
			return;
		}

		wp_enqueue_style(
			'happyforms-preview',
			happyforms_get_plugin_url() . 'core/assets/css/preview.css',
			array(), HAPPYFORMS_VERSION
		);
	}

	/**
	 * Action: enqueue HappyForms styles and scripts
	 * for the Customizer preview part.
	 *
	 * @since  1.3
	 *
	 * @hooked action customize_preview_init
	 *
	 * @return void
	 */
	public function enqueue_scripts_preview() {
		if ( ! happyforms_is_preview() ) {
			return;
		}

		$preview_deps = apply_filters(
			'happyforms_preview_dependencies',
			array( 'customize-preview' )
		);

		wp_enqueue_script(
			'happyforms-preview',
			happyforms_get_plugin_url() . 'inc/assets/js/preview.js',
			$preview_deps, HAPPYFORMS_VERSION, true
		);

		wp_localize_script(
			'happyforms-preview',
			'_happyformsPreviewSettings',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			)
		);

		require_once( happyforms_get_core_folder() . '/templates/preview-form-pencil.php' );
	}

	/**
	 * Action: register the HappyForms widget.
	 *
	 * @since 1.0
	 *
	 * @hooked action widgets_init
	 *
	 * @return void
	 */
	public function register_widget() {
		register_widget( 'HappyForms_Widget' );
	}

	public function show_help_tab() {
		$screen = get_current_screen();
		$show = false;

		if ( false !== strpos( $screen->id, 'happyforms_page' ) ) {
			$show = true;
		}

		if ( function_exists( 'happyforms_get_message_controller' ) && happyforms_get_message_controller()->post_type === $screen->post_type ) {
			$show = true;
		}

		if ( happyforms_get_form_controller()->post_type === $screen->post_type ) {
			$show = true;
		}

		if ( ! $show ) {
			return;
		}

		$screen->add_help_tab( array(
			'id' => 'happyforms_help_tab_overview',
			'title' => __( 'Overview', 'happyforms' ),
			'callback' => array( $this, 'help_tab_overview_contents' )
		) );

		$sidebar_content = $this->get_help_tab_sidebar_content();
		$screen->set_help_sidebar( $sidebar_content );
	}

	public function help_tab_overview_contents() {
		?>
		<p><?php _e( 'Hey 👋 Welcome to your HappyForms Dashboard!', 'happyforms' ); ?></p>

		<p><?php printf(
			__( 'Are you looking for help? Well, we’ve swept the nacho crumbs from our keyboards, refilled our ginger beers and are ready to reply with answers! So, go on, email %s.', 'happyforms' ),
			'<a href="mailto:support@thethemefoundry.com">support@thethemefoundry.com</a>'
		); ?></p>
		<?php
	}

	public function get_help_tab_sidebar_content() {
		ob_start();
		?>
		<p><strong><?php _e( 'For more help', 'happyforms' ); ?>:</strong></p>

		<p><a href="https://happyforms.me/help-guide"><?php _e( 'Help guide', 'happyforms' ); ?></a></p>
		<?php
		$content = ob_get_clean();

		return $content;
	}

}
