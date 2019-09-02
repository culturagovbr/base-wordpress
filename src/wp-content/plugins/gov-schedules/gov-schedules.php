<?php
/**
 * Plugin Name:       Gov Schedules
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       @TODO
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/darciro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (!class_exists('Gov_Schedules')) :

	class Gov_Schedules
	{

		private $screens = array(
			'event',
		);

		private $fields = array(
			array(
				'id' => 'dia-todo',
				'label' => 'Dia todo',
				'type' => 'checkbox',
			),
			array(
				'id' => 'data-de-incio',
				'label' => 'Data de início',
				'type' => 'text',
			),
			array(
				'id' => 'data-de-fim',
				'label' => 'Data de fim',
				'type' => 'text',
			),
			array(
				'id' => 'location',
				'label' => 'Local',
				'type' => 'text',
			),
		);

		public function __construct()
		{
			setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
			date_default_timezone_set('America/Sao_Paulo');

			// require_once dirname( __FILE__ ) . '/inc/options-page.php';
			add_action( 'init', array($this, 'event_cpt' ) );
			add_filter( 'manage_event_posts_columns', array($this, 'add_event_columns' ) );
			add_action( 'manage_posts_custom_column', array($this, 'event_custom_columns' ), 10, 2 );
			add_action( 'wp_enqueue_scripts', array($this, 'register_gs_styles' ) );
			add_action( 'admin_enqueue_scripts', array($this, 'register_gs_admin_styles' ) );
			add_action( 'wp_enqueue_scripts', array($this, 'register_gs_scripts' ) );
			add_action( 'admin_enqueue_scripts', array($this, 'register_gs_admin_scripts' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );
			add_action( 'init', array($this, 'gs_shortcodes' ) );
			add_action( 'wp_ajax_gs_get_week_events', array( $this, 'gs_get_week_events' ) );
			add_action( 'wp_ajax_nopriv_gs_get_week_events', array( $this, 'gs_get_week_events' ) );
			add_action( 'archive_template', array( $this, 'yourplugin_get_custom_archive_template' ) );
		}

		/**
		 * Create a custom post type to manage events
		 *
		 */
		public function event_cpt()
		{
			register_post_type('event', array(
					'labels' => array(
						'name' => 'Agenda',
						'singular_name' => 'Evento',
						'add_new' => 'Novo evento',
						'add_new_item' => 'Novo evento',
					),
					'description' => 'Calendário de Eventos',
					'public' => true,
					'has_archive' => true,
					'taxonomies' => array('event-category'),
					'supports' => array('title', 'editor', 'excerpt'),
					'menu_icon' => 'dashicons-calendar-alt',
					'rewrite' => array(
						'slug' => 'agenda'
					)
				)
			);

			$labels = array(
				'name'                       => 'Categorias do evento',
				'singular_name'              => 'Categoria do evento',
				'search_items'               => 'Buscar por categorias',
				'all_items'                  => 'Todas as categorias',
				'edit_item'                  => 'Editar categoria',
				'update_item'                => 'Atualizar categoria',
				'add_new_item'               => 'Nova categoria',
				'new_item_name'              => 'Nova categoria',
				'menu_name'                  => 'Categorias do evento',
			);

			$args = array(
				'hierarchical'          => true,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'event-category' ),
			);

			register_taxonomy( 'event-category', 'event', $args );
		}

		/**
		 * Add new columns to our custom post type
		 *
		 * @param $columns
		 * @return array
		 */
		public function add_event_columns($columns)
		{
			return array_merge($columns, array(
				'start_date' => 'Data de início',
				'end_date' => 'Data de fim',
				'location' => 'Local'
			));
		}

		/**
		 * Fill custom columns with data
		 *
		 * @param $column
		 * @param $post_id
		 */
		public function event_custom_columns($column, $post_id)
		{
			switch ($column) {
				case 'start_date':
					$date = get_post_meta( $post_id, 'dados_do_evento_data-de-incio', true );
					$raw_date = explode(' ', $date );
					$d = implode('/', array_reverse( explode('-', $raw_date[0]) ) ) . ' ' . $raw_date[1];
					echo $d;
					break;
				case 'end_date':
					$date = get_post_meta( $post_id, 'dados_do_evento_data-de-fim', true );
					$raw_date = explode(' ', $date );
					$d = implode('/', array_reverse( explode('-', $raw_date[0]) ) ) . ' ' . $raw_date[1];
					echo $d;
					break;
				case 'location':
					$location = get_post_meta( $post_id, 'dados_do_evento_location', true );
					echo $location;
					break;
			}
		}

		/**
		 * Register stylesheet for our plugin
		 *
		 */
		public function register_gs_styles()
		{
			wp_register_style('gov-schedules-styles', plugin_dir_url(__FILE__) . 'assets/css/gov-schedules.css');
			wp_enqueue_style('gov-schedules-styles');
		}

		/**
		 * Register stylesheet for our plugin
		 *
		 */
		public function register_gs_admin_styles()
		{
			wp_register_style('jquery-ui-timepicker-addon-styles', plugin_dir_url(__FILE__) . 'assets/css/jquery-ui-timepicker-addon.css');
			wp_enqueue_style('jquery-ui-timepicker-addon-styles');
		}

		/**
		 * Register JS for our plugin
		 *
		 */
		public function register_gs_scripts()
		{
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script('gs-scripts-scripts', plugin_dir_url(__FILE__) . 'assets/js/gs-scripts.js', array('jquery', 'jquery-ui-datepicker'), false, true);
			wp_localize_script( 'gs-scripts-scripts', 'oscar_minc_vars', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'upload_file_nonce' => wp_create_nonce( 'oscar-video' ),
				)
			);
		}

		/**
		 * Register admin JS for our plugin
		 *
		 */
		public function register_gs_admin_scripts()
		{
			wp_enqueue_script('jquery-ui-timepicker-addon', plugin_dir_url(__FILE__) . 'assets/js/jquery-ui-timepicker-addon.js', array('jquery', 'jquery-ui-datepicker', 'jquery-ui-spinner'), false, true);
			wp_enqueue_script('gs-admin-scripts', plugin_dir_url(__FILE__) . 'assets/js/gs-admin-scripts.js', array('jquery-ui-timepicker-addon'), false, true);
		}

		/**
		 * Hooks into WordPress' add_meta_boxes function.
		 * Goes through screens (post types) and adds the meta box.
		 */
		public function add_meta_boxes() {
			foreach ( $this->screens as $screen ) {
				add_meta_box(
					'dados-do-evento',
					__( 'Dados do evento', 'gs' ),
					array( $this, 'add_meta_box_callback' ),
					$screen,
					'normal',
					'default'
				);
			}
		}

		/**
		 * Generates the HTML for the meta box
		 *
		 * @param object $post WordPress post object
		 */
		public function add_meta_box_callback( $post ) {
			wp_nonce_field( 'dados_do_evento_data', 'dados_do_evento_nonce' );
			$this->generate_fields( $post );
		}

		/**
		 * Generates the field's HTML for the meta box.
		 */
		public function generate_fields( $post ) {
			$output = '';
			foreach ( $this->fields as $field ) {
				$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
				$db_value = get_post_meta( $post->ID, 'dados_do_evento_' . $field['id'], true );
				$raw_date = explode(' ', $db_value );
				$db_value = implode('/', array_reverse( explode('-', $raw_date[0]) ) ) . ' ' . $raw_date[1];
				switch ( $field['type'] ) {
					case 'checkbox':
						$input = sprintf(
							'<input %s id="%s" name="%s" type="checkbox" value="1">',
							$db_value === '1' ? 'checked' : '',
							$field['id'],
							$field['id']
						);
						break;
					default:
						if( $field['id'] !== 'location' ){
							$input = sprintf(
								'<input %s id="%s" name="%s" type="%s" value="%s">',
								$field['type'] !== 'color' ? 'class="regular-text datepicker"' : '',
								$field['id'],
								$field['id'],
								$field['type'],
								$db_value
							);
						} else {
							$input = sprintf(
								'<input class="regular-text" id="%s" name="%s" type="%s" value="%s">',
								$field['id'],
								$field['id'],
								$field['type'],
								$db_value
							);
						}

				}
				$output .= $this->row_format( $label, $input );
			}
			echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
		}

		/**
		 * Generates the HTML for table rows.
		 */
		public function row_format( $label, $input ) {
			return sprintf(
				'<tr><th scope="row">%s</th><td>%s</td></tr>',
				$label,
				$input
			);
		}
		/**
		 * Hooks into WordPress' save_post function
		 */
		public function save_post( $post_id ) {
			if ( ! isset( $_POST['dados_do_evento_nonce'] ) )
				return $post_id;

			$nonce = $_POST['dados_do_evento_nonce'];
			if ( !wp_verify_nonce( $nonce, 'dados_do_evento_data' ) )
				return $post_id;

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;

			foreach ( $this->fields as $field ) {

				if ( isset( $_POST[ $field['id'] ] ) ) {
					switch ( $field['type'] ) {
						case 'email':
							$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
							break;
						case 'text':
							if( $field['id'] !== 'location' ) {
								$raw_date = explode(' ', $_POST[$field['id']]);
								$_POST[$field['id']] = implode('-', array_reverse(explode('/', $raw_date[0]))) . ' ' . $raw_date[1];
							} else {
								$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
							}
							break;
					}
					update_post_meta( $post_id, 'dados_do_evento_' . $field['id'], $_POST[ $field['id'] ] );
				} else if ( $field['type'] === 'checkbox' ) {
					update_post_meta( $post_id, 'dados_do_evento_' . $field['id'], '0' );
				}
			}

			echo '<pre>';
			// wp_die( var_dump($this->fields, $_POST) );
			echo '</pre>';
		}

		/**
		 * Shortcodes
		 *
		 * @param $atts
		 * @return string
		 */
		public function gs_shortcodes($atts)
		{
			require_once plugin_dir_path( __FILE__ ) . 'inc/shortcodes.php';
			$gs_shortcodes = new Gov_Schedules_Shortcodes();
		}

		public function gs_get_week_events () {
			$d = $_POST['date'];
			$c = $_POST['event_category'];
			$daypicker = '';
			$events = '';
			$month = explode('-', $d);
			$month = $month[1];
			$dateObj   = DateTime::createFromFormat('!m', $month);
			$month_name = strftime('%b', $dateObj->format('U') );

			for($i = 3; $i >= 1; $i--) {
				$date = date_create($d);
				date_sub($date, date_interval_create_from_date_string( $i . ' days'));

				$daypicker .= '<li>';
				$daypicker .= '<a href="#" data-day="'. date_format($date, 'Y-m-d') .'">';
				$daypicker .= '<span>'. date_format($date, 'd') .'</span>';
				$daypicker .= '<small>'. strftime('%a', strtotime( date_format($date, 'Y-m-d') ) ) .'</small>';
				$daypicker .= '</a>';
				$daypicker .= '</li>';
			}

			for($i = 0; $i <= 3; $i++) {
				$date = date_create($d);
				date_add($date, date_interval_create_from_date_string($i . ' days'));

				$daypicker .= $i === 0 ? '<li class="selected">' : '<li>';
				$daypicker .= '<a href="#" data-day="'. date_format($date, 'Y-m-d') . '">';
				$daypicker .= '<span>'. date_format($date, 'd') . '</span>';
				$daypicker .= '<small>'. strftime('%a', strtotime( date_format($date, 'Y-m-d') ) ) .'</small>';
				$daypicker .= '</a>';
				$daypicker .= '</li>';
			}

			$args = array(
				'post_type' => 'event',
				'tax_query' => array(
					array (
						'taxonomy' => 'event-category',
						'field' => 'slug',
						'terms' => $c,
					)
				),
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'dados_do_evento_data-de-incio',
						'value'   => $d,
						'compare' => '=',
						'type'    => 'DATE'
					),
					array(
						'relation' => 'AND',
						array(
							'key'     => 'dados_do_evento_data-de-incio',
							'value'   => $d,
							'compare' => '<=',
							'type'    => 'DATE'
						),
						array(
							'key'     => 'dados_do_evento_data-de-fim',
							'value'   => $d,
							'compare' => '>=',
							'type'    => 'DATE'
						),
						array(
							'key'     => 'dados_do_evento_data-de-fim',
							'value'   => '',
							'compare' => '!='
						)
					)
				)
			);

			$query = new WP_Query($args);
			if( $query->have_posts() ):

				while ($query->have_posts()) : $query->the_post();

					$locaction = get_post_meta( get_the_ID(), 'dados_do_evento_location', true );
					$date = get_post_meta( get_the_ID(), 'dados_do_evento_data-de-incio', true );
					$raw_date = explode(' ', $date );

					$events .= '<div class="event-item">';
					$events .= '<h3><a href="'. get_the_permalink() .'">'. get_the_title() .'</a></h3>';
					$events .= '<span class="location">'. $locaction .'</span>';
					$events .= '<span class="time">'. $raw_date[1] .'</span>';
					$events .= '</div>';

				endwhile; wp_reset_query();

			else:

				$events = false;

			endif;

			$data = array(
				'month'  => $month_name,
				'weeks'  => $daypicker,
				'events' => $events
			);
			wp_send_json_success( $data );
		}

		function yourplugin_get_custom_archive_template($template) {
			global $wp_query;
			if (is_post_type_archive('event')) {
				$template = require_once plugin_dir_path( __FILE__ ) . 'inc/archive-event.php';
			}
			return $template;
		}

	}

	// Initialize our plugin
	$oscar_minc = new Gov_Schedules();

endif;
