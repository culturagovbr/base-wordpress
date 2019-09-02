<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Hugeit_Lightbox_Model
 */
class Hugeit_Lightbox_Model {


	public function save() {
		if ( isset( $_POST['params'] ) ) {
			foreach ( $_POST['params'] as $name => $value ) {
				update_option( $name, wp_unslash( sanitize_text_field( $value ) ) );
			}
			?>
			<div class="updated"><p><strong><?php _e( 'Item Saved' ); ?></strong></p></div>
			<?php
		}
	}

	/**
	 * Lightbox default options
	 *
	 * @return array
	 */
	public function default_options() {
		$optons = array(
			'hugeit_lightbox_size'                   => '17',
			'hugeit_lightbox_width'                  => '500',
			'hugeit_lightbox_href'                   => 'False',
			'hugeit_lightbox_scalephotos'            => 'true',
			'hugeit_lightbox_rel'                    => 'false',
			'hugeit_lightbox_scrolling'              => 'false',
			'hugeit_lightbox_opacity'                => '20',
			'hugeit_lightbox_open'                   => 'false',
			'hugeit_lightbox_overlayclose'           => 'true',
			'hugeit_lightbox_esckey'                 => 'false',
			'hugeit_lightbox_arrowkey'               => 'false',
			'hugeit_lightbox_loop'                   => 'true',
			'hugeit_lightbox_data'                   => 'false',
			'hugeit_lightbox_classname'              => 'false',
			'hugeit_lightbox_closebutton'            => 'true',
			'hugeit_lightbox_current'                => 'image',
			'hugeit_lightbox_previous'               => 'previous',
			'hugeit_lightbox_next'                   => 'next',
			'hugeit_lightbox_close'                  => 'close',
			'hugeit_lightbox_iframe'                 => 'false',
			'hugeit_lightbox_inline'                 => 'false',
			'hugeit_lightbox_html'                   => 'false',
			'hugeit_lightbox_photo'                  => 'false',
			'hugeit_lightbox_height'                 => '500',
			'hugeit_lightbox_innerwidth'             => 'false',
			'hugeit_lightbox_innerheight'            => 'false',
			'hugeit_lightbox_initialwidth'           => '300',
			'hugeit_lightbox_initialheight'          => '100',
			'hugeit_lightbox_maxwidth'               => '768',
			'hugeit_lightbox_maxheight'              => '500',
			'hugeit_lightbox_slideshow'              => 'false',
			'hugeit_lightbox_slideshowspeed'         => '2500',
			'hugeit_lightbox_slideshowauto'          => 'true',
			'hugeit_lightbox_slideshowstart'         => 'start slideshow',
			'hugeit_lightbox_slideshowstop'          => 'stop slideshow',
			'hugeit_lightbox_fixed'                  => 'true',
			'hugeit_lightbox_top'                    => 'false',
			'hugeit_lightbox_bottom'                 => 'false',
			'hugeit_lightbox_left'                   => 'false',
			'hugeit_lightbox_right'                  => 'false',
			'hugeit_lightbox_reposition'             => 'false',
			'hugeit_lightbox_retinaimage'            => 'true',
			'hugeit_lightbox_retinaurl'              => 'false',
			'hugeit_lightbox_retinasuffix'           => '@2x.$1',
			'hugeit_lightbox_returnfocus'            => 'true',
			'hugeit_lightbox_trapfocus'              => 'true',
			'hugeit_lightbox_fastiframe'             => 'true',
			'hugeit_lightbox_preloading'             => 'true',
			'hugeit_lightbox_title_position'         => '5',
			'hugeit_lightbox_size_fix'               => 'false',
			'hugeit_lightbox_watermark_width'        => '30',
			'hugeit_lightbox_watermark_position'     => '3',
			'hugeit_lightbox_watermark_img_src'      => hugeit_lightbox_plugins_url() . '/images/No-image-found.jpg',
			'hugeit_lightbox_watermark_transparency' => '100',
			'hugeit_lightbox_watermark_image'        => 'false'
		);

		return $optons;
	}

	/**
	 * Lightbox general options
	 *
	 * @return array
	 */
	public function general_options() {
		$optons = array(
			'hugeit_lightbox_style'      => '1',
			'hugeit_lightbox_transition' => 'elastic',
			'hugeit_lightbox_speed'      => '800',
			'hugeit_lightbox_fadeout'    => '300',
			'hugeit_lightbox_title'      => 'false',
			'hugeit_lightbox_type'       => 'old_type'
		);

		return $optons;
	}

