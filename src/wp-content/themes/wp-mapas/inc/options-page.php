<?php
/**
 * Register our mapasculturais_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'mapasculturais_options_page' );
function mapasculturais_options_page() {
    // add top level menu page
    add_submenu_page(
        'edit.php?post_type=inscricao',
        'Configurações',
        'Configurações',
        'manage_options',
        'inscricao-options-page',
        'mapasculturais_options_page_html' 
    );
}

/**
 * top level menu:
 * callback functions
 */
function mapasculturais_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'mapasculturais_options', 'mapasculturais_options_message', __( 'Configurações salvas', 'mapasculturais' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'mapasculturais_options' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields( 'mapasculturais' );
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections( 'mapasculturais' );
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
    register_setting( 'mapasculturais', 'mapasculturais_options' );

    add_settings_section(
        'mapasculturais_main_section',
        'Campos personalizados',
        '',
        'mapasculturais'
    );

    add_settings_section(
        'oscar_mail_confirmation_section',
        'Email de confirmação',
        '',
        'mapasculturais'
    );

    add_settings_field(
        'acf_group_id_option',
        'Identificação do grupo ACF',
        'acf_group_id_option',
        'mapasculturais',
        'mapasculturais_main_section',
        [
            'label_for' => 'acf_group_id_option',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'acf_email_id_option',
        'Identificação do campo de email do formulário',
        'acf_email_id_option',
        'mapasculturais',
        'mapasculturais_main_section',
        [
            'label_for' => 'acf_email_id_option',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'mapasculturais_email_from_name',
        'Remetente',
        'mapasculturais_email_from_name',
        'mapasculturais',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'mapasculturais_email_from_name',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'mapasculturais_email_from',
        'Email de remetente',
        'mapasculturais_email_from',
        'mapasculturais',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'mapasculturais_email_from',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'mapasculturais_email_body',
        'Texto para o email de envio do formulário',
        'mapasculturais_email_body',
        'mapasculturais',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'mapasculturais_email_body',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'mapasculturais_monitoring_emails',
        'Emails para monitoramento',
        'mapasculturais_monitoring_emails',
        'mapasculturais',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'mapasculturais_monitoring_emails',
            'class' => 'form-field',
        ]
    );
}

function acf_group_id_option( $args ) {
    $options = get_option( 'mapasculturais_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="mapasculturais_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['acf_group_id_option']; ?>">
    <p class="description">
        Insira o slug para o grupo de campos personalizados do <a href="<?php echo admin_url('edit.php?post_type=acf-field-group'); ?>">Advanced Custom Fields</a>
    </p>
    <?php
}

function acf_email_id_option( $args ) {
    $options = get_option( 'mapasculturais_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="mapasculturais_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['acf_email_id_option']; ?>">
    <p class="description">
        Insira o slug para do campo email do formulário
    </p>
    <?php
}

function mapasculturais_email_from_name( $args ) {
    $options = get_option( 'mapasculturais_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="mapasculturais_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['mapasculturais_email_from_name']; ?>">
    <?php
}

function mapasculturais_email_from( $args ) {
    $options = get_option( 'mapasculturais_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="mapasculturais_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['mapasculturais_email_from']; ?>">
    <?php
}

function mapasculturais_email_body( $args ) {
    $options = get_option( 'mapasculturais_options' ); ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="mapasculturais_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="10"><?php echo $options['mapasculturais_email_body']; ?></textarea>
    <p class="description">
        Mensagem recebida pelo usuário ao realizar o cadastro do formulário.
    </p>
    <?php
}

function mapasculturais_email_body_video_received( $args ) {
    $options = get_option( 'mapasculturais_options' ); ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="mapasculturais_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="10"><?php echo $options['mapasculturais_email_body_video_received']; ?></textarea>
    <p class="description">
        Mensagem recebida pelo usuário após o correto envio do vídeo.
    </p>
 <?php
}

function mapasculturais_monitoring_emails( $args ) {
    $options = get_option( 'mapasculturais_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="mapasculturais_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['mapasculturais_monitoring_emails']; ?>">
    <p class="description">
        Estes emails receberão uma notificação sempre que for realizado uma inscrição através do formulário. Separe múltiplos emails com vírgulas.
    </p>
    <?php
}