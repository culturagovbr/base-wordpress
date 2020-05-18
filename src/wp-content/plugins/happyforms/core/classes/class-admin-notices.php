<?php

class HappyForms_Admin_Notices {

	/**
	 * The singleton instance.
	 *
	 * @since 1.0
	 *
	 * @var HappyForms_Admin_Notices
	 */
	private static $instance;

	/**
	 * The notices registered for the current session.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	private $notices = array();

	/**
	 * The singleton constructor.
	 *
	 * @since 1.0
	 *
	 * @return HappyForms_Admin_Notices
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
		add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
		add_action( 'happyforms_preview_notices', array( $this, 'display_preview_notices' ) );
		add_action( 'happyforms_form_before', array( $this, 'handle_preview_notices' ), 20 );
		add_action( 'wp_ajax_happyforms_hide_notice', array( $this, 'handle_ajax' ) );
	}

	/**
	 * Register a notice to be displayed after the next refresh.
	 *
	 * @since 1.0
	 *
	 * @param int|string $id      The notice ID.
	 * @param string     $message The notice message.
	 * @param array      $args    Configuration data for the notice.
	 * @param WP_User    $user    An optional user to scope this notice to.
	 *
	 * @return void
	 */
	public function register( $id, $message, $args = array(), WP_User $user = null ) {
		$defaults = array(
			'cap' => 'switch_themes',
			'dismissible' => false,
			'screen' => array( 'dashboard' ),
			'type' => 'info',
			'one-time' => false,
		);

		$args = wp_parse_args( $args, $defaults );
		$notice = array_merge( array( 'message' => $message ), $args );
		$this->notices[$id] = $notice;

		$transient_id = $this->get_user_transient_id();
		$user_notices = $this->get_user_notices();
		$user_notices[$id] = $notice;
		set_transient( $transient_id, $user_notices );
	}

	/**
	 * Get the registered notices for the current session.
	 *
	 * @since 1.0
	 *
	 * @param WP_Screen $screen The current screen object.
	 *
	 * @return array
	 */
	private function get_notices( $screen_id = '' ) {
		$notices = array();
		$dismissed = $this->get_dismissed_notices( get_current_user_id() );

		foreach ( $this->notices as $id => $notice ) {
			if ( current_user_can( $notice['cap'] )
				&& in_array( $screen_id, $notice['screen'] )
				&& ! in_array( $id, $dismissed ) ) {
				$notices[$id] = $notice;
			}
		}

		$user_notices = $this->get_user_notices();

		foreach ( $user_notices as $id => $notice ) {
			if ( current_user_can( $notice['cap'] ) && in_array( $screen_id, $notice['screen'] ) && ! in_array( $id, $dismissed ) ) {
				$notices[$id] = $notice;
			}
		}

		if ( ! empty( $user_notices ) ) {
			$transient_id = $this->get_user_transient_id();
			delete_transient( $transient_id );
		}

		return $notices;
	}

	/**
	 * Get the registered notices for the specified user.
	 *
	 * @since 1.0
	 *
	 * @param WP_User $user The user object to fetch notices for.
	 *
	 * @return array
	 */
	private function get_user_notices( WP_User $user = null ) {
		$transient_id = $this->get_user_transient_id();
		$notices = get_transient( $transient_id );
		$notices = false !== $notices ? $notices : array();

		return $notices;
	}

	/**
	 * Action: display the notices registered for the current section.
	 *
	 * @since 1.0
	 *
	 * @hooked action admin_notices
	 *
	 * @return void
	 */
	public function display( $screen_id = '' ) {
		$notices = $this->get_notices( $screen_id );

		foreach ( $notices as $id => $notice ) {
			$type = $notice['type'];

			$default_classes = array( 'notice', 'happyforms-notice' );
			$extra_classes = ( isset( $notice['classes'] ) ) ? $notice['classes'] : array( 'notice-' . $type );

			$classes = array_merge( $default_classes, $extra_classes );

			$message = $notice['message'];
			$dismissible = wp_validate_boolean( $notice['dismissible'] );
			$onetime = wp_validate_boolean( $notice['one-time'] );
			$nonce = ( $dismissible && ! $onetime ) ? ' data-nonce="' . esc_attr( wp_create_nonce( 'happyforms_dismiss_' . $id ) ) . '"' : '';

			if ( $dismissible ) {
				$classes[] = 'is-dismissible';
			}

			$classes_string = implode( ' ', $classes );
			?>
			<div id="happyforms-notice-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes_string ); ?>"<?php echo $nonce; ?>>
				<?php if ( isset( $notice['title'] ) ) : ?>
					<h2 class="happyforms-notice__title"><?php echo $notice['title']; ?></h2>
				<?php endif; ?>
				<?php
				if ( 'custom' !== $type ) :
					echo wpautop( $message );
				else:
					echo $message;
				endif;
				?>
				<?php if ( $dismissible && 'custom' === $type && 'happyforms-preview' === $screen_id ) : ?>
					<a href="#" class="happyforms-dismiss-notice"></a>
				<?php endif; ?>
			</div>
			<?php
		}
	}