	/**
	 * Responsive Lightbox default options
	 *
	 * @return array
	 */
	public function default_resp_options() {
		$options = array(
			'hugeit_lightbox_slideAnimationType'            => 'effect_1',
			'hugeit_lightbox_overlayDuration'               => '150',
			'hugeit_lightbox_escKey_new'                    => 'false',
			'hugeit_lightbox_keyPress_new'                  => 'false',
			'hugeit_lightbox_arrows'                        => 'true',
			'hugeit_lightbox_mouseWheel'                    => 'false',
			'hugeit_lightbox_download'                      => 'false',
			'hugeit_lightbox_showCounter'                   => 'false',
			'hugeit_lightbox_nextHtml'                      => '',     //not used
			'hugeit_lightbox_prevHtml'                      => '',     //not used
			'hugeit_lightbox_sequence_info'                 => 'image',
			'hugeit_lightbox_sequenceInfo'                  => 'of',
			'hugeit_lightbox_width_new'                     => '100',
			'hugeit_lightbox_height_new'                    => '100',
			'hugeit_lightbox_videoMaxWidth'                 => '790',
			'hugeit_lightbox_slideshow_new'                 => 'false',
			'hugeit_lightbox_slideshow_auto_new'            => 'false',
			'hugeit_lightbox_slideshow_speed_new'           => '2500',
			'hugeit_lightbox_slideshow_start_new'           => '',     //not used
			'hugeit_lightbox_slideshow_stop_new'            => '',     //not used
			'hugeit_lightbox_watermark'                     => 'false',
			'hugeit_lightbox_socialSharing'                 => 'false',
			'hugeit_lightbox_facebookButton'                => 'false',
			'hugeit_lightbox_twitterButton'                 => 'false',
			'hugeit_lightbox_googleplusButton'              => 'false',
			'hugeit_lightbox_pinterestButton'               => 'false',
			'hugeit_lightbox_linkedinButton'                => 'false',
			'hugeit_lightbox_tumblrButton'                  => 'false',
			'hugeit_lightbox_redditButton'                  => 'false',
			'hugeit_lightbox_bufferButton'                  => 'false',
			'hugeit_lightbox_diggButton'                    => 'false',
			'hugeit_lightbox_vkButton'                      => 'false',
			'hugeit_lightbox_yummlyButton'                  => 'false',
			'hugeit_lightbox_watermark_text'                => 'WaterMark',
			'hugeit_lightbox_watermark_textColor'           => 'ffffff',
			'hugeit_lightbox_watermark_textFontSize'        => '30',
			'hugeit_lightbox_watermark_containerBackground' => '000000',
			'hugeit_lightbox_watermark_containerOpacity'    => '90',
			'hugeit_lightbox_watermark_containerWidth'      => '300',
			'hugeit_lightbox_watermark_position_new'        => '9',
			'hugeit_lightbox_watermark_opacity'             => '70',
			'hugeit_lightbox_watermark_margin'              => '10',
			'hugeit_lightbox_watermark_img_src_new'         => hugeit_lightbox_plugins_url() . '/images/No-image-found.jpg'
		);

		return $options;
	}


	/**
	 * Responsive Lightbox default options
	 *
	 * @return array
	 */
	public function general_resp_options() {
		$options = array(
			'hugeit_lightbox_lightboxView'                  => 'view1',
			'hugeit_lightbox_speed_new'                     => '600',
			'hugeit_lightbox_overlayClose_new'              => 'true',
			'hugeit_lightbox_loop_new'                      => 'true',
			'hugeit_lightbox_fullwidth_effect'              => 'false',
			'hugeit_lightbox_thumbs'     					=> 'false',
			'hugeit_lightbox_showTitle'                		=> 'true',
			'hugeit_lightbox_showDesc'               		=> 'false',
			'hugeit_lightbox_showBorder'               		=> 'false',
			'hugeit_lightbox_imageframe'                    => 'frame_0',
			'hugeit_lightbox_fullscreen_effect'     		=> 'false',
			'hugeit_lightbox_rightclick_protection'     	=> 'true',
			'hugeit_lightbox_arrows_hover_effect'           => '0',
			'lightbox_open_close_effect'                    => '0',
			'hugeit_lightbox_view_info'                     => 'false'
		);
		return $options;
	}

	function lightbox_get_option() {
		$lightbox_options    = $this->general_options();
		$lightbox_get_option = array();
		foreach ( $lightbox_options as $name => $value ) {
			$lightbox_get_option[ $name ] = get_option( $name );
		}

		return $lightbox_get_option;
	}

	function lightbox_get_resp_option() {
		$lightbox_options         = $this->general_resp_options();
		$lightbox_get_resp_option = array();
		foreach ( $lightbox_options as $name => $value ) {
			$lightbox_get_resp_option[ $name ] = get_option( $name );
		}

		return $lightbox_get_resp_option;
	}

}
