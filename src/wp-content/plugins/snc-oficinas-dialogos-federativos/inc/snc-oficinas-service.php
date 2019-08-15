<?php

if (!defined('WPINC'))
    die();

final class SNC_Oficinas_Service
{
    public static function get_quantitativo_inscritos($postIdOficina = null)
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;

        $conditionAnd = [];
        $command = "get_results";

        if (null != $postIdOficina) {
            $conditionAnd[] = "o.ID = {$postIdOficina}";
            $command = "get_row";
        }

        $where = (count($conditionAnd) > 0 ? " AND " : "") . implode(" AND ", $conditionAnd);

        $query = "SELECT o.ID, 
                         o.post_title, 
                         DATE_FORMAT(STR_TO_DATE(ini.meta_value, '%Y%m%d'), '%d/%m/%Y') AS data_inicio,
                         DATE_FORMAT(STR_TO_DATE(fim.meta_value, '%Y%m%d'), '%d/%m/%Y') AS data_fim,
                         uf.meta_value AS uf,
                         nt.meta_value AS total_vagas,
                         COUNT(insc.ID) AS total_inscritos,
                         COUNT(conf.ID) AS total_confirmados,
                         COUNT(canc.ID) AS total_cancelados,
                         COUNT(list.ID) AS total_lista_espera,
                         COUNT(pend.ID) AS total_pendentes
                    FROM {$postTable} o 
                    JOIN {$postMetaTable} ini 
                      ON ini.post_id = o.ID 
                     AND ini.meta_key = 'oficina_data_inicio'
                    JOIN {$postMetaTable} fim
                      ON fim.post_id = o.ID 
                     AND fim.meta_key = 'oficina_data_final'
                    JOIN {$postMetaTable} uf
                      ON uf.post_id = o.ID
                     AND uf.meta_key = 'oficina_unidade_da_federacao' 
                    JOIN {$postMetaTable} nt
                      ON nt.post_id = o.ID
                     AND nt.meta_key = 'oficina_numero_turma' 
                    LEFT JOIN {$postMetaTable} io 
                      ON io.meta_value = o.ID
                     AND io.meta_key = 'inscricao_oficina_uf'
                    LEFT JOIN {$postTable} insc
                      ON insc.ID = io.post_id
                     AND insc.post_status NOT IN ('auto-draft')
                    LEFT JOIN {$postTable} conf
                      ON conf.ID = io.post_id
                     AND conf.post_status = 'confirmed'
                    LEFT JOIN {$postTable} canc
                      ON canc.ID = io.post_id
                     AND canc.post_status = 'canceled'
                    LEFT JOIN {$postTable} list
                      ON list.ID = io.post_id
                     AND list.post_status = 'waiting_list'
                    LEFT JOIN {$postTable} pend
                      ON pend.ID = io.post_id
                     AND pend.post_status = 'pending'
                   WHERE o.post_type = 'oficinas'
                     AND o.post_status NOT IN ('auto-draft', 'canceled')
                     AND STR_TO_DATE(fim.meta_value, '%Y%m%d') >= NOW()
                     {$where}
                   GROUP BY o.ID, 
                            o.post_title,
                            ini.meta_value,
                            fim.meta_value,
                            uf.meta_value,
                            nt.meta_value";