	public function display_admin_notices() {
		$screen = get_current_screen();
		return $this->display( $screen->id );
	}

	public function display_preview_notices() {
		$screen_id = 'happyforms-preview';
		return $this->display( $screen_id );
	}

	public function handle_preview_notices( $form ) {
		if ( happyforms_is_preview() ) {
			$user_id = get_current_user_id();
			$notices = $this->get_notices( 'happyforms-preview' );
			$dismissed_notices = $this->get_dismissed_notices( $user_id );

			if ( 0 !== $form['ID'] ) {
				foreach ( $notices as $id => $notice ) {
					if ( 0 === $id ) {
						continue;
					}

					$new_form_notice_id = str_replace( $form['ID'], 0, $id );

					if ( in_array( $new_form_notice_id, $dismissed_notices ) && ! in_array( $id, $dismissed_notices ) ) {
						$dismissed_new_form_notice_id_index = array_search( $new_form_notice_id, $dismissed_notices );
						unset( $dismissed_notices[$dismissed_new_form_notice_id_index] );

						array_push( $dismissed_notices, $id );
					}
				}

				$this->update_dismissed_notices( $user_id, $dismissed_notices );
			}
		}
	}

	/**
	 * Get the transient ID for the specified user.
	 *
	 * @since 1.0
	 *
	 * @param WP_User $user The user to retrieve the transient ID for.
	 *
	 * @return string
	 */
	public function get_user_transient_id( WP_User $user = null ) {
		if ( is_null( $user ) ) {
			$user = wp_get_current_user();
		}

		return 'happyforms_admin_notices_' . md5( $user->user_login );
	}

	/**
	 * Action: handle ajax requests of notice dismissal.
	 *
	 * @since 1.0
	 *
	 * @hooked action wp_ajax_happyforms_hide_notice
	 *
	 * @return void
	 */
	public function handle_ajax() {
		// Only run this during an Ajax request.
		if ( 'wp_ajax_happyforms_hide_notice' !== current_action() ) {
			return;
		}

		// Get POST parameters
		$nid = isset( $_POST['nid'] )   ? sanitize_key( $_POST['nid'] ) : false;
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

		// Check requirements
		if ( ! defined( 'DOING_AJAX' ) ||
			true !== DOING_AJAX ||
			false === $nid ||
			false === $nonce ||
			! wp_verify_nonce( $nonce, 'happyforms_dismiss_' . $nid ) ) {
			// Requirement check failed. Bail.
			wp_die();
		}

		// Get the array of notices that the current user has already dismissed
		$user_id = get_current_user_id();
		$dismissed = $this->get_dismissed_notices( $user_id );

		// Add a new notice to the array
		$dismissed[] = $nid;
		$success = $this->update_dismissed_notices( $user_id, $dismissed );

		// Return a success response.
		if ( $success ) {
			echo 1;
		}
		wp_die();
	}

	/**
	 * Return a list of dismissed notices for the specified user.
	 *
	 * @since 1.0
	 *
	 * @param int|string $user_id The ID of the user who's
	 *                            dismissed the notices.
	 *
	 * @return array
	 */
	public function get_dismissed_notices( $user_id ) {
		$dismissed = get_user_meta( $user_id, 'happyforms-dismissed-notices', true );

		if ( ! is_array( $dismissed ) ) {
			$dismissed = array();
		}

		return $dismissed;
	}

	/**
	 * Update the list of dismissed notices for the specified user.
	 *
	 * @since 1.0
	 *
	 * @param int|string $user_id The ID of the user who's
	 *                            dismissed the notices.
	 * @param array $notices      A list of notices to dismiss.
	 *
	 * @return int|boolean
	 */
	public function update_dismissed_notices( $user_id, array $notices ) {
		return update_user_meta( $user_id, 'happyforms-dismissed-notices', $notices );
	}

}

if ( ! function_exists( 'happyforms_get_admin_notices' ) ):
/**
 * Get the HappyForms_Admin_Notices class instance.
 *
 * @since 1.0
 *
 * @return HappyForms_Admin_Notices
 */
function happyforms_get_admin_notices() {
	return HappyForms_Admin_Notices::instance();
}

endif;

/**
 * Initialize the HappyForms_Admin_Notices class immediately.
 */
happyforms_get_admin_notices();
