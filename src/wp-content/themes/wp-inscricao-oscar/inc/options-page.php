<?php

/**
 * Register our oscar_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'oscar_options_page' );
function oscar_options_page() {
    // add top level menu page
    add_submenu_page(
        'edit.php?post_type=inscricao',
        'Configurações',
        'Configurações',
        'manage_options',
        'inscricao-options-page',
        'oscar_options_page_html' 
    );
}

/**
 * top level menu:
 * callback functions
 */
function oscar_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'oscar_options', 'oscar_options_message', __( 'Configurações salvas', 'oscar' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'oscar_options' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields( 'oscar' );
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections( 'oscar' );
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
    register_setting( 'oscar', 'oscar_options' );

    add_settings_section(
        'oscar_video_upload_section',
        'Formulário de envio de vídeo',
        '',
        'oscar'
    );

    add_settings_section(
        'oscar_mail_confirmation_section',
        'Email de confirmação',
        '',
        'oscar'
    );

    add_settings_field(
        'oscar_movie_extensions',
        'Extensões permitidas',
        'oscar_movie_extensions',
        'oscar',
        'oscar_video_upload_section',
        [
            'label_for' => 'oscar_movie_extensions',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'oscar_movie_max_size',
        'Tamanho máximo para o vídeo',
        'oscar_movie_max_size',
        'oscar',
        'oscar_video_upload_section',
        [
            'label_for' => 'oscar_movie_max_size',
        ]
    );

    add_settings_field(
        'oscar_movie_uploaded_message',
        'Mensagem de sucesso ao enviar o vídeo',
        'oscar_movie_uploaded_message',
        'oscar',
        'oscar_video_upload_section',
        [
            'label_for' => 'oscar_movie_uploaded_message',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'oscar_email_from',
        'Email para o remetente',
        'oscar_email_from',
        'oscar',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'oscar_email_from',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'oscar_email_body',
        'Texto para o email de envio do formulário',
        'oscar_email_body',
        'oscar',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'oscar_email_body',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'oscar_email_body_video_received',
        'Texto para o email de recebimento do vídeo',
        'oscar_email_body_video_received',
        'oscar',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'oscar_email_body_video_received',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'oscar_videos_folder',
        'Diretório de upload',
        'oscar_videos_folder',
        'oscar',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'oscar_videos_folder',
            'class' => 'form-field',
        ]
    );

    if( !empty($_GET['debug']) ){
        add_settings_field(
            'oscar_debug_view',
            'Debug',
            'oscar_debug_view',
            'oscar',
            'oscar_mail_confirmation_section',
            [
                'label_for' => 'oscar_debug_view',
                'class' => 'form-field',
            ]
        );

        add_settings_field(
            'oscar_delete_user_video_sent_meta',
            'Deletar envio de vídeo de usuário',
            'oscar_delete_user_video_sent_meta',
            'oscar',
            'oscar_mail_confirmation_section',
            [
                'label_for' => 'oscar_delete_user_video_sent_meta',
                'class' => 'form-field',
            ]
        );
    }
}

function oscar_movie_extensions( $args ) {
    $options = get_option( 'oscar_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="oscar_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['oscar_movie_extensions']; ?>">
    <p class="description">
        Defina as extensões permitidas para os vídeos, separando as com vírgulas. Exemplo: mp4, avi, mkv, wmv.
    </p>
    <?php
}

function oscar_movie_max_size( $args ) {
    $options = get_option( 'oscar_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="oscar_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="number" value="<?php echo $options['oscar_movie_max_size']; ?>">
    <p class="description">
        Tamanho em Gigabytes
    </p>
    <?php
}

function oscar_movie_uploaded_message( $args ) {
    $options = get_option( 'oscar_options' ); ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="oscar_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="5"><?php echo $options['oscar_movie_uploaded_message']; ?></textarea>
    <p class="description">
        Essa é a mensagem que o usuário verá ao enviar um vídeo com sucesso. Além disso, um email de confirmação será enviado para o mesmo.
    </p>
    <?php
}

function oscar_email_from( $args ) {
    $options = get_option( 'oscar_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="oscar_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['oscar_email_from']; ?>">
    <?php
}

function oscar_email_body( $args ) {
    $options = get_option( 'oscar_options' ); ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="oscar_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="10"><?php echo $options['oscar_email_body']; ?></textarea>
    <p class="description">
        Mensagem recebida pelo usuário ao realizar o cadastro do formulário.
    </p>
    <?php
}

function oscar_email_body_video_received( $args ) {
    $options = get_option( 'oscar_options' ); ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="oscar_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="10"><?php echo $options['oscar_email_body_video_received']; ?></textarea>
    <p class="description">
        Mensagem recebida pelo usuário após o correto envio do vídeo.
    </p>
 <?php
}

function oscar_videos_folder( $args ) {
    $uploads = wp_upload_dir();
    $path = $uploads['basedir'] . '/oscar-videos'; ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" value="<?php echo $path; ?>" disabled="true">
    <p class="description">
        Diretório para onde os vídeos são enviados. Campo apenas para visualização!
    </p>
    <?php
}

function oscar_debug_view( $args ) {
    $options = get_option( 'oscar_options' );
    echo '<h3>Opções salvas</h3>';
    echo '<pre>';
    var_dump($options);
    echo '</pre>';

    echo '<h3>Sessões salvas</h3>';
    if( session_start() ){
        echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';        
    }else {
        echo 'Não foi possível iniciar a sessão!';
    }
}

function oscar_delete_user_video_sent_meta( $args ) { ?>
    <input id="delete_user_video_sent_meta" name="delete_user_video_sent_meta" type="number" value="">
    <p class="description">
        Insira o ID do usuário para deletar sua limitação ao enviar vídeos.
    </p>
    <?php
}

if( !empty( $_POST['delete_user_video_sent_meta'] ) ){
    if( !delete_user_meta($_POST['delete_user_video_sent_meta'], '_oscar_video_sent') ){
        error_log("Não foi possível remover a limitação para envio de usuários do ID " . $_POST['delete_user_video_sent_meta']);
    }
}