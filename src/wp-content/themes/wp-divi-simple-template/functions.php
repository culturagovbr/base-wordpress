<?php
// adicionando css que faz a função do divi custom css (Carrega por último)
function divi_child_enqueue_styles() {
    wp_enqueue_style( 'open-sans-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' );
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css');
    wp_enqueue_style( 'alto-contraste', get_stylesheet_directory_uri() . '/assets/css/alto-contraste.css');
    wp_enqueue_script( 'alto-contraste', get_stylesheet_directory_uri() . '/assets/js/alto-contraste.js');
    wp_enqueue_script( 'pdfmake', get_stylesheet_directory_uri() . '/assets/js/pdfmake.min.js');
    wp_enqueue_script( 'vfs_fonts', get_stylesheet_directory_uri() . '/assets/js/vfs_fonts.js');
    wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js');

    $pdaJSObj = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'post_id' => get_the_ID(),
        'post_name' => get_the_title( get_the_ID() ),
    );

    wp_localize_script( 'scripts', 'pdaJSObj', $pdaJSObj );
}

add_action( 'wp_head', 'divi_child_enqueue_styles' );

/**
 * Retrieve a custom option
 */
function get_minc_option( $option ) {
    $options = get_option( 'minc_simpletheme_options' );
    return $options[ $option ];
}

/**
 * Register our options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'oscar_options_page' );
function oscar_options_page() {
    // add top level menu page
    add_submenu_page(
        'themes.php',
        'Configurações do tema',
        'Configurações do tema',
        'manage_options',
        'inscricao-options-page',
        'options_page_html' 
    );
}

/**
 * top level menu:
 * callback functions
 */
function options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'minc_simpletheme_options', 'minc_simpletheme_options_message', __( 'Configurações salvas', 'minc_simpletheme' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'minc_simpletheme_options' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <?php $options = get_option( 'minc_simpletheme_options' ); ?>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields( 'minc_simpletheme' );
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections( 'minc_simpletheme' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

/**
 * register our wporg_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'minc_simpletheme_settings_init' );
function minc_simpletheme_settings_init() {
    register_setting( 'minc_simpletheme', 'minc_simpletheme_options' );

    // Sections
    add_settings_section(
        'minc_simpletheme_general_options_section',
        'Geral',
        '',
        'minc_simpletheme'
    );

    add_settings_section(
        'minc_simpletheme_header_options_section',
        'Cabeçalho',
        '',
        'minc_simpletheme'
    );

    // Fields
    add_settings_field(
        'minc_simpletheme_theme_color',
        'Esquema de cores',
        'minc_simpletheme_theme_color',
        'minc_simpletheme',
        'minc_simpletheme_general_options_section',
        [
            'label_for' => 'theme_color',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'minc_simpletheme_show_searchbar',
        'Mostra campo de busca',
        'minc_simpletheme_show_searchbar',
        'minc_simpletheme',
        'minc_simpletheme_header_options_section',
        [
            'label_for' => 'show_searchbar',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'minc_simpletheme_show_social_links',
        'Mostrar link para as redes sociais',
        'minc_simpletheme_show_social_links',
        'minc_simpletheme',
        'minc_simpletheme_header_options_section',
        [
            'label_for' => 'show_social_links',
            'class' => 'form-field',
        ]
    );
}

function minc_simpletheme_theme_color( $args ) {
    $options = get_option( 'minc_simpletheme_options' ); ?>
    <select name="minc_simpletheme_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
        <option value="verde" <?php if( $options['theme_color'] == 'verde' ) { echo 'selected="true"'; }; ?>>Verde</option>
        <option value="branco" <?php if( $options['theme_color'] == 'branco' ) { echo 'selected="true"'; }; ?>>Branco</option>
        <option value="amarelo" <?php if( $options['theme_color'] == 'amarelo' ) { echo 'selected="true"'; }; ?>>Amarelo</option>
        <option value="azul" <?php if( $options['theme_color'] == 'azul' ) { echo 'selected="true"'; }; ?>>Azul</option>
    </select>
    <p class="description">
        Esta será a paleta de cores usada no visual do site
    </p>
    <?php
}

function minc_simpletheme_show_searchbar( $args ) {
    $options = get_option( 'minc_simpletheme_options' ); ?>
    <label for="<?php echo esc_attr( $args['label_for'] ); ?>">
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="minc_simpletheme_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="checkbox" value="1" <?php checked( "1", $options['show_searchbar'], true ); ?>> Sim
    </label>
    <p class="description">
        Marque esta opção para habilitar/desabilitar a visualização do campo de busca no cabeçalho
    </p>
    <?php
}

function minc_simpletheme_show_social_links( $args ) {
    $options = get_option( 'minc_simpletheme_options' ); ?>
    <label for="<?php echo esc_attr( $args['label_for'] ); ?>">
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="minc_simpletheme_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="checkbox" value="1" <?php checked( "1", $options['show_social_links'], true ); ?>> Sim
    </label>
    <p class="description">
        Marque esta opção para habilitar/desabilitar a visualização dos links para as redes sociais
    </p>
    <?php
}

function minc_simpletheme_show_social_links_xxx( $args ) {
    $options = get_option( 'minc_simpletheme_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="minc_simpletheme_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['oscar_movie_extensions']; ?>">
    <p class="description">
        Defina as extensões permitidas para os vídeos, separando as com vírgulas. Exemplo: mp4, avi, mkv, wmv.
    </p>
    <?php
}