<?php

class HappyForms_Form_Controller {

	/**
	 * The singleton instance.
	 *
	 * @since 1.0
	 *
	 * @var HappyForms_Form_Controller
	 */
	private static $instance;

	/**
	 * The form post type slug.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public $post_type = 'happyform';

	/**
	 * Form editing capability.
	 *
	 */
	public $capability = 'happyforms_manage_form';

	/**
	 * The singleton constructor.
	 *
	 * @since 1.0
	 *
	 * @return HappyForms_Form_Controller
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
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'add_role_capabilities' ) );
		add_action( 'wp', array( $this, 'inject_new_form' ) );
		add_filter( 'single_template', array( $this, 'single_template' ), 20 );
		add_action( 'delete_post', array( $this, 'delete_post' ) );

		add_action( 'happyforms_form_before', array( $this, 'render_title' ) );
	}

	/**
	 * Action: register the form custom post type.
	 *
	 * @hooked action init
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name' => __( 'Forms', 'happyforms' ),
			'singular_name' => __( 'Form', 'happyforms' ),
			'add_new' => __( 'Add New', $this->post_type, 'happyforms' ),
			'add_new_item' => __( 'Build form', 'happyforms' ),
			'edit_item' => __( 'Edit form', 'happyforms' ),
			'new_item' => __( 'Build form', 'happyforms' ),
			'view_item' => __( 'View form', 'happyforms' ),
			'view_items' => __( 'View forms', 'happyforms' ),
			'search_items' => __( 'Search Forms', 'happyforms' ),
			'not_found' => __( 'No forms found.', 'happyforms' ),
			'not_found_in_trash' => __( 'No forms found in Trash', 'happyforms' ),
			'all_items' => __( 'All Forms', 'happyforms' ),
			'menu_name' => __( 'All Forms', 'happyforms' ),
		);

		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => is_customize_preview(),
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => false,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'happyform' ),
			'capability_type' => 'page',
			'has_archive' => false,
			'hierarchical' => false,
			'supports' => array( 'author', 'custom-fields' ),
		);

		$args = apply_filters( 'happyforms_happyform_post_type_args', $args );

		register_post_type( $this->post_type, $args );

		$tracking_status = happyforms_get_tracking()->get_status();

		if ( 1 === intval( $tracking_status['status'] ) ) {
			flush_rewrite_rules();
		}
	}

	public function add_role_capabilities() {
		$admin_role = get_role( 'administrator' );
		$admin_role->add_cap( $this->capability );
	}

	/**
	 * Action: inject a virtual HappyForms post object
	 * if we're previewing a new form.
	 *
	 * @since 1.3
	 *
	 * @hooked action template_redirect
	 *
	 * @return void
	 */
	public function inject_new_form() {
		global $wp_query;

		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! isset( $wp_query->query['p'] ) ||
			! isset( $wp_query->query['post_type'] ) ) {
			return;
		}

		$queried_post_type = $wp_query->query['post_type'];
		$queried_post_id = intval( $wp_query->query['p'] );

		if ( $this->post_type !== $queried_post_type || 0 !== $queried_post_id ) {
			return;
		}