        return $wpdb->$command($query);
    }

    public static function get_email_admin()
    {
        global $wpdb;

        $metakey = str_replace("_posts", "", $wpdb->posts);

        $userTable = $wpdb->users;
        $userMetaTable = $wpdb->usermeta;

        $query = "SELECT u.user_email, u.display_name 
                    FROM {$userTable} u 
                    JOIN {$userMetaTable} um
                      ON um.user_id = u.ID
                   WHERE um.meta_key LIKE '%{$metakey}%' 
                     AND um.meta_value LIKE '%administrator%'";

        return $wpdb->get_results($query);
    }

    public static function get_oficina_insc_waiting($post_id, $limitVal = 0)
    {
        $limit = (int)$limitVal > 0 ? " LIMIT {$limitVal} " : "";

        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;

        $query = "SELECT o.ID, 
                         o.post_title, 
                         uf.meta_value AS uf,
                         nt.meta_value AS total_vagas,
                         list.ID AS post_id_lista_espera
                    FROM {$postTable} o 
                    JOIN {$postMetaTable} uf
                      ON uf.post_id = o.ID
                     AND uf.meta_key = 'oficina_unidade_da_federacao' 
                    JOIN {$postMetaTable} nt 
                      ON nt.post_id = o.ID 
                     AND nt.meta_key = 'oficina_numero_turma'
                    JOIN {$postMetaTable} io 
                      ON io.meta_value = o.ID
                     AND io.meta_key = 'inscricao_oficina_uf'
                    JOIN {$postTable} list
                      ON list.ID = io.post_id
                     AND list.post_status = 'waiting_list'
                   WHERE o.post_type = 'oficinas'
                     AND o.post_status NOT IN ('auto-draft') 
                     AND o.ID = {$post_id}
                   ORDER BY list.post_date 
                   {$limit}";

        return $wpdb->get_results($query);
    }

    public static function get_oficina_by_insc($post_id)
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;

        $query = "SELECT io.meta_value AS oficina_id 
                    FROM {$postTable} insc  
                    JOIN {$postMetaTable} io 
                      ON io.post_id = insc.ID
                     AND io.meta_key = 'inscricao_oficina_uf'
                   WHERE insc.ID = {$post_id}
                     AND insc.post_type = 'inscricao-oficina'";

        return $wpdb->get_row($query);
    }

    public static function get_oficina_insc($post_id)
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;

        $query = "SELECT o.ID, 
                         o.post_title, 
                         ini.meta_value AS data_inicio,
                         hini.meta_value AS hora_inicio,
                         fim.meta_value AS data_fim,
                         hfim.meta_value AS hora_fim,  
                         wf.ID as post_id
                    FROM {$postTable} o 
                    JOIN {$postMetaTable} ini
                      ON ini.post_id = o.ID
                     AND ini.meta_key = 'oficina_data_inicio'
                    JOIN {$postMetaTable} hini
                      ON hini.post_id = o.ID
                     AND hini.meta_key = 'oficina_horario_inicio'
                    JOIN {$postMetaTable} fim
                      ON fim.post_id = o.ID
                     AND fim.meta_key = 'oficina_data_final'
                    JOIN {$postMetaTable} hfim
                      ON hfim.post_id = o.ID
                     AND hfim.meta_key = 'oficina_horario_termino'
                    JOIN {$postMetaTable} io 
                      ON io.meta_value = o.ID
                     AND io.meta_key = 'inscricao_oficina_uf'
                    JOIN {$postTable} wf
                      ON wf.ID = io.post_id
                     AND wf.post_status = 'waiting_presence'
                   WHERE o.post_type = 'oficinas'
                     AND o.post_status NOT IN ('auto-draft')
                     AND conf.ID = {$post_id}";

        return $wpdb->get_results($query);
    }

    public static function snc_next_oficinas($numDiasAntes = 1)
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;
        $date = date("Y-m-d");

        $query = "SELECT o.ID, 
                         o.post_title, 
                         ini.meta_value AS data_inicio,
                         conf.ID as post_id
                    FROM {$postTable} o 
                    JOIN {$postMetaTable} ini
                      ON ini.post_id = o.ID
                     AND ini.meta_key = 'oficina_data_inicio'
                    JOIN {$postMetaTable} io 
                      ON io.meta_value = o.ID
                     AND io.meta_key = 'inscricao_oficina_uf'
                    JOIN {$postTable} conf
                      ON conf.ID = io.post_id
                     AND conf.post_status = 'confirmed'
                   WHERE o.post_type = 'oficinas'
                     AND o.post_status NOT IN ('auto-draft')
                     AND DATE_FORMAT(DATE_SUB(STR_TO_DATE(ini.meta_value, '%Y%m%d'), INTERVAL {$numDiasAntes} DAY), '%Y-%m-%d') = '{$date}'";

        return $wpdb->get_results($query);
    }

    public static function snc_next_oficinas_to_finish()
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;
        $date = date("Y-m-d H:i:s");

        $query = "SELECT o.ID, 
                         o.post_title, 
                         fim.meta_value AS data_fim,
                         hfim.meta_value AS hora_fim,  
                         conf.ID as post_id
                    FROM {$postTable} o 
                    JOIN {$postMetaTable} fim
                      ON fim.post_id = o.ID
                     AND fim.meta_key = 'oficina_data_final'
                    JOIN {$postMetaTable} hfim
                      ON hfim.post_id = o.ID
                     AND hfim.meta_key = 'oficina_horario_termino'
                    JOIN {$postMetaTable} io 
                      ON io.meta_value = o.ID
                     AND io.meta_key = 'inscricao_oficina_uf'
                    JOIN {$postTable} conf
                      ON conf.ID = io.post_id
                     AND conf.post_status = 'confirmed'
                   WHERE o.post_type = 'oficinas'
                     AND o.post_status NOT IN ('auto-draft')
                     AND CONVERT_TZ(
                                    DATE_FORMAT(
         						                STR_TO_DATE(
         						                            CONCAT_WS(' ', fim.meta_value, hfim.meta_value), 
         						                            '%Y%m%d %H:%i:%s'), 
         						                '%Y-%m-%d %H:%i:%s'), 
         						    'GMT', '+03:00') <= '{$date}'";

        return $wpdb->get_results($query);
    }

    public static function update_list_waiting($post_id)
    {
        global $wpdb;

        $postTable = $wpdb->posts;

        return $wpdb->update($postTable, array('post_status' => 'confirmed',), array('ID' => $post_id), array('%s'), array('%d'));
    }

    public static function trigger_change_waiting_list($post_id)
    {
        $oficinaQuant = self::get_quantitativo_inscritos($post_id);
        $vagasLiberadas = $oficinaQuant->total_vagas - $oficinaQuant->total_confirmados;

        if ($oficinaQuant->total_lista_espera > 0 && $vagasLiberadas > 0) {
            $listaEspera = self::get_oficina_insc_waiting($post_id, $vagasLiberadas);

            foreach ((array)$listaEspera as $lista) {
                if (self::update_list_waiting($lista->post_id_lista_espera)) {
                    $oficinasEmail = new SNC_Oficinas_Email($lista->post_id_lista_espera, 'snc_email_effectiveness_subscription');
                    $oficinasEmail->snc_send_mail_user();
                }
            }
        }

        return;
    }

    public static function trigger_change_waiting_presence()
    {
        $listaFinaliza = self::snc_next_oficinas_to_finish();

        foreach ((array)$listaFinaliza as $lista) {
            $subscription = array('ID' => $lista->post_id, 'post_status' => 'waiting_presence');
            wp_update_post($subscription);
        }
    }
}