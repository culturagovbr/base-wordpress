<?php
/**
 * Portal Padrão Theme Settings
 *
 * @package Portal_Padrão_WP
 */
class PPThemeOptions {
	private $pp_theme_options_options;

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this , 'pp_theme_admin_scripts' ) );
		add_action( 'admin_menu', array( $this, 'pp_theme_options_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'pp_theme_options_page_init' ) );
		add_action( 'admin_init' , array( $this , 'pp_theme_add_blog_denomination_field' ) );
	}

	public function pp_theme_admin_scripts( $hook ) {
		if($hook != 'appearance_page_pp-theme-options') {
			return;
		}
		wp_enqueue_style('pp-theme-options-font-awesome', get_template_directory_uri().'/node_modules/font-awesome/css/font-awesome.css');
		wp_enqueue_style('pp-theme-options-admin-styles', get_template_directory_uri().'/assets/stylesheets/dist/pp-theme-options.css');

		wp_enqueue_script( 'pp-theme-options-admin-scripts', get_template_directory_uri() . '/assets/js/dist/pp-theme-options.js', array('jquery'), false, true );
	}

	public function pp_theme_options_add_plugin_page() {
		add_submenu_page(
			'themes.php',
			__( 'Theme configurations', 'pp-wp' ),
			__( 'Theme configurations', 'pp-wp' ),
			'manage_options',
			'pp-theme-options',
			array( $this, 'pp_theme_options_create_admin_page' )
		);
	}

	public function pp_theme_options_create_admin_page() {
		$this->pp_theme_options_options = get_option( 'pp_theme_options_option_name' ); ?>

		<div class="wrap">
			<h2><?php _e( 'Theme configurations', 'pp-wp' ); ?></h2>
			<p>@TODO</p>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'pp_theme_options_option_group' );
				do_settings_sections( 'pp-theme-options-admin' );
				submit_button();
				?>
			</form>
		</div>
	<?php }

	public function pp_theme_options_page_init() {
		register_setting(
			'pp_theme_options_option_group',
			'pp_theme_options_option_name',
			array( $this, 'pp_theme_options_sanitize' )
		);

		add_settings_section(
			'pp_theme_options_visual_section',
			__( 'Visual configurations', 'pp-wp' ),
			array( $this, 'pp_theme_options_visual_section_info' ),
			'pp-theme-options-admin'
		);

		add_settings_section(
			'pp_theme_options_header_section',
			__( 'Header area', 'pp-wp' ),
			array( $this, 'pp_theme_options_header_section_info' ),
			'pp-theme-options-admin'
		);

		add_settings_field(
			'color_palette', 						// id
			__( 'Color palette', 'pp-wp' ), 			// title
			array( $this, 'color_palette_callback' ), 	// callback
			'pp-theme-options-admin', 				// page
			'pp_theme_options_visual_section' 	// section
		);

		add_settings_field(
			'custom_color_palette', 							// id
			__( 'Custom palette', 'pp-wp' ),							// title
			array( $this, 'custom_color_palette_callback' ), 	// callback
			'pp-theme-options-admin', 						// page
			'pp_theme_options_visual_section' 			// section
		);

		/* add_settings_field(
			'textareaexample_1', 						// id
			'TextareaExample', 							// title
			array( $this, 'textareaexample_1_callback' ), 	// callback
			'pp-theme-options-admin', 					// page
			'pp_theme_options_visual_section' 		// section
		);

		add_settings_field(
			'radioexample_4', 						// id
			'RadioExample', 						// title
			array( $this, 'radioexample_4_callback' ), 	// callback
			'pp-theme-options-admin', 				// page
			'pp_theme_options_visual_section' 	// section
		); */

		add_settings_field(
			'show_search',
			__( 'Show search bar', 'pp-wp' ),
			array( $this, 'show_search_callback' ),
			'pp-theme-options-admin',
			'pp_theme_options_header_section'
		);

		add_settings_field(
			'show_social_links',
			__( 'Show social media links', 'pp-wp' ),
			array( $this, 'show_social_links_callback' ),
			'pp-theme-options-admin',
			'pp_theme_options_header_section'
		);

		add_settings_field(
			'social_links',
			__( 'Social media links', 'pp-wp' ),
			array( $this, 'social_links_callback' ),
			'pp-theme-options-admin',
			'pp_theme_options_header_section'
		);
	}

	public function pp_theme_options_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['custom_color_palette'] ) ) {
			$sanitary_values['custom_color_palette'] = sanitize_text_field( $input['custom_color_palette'] );
		}

		if ( isset( $input['textareaexample_1'] ) ) {
			$sanitary_values['textareaexample_1'] = esc_textarea( $input['textareaexample_1'] );
		}

		if ( isset( $input['color_palette'] ) ) {
			$sanitary_values['color_palette'] = $input['color_palette'];
			update_option ('color_palette', $input['color_palette']);
		}

		if ( isset( $input['show_search'] ) ) {
			$sanitary_values['show_search'] = $input['show_search'];
		}

		if ( isset( $input['show_social_links'] ) ) {
			$sanitary_values['show_social_links'] = $input['show_social_links'];
		}

		if ( isset( $input['social_links'] ) ) {
		    /*echo '<pre>';
		    wp_die( var_dump ( $input['social_links'] ) );*/
			$sanitary_values['social_links'] = $input['social_links'];
		}

		if ( isset( $input['radioexample_4'] ) ) {
			$sanitary_values['radioexample_4'] = $input['radioexample_4'];
		}

		return $sanitary_values;
	}

	public function pp_theme_options_visual_section_info() {

	}

	public function custom_color_palette_callback() {

	    echo '@TODO';
		/* printf(
			'<input class="regular-text" type="text" name="pp_theme_options_option_name[custom_color_palette]" id="custom_color_palette" value="%s">',
			isset( $this->pp_theme_options_options['custom_color_palette'] ) ? esc_attr( $this->pp_theme_options_options['custom_color_palette']) : ''
		); */
	}

	public function textareaexample_1_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="pp_theme_options_option_name[textareaexample_1]" id="textareaexample_1">%s</textarea>',
			isset( $this->pp_theme_options_options['textareaexample_1'] ) ? esc_attr( $this->pp_theme_options_options['textareaexample_1']) : ''
		);
	}

	public function color_palette_callback() { ?>
		<select name="pp_theme_options_option_name[color_palette]" id="color_palette">
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'yellow') ? 'selected' : '' ; ?>
			<option value="yellow" <?php echo $selected; ?>><?php _e( 'Yellow', 'pp-wp' ); ?></option>
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'blue') ? 'selected' : '' ; ?>
			<option value="blue" <?php echo $selected; ?>><?php _e( 'Blue', 'pp-wp' ); ?></option>
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'white') ? 'selected' : '' ; ?>
			<option value="white" <?php echo $selected; ?>><?php _e( 'White', 'pp-wp' ); ?></option>
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'green') ? 'selected' : '' ; ?>
			<option value="green" <?php echo $selected; ?>><?php _e( 'Green', 'pp-wp' ); ?></option>
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'custom') ? 'selected' : '' ; ?>
			<option value="custom" <?php echo $selected; ?>><?php _e( 'Custom', 'pp-wp' ); ?></option>
		</select>
		<p class="description">
			<?php _e( 'This will be the color palette used for the visual of the site', 'pp-wp' ); ?>
		</p>
		<?php
	}

	public function show_search_callback() {
		printf(
			'<input type="checkbox" name="pp_theme_options_option_name[show_search]" id="show_search" value="true" %s> <label for="show_search">Sim</label>',
			( isset( $this->pp_theme_options_options['show_search'] ) && $this->pp_theme_options_options['show_search'] === 'true' ) ? 'checked' : ''
		); ?>
		<p class="description">
			<?php _e( 'Check this option to enable/disable the search field display in the header', 'pp-wp' ); ?>
		</p>
		<?php
	}

	public function show_social_links_callback() {
		printf(
			'<input type="checkbox" name="pp_theme_options_option_name[show_social_links]" id="show_social_links" value="true" %s> <label for="show_social_links">Sim</label>',
			( isset( $this->pp_theme_options_options['show_social_links'] ) && $this->pp_theme_options_options['show_social_links'] === 'true' ) ? 'checked' : ''
		); ?>
		<p class="description">
			<?php _e( 'Check this option to enable / disable viewing links to social networks', 'pp-wp' ); ?>
		</p>
		<?php
	}

	public function social_links_callback() { ?>
        <table id="pp-theme-options-social-media-links">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php _e( 'URL', 'pp-wp' ); ?></th>
                    <th><?php _e( 'Title', 'pp-wp' ); ?></th>
                    <th><?php _e( 'Icon', 'pp-wp' ); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if( isset( $this->pp_theme_options_options['social_links'] ) ) :
                    $i = 0;
                    foreach ($this->pp_theme_options_options['social_links'] as $social_links ) : ?>
                        <tr>
                            <td>
                                <div class="actions">
                                    <a href="#" title="Remover">
                                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                    </a>
                                    <span><?php echo ( $i + 1 ); ?></span>
                                </div>
                            </td>
                            <td>
                                <input class="regular-text" type="text" name="pp_theme_options_option_name[social_links][<?php echo $i; ?>][url]" id="social_link_url_<?php echo $i; ?>" value="<?php echo $social_links['url']; ?>">
                            </td>
                            <td>
                                <input class="regular-text" type="text" name="pp_theme_options_option_name[social_links][<?php echo $i; ?>][title]" id="social_link_title_<?php echo $i; ?>" value="<?php echo $social_links['title']; ?>">
                            </td>
                            <td>
                                <select class="fa-ico-toggler">
                                    <option data-icon="fa-facebook" <?php echo ( $social_links['icon'] == 'fa-facebook' ) ? 'selected="true"' : ''; ?>>Facebook</option>
                                    <option data-icon="fa-facebook-official" <?php echo ( $social_links['icon'] == 'fa-facebook-official' ) ? 'selected="true"' : ''; ?>>Facebook 2</option>
                                    <option data-icon="fa-youtube" <?php echo ( $social_links['icon'] == 'fa-youtube' ) ? 'selected="true"' : ''; ?>>Youtube</option>
                                    <option data-icon="fa-youtube-play" <?php echo ( $social_links['icon'] == 'fa-youtube-play' ) ? 'selected="true"' : ''; ?>>Youtube 2</option>
                                    <option data-icon="fa-youtube-square" <?php echo ( $social_links['icon'] == 'fa-youtube-square' ) ? 'selected="true"' : ''; ?>>Youtube 3</option>
                                    <option data-icon="fa-instagram" <?php echo ( $social_links['icon'] == 'fa-instagram' ) ? 'selected="true"' : ''; ?>>Instagram</option>
                                    <option data-icon="fa-twitter" <?php echo ( $social_links['icon'] == 'fa-twitter' ) ? 'selected="true"' : ''; ?>>Twitter</option>
                                    <option data-icon="fa-twitter-square" <?php echo ( $social_links['icon'] == 'fa-twitter-square' ) ? 'selected="true"' : ''; ?>>Twitter 2</option>
                                    <option data-icon="fa-pinterest" <?php echo ( $social_links['icon'] == 'fa-pinterest' ) ? 'selected="true"' : ''; ?>>Pinterest</option>
                                    <option data-icon="fa-pinterest-p" <?php echo ( $social_links['icon'] == 'fa-pinterest-p' ) ? 'selected="true"' : ''; ?>>Pinterest 2</option>
                                    <option data-icon="fa-pinterest-square" <?php echo ( $social_links['icon'] == 'fa-pinterest-square' ) ? 'selected="true"' : ''; ?>>Pinterest 3</option>
                                    <option data-icon="fa-reddit" <?php echo ( $social_links['icon'] == 'fa-reddit' ) ? 'selected="true"' : ''; ?>>Reddit</option>
                                    <option data-icon="fa-reddit-alien" <?php echo ( $social_links['icon'] == 'fa-reddit-alien' ) ? 'selected="true"' : ''; ?>>Reddit 2</option>
                                    <option data-icon="fa-reddit-square" <?php echo ( $social_links['icon'] == 'fa-reddit-square' ) ? 'selected="true"' : ''; ?>>Reddit 3</option>
                                    <option data-icon="fa-tumblr" <?php echo ( $social_links['icon'] == 'fa-tumblr' ) ? 'selected="true"' : ''; ?>>Tumblr</option>
                                    <option data-icon="fa-tumblr-square" <?php echo ( $social_links['icon'] == 'fa-tumblr-square' ) ? 'selected="true"' : ''; ?>>Tumblr 2</option>
                                    <option data-icon="fa-flickr" <?php echo ( $social_links['icon'] == 'fa-flickr' ) ? 'selected="true"' : ''; ?>>Flickr</option>
                                    <option data-icon="fa-google-plus" <?php echo ( $social_links['icon'] == 'fa-google-plus' ) ? 'selected="true"' : ''; ?>>Google+</option>
                                    <option data-icon="fa-google-plus-square" <?php echo ( $social_links['icon'] == 'fa-google-plus-square' ) ? 'selected="true"' : ''; ?>>Google+ 2</option>
                                    <option data-icon="fa-linkedin" <?php echo ( $social_links['icon'] == 'fa-linkedin' ) ? 'selected="true"' : ''; ?>>LinkedIn</option>
                                    <option data-icon="fa-linkedin-square" <?php echo ( $social_links['icon'] == 'fa-linkedin-square' ) ? 'selected="true"' : ''; ?>>LinkedIn 2</option>
                                    <option data-icon="fa-github" <?php echo ( $social_links['icon'] == 'fa-github' ) ? 'selected="true"' : ''; ?>>Github</option>
                                    <option data-icon="fa-github-alt" <?php echo ( $social_links['icon'] == 'fa-github-alt' ) ? 'selected="true"' : ''; ?>>Github 2</option>
                                    <option data-icon="fa-github-square" <?php echo ( $social_links['icon'] == 'fa-github-square' ) ? 'selected="true"' : ''; ?>>Github 3</option>
                                    <option data-icon="fa-rss" <?php echo ( $social_links['icon'] == 'fa-rss' ) ? 'selected="true"' : ''; ?>>RSS</option>
                                </select>
                                <input class="fa-ico-selected" type="hidden" name="pp_theme_options_option_name[social_links][<?php echo $i; ?>][icon]" value="<?php echo $social_links['icon']; ?>">
                            </td>
                            <td>
                                <i class="fa-ico-toggle fa <?php echo $social_links['icon']; ?>" aria-hidden="true"></i>
                            </td>
                        </tr>

                    <?php $i++; endforeach;
				else : ?>
                    <tr class="fr">
                        <td>
                            <div class="actions">
                                <a href="#">
                                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                </a>
                                <span>1</span>
                            </div>
                        </td>
                        <td>
                            <input class="regular-text" type="text" name="pp_theme_options_option_name[social_links][0][url]" id="social_link_url_0" value="">
                        </td>
                        <td>
                            <input class="regular-text" type="text" name="pp_theme_options_option_name[social_links][0][title]" id="social_link_title_0" value="">
                        </td>
                        <td>
                            <select class="fa-ico-toggler">
                                <option data-icon="fa-facebook">Facebook</option>
                                <option data-icon="fa-facebook-official">Facebook 2</option>
                                <option data-icon="fa-youtube" selected="true">Youtube</option>
                                <option data-icon="fa-youtube-play">Youtube 2</option>
                                <option data-icon="fa-youtube-square">Youtube 3</option>
                                <option data-icon="fa-instagram">Instagram</option>
                                <option data-icon="fa-twitter">Twitter</option>
                                <option data-icon="fa-twitter-square">Twitter 2</option>
                                <option data-icon="fa-pinterest">Pinterest</option>
                                <option data-icon="fa-pinterest-p">Pinterest 2</option>
                                <option data-icon="fa-pinterest-square">Pinterest 3</option>
                                <option data-icon="fa-reddit">Reddit</option>
                                <option data-icon="fa-reddit-alien">Reddit 2</option>
                                <option data-icon="fa-reddit-square">Reddit 3</option>
                                <option data-icon="fa-tumblr">Tumblr</option>
                                <option data-icon="fa-tumblr-square">Tumblr 2</option>
                                <option data-icon="fa-flickr">Flickr</option>
                                <option data-icon="fa-google-plus">Google+</option>
                                <option data-icon="fa-google-plus-square">Google+ 2</option>
                                <option data-icon="fa-linkedin">LinkedIn</option>
                                <option data-icon="fa-linkedin-square">LinkedIn 2</option>
                                <option data-icon="fa-github">Github</option>
                                <option data-icon="fa-github-alt">Github 2</option>
                                <option data-icon="fa-github-square">Github 3</option>
                                <option data-icon="fa-rss">RSS</option>
                            </select>
                            <input class="fa-ico-selected" type="hidden" name="pp_theme_options_option_name[social_links][0][icon]" value="fa-facebook">
                        </td>
                        <td>
                            <i class="fa-ico-toggle fa fa-facebook" aria-hidden="true"></i>
                        </td>
                    </tr>
				<?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <a href="#" id="pp-theme-options-social-media-links-add-row"><?php _e( 'Add new row', 'pp-wp' ); ?></a>
                    </td>
                </tr>
            </tfoot>
        </table>
		<?php
	}

	public function radioexample_4_callback() { ?>
		<fieldset><?php $checked = ( isset( $this->pp_theme_options_options['radioexample_4'] ) && $this->pp_theme_options_options['radioexample_4'] === 'option-one' ) ? 'checked' : '' ; ?>
			<label for="radioexample_4-0"><input type="radio" name="pp_theme_options_option_name[radioexample_4]" id="radioexample_4-0" value="option-one" <?php echo $checked; ?>> Option One</label><br>
			<?php $checked = ( isset( $this->pp_theme_options_options['radioexample_4'] ) && $this->pp_theme_options_options['radioexample_4'] === 'option-two' ) ? 'checked' : '' ; ?>
			<label for="radioexample_4-1"><input type="radio" name="pp_theme_options_option_name[radioexample_4]" id="radioexample_4-1" value="option-two" <?php echo $checked; ?>> Option Two</label><br>
			<?php $checked = ( isset( $this->pp_theme_options_options['radioexample_4'] ) && $this->pp_theme_options_options['radioexample_4'] === 'option-xxx' ) ? 'checked' : '' ; ?>
			<label for="radioexample_4-2"><input type="radio" name="pp_theme_options_option_name[radioexample_4]" id="radioexample_4-2" value="option-xxx" <?php echo $checked; ?>> Option XXX</label>
		</fieldset>
		<?php
	}

	/**
	 * Add new fields to wp-admin/options-general.php page
	 */
	public function pp_theme_add_blog_denomination_field() {
		register_setting( 'general', 'blogdenomination', 'esc_attr' );
		add_settings_field(
			'blogdenomination',
			'<label for="extra_blog_desc_id">Denominação do órgão</label>',
			array( $this, 'pp_theme_blog_denomination_callback' ),
			'general'
		);
	}

	/**
	 * HTML for extra settings
	 */
	public function pp_theme_blog_denomination_callback() {
		$value = get_option( 'blogdenomination', '' );
		echo '<input type="text" id="extra_blog_desc_id" name="blogdenomination" value="' . esc_attr( $value ) . '" />';
	}

}
if ( is_admin() )
	$pp_theme_options = new PPThemeOptions();

