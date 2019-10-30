<?php
/**
 * Register our oscar_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'software_catalog_options_page' );
function software_catalog_options_page() {
    // add top level menu page
    add_submenu_page(
        'options-general.php',
        'Catálogo de Software',
        'Catálogo de Software',
        'manage_options',
        'software-catalog-options-page',
        'software_catalog_options_page_html' 
    );
}

/**
 * Top level menu
 * 
 */
function software_catalog_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        // add_settings_error( 'software_catalog_options', 'software_catalog_options_message', 'Configurações salvas', 'updated' );
    }

    // show error/update messages
    settings_errors( 'software_catalog_options' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields( 'software_catalog_settings_field' );
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections( 'software_catalog_settings_field' );
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
add_action( 'admin_init', 'oscar_settings_init' );
function oscar_settings_init() {
    register_setting( 'software_catalog_settings_field', 'software_catalog_options' );

    add_settings_section(
        'main_section',
        'Configurações',
        '',
        'software_catalog_settings_field'
    );

    add_settings_section(
        'layout_section',
        'Layout',
        '',
        'software_catalog_settings_field'
    );

    add_settings_field(
        'organizations',
        'Organizações',
        'organizations',
        'software_catalog_settings_field',
        'main_section',
        [
            'label_for' => 'organizations'
        ]
    );

    add_settings_field(
        'upper_title',
        'Título para os repositórios',
        'upper_title',
        'software_catalog_settings_field',
        'main_section',
        [
            'label_for' => 'upper_title'
        ]
    );

    add_settings_field(
        'team',
        'Time',
        'team',
        'software_catalog_settings_field',
        'main_section',
        [
            'label_for' => 'team'
        ]
    );

    add_settings_field(
        'lower_title',
        'Título para o time',
        'lower_title',
        'software_catalog_settings_field',
        'main_section',
        [
            'label_for' => 'lower_title'
        ]
    );

    add_settings_field(
        'layout_columns',
        'Colunas',
        'layout_columns',
        'software_catalog_settings_field',
        'layout_section',
        [
            'label_for' => 'layout_columns'
        ]
    );

    add_settings_field(
        'disable_default_css',
        'Desabilitar CSS',
        'disable_default_css',
        'software_catalog_settings_field',
        'layout_section',
        [
            'label_for' => 'disable_default_css'
        ]
    );

    add_settings_field(
        'add_custom_css',
        'Adicionar CSS',
        'add_custom_css',
        'software_catalog_settings_field',
        'layout_section',
        [
            'label_for' => 'add_custom_css'
        ]
    );
}

function organizations( $args ) {
    $options = get_option( 'software_catalog_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['organizations']; ?>" class="regular-text">
    <p class="description">
        Defina as organizações, separando-as com vírgulas. Exemplo: culturagovbr, CoordCulturaDigital-Minc, google, facebook, etc.
    </p>
    <?php
}

function upper_title( $args ) {
    $options = get_option( 'software_catalog_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['upper_title']; ?>" class="regular-text">
    <p class="description">
        Deixe em branco para remover qualquer título.
    </p>
    <?php
}

function team( $args ) {
    $options = get_option( 'software_catalog_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['team']; ?>" class="regular-text">
    <p class="description">
        Identificação do time. Exemplo: culturagovbr.
    </p>
    <?php
}

function lower_title( $args ) {
    $options = get_option( 'software_catalog_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['lower_title']; ?>" class="regular-text">
    <p class="description">
        Deixe em branco para remover qualquer título.
    </p>
    <?php
}

function layout_columns( $args ) {
    $options = get_option( 'software_catalog_options' ); ?>

    <label for="<?php echo esc_attr( $args['label_for'] ); ?>-1" style="margin-right: 15px;">
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>-1" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="radio" value="1" <?php checked(1, $options['layout_columns'], true); ?>>
        1 Coluna
    </label>
    <label for="<?php echo esc_attr( $args['label_for'] ); ?>-2" style="margin-right: 15px;">
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>-2" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="radio" value="2" <?php checked(2, $options['layout_columns'], true); ?>>
        2 Colunas
    </label>
    <label for="<?php echo esc_attr( $args['label_for'] ); ?>-3" style="margin-right: 15px;">
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>-3" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="radio" value="3" <?php checked(3, $options['layout_columns'], true); ?>>
        3 Colunas
    </label>
    <label for="<?php echo esc_attr( $args['label_for'] ); ?>-4" style="margin-right: 15px;">
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>-4" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="radio" value="4" <?php checked(4, $options['layout_columns'], true); ?>>
        4 Colunas
    </label>

    <?php
}


function disable_default_css( $args ) {
    $options = get_option( 'software_catalog_options' ); ?>
    <label for="<?php echo esc_attr( $args['label_for'] ); ?>">
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="checkbox" value="1" <?php checked(1, $options['disable_default_css'], true); ?>>
        Sim
    </label>
    <?php
}

function add_custom_css( $args ) {
    $options = get_option( 'software_catalog_options' ); ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="software_catalog_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="5" cols="75"><?php echo $options['add_custom_css']; ?></textarea>
    <p class="description">
        Adicione CSS puro. Exemplo: <code>#software-catalog-repos .software-catalog-project h2{ color: #2ea3f2; }</code>
    </p>
    <?php
}