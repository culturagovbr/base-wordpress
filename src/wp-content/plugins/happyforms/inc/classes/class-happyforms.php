<?php
class HappyForms extends HappyForms_Core {

	public $default_notice;
	public $action_archive = 'archive';

	public function initialize_plugin() {
		parent::initialize_plugin();

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'happyforms_do_setup_control', array( $this, 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_email_control', array( $this, 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_style_control', array( $this, 'do_control' ), 10, 3 );
		add_filter( 'happyforms_setup_controls', array( $this, 'add_dummy_setup_controls' ) );
		add_filter( 'happyforms_email_controls', array( $this, 'add_dummy_email_controls' ) );
		add_filter( 'happyforms_style_controls', array( $this, 'add_dummy_style_controls' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'print_upgrade_modals' ) );
		add_action( 'parse_request', array( $this, 'parse_archive_request' ) );
		add_action( 'init', array( $this, 'display_notices' ) );

		$this->register_dummy_parts();
		$this->add_setup_logic_upgrade_links();
	}

	public function register_dummy_parts() {
		$part_library = happyforms_get_part_library();

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-website-url-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_WebsiteUrl_Dummy', 3 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-attachment-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Attachment_Dummy', 6 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-table-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Table_Dummy', 7 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-poll-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Poll_Dummy', 10 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-phone-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Phone_Dummy', 11 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-date-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Date_Dummy', 12 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-page-break-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_PageBreak_Dummy', 13 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-address-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Address_Dummy', 14 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-scale-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Scale_Dummy', 15 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-rich-text-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_RichText_Dummy', 16 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-legal-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Legal_Dummy', 17 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-signature-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Signature_Dummy', 18 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-rating-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Rating_Dummy', 19 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-narrative-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Narrative_Dummy', 20 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-placeholder-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Placeholder_Dummy', 21 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-mailchimp-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_OptIn_Dummy', 22 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-payments-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Payments_Dummy', 23 );
	}

	public function add_dummy_setup_controls( $controls ) {
		$controls[1450] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'shuffle_parts',
			'label' => __( 'Shuffle parts', 'happyforms' ),
			'tooltip' => __( 'Shuffle the order of all form parts to avoid biases in your responses.', 'happyforms' ),
		);