/*
 * Retrieve this value with:
 * $pp_theme_options_options = get_option( 'pp_theme_options_option_name' ); // Array of All Options
 * $custom_color_palette = $pp_theme_options_options['custom_color_palette']; // TextExample
 * $textareaexample_1 = $pp_theme_options_options['textareaexample_1']; // TextareaExample
 * $color_palette = $pp_theme_options_options['color_palette']; // colorPalette
 * $show_search = $pp_theme_options_options['show_search']; // CheckboxExample
 * $radioexample_4 = $pp_theme_options_options['radioexample_4']; // RadioExample
 */

/**
 * Adds a metabox with different options to pages
 *
 */
add_action( 'add_meta_boxes', 'add_pp_wp_page_option_metabox' );
add_action( 'save_post', 'pp_wp_page_save_options_from_metabox', 10, 2 );
function add_pp_wp_page_option_metabox(){

	add_meta_box (
		'pp-wp-page-option',
		'Opções de página',
		'pp_wp_page_option_metabox',
		'page',
		'side',
		'high'
	);

}
function pp_wp_page_option_metabox ( $post ) { ?>

	<?php
        wp_nonce_field( basename( __FILE__ ), 'pp_wp_page_option_metabox_nonce' );
        $hide_breadcrumbs = get_post_meta( $post->ID, 'hide-breadcrumbs', true );
        $hide_page_title = get_post_meta( $post->ID, 'hide-page-title', true );
        $remove_internal_padding = get_post_meta( $post->ID, 'remove-internal-padding', true );
    ?>

    <p>
        <label for="hide-breadcrumbs">
            <input id="hide-breadcrumbs" name="hide-breadcrumbs" type="checkbox" value="1" <?php echo $hide_breadcrumbs ? 'checked="true"' : ''; ?>> Remover trilha de navegação (breadcrumbs) da página
        </label>
    </p>
    <p>
        <label for="hide-page-title">
            <input id="hide-page-title" name="hide-page-title" type="checkbox" value="1" <?php echo $hide_page_title ? 'checked="true"' : ''; ?>> Remover título principal da página
        </label>
    </p>
    <p>
        <label for="remove-internal-padding">
            <input id="remove-internal-padding" name="remove-internal-padding" type="checkbox" value="1" <?php echo $remove_internal_padding? 'checked="true"' : ''; ?>> Remover espaçamento interno
        </label>
    </p>

<?php }