		// See https://barn2.co.uk/create-fake-wordpress-post-fly/
		$post = $this->create_virtual();
		$this->inject_virtual_post( $post );
	}

	/**
	 * Filter: filter the template path used for
	 * the Customize screen preview and frontend rendering.
	 *
	 * @since 1.0
	 *
	 * @hooked filter single_template
	 *
	 * @param $single_template The original template path.
	 *
	 * @return string
	 */
	public function single_template( $single_template ) {
		global $post;

		if ( $post->post_type == happyforms_get_form_controller()->post_type ) {
			if ( is_customize_preview() ) {
				$single_template = happyforms_get_core_folder() . '/templates/preview-form-edit.php';
			} else {
				$single_template = happyforms_get_core_folder() . '/templates/single-form.php';
			}
		}

		return $single_template;
	}

	public function get_post_fields() {
		$fields = array(
			'ID' => array(
				'default' => '0',
				'sanitize' => 'intval',
			),
			'post_title' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'post_status' => array(
				'default' => 'publish',
				'sanitize' => 'happyforms_sanitize_post_status',
			),
			'post_type' => array(
				'default' => $this->post_type,
				'sanitize' => 'sanitize_text_field',
			)
		);

		return $fields;
	}

	public function get_meta_fields() {
		global $current_user;

		$fields = array(
			'layout' => array(
				'default' => array(),
			),
			'parts' => array(
				'default' => array(),
			),
		);

		/**
		 * Filter fields stored as form post meta.
		 *
		 * @since 1.3
		 *
		 * @param array $fields Registered post meta fields.
		 *
		 * @return array
		 */
		$fields = apply_filters( 'happyforms_meta_fields', $fields );

		return $fields;
	}

	/**
	 * Get the defaults and sanitization configuration
	 * for the fields of the form post object.
	 *
	 * @since 1.0
	 *
	 * @param string $group An optional subset of fields
	 *                      to retrieve configuration for.
	 *
	 * @return array
	 */
	public function get_fields( $group = '' ) {
		$fields = array();

		switch ( $group ) {
			case 'post':
				$fields = $this->get_post_fields();
				break;
			case 'meta':
				$fields = $this->get_meta_fields();
				break;
			default:
				$fields = array_merge(
					$this->get_post_fields(),
					$this->get_meta_fields()
				);
				break;
		}

		return $fields;
	}

	public function get_field( $field ) {
		$fields = $this->get_fields();

		if ( isset( $fields[$field] ) ) {
			return $fields[$field];
		}

		return null;
	}

	public function get_defaults( $group = '' ) {
		$defaults = wp_list_pluck( $this->get_fields( $group ), 'default' );

		return $defaults;
	}

	public function get_default( $field ) {
		$defaults = $this->get_defaults();

		if ( isset( $defaults[$field] ) ) {
			return $defaults[$field];
		}

		return null;
	}

	public function validate_field( &$value, $key ) {
		$field = $this->get_field( $key );

		if ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
			$callback = $field['sanitize'];
			$value = call_user_func( $callback, $value );
		};
	}

	/**
	 * Validate the form data submitted from the Customize screen.
	 *
	 * @since 1.0
	 *
	 * @param array $post_data The raw input form data.
	 *
	 * @return array
	 */
	public function validate_fields( $post_data = array() ) {
		$defaults = $this->get_defaults();
		$filtered = array_intersect_key( $post_data, $defaults );
		$validated = wp_parse_args( $post_data, $filtered );
		array_walk( $validated, array( $this, 'validate_field' ) );

		return $validated;
	}

	/**
	 * Creates a virtual form post object.
	 *
	 * @since 1.3
	 *
	 * @return WP_Post
	 */
	private function create_virtual() {
		$post_id = 0;
		$defaults = $this->get_defaults();

		$post = new stdClass();
		$post->ID = $post_id;
		$post->post_author = 1;
		$post->post_date = current_time( 'mysql' );
		$post->post_date_gmt = current_time( 'mysql', 1 );
		$post->post_title = $this->get_default( 'post_title' );
		$post->post_content = '';
		$post->post_status = 'publish';
		$post->comment_status = 'closed';
		$post->ping_status = 'closed';
		$post->post_name = '';
		$post->post_type = $this->post_type;
		$post->filter = 'raw';

		$wp_post = new WP_Post( $post );
		wp_cache_add( $post_id, $wp_post, 'posts' );

		return $wp_post;
	}

	/**
	 * Injects a virtual post object
	 * in the current query.
	 *
	 * @since 1.3
	 *
	 * @return WP_Post
	 */
	private function inject_virtual_post( $post ) {
		global $wp, $wp_query;

		$wp_query->post = $post;
		$wp_query->posts = array( $post );
		$wp_query->queried_object = $post;
		$wp_query->queried_object_id = 0;
		$wp_query->found_posts = 1;
		$wp_query->post_count = 1;
		$wp_query->max_num_pages = 1;
		$wp_query->is_page = false;
		$wp_query->is_singular = true;
		$wp_query->is_single = true;
		$wp_query->is_attachment = false;
		$wp_query->is_archive = false;
		$wp_query->is_category = false;
		$wp_query->is_tag = false;
		$wp_query->is_tax = false;
		$wp_query->is_author = false;
		$wp_query->is_date = false;
		$wp_query->is_year = false;
		$wp_query->is_month = false;
		$wp_query->is_day = false;
		$wp_query->is_time = false;
		$wp_query->is_search = false;
		$wp_query->is_feed = false;
		$wp_query->is_comment_feed = false;
		$wp_query->is_trackback = false;
		$wp_query->is_home = false;
		$wp_query->is_embed = false;
		$wp_query->is_404 = false;
		$wp_query->is_paged = false;
		$wp_query->is_admin = false;
		$wp_query->is_preview = false;
		$wp_query->is_robots = false;
		$wp_query->is_posts_page = false;
		$wp_query->is_post_type_archive = false;

		$GLOBALS['wp_query'] = $wp_query;
		$wp->register_globals();
	}

	/**
	 * Create a new form post object.
	 *
	 * @since 1.0
	 *
	 * @return int|string
	 */
	public function create() {
		$defaults = $this->get_defaults( 'post' );
		$meta = $this->get_defaults( 'meta' );
		$meta = happyforms_prefix_meta( $meta );
		$data = array_merge( $defaults, $meta );
		$data = apply_filters( 'happyforms_create_form_data', $data );
		$defaults = array_intersect_key( $data, $defaults );
		$meta = array_intersect_key( $data, $meta );
		unset( $meta['_happyforms_parts'] );

		$post_data = array_merge( $defaults, array(
			'meta_input' => $meta
		) );

		$result = wp_insert_post( wp_slash( $post_data ), true );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$result = get_post( $result );

		return $result;
	}

	/**
	 * Get a list of forms.
	 *
	 * @since 1.0
	 *
	 * @param array   $post_ids A list of form IDs to fetch.
	 * @param boolean $only_id  Whether or not to limit the
	 *                          results to the ID field.
	 *
	 * @return array
	 */
	public function do_get( $post_ids = array(), $only_id = false ) {
		$query_params = array(
			'post_type'   => happyforms_get_form_controller()->post_type,
			'post_status' => array( 'publish', 'archive', 'trash' ),
			'posts_per_page' => -1,
		);

		$query_params['post__in'] = is_array( $post_ids ) ? $post_ids : array( $post_ids );

		if ( true === $only_id ) {
			$query_params['fields'] = 'ids';
		}

		if ( 0 !== $post_ids ) {
			$forms = get_posts( $query_params );
		} else {
			$forms = array( $this->create_virtual() );
		}

		if ( true === $only_id ) {
			return $forms;
		}

		$form_entries = array();

		foreach ( $forms as $form ) {
			$form_entries[] = $this->to_array( $form );
		}

		if ( ! is_array( $post_ids ) ) {
			if ( count( $form_entries ) > 0 ) {
				return $form_entries[0];
			} else {
				return false;
			}
		}

		return $form_entries;
	}

	public function get( $post_ids = array(), $only_id = false ) {
		$args = md5( serialize( func_get_args() ) );
		$key = "_happyforms_cache_forms_get_{$args}";
		$found = false;
		$result = wp_cache_get( $key, '', false, $found );

		if ( false === $found ) {
			$result = $this->do_get( $post_ids, $only_id );
			wp_cache_set( $key, $result );
		}

		return $result;
	}

	/**
	 * Turn a form post object into an array.
	 *
	 * @param WP_Post $form The form post object.
	 *
	 * @return array
	 */
	public function to_array( $form ) {
		$form_array = $form->to_array();

		$defaults = $this->get_defaults( 'meta' );
		$meta = happyforms_unprefix_meta( get_post_meta( $form->ID ) );
		$form_array = array_merge( $form_array, wp_parse_args( $meta, $defaults ) );
		$form_array['layout'] = isset( $form_array['layout'] ) ? $form_array['layout'] : array();
		$form_array['parts'] = array();

		foreach ( $form_array['layout'] as $p => $part_id ) {
			$part = $form_array[$part_id];
			$part_class = happyforms_get_part_library()->get_part( $part['type'] );
			if ( $part_class ) {
				$form_array['parts'][] = wp_parse_args( $part, $part_class->get_customize_defaults() );
			}
			unset( $form_array[$part_id] );
		}

		$form_array = apply_filters( 'happyforms_get_form_data', $form_array );

		return $form_array;
	}

	/**
	 * Update a form post object.
	 *
	 * @since 1.0
	 *
	 * @param array $form_data The raw input form data.
	 *
	 * @return array
	 */
	public function update( $form_data = array() ) {
		if ( empty( $form_data['post_title'] ) ) {
			$form_data['post_title'] = __( '(no title)', 'happyforms' );
		}

		$validated_data = $this->validate_fields( $form_data );

		if ( isset( $validated_data['ID'] ) && 0 === $validated_data['ID'] ) {
			$form = $this->create();
			$validated_data['ID'] = $form->ID;
		}

		$post_data = array_intersect_key( $validated_data, $this->get_defaults( 'post' ) );
		$meta_data = array_intersect_key( $validated_data, $this->get_defaults( 'meta' ) );
		$meta_data = happyforms_prefix_meta( $meta_data );

		// Flatten data to make it filterable
		$update_data = array_merge( $post_data, $meta_data );
		$update_data = apply_filters( 'happyforms_update_form_data', $update_data );

		// Rebuild update array format
		$post_data = array_intersect_key( $update_data, $post_data);
		$meta_data = array_intersect_key( $update_data, $meta_data);
		unset( $meta_data['_happyforms_parts'] );

		$update_data = array_merge( $post_data, array(
			'meta_input' => $meta_data
		) );

		$result = wp_update_post( $update_data, true );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Update parts
		if ( isset( $form_data['parts'] ) ) {
			$part_layout = array();
			$parts_data = $form_data['parts'];
			$library = happyforms_get_part_library();

			foreach ( $parts_data as $part_data ) {
				$validated_part = $library->validate_part( $part_data );
				$validated_part = apply_filters( 'happyforms_validate_part', $validated_part );

				if ( ! is_wp_error( $validated_part ) ) {
					$part_id = $part_data['id'];
					$part_layout[] = $part_id;
					happyforms_update_meta( $result, $part_id, $validated_part );
				}
			}

			happyforms_update_meta( $result, 'layout', $part_layout );
		}

		// Cleanup stale parts
		$part_layout = happyforms_get_meta( $result, 'layout', true );
		$form_meta = happyforms_unprefix_meta( get_post_meta( $result ) );
		$form_meta = array_diff_key( $form_meta, $this->get_meta_fields() );
		$stale_parts = array_diff_key( $form_meta, array_flip( $part_layout ) );
		$stale_parts = happyforms_prefix_meta( $stale_parts );
		$stale_parts = array_keys( $stale_parts );

		foreach ( $stale_parts as $part_meta ) {
			delete_post_meta( $result, $part_meta );
		}

		// Cleanup stale parts array meta
		delete_post_meta( $result, '_happyforms_parts' );

		$result = $this->to_array( get_post( $result ) );

		do_action( 'happyforms_form_updated', $result );

		return $result;
	}

	/**
	 * Duplicate a form post object.
	 *
	 * @since 1.0
	 *
	 * @param array $form The form data to be duplicated.
	 *
	 * @return bool|int The ID of the duplicated form object.
	 */
	public function duplicate( $form ) {
		$duplicate = array_intersect_key( $form->to_array(), array_flip( array(
			'post_type', 'post_status',
		) ) );

		$duplicate['post_title'] = trim( $form->post_title . __( ' Copy', 'happyforms' ) );
		$duplicate_id = wp_insert_post( $duplicate );

		if ( ! is_wp_error( $duplicate_id ) ) {
			$form_meta = get_post_meta( $form->ID );
			$form_meta = array_map( 'reset', $form_meta );
			$form_meta = array_map( 'maybe_unserialize', $form_meta );

			foreach ( $form_meta as $key => $value ) {
				add_post_meta( $duplicate_id, $key, $value );
			}
		}

		return $duplicate_id;
	}

	/**
	 * Delete a form post object.
	 *
	 * @since 1.0
	 *
	 * @param int|string $form_id The ID of the form object.
	 *
	 * @return boolean|WP_Post
	 */
	public function delete( $form_id ) {
		$result = wp_delete_post( $form_id, true );

		return $result;
	}

	/**
	 * Action: remove form messages when a form is removed.
	 *
	 * @since 1.0
	 *
	 * @hooked action delete_post
	 *
	 * @param int|string $post_id The ID of the form object.
	 *
	 * @return void
	 */
	public function delete_post( $post_id ) {
		$post = get_post( $post_id );

		if ( $this->post_type !== $post->post_type ) {
			return;
		}

		do_action( 'happyforms_form_deleted', $post_id );
	}

	public function get_latest() {
		$forms = get_posts( "post_type={$this->post_type}&numberposts=1" );
		$form_id = $forms[0]->ID;
		$form = $this->get( $form_id );

		return $form;
	}

	public function get_parts_by_type( $form, $type = '' ) {
		$parts = array_filter( $form['parts'], function( $part ) use( $type ) {
			return $part['type'] === $type;
		} );

		$parts = array_values( $parts );

		return $parts;
	}

	/**
	 * Return the first part with the given type found in a form.
	 *
	 * @since 1.0
	 *
	 * @param array  $form_data The data of the form the part belongs to.
	 * @param string $type      The type of the part.
	 *
	 * @return boolean|array
	 */
	public function get_first_part_by_type( $form_data, $type = '' ) {
		$part = false;

		foreach( $form_data['parts'] as $_part ) {
			if ( $type === $_part['type'] ) {
				$part = $_part;
				break;
			}
		}

		$part = apply_filters( 'happyforms_get_first_part_by_type_' . $type, $part, $form_data );

		return $part;
	}

	public function get_part_by_id( $form_data, $part_id ) {
		$part_ids = wp_list_pluck( $form_data['parts'], 'id' );
		$parts = array_combine( $part_ids, $form_data['parts'] );

		if ( isset( $parts[$part_id] ) ) {
			return $parts[$part_id];
		}

		return false;
	}

	/**
	 * Get whether or not the given form data has spam prevention on.
	 *
	 * @since 1.0
	 *
	 * @param array $form_data The form data.
	 *
	 * @return int
	 */
	public function has_spam_protection( $form_data ) {
		return $form_data['spam_prevention'];
	}

	/**
	 * Get form-wide submission notice definitions.
	 *
	 * @since 1.0
	 *
	 * @param array $form_data The form data.
	 *
	 * @return array
	 */
	public function get_message_definitions( $form_data ) {
		return array(
			'form_error' => array(
				'type' => 'error-submission',
				'message' => html_entity_decode( $form_data['error_message'] ),
			),
			'form_success' => array(
				'type' => 'success',
				'message' => html_entity_decode( $form_data['confirmation_message'] ),
			),
		);
	}

	/**
	 * Get the HTML string of a rendered form.
	 *
	 * @since 1.0
	 *
	 * @param array $form The form data.
	 *
	 * @return string
	 */
	public function render( $form = array(), $render_styles = false ) {
		$form_markup = '';

		if ( empty( $form ) ) {
			return $form_markup;
		}

		ob_start();

		if ( $render_styles ) {
			happyforms_the_form_styles( $form );
		}

		happyforms_additional_css( $form );

		$template_path = happyforms_get_core_folder() . '/templates/single-form.php';
		$template_path = apply_filters( 'happyforms_form_template_path', $template_path, $form );
		require( $template_path );
		$form_markup = ob_get_clean();

		return $form_markup;
	}

	public function render_title( $form ) {
		do_action( 'happyforms_before_title', $form );
		happyforms_the_form_title( $form );
		do_action( 'happyforms_after_title', $form );
	}

	public function get_default_steps( $form ) {
		$steps = array(
			1000 => 'submit',
		);

		return $steps;
	}

}

if ( ! function_exists( 'happyforms_get_form_controller' ) ):
/**
 * Get the HappyForms_Form_Controller class instance.
 *
 * @since 1.0
 *
 * @return HappyForms_Form_Controller
 */
function happyforms_get_form_controller() {
	return HappyForms_Form_Controller::instance();
}

endif;

/**
 * Initialize the HappyForms_Form_Controller class immediately.
 */
happyforms_get_form_controller();
