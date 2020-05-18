<?php
class HappyForms_Widget extends WP_Widget {

	/**
	 * Widget constructor.
	 *
	 * @since 1.0
	 *
	 */
	function __construct() {
		parent::__construct(
			'happyforms_widget',
			__( 'HappyForms', 'happyforms' ),
			array(
				'description' => __( 'Easily add your HappyForms to widget areas.', 'happyforms' )
			)
		);
	}

	/**
	 * Render the widget.
	 *
	 * @since 1.0
	 *
	 * @param array $args     The widget configuration.
	 * @param array $instance The widget instance attributes.
	 *
	 */
	public function widget( $args, $instance ) {
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
		$form_id = ( isset( $instance['form_id'] ) ) ? $instance['form_id'] : '';

		$title = apply_filters( 'widget_title', $title );


		echo $args[ 'before_widget' ];

		if ( !empty( $title ) ) {
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		}

		if ( !empty( $form_id ) && intval( $form_id ) ) {
			echo do_shortcode( '[happyforms id="'. $form_id .'"]' );
		}

		echo $args[ 'after_widget' ];
	}

	/**
	 * Render the configuration form.
	 *
	 * @since 1.0
	 *
	 * @param array $instance The widget instance data.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$title = '';
		$form_value = '';

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}

		if ( isset( $instance['form_id'] ) ) {
			$form_value = $instance['form_id'];
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'happyforms' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'form_id' ); ?>"><?php _e( 'Form:', 'happyforms' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'form_id' ); ?>" name="<?php echo $this->get_field_name( 'form_id' ); ?>">
			<?php
			$forms = happyforms_get_form_controller()->get();

			foreach ( $forms as $form ) {
				echo '<option value="'. $form['ID'] .'" '. selected( $form_value == $form['ID'] ) .'">'. $form['post_title'] .'</option>';
			}
			?>
			</select>
		</p>
		<?php
	}

	/**
	 * Update the widget attributes.
	 *
	 * @since 1.0
	 *
	 * @param array $old Previous widget instance attributes.
	 * @param array $new New widget instance attributes.
	 *
	 * @return array
	 *
	 */
	public function update( $new, $old ) {
		$instance = array();
		$instance['title'] = ( !empty( $new[ 'title' ] ) ) ? esc_attr( $new[ 'title' ] ) : '';
		$instance['form_id'] = ( !empty( $new[ 'form_id' ] ) ) ? intval( $new[ 'form_id' ] ) : '';

		return $instance;
	}
}
