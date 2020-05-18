<?php

class HappyForms_Tracking {

	/**
	 * The singleton instance.
	 *
	 * @since 1.0
	 *
	 * @var HappyForms_Tracking
	 */
	private static $instance;

	/**
	 * The name of the tracking option entry.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public $activation_option = 'happyforms-tracking';

	/**
	 * The action of the welcome form.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public $monitor_action = 'https://emailoctopus.com/lists/a58bf658-425e-11ea-be00-06b4694bee2a/members/embedded/1.3/add';

	/**
	 * The name of the email field in the welcome form.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public $monitor_email_field = 'field_0';

	/**
	 * The name of the status field in the welcome form.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public $monitor_status_field = 'cm-f-dyikmik';

	/**
	 * The singleton constructor.
	 *
	 * @since 1.0
	 *
	 * @return HappyForms_Tracking
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
	 * @since 1.0
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'admin_init', array( $this, 'redirect_to_settings_page' ) );
		add_action( 'wp_ajax_happyforms_update_tracking', array( $this, 'ajax_update_tracking' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_footer', array( $this, 'print_templates' ) );
	}

	/**
	 * Get the tracking status.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_status() {
		$status = get_option( $this->activation_option, array(
			'status' => 0,
			'email' => '',
		) );

		return $status;
	}

	/**
	 * Update the tracking status.
	 *
	 * @since 1.0
	 *
	 * @param string $status The status counter.
	 * @param string $email  The user email.
	 *
	 * @return void
	 */
	public function update_status( $status, $email = '' ) {
		update_option( $this->activation_option, array(
			'status' => $status,
			'email' => $email,
		) );
	}

	/**
	 * Action: handle the ajax request for the update
	 * of tracking status
	 *
	 * @since 1.0
	 *
	 * @hooked action wp_ajax_happyforms_update_tracking
	 *
	 * @return void
	 */
	public function ajax_update_tracking() {
		if ( isset( $_REQUEST['status'] ) ) {
			$current_status = $this->get_status();
			$status = $_REQUEST['status'];
			$email = isset( $_REQUEST['email'] ) ? $_REQUEST['email'] : $current_status['email'];

			$this->update_status( $status, $email );
		}

		wp_die( 1 );
	}

	/**
	 * Action: redirect to the welcome page, if it's first run
	 * and the user hasn't skipped this step yet.
	 *
	 * @since 1.0
	 *
	 * @hooked action admin_init
	 *
	 * @return void
	 */
	public function redirect_to_settings_page() {
		$status = get_option( $this->activation_option );
		$show_welcome_page = apply_filters( 'happyforms_show_welcome_page', true );

		if ( $show_welcome_page && ( 1 === intval( $status['status'] ) ) ) {
			$url = admin_url( 'admin.php?page=happyforms-welcome' );

			$this->update_status( 2 );
			wp_safe_redirect( $url );

			exit;
		}
	}

	/**
	 * Output the welcome page.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Sorry, you are not allowed to access this page.', 'happyforms' ) );
		}

		require_once( happyforms_get_core_folder() . '/templates/admin-tracking.php' );
	}

	/**
	 * Action: enqueue styles and scripts for the welcome page.
	 *
	 * @since 1.0
	 *
	 * @hooked action admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function admin_scripts() {
		$current_screen = get_current_screen();

		if ( 'happyforms_page_happyforms-welcome' === $current_screen->id ) {
			wp_enqueue_script(
				'happyforms-tracking',
				happyforms_get_plugin_url() . 'core/assets/js/welcome.js',
				array(), HAPPYFORMS_VERSION, true
			);
		}
	}

	/**
	 * Output Javascript template partials used in the welcome page.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function print_template( $template ) {
		if ( 'success' === $template ): ?>
		<h2><?php _e( 'Thank you!', 'happyforms' ); ?></h2>
		<p class="about-description"><?php _e( 'Now let\'s go enjoy HappyForms.', 'happyforms' ); ?></p>
		<p><?php _e( 'You\'ve set up notifications and helped us to improve HappyForms. You\'re ready to get started with your first form.', 'happyforms' ); ?></p>
		<a href="<?php echo happyforms_get_form_edit_link( 0, happyforms_get_all_form_link() ); ?>" class="button button-primary button-hero" id="happyforms-tracking-proceed"><?php _e( 'Create your first form', 'happyforms' ); ?></a>
		<?php elseif ( 'error' === $template ): ?>
		<p class="about-description"><?php _e( 'Aw snap! Something went wrong.', 'happyforms' ); ?></p>
		<p><?php _e( 'Error description', 'happyforms' ); ?></p>
		<?php endif;
	}

	/**
	 * Action: output Javascript templates for the welcome page.
	 *
	 * @since 1.0
	 *
	 * @hooked action admin_footer
	 *
	 * @return void
	 */
	public function print_templates() {
		?>
		<script type="text/template" id="happyforms-tracking-success">
		<?php $this->print_template( 'success' ); ?>
		</script>
		<script type="text/template" id="happyforms-tracking-error">
		<?php $this->print_template( 'error' ); ?>
		</script>
		<?php
	}
}

if ( ! function_exists( 'happyforms_get_tracking' ) ):
/**
 * Get the HappyForms_Tracking class instance.
 *
 * @since 1.0
 *
 * @return HappyForms_Tracking
 */
function happyforms_get_tracking() {
	return HappyForms_Tracking::instance();
}

endif;

/**
 * Initialize the HappyForms_Tracking class immediately.
 */
happyforms_get_tracking();

