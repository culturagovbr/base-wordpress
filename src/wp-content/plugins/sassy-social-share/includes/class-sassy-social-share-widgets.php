<?php

/**
 * The file that defines classes for widgets
 *
 * Class definitions that include functions used for widgets.
 *
 * @since    1.0.0
 *
 */

/**
 * Standard Widget class.
 *
 * This is used to define functions for Standard Sharing Widget.
 *
 * @since    1.0.0
 */
class Sassy_Social_Share_Standard_Widget extends WP_Widget { 
	
	/**
	 * Options saved in database.
	 *
	 * @since    1.0.0
	 */
	private $options;

	/**
	 * Member to assign object of Sassy_Social_Share_Public Class.
	 *
	 * @since    1.0.0
	 */
	private $public_class_object;

	/**
	 * Assign plugin options to private member $options and define widget title, description etc.
	 *
	 * @since    1.0.0
	 */
	public function __construct() { 
		
		global $heateor_sss;

		$this->options = $heateor_sss->options;

		$this->public_class_object = new Sassy_Social_Share_Public( $heateor_sss->options, HEATEOR_SSS_VERSION );

		parent::__construct( 
			'Heateor_SSS_Sharing', // unique id 
			__( 'Sassy Social Share - Standard Widget' ), // Widget title 
			array( 'description' => __( 'Standard sharing widget. Let your website users share content on popular Social networks like Facebook, Twitter, Tumblr, Google+ and many more', 'sassy-social-share' ) )
		); 
	}  

	/**
	 * Render widget at front-end
	 *
	 * @since    1.0.0
	 */
	public function widget( $args, $instance ) { 
		// return if standard sharing is disabled
		if ( ! isset( $this->options['hor_enable'] ) ) {
			return;
		}
		extract( $args );
		if ( $instance['hide_for_logged_in'] == 1 && is_user_logged_in() ) return;
		
		global $post;
		$post_id = $post -> ID;
		if ( isset( $instance['target_url'] ) ) {
			if ( $instance['target_url'] == 'default' ) {
				$sharing_url = html_entity_decode( esc_url( $this->public_class_object->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
				if ( is_home() ) {
					$sharing_url = home_url();
					$post_id = 0;
				} elseif ( ! is_singular() ) {
					$post_id = 0;
				} elseif ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] ) {
					$sharing_url = html_entity_decode( esc_url( $this->public_class_object->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
				} elseif ( get_permalink( $post -> ID ) ) {
					$sharing_url = get_permalink( $post->ID );
				}
			} elseif ( $instance['target_url'] == 'homepage' ) {
				$sharing_url = home_url();
				$post_id = 0;
			} elseif ( $instance['target_url'] == 'custom' ) {
				$sharing_url = isset( $instance['target_url_custom'] ) ? trim( $instance['target_url_custom'] ) : get_permalink( $post->ID );
				$post_id = 0;
			}
		} else {
			$sharing_url = get_permalink( $post->ID );
		}
		$sharing_url = $this->public_class_object->apply_target_share_url_filter( $sharing_url, 'horizontal', ! is_singular() ? true : false );
		// share count transient ID
		$this->public_class_object->share_count_transient_id = $this->public_class_object->get_share_count_transient_id( $sharing_url );
		$cached_share_count = $this->public_class_object->get_cached_share_count( $this->public_class_object->share_count_transient_id );

		echo "<div class='heateor_sss_sharing_container heateor_sss_horizontal_sharing' heateor-sss-data-href='" . $sharing_url . "' ". ( ( $cached_share_count !== false || $this->public_class_object->is_amp_page() ) ? 'heateor-sss-no-counts="1"' : "" ) .">";
		
		echo $before_widget;
		
		if ( ! empty( $instance['title'] ) ) { 
			$title = apply_filters( 'widget_title', $instance[ 'title' ] ); 
			echo $before_title . $title . $after_title;
		}

		if ( ! empty( $instance['before_widget_content'] ) ) { 
			echo '<div>' . $instance['before_widget_content'] . '</div>'; 
		}
		$short_url = $this->public_class_object->get_short_url( $sharing_url, $post_id );

		echo $this->public_class_object->prepare_sharing_html( $short_url ? $short_url : $sharing_url, 'horizontal', isset( $instance['show_counts'] ), isset( $instance['total_shares'] ), ! is_singular() ? true : false );

		if ( ! empty( $instance['after_widget_content'] ) ) { 
			echo '<div>' . $instance['after_widget_content'] . '</div>'; 
		}
		
		echo '</div>';
		if ( ( isset( $instance['show_counts'] ) || isset( $instance['total_shares'] ) ) && $cached_share_count === false ) {
			echo '<script>heateorSssLoadEvent(
		function() {
			// sharing counts
			heateorSssCallAjax(function() {
				heateorSssGetSharingCounts();
			});
		}
	);</script>';
		}
		echo $after_widget;
	} 

	/** 
	 * Everything which should happen when user edit widget at admin panel.
	 *
	 * @since    1.0.0
	 */
	public function update( $new_instance, $old_instance ) { 
		
		$instance = $old_instance; 
		$instance['title'] = strip_tags( $new_instance['title'] ); 
		$instance['show_counts'] = $new_instance['show_counts'];
		$instance['total_shares'] = $new_instance['total_shares']; 
		$instance['target_url'] = $new_instance['target_url'];
		$instance['target_url_custom'] = $new_instance['target_url_custom'];  
		$instance['before_widget_content'] = $new_instance['before_widget_content']; 
		$instance['after_widget_content'] = $new_instance['after_widget_content']; 
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];  

		return $instance; 

	}  

