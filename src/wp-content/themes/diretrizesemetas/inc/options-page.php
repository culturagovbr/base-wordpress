<?php
/**
 * Register our diretrizesemetas_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'diretrizesemetas_options_page' );
function diretrizesemetas_options_page() {
    // add top level menu page
    add_submenu_page(
        'edit.php?post_type=inscricao',
        'Configurações',
        'Configurações',
        'manage_options',
        'inscricao-options-page',
        'diretrizesemetas_options_page_html' 
    );
}

/**
 * top level menu:
 * callback functions
 */
function diretrizesemetas_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'diretrizesemetas_options', 'diretrizesemetas_options_message', __( 'Configurações salvas', 'diretrizesemetas' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'diretrizesemetas_options' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields( 'diretrizesemetas' );
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections( 'diretrizesemetas' );
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
    register_setting( 'diretrizesemetas', 'diretrizesemetas_options' );

    add_settings_section(
        'diretrizesemetas_main_section',
        'Campos personalizados',
        '',
        'diretrizesemetas'
    );

    add_settings_section(
        'oscar_mail_confirmation_section',
        'Email de confirmação',
        '',
        'diretrizesemetas'
    );

    add_settings_section(
        'export_data_section',
        'Exportação de dados',
        '',
        'diretrizesemetas'
    );

    add_settings_field(
        'acf_group_id_option',
        'Identificação do grupo ACF',
        'acf_group_id_option',
        'diretrizesemetas',
        'diretrizesemetas_main_section',
        [
            'label_for' => 'acf_group_id_option',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'acf_email_id_option',
        'Identificação do campo de email do formulário',
        'acf_email_id_option',
        'diretrizesemetas',
        'diretrizesemetas_main_section',
        [
            'label_for' => 'acf_email_id_option',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'diretrizesemetas_email_from_name',
        'Remetente',
        'diretrizesemetas_email_from_name',
        'diretrizesemetas',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'diretrizesemetas_email_from_name',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'diretrizesemetas_email_from',
        'Email de remetente',
        'diretrizesemetas_email_from',
        'diretrizesemetas',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'diretrizesemetas_email_from',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'diretrizesemetas_email_body',
        'Texto para o email de envio do formulário',
        'diretrizesemetas_email_body',
        'diretrizesemetas',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'diretrizesemetas_email_body',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'diretrizesemetas_monitoring_emails',
        'Emails para monitoramento',
        'diretrizesemetas_monitoring_emails',
        'diretrizesemetas',
        'oscar_mail_confirmation_section',
        [
            'label_for' => 'diretrizesemetas_monitoring_emails',
            'class' => 'form-field',
        ]
    );

    add_settings_field(
        'diretrizesemetas_export_xls',
        'Exportar',
        'diretrizesemetas_export_xls',
        'diretrizesemetas',
        'export_data_section',
        [
            'label_for' => 'diretrizesemetas_export_xls',
            'class' => 'form-field',
        ]
    );
}

function acf_group_id_option( $args ) {
    $options = get_option( 'diretrizesemetas_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="diretrizesemetas_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['acf_group_id_option']; ?>">
    <p class="description">
        Insira o slug para o grupo de campos personalizados do <a href="<?php echo admin_url('edit.php?post_type=acf-field-group'); ?>">Advanced Custom Fields</a>
    </p>
    <?php
}

function acf_email_id_option( $args ) {
    $options = get_option( 'diretrizesemetas_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="diretrizesemetas_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['acf_email_id_option']; ?>">
    <p class="description">
        Insira o slug para do campo email do formulário
    </p>
    <?php
}

function diretrizesemetas_email_from_name( $args ) {
    $options = get_option( 'diretrizesemetas_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="diretrizesemetas_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['diretrizesemetas_email_from_name']; ?>">
    <?php
}

function diretrizesemetas_email_from( $args ) {
    $options = get_option( 'diretrizesemetas_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="diretrizesemetas_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['diretrizesemetas_email_from']; ?>">
    <?php
}

function diretrizesemetas_email_body( $args ) {
    $options = get_option( 'diretrizesemetas_options' ); ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="diretrizesemetas_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="10"><?php echo $options['diretrizesemetas_email_body']; ?></textarea>
    <p class="description">
        Mensagem recebida pelo usuário ao realizar o cadastro do formulário.
    </p>
    <?php
}

function diretrizesemetas_email_body_video_received( $args ) {
    $options = get_option( 'diretrizesemetas_options' ); ?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="diretrizesemetas_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="10"><?php echo $options['diretrizesemetas_email_body_video_received']; ?></textarea>
    <p class="description">
        Mensagem recebida pelo usuário após o correto envio do vídeo.
    </p>
 <?php
}

function diretrizesemetas_monitoring_emails( $args ) {
    $options = get_option( 'diretrizesemetas_options' ); ?>

    <input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="diretrizesemetas_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" value="<?php echo $options['diretrizesemetas_monitoring_emails']; ?>">
    <p class="description">
        Estes emails receberão uma notificação sempre que for realizado uma inscrição através do formulário. Separe múltiplos emails com vírgulas.
    </p>
    <?php
}

function diretrizesemetas_export_xls( $args ) {
    $options = get_option( 'diretrizesemetas_options' ); ?>

    <?php
    $args = array( 
        'post_type' => 'inscricao',
        'posts_per_page' => -1
    );
    $the_query = new WP_Query( $args );

    if ( $the_query->have_posts() ) : ?>
        <table id="diretrizesemetas-export-data">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Unidade</th>
                    <th>Pilares</th>
                    <th>Natureza da entrega</th>
                    <th>Classificação da entrega</th>
                    <th>Produto/Entrega</th>
                    <th>Descrição</th>
                    <th>Data limite</th>
                    <th>Custo</th>
                    <th>Situação</th>
                    <th>Execução</th>
                    <th>Ação/Etapas</th>
                    <th>Localizador</th>
                    <th>Outros</th>
                    <th>Descrição</th>
                    <th>Prazo</th>
                    <th>Custo</th>
                    <th>% Execução</th>
                </tr>
            </thead>
            <tbody>
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <?php if ( count( get_field('acoes_etapas') ) > 1 ) { 

                    while ( have_rows('acoes_etapas') ) : the_row(); ?>

                        <tr>
                            <td class="tableexport-string"><?php the_title(); ?></td>
                            <td class="tableexport-string"><?php the_field('unidade'); ?></td>
                            <td class="tableexport-string"><?php the_field('pilares'); ?></td>
                            <td class="tableexport-string"><?php the_field('natureza_da_entrega'); ?></td>
                            <td class="tableexport-string"><?php the_field('classificacao_da_entrega'); ?></td>
                            <td class="tableexport-string"><?php the_field('produto_entrega'); ?></td>
                            <td class="tableexport-string"><?php the_field('descricao'); ?></td>
                            <td class="tableexport-string"><?php the_field('data_limite'); ?></td>
                            <td class="tableexport-string"><?php the_field('custo'); ?></td>
                            <td class="tableexport-string"><?php the_field('situacao'); ?></td>
                            <td class="tableexport-string"><?php the_field('percentual_execucao'); ?></td>
                            <td class="tableexport-string"><?php the_sub_field('acao_etapas'); ?></td>
                            <td class="tableexport-string"><?php the_sub_field('localizador'); ?></td>
                            <td class="tableexport-string"><?php the_sub_field('outros'); ?></td>
                            <td class="tableexport-string"><?php the_sub_field('descricao'); ?></td>
                            <td class="tableexport-string"><?php the_sub_field('prazo'); ?></td>
                            <td class="tableexport-string"><?php the_sub_field('custo'); ?></td>
                            <td class="tableexport-string"><?php the_sub_field('percentual_execucao'); ?></td>
                        </tr>

                    <?php endwhile; ?>
                <?php } else { ?>
                    <tr>
                        <td class="tableexport-string"><?php the_title(); ?></td>
                        <td class="tableexport-string"><?php the_field('unidade'); ?></td>
                        <td class="tableexport-string"><?php the_field('pilares'); ?></td>
                        <td class="tableexport-string"><?php the_field('natureza_da_entrega'); ?></td>
                        <td class="tableexport-string"><?php the_field('classificacao_da_entrega'); ?></td>
                        <td class="tableexport-string"><?php the_field('produto_entrega'); ?></td>
                        <td class="tableexport-string"><?php the_field('descricao'); ?></td>
                        <td class="tableexport-string"><?php the_field('data_limite'); ?></td>
                        <td class="tableexport-string"><?php the_field('custo'); ?></td>
                        <td class="tableexport-string"><?php the_field('situacao'); ?></td>
                        <td class="tableexport-string"><?php the_field('percentual_execucao'); ?></td>
                        <td class="tableexport-string"></td>
                        <td class="tableexport-string"></td>
                        <td class="tableexport-string"></td>
                        <td class="tableexport-string"></td>
                        <td class="tableexport-string"></td>
                        <td class="tableexport-string"></td>
                        <td class="tableexport-string"></td>
                    </tr>
                <?php } ?>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php wp_reset_postdata();
    else :
        // no posts found
    endif; ?>
    <?php
}

if( !empty( $_POST['delete_user_video_sent_meta'] ) ){
    if( !delete_user_meta($_POST['delete_user_video_sent_meta'], '_oscar_video_sent') ){
        error_log("Não foi possível remover a limitação para envio de usuários do ID " . $_POST['delete_user_video_sent_meta']);
    }
}