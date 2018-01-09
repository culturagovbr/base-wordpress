<?php
/**
 * Portal Padrão Theme Settings
 *
 * @package Portal_Padrão_WP
 */
class PPThemeOptions {
	private $pp_theme_options_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'pp_theme_options_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'pp_theme_options_page_init' ) );
		add_action( 'admin_init' , array( $this , 'pp_theme_add_blog_denomination_field' ) );
	}

	public function pp_theme_options_add_plugin_page() {
		add_submenu_page(
			'themes.php',
			'Configurações do tema',
			'Configurações do tema',
			'manage_options',
			'pp-theme-options',
			array( $this, 'pp_theme_options_create_admin_page' )
		);
	}

	public function pp_theme_options_create_admin_page() {
		$this->pp_theme_options_options = get_option( 'pp_theme_options_option_name' ); ?>

		<div class="wrap">
			<h2>Configurações do tema</h2>
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
			'Configurações visuais',
			array( $this, 'pp_theme_options_visual_section_info' ),
			'pp-theme-options-admin'
		);

		add_settings_section(
			'pp_theme_options_header_section',
			'Cabeçalhos',
			array( $this, 'pp_theme_options_header_section_info' ),
			'pp-theme-options-admin'
		);

		add_settings_field(
			'color_palette', 						// id
			'Paleta de cores do tema', 				// title
			array( $this, 'color_palette_callback' ), 	// callback
			'pp-theme-options-admin', 				// page
			'pp_theme_options_visual_section' 	// section
		);

		add_settings_field(
			'custom_color_palette', 							// id
			'Paleta personalizada',							// title
			array( $this, 'custom_color_palette_callback' ), 	// callback
			'pp-theme-options-admin', 						// page
			'pp_theme_options_visual_section' 			// section
		);

		add_settings_field(
			'textareaexample_1', 						// id
			'TextareaExample', 							// title
			array( $this, 'textareaexample_1_callback' ), 	// callback
			'pp-theme-options-admin', 					// page
			'pp_theme_options_visual_section' 		// section
		);

		add_settings_field(
			'show_search',
			'Mostrar campo de busca',
			array( $this, 'show_search_callback' ),
			'pp-theme-options-admin',
			'pp_theme_options_header_section'
		);

		add_settings_field(
			'show_social_links',
			'Mostrar links para redes sociais',
			array( $this, 'show_social_links_callback' ),
			'pp-theme-options-admin',
			'pp_theme_options_header_section'
		);

		add_settings_field(
			'radioexample_4', 						// id
			'RadioExample', 						// title
			array( $this, 'radioexample_4_callback' ), 	// callback
			'pp-theme-options-admin', 				// page
			'pp_theme_options_visual_section' 	// section
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

		if ( isset( $input['radioexample_4'] ) ) {
			$sanitary_values['radioexample_4'] = $input['radioexample_4'];
		}

		return $sanitary_values;
	}

	public function pp_theme_options_visual_section_info() {

	}

	public function custom_color_palette_callback() {
		printf(
			'<input class="regular-text" type="text" name="pp_theme_options_option_name[custom_color_palette]" id="custom_color_palette" value="%s">',
			isset( $this->pp_theme_options_options['custom_color_palette'] ) ? esc_attr( $this->pp_theme_options_options['custom_color_palette']) : ''
		);
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
			<option value="yellow" <?php echo $selected; ?>>Amarelo</option>
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'blue') ? 'selected' : '' ; ?>
			<option value="blue" <?php echo $selected; ?>>Azul</option>
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'white') ? 'selected' : '' ; ?>
			<option value="white" <?php echo $selected; ?>>Branco</option>
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'green') ? 'selected' : '' ; ?>
			<option value="green" <?php echo $selected; ?>>Verde</option>
			<?php $selected = (isset( $this->pp_theme_options_options['color_palette'] ) && $this->pp_theme_options_options['color_palette'] === 'custom') ? 'selected' : '' ; ?>
			<option value="custom" <?php echo $selected; ?>>Personalizado</option>
		</select>
		<p class="description">
			Esta será a paleta de cores usada no visual do site
		</p>
		<?php
	}

	public function show_search_callback() {
		printf(
			'<input type="checkbox" name="pp_theme_options_option_name[show_search]" id="show_search" value="show_search" %s> <label for="show_search">Sim</label>',
			( isset( $this->pp_theme_options_options['show_search'] ) && $this->pp_theme_options_options['show_search'] === 'show_search' ) ? 'checked' : ''
		); ?>
		<p class="description">
			Marque esta opção para habilitar/desabilitar a visualização do campo de busca no cabeçalho
		</p>
		<?php
	}

	public function show_social_links_callback() {
		printf(
			'<input type="checkbox" name="pp_theme_options_option_name[show_social_links]" id="show_social_links" value="show_social_links" %s> <label for="show_social_links">Sim</label>',
			( isset( $this->pp_theme_options_options['show_social_links'] ) && $this->pp_theme_options_options['show_social_links'] === 'show_social_links' ) ? 'checked' : ''
		); ?>
		<p class="description">
			Marque esta opção para habilitar/desabilitar a visualização dos links para as redes sociais
		</p>
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