	/** 
	 * Widget options form at admin panel.
	 *
	 * @since    1.0.0
	 */
	public function form( $instance ) { 
		
		// default widget settings
		$defaults = array( 'title' => 'Share the joy', 'show_counts' => 0, 'total_shares' => 0, 'target_url' => 'default', 'target_url_custom' => '', 'before_widget_content' => '', 'after_widget_content' => '' );

		foreach ( $instance as $key => $value ) {
			if ( is_string( $value ) ) {
				$instance[ $key ] = esc_attr( $value );
			}
		}
		
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		?> 
		<script type="text/javascript">
			function heateorSssToggleHorSharingTargetUrl(val) {
				if (val == 'custom' ) {
					jQuery( '.heateorSssHorSharingTargetUrl' ).css( 'display', 'block' );
				} else {
					jQuery( '.heateorSssHorSharingTargetUrl' ).css( 'display', 'none' );
				}
			}
		</script>
		<p> 
			<p><strong>Note:</strong> <?php _e( 'Make sure "Standard Sharing Interface" is enabled in "Standard Interface" section at "Sassy Social Share" page.', 'sassy-social-share' ) ?></p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'sassy-social-share' ); ?></label> 
			<input style="width: 95%" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" /> <br/><br/>
			<label for="<?php echo $this->get_field_id( 'show_counts' ); ?>"><?php _e( 'Show individual share counts:', 'sassy-social-share' ); ?></label> 
			<input id="<?php echo $this->get_field_id( 'show_counts' ); ?>" name="<?php echo $this->get_field_name( 'show_counts' ); ?>" type="checkbox" value="1" <?php echo isset( $instance['show_counts'] ) && $instance['show_counts'] == 1 ? 'checked' : ''; ?> /><br/><br/>
			<label for="<?php echo $this->get_field_id( 'total_shares' ); ?>"><?php _e( 'Show total shares:', 'sassy-social-share' ); ?></label> 
			<input id="<?php echo $this->get_field_id( 'total_shares' ); ?>" name="<?php echo $this->get_field_name( 'total_shares' ); ?>" type="checkbox" value="1" <?php echo isset( $instance['total_shares'] ) && $instance['total_shares'] == 1 ? 'checked' : ''; ?> /><br/> <br/>
			<label for="<?php echo $this->get_field_id( 'target_url' ); ?>"><?php _e( 'Target Url:', 'sassy-social-share' ); ?></label> 
			<select style="width: 95%" onchange="heateorSssToggleHorSharingTargetUrl(this.value)" class="widefat" id="<?php echo $this->get_field_id( 'target_url' ); ?>" name="<?php echo $this->get_field_name( 'target_url' ); ?>">
				<option value="">--<?php _e( 'Select', 'sassy-social-share' ) ?>--</option>
				<option value="default" <?php echo isset( $instance['target_url'] ) && $instance['target_url'] == 'default' ? 'selected' : '' ; ?>>Url of the webpage where icons are located (default)</option>
				<option value="homepage" <?php echo isset( $instance['target_url'] ) && $instance['target_url'] == 'homepage' ? 'selected' : '' ; ?>>Url of the homepage of your website</option>
				<option value="custom" <?php echo isset( $instance['target_url'] ) && $instance['target_url'] == 'custom' ? 'selected' : '' ; ?>>Custom Url</option>
			</select>
			<input placeholder="Custom url" style="margin-top: 5px; <?php echo ! isset( $instance['target_url'] ) || $instance['target_url'] != 'custom' ? 'display: none' : '' ; ?>" class="widefat heateorSssHorSharingTargetUrl" id="<?php echo $this->get_field_id( 'target_url_custom' ); ?>" name="<?php echo $this->get_field_name( 'target_url_custom' ); ?>" type="text" value="<?php echo isset( $instance['target_url_custom'] ) ? $instance['target_url_custom'] : ''; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'before_widget_content' ); ?>"><?php _e( 'Before widget content:', 'sassy-social-share' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'before_widget_content' ); ?>" type="text" value="<?php echo $instance['before_widget_content']; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'after_widget_content' ); ?>"><?php _e( 'After widget content:', 'sassy-social-share' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'after_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'after_widget_content' ); ?>" type="text" value="<?php echo $instance['after_widget_content']; ?>" /> 
			<br /><br /><label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'sassy-social-share' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value="1" <?php if ( isset( $instance['hide_for_logged_in'] )  && $instance['hide_for_logged_in'] == 1 ) echo 'checked="checked"'; ?> /> 
		</p> 
	<?php 
    }

} 