/**
 * Saving the metabox with options
 *
 */
function pp_wp_page_save_options_from_metabox( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['pp_wp_page_option_metabox_nonce'] ) || !wp_verify_nonce( $_POST['pp_wp_page_option_metabox_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );

	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	// $new_meta_value = ( isset( $_POST['hide-breadcrumbs'] ) ? sanitize_html_class( $_POST['hide-breadcrumbs'] ) : '' );
	$hide_breadcrumbs = ( isset( $_POST['hide-breadcrumbs'] ) ? true : false );
	$hide_page_title = ( isset( $_POST['hide-page-title'] ) ? true : false );
	$remove_internal_padding = ( isset( $_POST['remove-internal-padding'] ) ? true : false );

	update_post_meta( $post_id, 'hide-breadcrumbs', $hide_breadcrumbs );
	update_post_meta( $post_id, 'hide-page-title', $hide_page_title );
	update_post_meta( $post_id, 'remove-internal-padding', $remove_internal_padding);

	/* Get the meta key. */
	// $meta_key = 'smashing_post_class';

	/* Get the meta value of the custom field key. */
	// $meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	// if ( $new_meta_value && '' == $meta_value )
    //		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
    // elseif ( $new_meta_value && $new_meta_value != $meta_value )
    //		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
    // elseif ( '' == $new_meta_value && $meta_value )
    //		delete_post_meta( $post_id, $meta_key, $meta_value );

}