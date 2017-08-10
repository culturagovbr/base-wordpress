<?php

class STC_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'stc_widget', // Base ID
			__( 'Subscribe to Category', 'stc_textdomain' ), // Name
			array( 'description' => __( 'Adding the subscribe form to a widget area.', 'stc_textdomain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		$categories_include = isset( $instance['categories_include'] ) && !empty( $instance['categories_include'] ) ? implode( ',', $instance['categories_include'] ) : false;
		$categories_exclude = isset( $instance['categories_exclude'] ) && !empty( $instance['categories_exclude'] ) ? implode( ',', $instance['categories_exclude'] ) : false;

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

    echo do_shortcode( '[stc-subscribe category_id_in="'.$categories_include.'" category_id_not_in="'.$categories_exclude.'"]' );

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Subscribe', 'stc_textdomain' );
		$categories_include = isset( $instance['categories_include'] ) ? $instance['categories_include'] : '';
		$categories_exclude = isset( $instance['categories_exclude'] ) ? $instance['categories_exclude'] : '';

		$categories = get_terms( 'category', array( 'hide_empty' => 0 ) );

		?>
		<div class="stc-widget-admin-wrapper">
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		<p>
			<em><?php _e('As default all categories are available for subscription. You should only use the checkboxes below if you want to include or exclude some categories for subscription.') ?></em>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Selectable categories for subscription: ', 'stc_textdomain' ); ?></label>
		</p>

		<?php if(! empty( $categories ) ) : ?>
			<div class="stc-category-include">
			<?php 
				foreach( $categories as $key => $category ) : 
					$in_category_id = isset( $instance['categories_include'][$key] ) ? $instance['categories_include'][$key] : false;
			?>
				<label><input type="checkbox" id="<?php echo $this->get_field_id( 'categories_include' ) . $key; ?>" name="<?php echo $this->get_field_name( 'categories_include' );?>[<?php echo $key ?>]" value="<?php echo $category->term_id;?>" <?php echo checked( $in_category_id, $category->term_id); ?>><?php echo $category->name; ?></label>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Excluded selectable categories for subscription:', 'stc_textdomain' ); ?></label></p>
		<?php if(! empty( $categories ) ) : ?>
			<div class="stc-category-exclude">
			<?php 
				foreach( $categories as $key => $category ) : 
					$in_category_id = isset( $instance['categories_exclude'][$key] ) ? $instance['categories_exclude'][$key] : false;
			?>
				<label><input type="checkbox" id="<?php echo $this->get_field_id( 'categories_exclude' ) . $key; ?>" name="<?php echo $this->get_field_name( 'categories_exclude' );?>[<?php echo $key ?>]" value="<?php echo $category->term_id;?>" <?php echo checked( $in_category_id, $category->term_id); ?>><?php echo $category->name; ?></label>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>


		</p>
		</div>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['categories_include'] = ( ! empty( $new_instance['categories_include'] ) ) ? $new_instance['categories_include'] : '';
		$instance['categories_exclude'] = ( ! empty( $new_instance['categories_exclude'] ) ) ? $new_instance['categories_exclude'] : '';

		return $instance;
	}

} // class Foo_Widget

// register STC_Widget
function register_stc_widget() {
    register_widget( 'STC_Widget' );
}
add_action( 'widgets_init', 'register_stc_widget' );
?>