/**
 * Floating Widget class.
 *
 * This is used to define functions for Floating Sharing Widget.
 *
 * @since    1.0.0
 */
class Sassy_Social_Share_Floating_Widget extends WP_Widget { 
	
	/**
	 * Options saved in database.
	 *
	 * @since    1.0.0
	 */
	private $options;

	/**
	 * Member to assign object of Sassy_Social_Share_Public Class.
	 *
	 * @since    1.0.0
	 */
	private $public_class_object;

	/**
	 * Assign plugin options to private member $options and define widget title, description etc.
	 *
	 * @since    1.0.0
	 */
	public function __construct() { 
		
		global $heateor_sss;

		$this->options = $heateor_sss->options;

		$this->public_class_object = new Sassy_Social_Share_Public( $heateor_sss->options, HEATEOR_SSS_VERSION );

		parent::__construct( 
			'Heateor_SSS_Floating_Sharing', // unique id 
			'Sassy Social Share - Floating Widget', // widget title 
			// additional parameters 
			array(
				'description' => __( 'Floating sharing widget. Let your website users share content on popular Social networks like Facebook, Twitter, Tumblr, Google+ and many more', 'sassy-social-share' ) ) 
			); 
	}  

	/**
	 * Render widget at front-end
	 *
	 * @since    1.0.0
	 */
	public function widget( $args, $instance ) { 
		
		// return if vertical sharing is disabled
		if ( ! isset( $this->options['vertical_enable'] ) ) {
			return;
		}
		extract( $args );
		if ( $instance['hide_for_logged_in'] == 1 && is_user_logged_in() ) return;
		
		global $post;
		$post_id = $post -> ID;
		if ( isset( $instance['target_url'] ) ) {
			if ( $instance['target_url'] == 'default' ) {
				$sharing_url = html_entity_decode( esc_url( $this->public_class_object->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
				if ( is_home() ) {
					$sharing_url = home_url();
					$post_id = 0;
				}  elseif ( ! is_singular() ) {
					$post_id = 0;
				} elseif ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] ) {
					$sharing_url = html_entity_decode( esc_url( $this->public_class_object->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
				} elseif ( get_permalink( $post -> ID ) ) {
					$sharing_url = get_permalink( $post->ID );
				}
			} elseif ( $instance['target_url'] == 'homepage' ) {
				$sharing_url = home_url();
				$post_id = 0;
			} elseif ( $instance['target_url'] == 'custom' ) {
				$sharing_url = isset( $instance['target_url_custom'] ) ? trim( $instance['target_url_custom'] ) : get_permalink( $post->ID );
				$post_id = 0;
			}
		} else {
			$sharing_url = get_permalink( $post->ID );
		}
		$sharing_url = $this->public_class_object->apply_target_share_url_filter( $sharing_url, 'vertical', false );
		$ssOffset = 0;
		if ( isset( $instance['alignment'] ) && isset( $instance[$instance['alignment'] . '_offset'] ) ) {
			$ssOffset = $instance[$instance['alignment'] . '_offset'];
		}
		
		// share count transient ID
		$this->public_class_object->share_count_transient_id = $this->public_class_object->get_share_count_transient_id( $sharing_url );
		$cached_share_count = $this->public_class_object->get_cached_share_count( $this->public_class_object->share_count_transient_id );

		echo "<div class='heateor_sss_sharing_container heateor_sss_vertical_sharing" . ( isset( $this->options['hide_mobile_sharing'] ) ? ' heateor_sss_hide_sharing' : '' ) . ( isset( $this->options['bottom_mobile_sharing'] ) ? ' heateor_sss_bottom_sharing' : '' ) . "' ss-offset='" . $ssOffset . "' style='width:" . ( ( $this->options['vertical_sharing_size'] ? $this->options['vertical_sharing_size'] : 35) + 4) . "px;".( isset( $instance['alignment'] ) && $instance['alignment'] != '' && isset( $instance[$instance['alignment'].'_offset'] ) ? $instance['alignment'].': '. ( $instance[$instance['alignment'].'_offset'] == '' ? 0 : $instance[$instance['alignment'].'_offset'] ) .'px;' : '' ).( isset( $instance['top_offset'] ) ? 'top: '. ( $instance['top_offset'] == '' ? 0 : $instance['top_offset'] ) .'px;' : '' ) . ( isset( $instance['vertical_bg'] ) && $instance['vertical_bg'] != '' ? 'background-color: '.$instance['vertical_bg'] . ';' : '-webkit-box-shadow:none;box-shadow:none;' ) . "' heateor-sss-data-href='". $sharing_url ."' ". ( ( $cached_share_count !== false || $this->public_class_object->is_amp_page() ) ? 'heateor-sss-no-counts="1"' : "" ) .">";
		
		$short_url = $this->public_class_object->get_short_url( $sharing_url, $post_id );

		//echo $before_widget;
		
		echo $this->public_class_object->prepare_sharing_html( $short_url ? $short_url : $sharing_url, 'vertical', isset( $instance['show_counts'] ), isset( $instance['total_shares'] ) );

		echo '</div>';
		if ( ( isset( $instance['show_counts'] ) || isset( $instance['total_shares'] ) ) && $cached_share_count === false ) {
			echo '<script>heateorSssLoadEvent(
		function() {
			// sharing counts
			heateorSssCallAjax(function() {
				heateorSssGetSharingCounts();
			});
		}
	);</script>';
		}
		//echo $after_widget;
	}  

	/** 
	 * Everything which should happen when user edit widget at admin panel.
	 *
	 * @since    1.0.0
	 */
	public function update( $new_instance, $old_instance ) { 
		
		$instance = $old_instance; 
		$instance['target_url'] = $new_instance['target_url'];
		$instance['show_counts'] = $new_instance['show_counts']; 
		$instance['total_shares'] = $new_instance['total_shares']; 
		$instance['target_url_custom'] = $new_instance['target_url_custom'];
		$instance['alignment'] = $new_instance['alignment'];
		$instance['left_offset'] = $new_instance['left_offset'];
		$instance['right_offset'] = $new_instance['right_offset'];
		$instance['top_offset'] = $new_instance['top_offset'];
		$instance['vertical_bg'] = $new_instance['vertical_bg'];
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];  

		return $instance; 

	}  

