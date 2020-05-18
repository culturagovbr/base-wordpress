<?php

class HappyForms_Migrations {

	/**
	 * The singleton instance.
	 *
	 * @var HappyForms_Migrations
	 */
	private static $instance;

	private $migrations = array();

	/**
	 * The name of the version option entry.
	 *
	 * @var string
	 */
	public $option = 'happyforms-data-version';

	/**
	 * The singleton constructor.
	 *
	 * @return HappyForms_Migrations
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		add_action( 'init', array( $this, 'add_migrations' ) );
	}

	public function add_migrations() {
		$this->add_migration( '1.0', array( $this, 'migrate_1_0' ) );
		$this->add_migration( '1.1', array( $this, 'migrate_1_1' ) );

		$this->migrate();
	}

	public function get_current_version() {
		$version = get_option( $this->option, '0' );

		return $version;
	}

	public function update_current_version( $version = '0' ) {
		update_option( $this->option, $version );
	}

	public function add_migration( $version, $callback ) {
		$this->migrations[$version] =
			isset( $this->migrations[$version] ) ?
			$this->migrations[$version] :
			array();

		$this->migrations[$version][] = $callback;
	}

	public function migrate() {
		$current_version = $this->get_current_version();

		uksort( $this->migrations, 'version_compare' );

		foreach( $this->migrations as $version => $migrations ) {
			if ( version_compare( $version, $current_version, '>' ) ) {
				foreach( $migrations as $callback ) {
					if ( is_callable( $callback ) ) {
						call_user_func( $callback, $version, $current_version );
					}
				}
			}

			$current_version = $version;
		}

		$this->update_current_version( $current_version );
	}

	public function migrate_1_0( $version, $current_version ) {
		global $wpdb;

		$form_controller = happyforms_get_form_controller();
		$forms = $form_controller->get();

		// Migrate forms
		foreach( $forms as $form ) {
			$form_id = $form['ID'];
			$fields = array_keys( $form_controller->get_meta_fields() );

			if ( 0 === count( $fields ) ) {
				continue;
			}

			$fields = array_merge( $fields, $form['layout'] );
			$fields = '(\'' . implode( '\', \'', $fields ) . '\')';

			$sql = "
				UPDATE $wpdb->postmeta meta JOIN $wpdb->posts posts
				ON meta.post_id = posts.ID
				SET meta.meta_key = CONCAT('_happyforms_', meta.meta_key)
				WHERE posts.ID = $form_id
				AND meta.meta_key IN $fields
				";

			$wpdb->query( $sql );
		}
	}

	public function migrate_1_1( $version, $current_version ) {
		$form_controller = happyforms_get_form_controller();
		$forms = $form_controller->get();

		foreach ( $forms as $form ) {
			if ( ! empty( $form['redirect_url'] ) && ! isset( $form['redirect_on_complete'] ) ) {
				happyforms_update_meta( $form['ID'], 'redirect_on_complete', 1 );
			}

			if ( ! empty( $form['html_id'] ) && ! isset( $form['use_html_id'] ) ) {
				happyforms_update_meta( $form['ID'], 'use_html_id', 1 );
			}
		}
	}

}

if ( ! function_exists( 'happyforms_get_migrations' ) ):
/**
 * Get the HappyForms_Migrations class instance.
 *
 * @return HappyForms_Migrations
 */
function happyforms_get_migrations() {
	return HappyForms_Migrations::instance();
}

endif;

/**
 * Initialize the HappyForms_Migrations class immediately.
 */
happyforms_get_migrations();
