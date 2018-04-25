<?php

/**
 * Implements a @TODO
 *
 */
class PPWPShortcodes {

    public static $carousel_count = 1;
    public static $slide_count = 1;

	public function __construct() {
		add_shortcode( 'carousel', array( $this, 'carousel_shortcode' ) );
		add_shortcode( 'carousel-item', array( $this, 'carousel_item_shortcode' ) );

		add_action( 'init', array( $this, 'register_pp_tinymce_button' ) );
	}

	public function register_pp_tinymce_button() {
		//Abort early if the user will never see TinyMCE
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
			return;

		//Add a callback to regiser our tinymce plugin
		add_filter('mce_external_plugins', array( $this, 'pp_register_tinymce_plugin' ) );

		// Add a callback to add our button to the TinyMCE toolbar
		add_filter('mce_buttons', array( $this, 'pp_add_tinymce_button' ));
	}

	//This callback registers our plug-in
	function pp_register_tinymce_plugin($plugin_array) {
		// wp_enqueue_script( 'pp-wp-scripts', get_template_directory_uri() . '/assets/js/dist/bundle.min.js', array('jquery'), false, true );
		$plugin_array['pp_button'] = get_template_directory_uri() . '/assets/js/dist/pp-tinymce-plugin.js';
		return $plugin_array;
	}

	//This callback adds our button to the toolbar
	function pp_add_tinymce_button($buttons) {
		//Add the button ID to the $button array
		$buttons[] = "pp_button";
		return $buttons;
	}

	public function add_carousel() {
	    return self::$carousel_count++;
    }

	public function add_carousel_item() {
	    return self::$slide_count++;
    }

	public function count_shortcode_uses( $shortcode_to_search, $content_to_look_for ) {
		$pattern = get_shortcode_regex();

		if (   preg_match_all( '/'. $pattern .'/s', $content_to_look_for, $matches )
		       && array_key_exists( 2, $matches )
		       && in_array( $shortcode_to_search, $matches[2] )
		) {
			/*
			echo '<pre>';
			var_dump( $matches );
			echo '</pre>';
			*/
			return count($matches[2]);
		}
    }

	public function carousel_shortcode( $params, $content = null ) {
		// extract the attributes into variables
		extract( shortcode_atts( array(
			'auto'      => true,
			'show_arrows'   => true,
			'show_pagination' => true
		), $params ) );

		// pass the attributes to getImages function and render the images
		// return $this->getImages($params['user'], $images, $width, $height, $caption);

		// echo $this->count_shortcode_uses('carousel-item', $content);

		ob_start(); ?>


        <div id="carousel-<?php echo self::$carousel_count; ?>" class="carousel slide <?php echo self::$slide_count; ?>" data-ride="carousel">

            <?php
            $carousel_items = $this->count_shortcode_uses('carousel-item', $content);
            if (  $carousel_items > 1 ): ?>
            <ol class="carousel-indicators">
                <?php foreach ($carousel_items as $item) {?>
                <li data-target="#carousel-1" data-slide-to="<?php echo $item; ?>" class="active"></li>
                <?php }; ?>
            </ol>
            <?php endif; ?>

            <div class="carousel-inner">
	            <?php echo do_shortcode( $content ); ?>
            </div>

            <a class="carousel-control-prev" href="#carousel-1" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel-1" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

		<?php
		$this->add_carousel();
		return ob_get_clean();
	}

	public function carousel_item_shortcode( $params, $content = null ) {
		extract( shortcode_atts( array(
			'title'         => '',
			'link'          => '',
			'link_target'   => '',
			'image'         => '',
			'desc'          => ''
		), $params ) );

		ob_start(); ?>

        <div class="carousel-item <?php echo self::$slide_count === 1 ? 'active' : ''; ?>">
            <?php if ( !empty($link) ) { ?>
            <a href="<?php echo $link; ?>" <?php echo !empty( $link_target ) ? 'target="_blank"' : ''; ?>>
            <?php } ?>
                <img class="d-block w-100"
                     src="<?php echo $image; ?>"
                     alt="<?php echo $title; ?>">
                <div class="carousel-caption d-none d-md-block">
                    <h5><?php echo $title; ?></h5>
                    <p><?php echo $desc; ?></p>
                </div>
            <?php if ( $link ) { ?>
            </a>
            <?php } ?>
        </div>

		<?php
		$this->add_carousel_item();
		return ob_get_clean();
	}

}

$ppwp_shortcodes = new PPWPShortcodes();