	/** 
	 * Widget options form at admin panel.
	 *
	 * @since    1.0.0
	 */
	public function form( $instance ) { 
		
		/* Set up default widget settings. */ 
		$defaults = array( 'alignment' => 'left', 'show_counts' => 0, 'total_shares' => 0, 'left_offset' => '40', 'right_offset' => '0', 'target_url' => 'default', 'target_url_custom' => '', 'top_offset' => '100', 'vertical_bg' => '' );

		foreach ( $instance as $key => $value ) {
			if ( is_string( $value ) ) {
				$instance[ $key ] = esc_attr( $value );
			}
		}
		
		$instance = wp_parse_args( ( array ) $instance, $defaults ); 
		?> 
		<p> 
			<script>
			function heateorSssToggleSharingOffset(alignment) {
				if (alignment == 'left' ) {
					jQuery( '.heateorSssSharingLeftOffset' ).css( 'display', 'block' );
					jQuery( '.heateorSssSharingRightOffset' ).css( 'display', 'none' );
				} else {
					jQuery( '.heateorSssSharingLeftOffset' ).css( 'display', 'none' );
					jQuery( '.heateorSssSharingRightOffset' ).css( 'display', 'block' );
				}
			}
			function heateorSssToggleVerticalSharingTargetUrl(val) {
				if (val == 'custom' ) {
					jQuery( '.heateorSssVerticalSharingTargetUrl' ).css( 'display', 'block' );
				} else {
					jQuery( '.heateorSssVerticalSharingTargetUrl' ).css( 'display', 'none' );
				}
			}
			</script>
			<p><strong>Note:</strong> <?php _e( 'Make sure "Floating Interface" is enabled in "Floating Interface" section at "Sassy Social Share" page.', 'sassy-social-share' ) ?></p>
			<label for="<?php echo $this->get_field_id( 'show_counts' ); ?>"><?php _e( 'Show individual share counts:', 'sassy-social-share' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'show_counts' ); ?>" name="<?php echo $this->get_field_name( 'show_counts' ); ?>" type="checkbox" value="1" <?php echo isset( $instance['show_counts'] ) && $instance['show_counts'] == 1 ? 'checked' : ''; ?> /><br/><br/> 
			<label for="<?php echo $this->get_field_id( 'total_shares' ); ?>"><?php _e( 'Show total shares:', 'sassy-social-share' ); ?></label> 
			<input id="<?php echo $this->get_field_id( 'total_shares' ); ?>" name="<?php echo $this->get_field_name( 'total_shares' ); ?>" type="checkbox" value="1" <?php echo isset( $instance['total_shares'] ) && $instance['total_shares'] == 1 ? 'checked' : ''; ?> /><br/> <br/>
			<label for="<?php echo $this->get_field_id( 'target_url' ); ?>"><?php _e( 'Target Url:', 'sassy-social-share' ); ?></label> 
			<select style="width: 95%" onchange="heateorSssToggleVerticalSharingTargetUrl(this.value)" class="widefat" id="<?php echo $this->get_field_id( 'target_url' ); ?>" name="<?php echo $this->get_field_name( 'target_url' ); ?>">
				<option value="">--<?php _e( 'Select', 'sassy-social-share' ) ?>--</option>
				<option value="default" <?php echo isset( $instance['target_url'] ) && $instance['target_url'] == 'default' ? 'selected' : '' ; ?>>Url of the webpage where icons are located (default)</option>
				<option value="homepage" <?php echo isset( $instance['target_url'] ) && $instance['target_url'] == 'homepage' ? 'selected' : '' ; ?>>Url of the homepage of your website</option>
				<option value="custom" <?php echo isset( $instance['target_url'] ) && $instance['target_url'] == 'custom' ? 'selected' : '' ; ?>>Custom Url</option>
			</select>
			<input placeholder="Custom url" style="width:95%; margin-top: 5px; <?php echo ! isset( $instance['target_url'] ) || $instance['target_url'] != 'custom' ? 'display: none' : '' ; ?>" class="widefat heateorSssVerticalSharingTargetUrl" id="<?php echo $this->get_field_id( 'target_url_custom' ); ?>" name="<?php echo $this->get_field_name( 'target_url_custom' ); ?>" type="text" value="<?php echo isset( $instance['target_url_custom'] ) ? $instance['target_url_custom'] : ''; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment', 'sassy-social-share' ); ?></label> 
			<select onchange="heateorSssToggleSharingOffset(this.value)" style="width: 95%" class="widefat" id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option value="left" <?php echo $instance['alignment'] == 'left' ? 'selected' : ''; ?>><?php _e( 'Left', 'sassy-social-share' ) ?></option>
				<option value="right" <?php echo $instance['alignment'] == 'right' ? 'selected' : ''; ?>><?php _e( 'Right', 'sassy-social-share' ) ?></option>
			</select>
			<div class="heateorSssSharingLeftOffset" <?php echo $instance['alignment'] == 'right' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'left_offset' ); ?>"><?php _e( 'Left Offset', 'sassy-social-share' ); ?></label> 
				<input style="width: 95%" class="widefat" id="<?php echo $this->get_field_id( 'left_offset' ); ?>" name="<?php echo $this->get_field_name( 'left_offset' ); ?>" type="text" value="<?php echo $instance['left_offset']; ?>" />px<br/>
			</div>
			<div class="heateorSssSharingRightOffset" <?php echo $instance['alignment'] == 'left' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'right_offset' ); ?>"><?php _e( 'Right Offset', 'sassy-social-share' ); ?></label> 
				<input style="width: 95%" class="widefat" id="<?php echo $this->get_field_id( 'right_offset' ); ?>" name="<?php echo $this->get_field_name( 'right_offset' ); ?>" type="text" value="<?php echo $instance['right_offset']; ?>" />px<br/>
			</div>
			<label for="<?php echo $this->get_field_id( 'top_offset' ); ?>"><?php _e( 'Top Offset', 'sassy-social-share' ); ?></label> 
			<input style="width: 95%" class="widefat" id="<?php echo $this->get_field_id( 'top_offset' ); ?>" name="<?php echo $this->get_field_name( 'top_offset' ); ?>" type="text" value="<?php echo $instance['top_offset']; ?>" />px<br/>
			
			<label for="<?php echo $this->get_field_id( 'vertical_bg' ); ?>"><?php _e( 'Background Color', 'sassy-social-share' ); ?></label> 
			<input style="width: 95%" class="widefat" id="<?php echo $this->get_field_id( 'vertical_bg' ); ?>" name="<?php echo $this->get_field_name( 'vertical_bg' ); ?>" type="text" value="<?php echo $instance['vertical_bg']; ?>" />
			
			<br /><br /><label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'sassy-social-share' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value="1" <?php if ( isset( $instance['hide_for_logged_in'] )  && $instance['hide_for_logged_in'] == 1 ) echo 'checked="checked"'; ?> /> 
		</p> 
	<?php 
    } 
}
