<?php
/**
 * Register our omc_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'omc_options_page' );
function omc_options_page() {
    // add top level menu page
    add_submenu_page(
        'edit.php?post_type=indicacao',
        'Exportar dados',
        'Exportar dados',
        'manage_options',
        'indicacao-options-page',
        'omc_options_page_html'
    );
}

function omc_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'omc_options', 'omc_options_message', __( 'Configurações salvas', 'omc' ), 'updated' );
    }

    settings_errors( 'omc_options' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'omc' );
            do_settings_sections( 'omc' );
            // submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

/**
 * register our wporg_settings_init to the admin_init action hook
 *
 */
add_action( 'admin_init', 'omc_settings_init' );
function omc_settings_init() {
    register_setting( 'omc', 'omc_options' );

    add_settings_section(
        'export_data_section',
        'Clique no botão abaixo para iniciar o processo de importação',
        '',
        'omc'
    );

    add_settings_field(
        'omc_export_xls',
        'Exportar',
        'omc_export_xls',
        'omc',
        'export_data_section',
        [
            'label_for' => 'omc_export_xls',
            'class' => 'form-field',
        ]
    );
}

function omc_export_xls( $args ) {
    $options = get_option( 'omc_options' ); ?>

    <?php
    $args = array( 
        'post_type' => 'indicacao',
        'posts_per_page' => -1
    );
    $the_query = new WP_Query( $args );

    if ( $the_query->have_posts() ) : ?>
        <table id="omc-export-data">
            <thead style="display: none;">
                <tr>
                    <th>#</th>
                    <th>Identificação</th>
                    <th>Segmentos Culturais</th>
                    <th>Nome Completo do Indicado</th>
                    <th>Nome Artístico do Indicado</th>
                    <th>Indicação In Memoriam</th>
                    <th>Sexo</th>
                    <th>Endereço do Indicado</th>
                    <th>CEP do Indicado</th>
                    <th>Telefone Residencial do Indicado</th>
                    <th>Telefone Celular do Indicado</th>
                    <th>E-mail do Indicado</th>
                    <th>Justificativa (Breve Currículo)</th>
                    <th>Nome Completo de quem Indicou</th>
                    <th>Sexo de quem Indicou</th>
                    <th>Endereço de quem Indicou</th>
                    <th>CEP de quem Indicou</th>
                    <th>Telefone Residencial de quem Indicou</th>
                    <th>Telefone Celular de quem Indicou</th>
                    <th>E-mail de quem Indicou</th>
                </tr>
            </thead>
            <tbody style="display: none;">
            <?php $i = 1; while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <tr>
                    <td class="tableexport-string"><?php echo $i; ?></td>
                    <td class="tableexport-string"><?php the_title(); ?></td>
                    <td class="tableexport-string"><?php the_field('segmentos_culturais'); ?></td>
                    <td class="tableexport-string"><?php the_field('nome_completo_do_indicado'); ?></td>
                    <td class="tableexport-string"><?php the_field('nome_artistico_do_indicado'); ?></td>
                    <td class="tableexport-string"><?php the_field('indicacao_in_memoriam'); ?></td>
                    <td class="tableexport-string"><?php the_field('sexo'); ?></td>
                    <td class="tableexport-string"><?php the_field('endereco_do_indicado'); ?></td>
                    <td class="tableexport-string"><?php the_field('cep_do_indicado'); ?></td>
                    <td class="tableexport-string"><?php the_field('telefone_residencial_do_indicado'); ?></td>
                    <td class="tableexport-string"><?php the_field('telefone_celular_do_indicado'); ?></td>
                    <td class="tableexport-string"><?php the_field('e-mail_do_indicado'); ?></td>
                    <td class="tableexport-string"><?php the_field('justificativa_breve_curriculo'); ?></td>
                    <td class="tableexport-string"><?php the_field('nome_completo_de_quem_indicou'); ?></td>
                    <td class="tableexport-string"><?php the_field('sexo_de_quem_indicou'); ?></td>
                    <td class="tableexport-string"><?php the_field('endereco_de_quem_indicou'); ?></td>
                    <td class="tableexport-string"><?php the_field('cep_de_quem_indicou'); ?></td>
                    <td class="tableexport-string"><?php the_field('telefone_residencial_de_quem_indicou'); ?></td>
                    <td class="tableexport-string"><?php the_field('telefone_celular_de_quem_indicou'); ?></td>
                    <td class="tableexport-string"><?php the_field('e-mail_de_quem_indicou'); ?></td>
                </tr>
            <?php $i++; endwhile; ?>
            </tbody>
        </table>
        <?php wp_reset_postdata();
    else :
        // no posts found
    endif; ?>
    <?php
}