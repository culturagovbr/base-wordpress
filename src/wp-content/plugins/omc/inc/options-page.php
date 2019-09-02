<?php
/**
 * Register our omc_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'omc_options_page');
function omc_options_page ()
{
	add_submenu_page(
		'edit.php?post_type=indicacao',
		'Configurações OMC',
		'Configurações OMC',
		'manage_options',
		'indicacao-options-page',
		'omc_options_page_html'
	);
}

function omc_options_page_html ()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	if (isset($_GET['settings-updated'])) {
		add_settings_error('omc_options', 'omc_options_message', __('Configurações salvas', 'omc'), 'updated');
	}

	settings_errors('omc_options');
	?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
			<?php
			settings_fields('omc');
			do_settings_sections('omc');
			submit_button('Salvar configurações');
			?>
        </form>
    </div>
	<?php
}

/**
 * register our settings_init to the admin_init action hook
 *
 */
add_action('admin_init', 'omc_settings_init');
function omc_settings_init ()
{
	register_setting(
		'omc',
		'omc_options'
	);

	add_settings_section(
		'oscar_mail_confirmation_section',
		'Email de confirmação',
		'',
		'omc'
	);

	add_settings_section(
		'export_data_section',
		'Exportar dados',
		'',
		'omc'
	);

	add_settings_field(
		'omc_email_from_name',
		'Remetente',
		'omc_email_from_name',
		'omc',
		'oscar_mail_confirmation_section',
		['label_for' => 'omc_email_from_name', 'class' => 'form-field',]
	);

	add_settings_field(
		'omc_email_from',
		'Email de remetente',
		'omc_email_from',
		'omc',
		'oscar_mail_confirmation_section',
		['label_for' => 'omc_email_from', 'class' => 'form-field',]
	);

	add_settings_field(
		'omc_monitoring_emails',
		'Emails para monitoramento',
		'omc_monitoring_emails',
		'omc',
		'oscar_mail_confirmation_section',
		['label_for' => 'omc_monitoring_emails', 'class' => 'form-field',]
	);

	add_settings_field(
        'omc_export_xls',
        'Clique no botão para iniciar o processo de exportação',
        'omc_export_xls',
        'omc',
        'export_data_section',
        ['label_for' => 'omc_export_xls', 'class' => 'form-field',]
    );
}

function omc_email_from_name ($args)
{
	$options = get_option('omc_options'); ?>

    <input id="<?php echo esc_attr($args['label_for']); ?>" name="omc_options[<?php echo esc_attr($args['label_for']); ?>]" type="text" value="<?php echo $options['omc_email_from_name']; ?>">
    <p class="description">
        Nome do rementente do email, o padrão é: <b><?php echo bloginfo('name'); ?></b>
    </p>
	<?php
}

function omc_email_from ($args)
{
	$options = get_option('omc_options'); ?>

    <input id="<?php echo esc_attr($args['label_for']); ?>" name="omc_options[<?php echo esc_attr($args['label_for']); ?>]" type="text" value="<?php echo $options['omc_email_from']; ?>">
    <p class="description">
        Endereço de email do rementente, o padrão é: <b><?php echo get_option('admin_email'); ?></b>
    </p>
	<?php
}

function omc_email_body_video_received ($args)
{
	$options = get_option('omc_options'); ?>
    <textarea id="<?php echo esc_attr($args['label_for']); ?>" name="omc_options[<?php echo esc_attr($args['label_for']); ?>]" rows="10"><?php echo $options['omc_email_body_video_received']; ?></textarea>
    <p class="description">
        Mensagem recebida pelo usuário após o correto envio do vídeo.
    </p>
	<?php
}

function omc_monitoring_emails ($args)
{
	$options = get_option('omc_options'); ?>

    <input id="<?php echo esc_attr($args['label_for']); ?>" name="omc_options[<?php echo esc_attr($args['label_for']); ?>]" type="text" value="<?php echo $options['omc_monitoring_emails']; ?>">
    <p class="description">
        Estes emails receberão uma notificação sempre que for realizado uma indicação através do formulário. Separe múltiplos emails com vírgulas.
    </p>
	<?php
}

function omc_export_xls ($args)
{
	$options = get_option('omc_options'); ?>

	<?php
	$args = array('post_type' => 'indicacao', 'posts_per_page' => -1);
	$the_query = new WP_Query($args);

	if ($the_query->have_posts()) : ?>
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
			<?php $i = 1;
			while ($the_query->have_posts()) : $the_query->the_post(); ?>
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