		$controls[1500] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'captcha',
			'label' => __( 'Use reCAPTCHA', 'happyforms' ),
			'tooltip' => __( 'Protect your form against bots using your Google ReCaptcha credentials.', 'happyforms' ),
		);

		$controls[1550] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'require_password',
			'label' => __( 'Require password', 'happyforms' ),
			'tooltip' => __( 'Only users with password will be able to view and submit the form.', 'happyforms' ),
		);

		$controls[1590] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'open_in_overlay_window',
			'label' => __( 'Open in overlay window', 'happyforms' ),
			'tooltip' => __( 'Generate a link that can be clicked to open an overlay window for this form.', 'happyforms' ),
		);

		$controls[1591] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'save_meta_data',
			'label' => __( 'Save user\'s meta data', 'happyforms' ),
			'tooltip' => __( 'User meta data like IP address, browser language, local time, and platform will be saved with each submission when checked.', 'happyforms' ),
		);

		$controls[1660] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'save_abandoned_responses',
			'label' => __( 'Save abandoned forms', 'happyforms' ),
			'tooltip' => __( 'Keep incomplete users responses stored in your WordPress database.', 'happyforms' ),
		);

		$controls[1661] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'save_and_resume_submissions',
			'label' => __( 'Allow save and resume', 'happyforms' ),
			'tooltip' => __( 'Let users temporarily save their incomplete response and continue filling out the form at a later time.', 'happyforms' ),
		);

		$controls[1691] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'unique_id',
			'label' => __( 'Add submission identifier', 'happyforms' ),
			'tooltip' => __( 'Tag responses with a unique, incremental identifier.', 'happyforms' ),
		);

		$controls[1800] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'preview_before_submit',
			'label' => __( 'Preview before submit', 'happyforms' ),
			'tooltip' => __( 'Let your users review their submission before confirming it.', 'happyforms' ),
		);

		$controls[1900] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'disable_submit_until_valid',
			'label' => __( 'Disable submit until valid', 'happyforms' ),
			'tooltip' => __( 'Reduce the opacity of the submit button until all required form parts are valid.', 'happyforms' )
		);

		$controls[2300] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'limit_responses',
			'label' => __( 'Limit submissions', 'happyforms' ),
			'tooltip' => __( 'Set limit on number of allowed form submission in general or per user.', 'happyforms' ),
		);

		$controls[3000] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'schedule_visibility',
			'label' => __( 'Schedule visibility', 'happyforms' ),
			'tooltip' => __( 'Show or hide this form during a chosen time and day. Go to Settings > Timezone to set your city offset.', 'happyforms' ),
		);

		return $controls;
	}

	public function add_dummy_email_controls( $controls ) {
		$controls[450] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'email_mark_and_reply',
			'label' => __( 'Include reply link', 'happyforms' ),
			'tooltip' => __( 'Reply to your users and mark their submission as read in one click.', 'happyforms' ),
		);

		$controls[453] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'alert_email_include_referral_url',
			'label' => __( 'Include referral link', 'happyforms' ),
			'tooltip' => __( 'Include the page link your form was submitted from.', 'happyforms' ),
		);

		$controls[454] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'attach_pdf_submission',
			'label' => __( 'Attach .pdf', 'happyforms' ),
			'tooltip' => __( 'Attach a PDF to your submission alert.', 'happyforms-upgrade' ),
		);

		$controls[819] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'attach_pdf',
			'label' => __( 'Attach .pdf', 'happyforms' ),
			'tooltip' => __( 'Attach a PDF to the recipient\'s confirmation email.', 'happyforms' ),
		);

		$controls[1660] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'abandoned_resume_send_alert_email',
			'label' => __( 'Send abandonment email', 'happyforms' ),
			'tooltip' => __( 'Notify users when they abandon your form before submitting it.', 'happyforms' ),
		);

		return $controls;
	}

	public function add_dummy_style_controls( $controls ) {
		$controls[110] = array(
			'type' => 'checkbox_dummy',
			'dummy_id' => 'use_theme_styles',
			'label' => __( 'Use theme styles', 'happyforms' ),
		);

		return $controls;
	}

	public function do_control( $control, $field, $index ) {
		$type = $control['type'];

		if ( 'checkbox_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/checkbox_dummy.php' );
		}
	}

	public function print_upgrade_modals() {
		require_once( happyforms_get_include_folder() . '/templates/admin/upgrade-modal.php' );
	}

	public function admin_menu() {
		parent::admin_menu();

		$form_controller = happyforms_get_form_controller();

		add_submenu_page(
			'happyforms',
			__( 'HappyForms Upgrade', 'happyforms' ),
			__( 'Upgrade', 'happyforms' ),
			$form_controller->capability,
			'https://happyforms.me/upgrade'
		);
	}

	public function admin_enqueue_scripts() {
		parent::admin_enqueue_scripts();

		wp_enqueue_style(
			'happyforms-free-admin',
			happyforms_get_plugin_url() . 'inc/assets/css/admin.css',
			array( 'thickbox' ), HAPPYFORMS_VERSION
		);

		wp_register_script(
			'happyforms-free-admin',
			happyforms_get_plugin_url() . 'inc/assets/js/admin/dashboard.js',
			array( 'thickbox' ), HAPPYFORMS_VERSION, true
		);

		$has_responses = get_transient( '_happyforms_has_responses' );

		if ( false === $has_responses ) {
			$responses = get_posts(
				array(
					'post_type' => 'happyforms-message'
				)
			);

			if ( ! empty( $responses ) ) {
				$has_responses = 1;

				set_transient( '_happyforms_has_responses', 1 );
			}
		}

		wp_localize_script(
			'happyforms-free-admin',
			'_happyFormsDashboardSettings',
			array(
				'modal_id' => 'happyforms-upgrade-modal'
			)
		);

		wp_enqueue_script( 'happyforms-free-admin' );
	}

	public function parse_archive_request() {
		global $pagenow;

		if ( 'edit.php' !== $pagenow ) {
			return;
		}

		$form_post_type = happyforms_get_form_controller()->post_type;

		if ( ! isset( $_GET['post_type'] ) || $form_post_type !== $_GET['post_type'] ) {
			return;
		}

		if ( ! isset( $_GET[$this->action_archive] ) ) {
			return;
		}

		$form_id = $_GET[$this->action_archive];
		$form_controller = happyforms_get_form_controller();
		$message_controller = happyforms_get_message_controller();
		$form = $form_controller->get( $form_id );

		if ( ! $form ) {
			return;
		}

		$message_controller->export_archive( $form );
	}

	public function display_notices() {
		if ( ! is_admin() ) {
			return;
		}

		$forms = happyforms_get_form_controller()->get();

		if ( 0 === count( $forms ) ) {
			return;
		}

		$this->display_review_notice( $forms );
	}

	public function is_new_user( $forms ) {
		if ( 1 !== count( $forms ) ) {
			return false;
		}

		$form = $forms[0];

		if ( 'Sample Form' === $form['post_title'] ) {
			return true;
		}

		return false;
	}

	public function display_review_notice( $forms ) {
		$last_form_index = count( $forms ) - 1;
		$form = $forms[$last_form_index];
		$form_date = new DateTime( $form['post_date'] );
		$now = new DateTime();
		$difference = $now->diff( $form_date );
		$days = $difference->format( '%a' );

		if ( 3 > intval( $days ) ) {
			return;
		}

		$hours = intval( $days ) * 16;
		$review_link = 'https://wordpress.org/support/plugin/happyforms/reviews/?filter=5#new-post';
		$upgrade_link = 'https://happyforms.me/upgrade';

		happyforms_get_admin_notices()->register(
			'happyforms_leave_a_review',
			sprintf(
				__( '<p>Can we ask a favor?</p><p>You created your first form %s days ago — how time flies! Since then, we’ve answered hundreds of community emails and spent %s hours coding and improving HappyForms.</p><p>As you probably know, plugin reviews are an important way in helping a young business like ours grow.</p><p>If you could please spare one minute for a review, it would put a huge smile on our faces. 😊</p><p><a href="%s" target="_blank" rel="noopener">Leave a review now</a>, or show your support by <a href="%s" target="_blank" class="external">upgrading to a paid plan</a>.</p>', 'happyforms' ),
				$days, $hours, $review_link, $upgrade_link
			),
			array(
				'type' => 'info',
				'screen' => array( 'edit-happyform' ),
				'dismissible' => true,
			)
		);
	}

	public function add_setup_logic_upgrade_links() {
		$control_slugs = array(
			'email_recipient',
			'email_bccs',
			'alert_email_subject',
			'redirect_url'
		);

		foreach ( $control_slugs as $slug ) {
			add_action( "happyforms_setup_control_{$slug}_after", array( $this, 'set_logic_link_template' ) );
		}
	}

	public function set_logic_link_template() {
		$html = '';

		ob_start();
			require( happyforms_get_core_folder() . '/templates/customize-form-setup-logic.php' );
		$html = ob_get_clean();

		echo $html;
	}
}
