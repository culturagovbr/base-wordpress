<?php

/* Campos adicionais do usuário */

add_action('edit_user_profile', 'consulta_edit_user_details');
add_action('show_user_profile', 'consulta_edit_user_details');

function consulta_edit_user_details($user) {

    ?>
    <table class="form-table">
    
    <tr>
    
        <th><label>Estado</label></th>
        <td>
            <select  tabindex='16'  name="estado" id="estado"  >
                <option value=""> Selecione </option>
                
                <?php $states = consulta_get_states(); ?>
                <?php foreach ($states as $s): ?>
                
                    <option value="<?php echo $s->sigla; ?>"  <?php if(get_user_meta($user->ID, 'estado', true) == $s->sigla) echo 'selected'; ?>  >
                        <?php echo $s->nome; ?>
                    </option>
                
                <?php endforeach; ?>
                
            </select>
        </td>
    
    </tr>
    
    <tr>
    
        <th><label>Município</label></th>
        <td>
            <input type="hidden" id="disable_first_municipio_ajax_call" value="1" />
            <select name="municipio" id="municipio">
                <?php echo consulta_get_cities_options(get_user_meta($user->ID, 'estado', true), get_user_meta($user->ID, 'municipio', true)); ?>
            </select>
        </td>
    
    </tr>
    
    </table>
    
    <?php
    
}

add_action('personal_options_update', 'consulta_save_user_details');
add_action('edit_user_profile_update', 'consulta_save_user_details');
/**
 * Save creators custom fields add via 
 * administrative profile edit page.
 * 
 * @param int $user_id
 * @return null
 */
function consulta_save_user_details($user_id) {

    update_user_meta($user_id, 'estado', $_POST['estado']);
    update_user_meta($user_id, 'municipio', $_POST['municipio']);
}

function consulta_get_cities_options($uf, $selected = '') {
    global $wpdb;

// var_dump($selected);

    $uf_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM uf WHERE sigla LIKE %s", $uf));

    if (!$uf_id) {
        return "<option value=''>Selecione um estado...</option>";
    }

    $cidades = $wpdb->get_results($wpdb->prepare("SELECT * FROM municipio WHERE ufid = %d order by nome", $uf_id));
    
    $o = '';
    
    
    if (is_array($cidades) && count($cidades) > 0) {

        foreach ($cidades as $cidade) {
            $sel = $cidade->nome == $selected ? 'selected' : '';
            $o .= "<option value='{$cidade->nome}' $sel>{$cidade->nome}</option>";
        }

    }
    
    return $o;
    
}

function consulta_print_cities_options() {

    echo consulta_get_cities_options($_POST['uf'], $_POST['selected']);
    die;
}
add_action('wp_ajax_nopriv_consulta_get_cities_options', 'consulta_print_cities_options');
add_action('wp_ajax_consulta_get_cities_options', 'consulta_print_cities_options');

function consulta_get_states() {
    global $wpdb;
    return $wpdb->get_results("SELECT * from uf ORDER BY sigla");
}
