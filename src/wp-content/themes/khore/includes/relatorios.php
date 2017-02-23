<?php

add_action('admin_init', 'relatorios_init');
add_action('admin_menu', 'relatorios_menu');


function relatorios_init() {
    register_setting('relatorios_options', 'relatorios', 'relatorios_validate_callback_function');

    wp_enqueue_script('khore-excel-export', get_template_directory_uri() . '/js/jquery.btechco.excelexport.js');
    wp_enqueue_script('khore-base64', get_template_directory_uri() . '/js/jquery.base.64.js');
    wp_enqueue_script('khore-relatorios', get_template_directory_uri() . '/js/relatorios.js');
    wp_enqueue_style('khore-css-admin', get_template_directory_uri() . '/admin.css');

}

function relatorios_menu() {
    $topLevelMenuLabel = 'Relatórios';
    
    /* Top level menu */
    add_menu_page($topLevelMenuLabel, $topLevelMenuLabel, 'curate', 'relatorios', 'relatorios_sumario_page_callback_function');
}


function relatorios_sumario_page_callback_function() {
    if(!current_user_can('curate')){
        return false;
    }
    $data[] = ['Nome completo', 'Data de inscrição', 'E-mail', 'Telefone', 'Coletivo / Entidade / Organização', 'Cidade', 'Estado', 'País', 'Possui necessidades especiais?', 'Receber informativos?'];
    $patterns_exclude = array(
        '/\"/',    // "
        '/\//',    // /
        "/´/",     // ´
        "/'/",     // '
        "/\[/",      // '
        "/\]/",      // '
        "/`/",
        "/;/"
    );
    
    $inscritos = get_inscritos(); ?>
<style>
#adminmenumain, #wpadminbar, .update-nag {
  display: none;
}

#tabela-relatorios {
margin-left: -160px;
}

#tabela-relatorios td {
font-size: 11px;
border-bottom: 1px solid #ccc;
    line-height: 1.2em;
}

</style>
<a href="."><- Voltar para administração</a>
    
    <div class="wrap span-20" id="tabela-relatorios">
      <h2>Total de inscritos: <?php echo get_total_inscritos(); ?></h1>    
        <table class="wp-list-table widefat" id="tblExport">
            <thead>
                <tr>
    <th scope="col"  class="manage-column column-role">Nome completo</th>
    <th scope="col"  class="manage-column column-role num">Data de inscrição</th>    
      <th scope="col"  class="manage-column column-role num">E-mail</th>
    <th scope="col"  class="manage-column column-role">Telefone</th>  
    <th scope="col"  class="manage-column column-role num">Coletivo / Entidade / Organização</th>
    <th scope="col"  class="manage-column column-role">Cidade</th>
    <th scope="col"  class="manage-column column-role">Estado</th>
    <th scope="col"  class="manage-column column-role">País</th>
    <th scope="col"  class="manage-column column-role">Possui necessidades especiais?</th>
    <th scope="col"  class="manage-column column-role">Receber informativos?</th>
                </tr>
            </thead>      
            <tbody>
                <?php foreach ($inscritos as $inscrito): ?>
<?php
$date = new DateTime($inscrito->data_inscricao);
$inscrito->data_inscricao = $date->format('d/m/Y');

    $field = [
        str_replace(array("'", '"'), '', preg_replace($patterns_exclude, '', $inscrito->nome_completo)), 
        $inscrito->data_inscricao,     
        str_replace(',', '-', preg_replace($patterns_exclude, '', $inscrito->email)), 
        str_replace('/', '-', preg_replace($patterns_exclude, '', $inscrito->telefone)), 
        str_replace(array(',', '/'), '-',  preg_replace($patterns_exclude, '', $inscrito->coletivo_entidade)), 
        str_replace(array(',', '/'), '-', preg_replace($patterns_exclude, '', $inscrito->cidade)),
        $inscrito->UF, 
        $inscrito->pais,
        str_replace(',', '-', preg_replace($patterns_exclude, '', $inscrito->necessidade_especial)), 
        $inscrito->receber_informacoes
    ];

    array_push($data, $field);
?>
                <tr class="alternate">
                                <td><?php echo $inscrito->nome_completo; ?></td>
                                <td><?php echo $inscrito->data_inscricao; ?></td>
                                <td><?php echo $inscrito->email; ?></td>
                                <td><?php echo $inscrito->telefone; ?></td>
                                <td><?php echo $inscrito->coletivo_entidade; ?></td>
                                <td><?php echo $inscrito->cidade; ?></td>
                                <td><?php echo $inscrito->UF; ?></td>
                                <td><?php echo $inscrito->pais; ?></td>
                                <td><?php echo $inscrito->necessidade_especial; ?></td>
                                <td><?php echo $inscrito->receber_informacoes; ?></td>
                            </tr>
                    <?php endforeach ?>
             </tbody> 
        </table>
                    <?php     $data = json_encode($data); ?>
<br/>
<div><h3>Exportar dados para XLS</h3>
<div id="btnExportXLS" class="export"></div>
</div>
        <iframe id="iframeExportar" frameborder="0" src="<?php echo get_template_directory_uri(); ?>/baixar-csv.php" data_filename='relatorio_inscritos' data_csv='<?php echo $data; ?>'>
    </div>
    <?php
   
}
