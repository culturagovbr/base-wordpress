<?php

class HappyForms_Session {

	/**
	 * The singleton instance.
	 *
	 * @since 1.0
	 *
	 * @var HappyForms_Session
	 */
	private static $instance;

	/**
	 * The list of registered errors.
	 *
	 * @since 1.4.6
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * The list of registered notices.
	 *
	 * @since 1.4.6
	 *
	 * @var array
	 */
	private $notices = array();

	/**
	 * A list of submit values.
	 *
	 * @since 1.4.6
	 *
	 * @var array
	 */
	private $values = array();

	private $states = array();

	/**
	 * Current form step
	 *
	 * @var int
	 */
	private $step = 0;

	/**
	 * Current literal step
	 *
	 * @var int
	 */
	private $literal_step = '';

	/**
	 * The singleton constructor.
	 *
	 * @since 1.0
	 *
	 * @return HappyForms_Message_Admin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function add_error( $key, $message, $component = false ) {
		if ( false !== $component ) {
			if ( ! isset( $this->errors[$key] ) || ! is_array( $this->errors[$key] ) ) {
				$this->errors[$key] = array();
			}
			$this->errors[$key][$component] = $message;
		} else {
			$this->errors[$key] = $message;
		}
	}

	public function remove_error( $key ) {
		if ( isset( $this->errors[$key] ) ) {
			unset( $this->errors[$key] );
		}
	}

	/**
	 * Add a notice to be displayed on the next refresh.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function add_notice( $key, $message ) {
		$this->notices[$key] = $message;
	}

	public function add_state( $key, $state ) {
		$this->states[$key][] = $state;
	}

	public function get_states( $location = '' ) {
		$states = array();

		if ( isset( $this->states[$location] ) ) {
			$states = $this->states[$location];
		}

		return $states;
	}

	public function remove_notice( $key ) {
		if ( isset( $this->notices[$key] ) ) {
			unset( $this->notices[$key] );
		}
	}

	public function add_value( $key, $value ) {
		$this->values[$key] = $value;
	}

	public function remove_value( $key ) {
		if ( isset( $this->values[$key] ) ) {
			unset( $this->values[$key] );
		}
	}

	/**
	 * Get the messages for the given form and location.
	 *
	 * @since 1.0
	 *
	 * @param string $location  The location to fetch messages for.
	 *
	 * @return array
	 */
	public function get_messages( $location = '' ) {
		$messages = array();

		if ( isset( $this->notices[$location] ) ) {
			$messages[] = array(
				'type' => 'success',
				'message' => $this->notices[$location],
			);
		}

		if ( isset( $this->errors[$location] ) ) {
			$messages[] = array(
				'type' => 'error',
				'message' => $this->errors[$location],
			);
		}

		return $messages;
	}

	public function get_value( $location = '', $component = false ) {
		$value = false;

		if ( isset( $this->values[$location] ) ) {
			$value = $this->values[$location];

			if ( false !== $component ) {
				$value = isset( $value[$component] ) ? $value[$component] : '';
			}
		}

		return $value;
	}

	public function clear_values() {
		$this->values = array();
	}

	public function current_step( $literal = false ) {
		return $literal ? $this->literal_step : $this->step;
	}

	public function next_step() {
		$this->step = $this->step + 1;
	}

	public function previous_step() {
		$this->step = max( 0, $this->step - 1 );
	}

	public function set_step( $step ) {
		$this->literal_step = $step;
		$this->step = intval( $step );
	}

	public function reset_step() {
		$this->step = 0;
	}

	public function serialize() {
		$data = array_merge( $this->values, array(
			'step' => $this->step,
		) );

		$serialized = wp_slash( json_encode( $data, JSON_UNESCAPED_UNICODE ) );

		return $serialized;
	}

	public function unserialize( $data ) {
		$data = json_decode( $data, true );

		if ( ! isset( $data['step'] ) ) {
			$data['step'] = 0;
		} else {
			$data['step'] = intval( $data['step'] );
		}

		return $data;
	}

	public function from_data( $data, $apply_step = false ) {
		$step = 0;

		if ( isset( $data['step'] ) ) {
			$step = intval( $data['step'] );
			unset( $data['step'] );
		}

		foreach( $data as $key => $value ) {
			$this->add_value( $key, $value );
		}

		if ( $apply_step ) {
			$this->set_step( $step );
		}
	}

}

if ( ! function_exists( 'happyforms_get_session' ) ):
/**
 * Get the HappyForms_Session class instance.
 *
 * @since 1.0
 *
 * @return HappyForms_Session
 */
function happyforms_get_session() {
	return HappyForms_Session::instance();
}

endif;
