<?php

if (!defined('WPINC'))
    die();

require_once SNC_ODF_PLUGIN_PATH . '/vendor/autoload.php';

final class SNC_Oficinas_Service
{
    public static function get_quantitativo_inscritos($postIdOficina = null)
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;

        $conditionAnd = [];
        $command = 'get_results';

        if (null != $postIdOficina) {
            $conditionAnd[] = 'o.ID = {$postIdOficina}';
            $command = 'get_row';
        }

        $where = (count($conditionAnd) > 0 ? ' AND ' : '') . implode(' AND ', $conditionAnd);

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
                         COUNT(pend.ID) AS total_pendentes,
                         COUNT(pres.ID) AS total_faltam_confimar_presenca,
                         COUNT(quest.ID) AS total_faltam_responder_questionario,
                         COUNT(finish.ID) AS total_finalizados,
                         COUNT(quest.ID) + COUNT(finish.ID) AS total_concluidos
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
                     LEFT JOIN {$postTable} pres  
                       ON pres.ID = io.post_id
                      AND pres.post_status = 'waiting_presence'
                    LEFT JOIN {$postTable} quest 
                      ON quest.ID = io.post_id
                     AND quest.post_status = 'waiting_questions'
                    LEFT JOIN {$postTable} finish 
                      ON finish.ID = io.post_id
                     AND finish.post_status = 'finish'
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

    public static function get_quantitativo_inscritos_concluidos()
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;

        $query = "SELECT o.ID, 
                         o.post_title, 
                         DATE_FORMAT(STR_TO_DATE(ini.meta_value, '%Y%m%d'), '%d/%m/%Y') AS data_inicio,
                         DATE_FORMAT(STR_TO_DATE(fim.meta_value, '%Y%m%d'), '%d/%m/%Y') AS data_fim,
                         uf.meta_value AS uf,
                         nt.meta_value AS total_vagas,
                         COUNT(insc.ID) AS total_inscritos
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
                     AND insc.post_status IN ('waiting_questions', 'finish')
                   WHERE o.post_type = 'oficinas'
                     AND o.post_status NOT IN ('auto-draft', 'canceled')
                   GROUP BY o.ID, 
                            o.post_title,
                            ini.meta_value,
                            fim.meta_value,
                            uf.meta_value,
                            nt.meta_value
                   ORDER BY o.ID, o.post_title";

        return $wpdb->get_results($query);
    }

    public static function get_all_inscritos_concluidos()
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;
        $userTable = $wpdb->users;
        $userMetaTable = $wpdb->usermeta;

        $query = "SELECT o.ID, 
                         o.post_title, 
                         u.display_name,
                         u.user_email,
                         cpf.meta_value AS nu_cpf,
                         rg.meta_value AS nu_rg,
                         endereco.meta_value AS st_endereco,
                         estado.meta_value AS st_estado,
                         municipio.meta_value AS st_municipio
                    FROM {$postTable} o 
                    LEFT JOIN {$postMetaTable} io 
                      ON io.meta_value = o.ID
                     AND io.meta_key = 'inscricao_oficina_uf'
                    JOIN {$postTable} insc
                      ON insc.ID = io.post_id
                     AND insc.post_status IN ('waiting_questions', 'finish')
                    JOIN {$userTable} u 
                      ON u.ID = insc.post_author
                    JOIN {$userMetaTable} cpf 
                      ON cpf.user_id = u.ID
                     AND cpf.meta_key = '_user_cpf'
                    JOIN {$userMetaTable} rg 
                      ON rg.user_id = u.ID
                     AND rg.meta_key = '_user_rg'
                    JOIN {$userMetaTable} endereco 
                      ON endereco.user_id = u.ID
                     AND endereco.meta_key = '_user_address'
                    JOIN {$userMetaTable} estado 
                      ON estado.user_id = u.ID
                     AND estado.meta_key = '_user_state'
                    JOIN {$userMetaTable} municipio 
                      ON municipio.user_id = u.ID
                     AND municipio.meta_key = '_user_county'
                   WHERE o.post_type = 'oficinas'
                     AND o.post_status NOT IN ('auto-draft', 'canceled')
                   ORDER BY o.ID, o.post_title";

        return $wpdb->get_results($query);
    }

    public static function get_all_inscritos()
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;
        $userTable = $wpdb->users;
        $userMetaTable = $wpdb->usermeta;

        $query = "SELECT o.ID, 
                         o.post_title as oficina, 
                         insc.ID AS num_inscricao,
                         u.display_name AS nome,
                         u.user_email AS email,
                         cpf.meta_value AS nu_cpf,
                         rg.meta_value AS nu_rg,
                         fone.meta_value AS telefone,
                         endereco.meta_value AS st_endereco,
                         estado.meta_value AS st_estado,
                         municipio.meta_value AS st_municipio,
                         genero.meta_value AS sexo,
                         escolar.meta_value AS escolaridade,
                         insc_perfil.meta_value AS perfil,
                         DATE_FORMAT(STR_TO_DATE(ini.meta_value, '%Y%m%d'), '%d/%m/%Y') AS data_inicio,
                         DATE_FORMAT(STR_TO_DATE(fim.meta_value, '%Y%m%d'), '%d/%m/%Y') AS data_fim,
                         interesse_1.meta_value AS interesse1,
                         interesse_2.meta_value AS interesse2,
                         interesse_3.meta_value AS interesse3,
                         interesse_4.meta_value AS interesse4,
                         interesse_5.meta_value AS interesse5,
                         car.meta_value AS cargo,
                         org.meta_value AS orgao,
                         esf.meta_value AS esfera
                    FROM {$postTable} o 
                    JOIN {$postMetaTable} io 
                      ON io.meta_value = o.ID
                     AND io.meta_key = 'inscricao_oficina_uf'
                    JOIN {$postMetaTable} ini 
                      ON ini.post_id = o.ID 
                     AND ini.meta_key = 'oficina_data_inicio'
                    JOIN {$postMetaTable} fim
                      ON fim.post_id = o.ID 
                     AND fim.meta_key = 'oficina_data_final'
                    JOIN {$postTable} insc
                      ON insc.ID = io.post_id
                    JOIN {$postMetaTable} insc_perfil
                      ON insc_perfil.post_id = insc.ID
                     AND insc_perfil.meta_key = 'inscricao_perfil'
                    JOIN {$userTable} u 
                      ON u.ID = insc.post_author
                    JOIN {$userMetaTable} cpf 
                      ON cpf.user_id = u.ID
                     AND cpf.meta_key = '_user_cpf'
                    JOIN {$userMetaTable} rg 
                      ON rg.user_id = u.ID
                     AND rg.meta_key = '_user_rg'
                    JOIN {$userMetaTable} fone 
                      ON fone.user_id = u.ID
                     AND fone.meta_key = '_user_phone'
                    JOIN {$userMetaTable} endereco 
                      ON endereco.user_id = u.ID
                     AND endereco.meta_key = '_user_address'
                    JOIN {$userMetaTable} estado 
                      ON estado.user_id = u.ID
                     AND estado.meta_key = '_user_state'
                    JOIN {$userMetaTable} municipio 
                      ON municipio.user_id = u.ID
                     AND municipio.meta_key = '_user_county'
                    JOIN {$userMetaTable} genero 
                      ON genero.user_id = u.ID
                     AND genero.meta_key = '_user_gender'
                     JOIN {$userMetaTable} escolar 
                      ON escolar.user_id = u.ID
                     AND escolar.meta_key = '_user_schooling'
                    JOIN {$postMetaTable} interesse_1 
                      ON interesse_1.post_id = insc.ID
                     AND interesse_1.meta_key = 'inscricao_interesse_1'
                    JOIN {$postMetaTable} interesse_2 
                      ON interesse_2.post_id = insc.ID
                     AND interesse_2.meta_key = 'inscricao_interesse_2'
                    JOIN {$postMetaTable} interesse_3 
                      ON interesse_3.post_id = insc.ID
                     AND interesse_3.meta_key = 'inscricao_interesse_3'
                    JOIN {$postMetaTable} interesse_4 
                      ON interesse_4.post_id = insc.ID
                     AND interesse_4.meta_key = 'inscricao_interesse_4'
                    JOIN {$postMetaTable} interesse_5 
                      ON interesse_5.post_id = insc.ID
                     AND interesse_5.meta_key = 'inscricao_interesse_5'
                    LEFT JOIN {$postMetaTable} car 
                      ON car.post_id = insc.ID
                     AND car.meta_key = 'inscricao_gestor_cargo'
                    LEFT JOIN {$postMetaTable} org 
                      ON org.post_id = insc.ID
                     AND org.meta_key = 'inscricao_gestor_orgao'
                    LEFT JOIN {$postMetaTable} esf 
                      ON esf.post_id = insc.ID
                     AND esf.meta_key = 'inscricao_gestor_tipo' 
                   WHERE o.post_type = 'oficinas'
                   ORDER BY STR_TO_DATE(ini.meta_value, '%Y%m%d'), 
                            o.post_title, 
                            u.display_name";

        return $wpdb->get_results($query);
    }

    public static function get_all_interesses()
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;

        $query = "SELECT a.total_inscritos,
                       ROUND((100 * a.total_i_1_1) / a.total_inscritos, 2) AS percentual_i_1_1,
                       ROUND((100 * a.total_i_1_2) / a.total_inscritos, 2) AS percentual_i_1_2,
                       ROUND((100 * a.total_i_1_3) / a.total_inscritos, 2) AS percentual_i_1_3,
                       ROUND((100 * a.total_i_1_4) / a.total_inscritos, 2) AS percentual_i_1_4,
                       ROUND((100 * a.total_i_1_5) / a.total_inscritos, 2) AS percentual_i_1_5,
                       ROUND((100 * a.total_i_1_6) / a.total_inscritos, 2) AS percentual_i_1_6,
                       ROUND((100 * a.total_i_1_7) / a.total_inscritos, 2) AS percentual_i_1_7,
                       ROUND((100 * a.total_i_1_8) / a.total_inscritos, 2) AS percentual_i_1_8,
                       ROUND((100 * a.total_i_1_9) / a.total_inscritos, 2) AS percentual_i_1_9,
                       ROUND((100 * a.total_i_1_10) / a.total_inscritos, 2) AS percentual_i_1_10,
                       ROUND((100 * a.total_i_2_1) / a.total_inscritos, 2) AS percentual_i_2_1,
                       ROUND((100 * a.total_i_2_2) / a.total_inscritos, 2) AS percentual_i_2_2,
                       ROUND((100 * a.total_i_2_3) / a.total_inscritos, 2) AS percentual_i_2_3,
                       ROUND((100 * a.total_i_2_4) / a.total_inscritos, 2) AS percentual_i_2_4,
                       ROUND((100 * a.total_i_2_5) / a.total_inscritos, 2) AS percentual_i_2_5,
                       ROUND((100 * a.total_i_2_6) / a.total_inscritos, 2) AS percentual_i_2_6,
                       ROUND((100 * a.total_i_2_7) / a.total_inscritos, 2) AS percentual_i_2_7,
                       ROUND((100 * a.total_i_2_8) / a.total_inscritos, 2) AS percentual_i_2_8,
                       ROUND((100 * a.total_i_2_9) / a.total_inscritos, 2) AS percentual_i_2_9,
                       ROUND((100 * a.total_i_2_10) / a.total_inscritos, 2) AS percentual_i_2_10,
                       ROUND((100 * a.total_i_3_1) / a.total_inscritos, 2) AS percentual_i_3_1,
                       ROUND((100 * a.total_i_3_2) / a.total_inscritos, 2) AS percentual_i_3_2,
                       ROUND((100 * a.total_i_3_3) / a.total_inscritos, 2) AS percentual_i_3_3,
                       ROUND((100 * a.total_i_3_4) / a.total_inscritos, 2) AS percentual_i_3_4,
                       ROUND((100 * a.total_i_3_5) / a.total_inscritos, 2) AS percentual_i_3_5,
                       ROUND((100 * a.total_i_3_6) / a.total_inscritos, 2) AS percentual_i_3_6,
                       ROUND((100 * a.total_i_3_7) / a.total_inscritos, 2) AS percentual_i_3_7,
                       ROUND((100 * a.total_i_3_8) / a.total_inscritos, 2) AS percentual_i_3_8,
                       ROUND((100 * a.total_i_3_9) / a.total_inscritos, 2) AS percentual_i_3_9,
                       ROUND((100 * a.total_i_3_10) / a.total_inscritos, 2) AS percentual_i_3_10,
                       ROUND((100 * a.total_i_4_1) / a.total_inscritos, 2) AS percentual_i_4_1,
                       ROUND((100 * a.total_i_4_2) / a.total_inscritos, 2) AS percentual_i_4_2,
                       ROUND((100 * a.total_i_4_3) / a.total_inscritos, 2) AS percentual_i_4_3,
                       ROUND((100 * a.total_i_4_4) / a.total_inscritos, 2) AS percentual_i_4_4,
                       ROUND((100 * a.total_i_4_5) / a.total_inscritos, 2) AS percentual_i_4_5,
                       ROUND((100 * a.total_i_4_6) / a.total_inscritos, 2) AS percentual_i_4_6,
                       ROUND((100 * a.total_i_4_7) / a.total_inscritos, 2) AS percentual_i_4_7,
                       ROUND((100 * a.total_i_4_8) / a.total_inscritos, 2) AS percentual_i_4_8,
                       ROUND((100 * a.total_i_4_9) / a.total_inscritos, 2) AS percentual_i_4_9,
                       ROUND((100 * a.total_i_4_10) / a.total_inscritos, 2) AS percentual_i_4_10,
                       ROUND((100 * a.total_i_5_1) / a.total_inscritos, 2) AS percentual_i_5_1,
                       ROUND((100 * a.total_i_5_2) / a.total_inscritos, 2) AS percentual_i_5_2,
                       ROUND((100 * a.total_i_5_3) / a.total_inscritos, 2) AS percentual_i_5_3,
                       ROUND((100 * a.total_i_5_4) / a.total_inscritos, 2) AS percentual_i_5_4,
                       ROUND((100 * a.total_i_5_5) / a.total_inscritos, 2) AS percentual_i_5_5,
                       ROUND((100 * a.total_i_5_6) / a.total_inscritos, 2) AS percentual_i_5_6,
                       ROUND((100 * a.total_i_5_7) / a.total_inscritos, 2) AS percentual_i_5_7,
                       ROUND((100 * a.total_i_5_8) / a.total_inscritos, 2) AS percentual_i_5_8,
                       ROUND((100 * a.total_i_5_9) / a.total_inscritos, 2) AS percentual_i_5_9,
                       ROUND((100 * a.total_i_5_10) / a.total_inscritos, 2) AS percentual_i_5_10
                  FROM (SELECT COUNT(DISTINCT insc.ID) AS total_inscritos,
                                 COUNT(i_1.post_id) AS total_i,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Política Nacional de Cultura Viva (Pontos, Pontões de Cultura)' THEN 1 ELSE 0 END) AS total_i_1_1,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Sistema Nacional de Cultura (Plano, Fundo de Cultura)' THEN 1 ELSE 0 END) AS total_i_1_2,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Novo Plano Nacional de Cultura' THEN 1 ELSE 0 END) AS total_i_1_3,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Gestão compartilhada e Participação Social (Conselho Nacional de Política Cultural e Conferência Nacional de Cultura)' THEN 1 ELSE 0 END) AS total_i_1_4,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Operacionalização dos sistemas de parcerias (Convênios, Consórcios, Termos de fomento, entre outros)' THEN 1 ELSE 0 END) AS total_i_1_5,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Orientações para elaboração de projetos culturais, no âmbito da diversidade cultural' THEN 1 ELSE 0 END) AS total_i_1_6,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Orientações para execução e prestação de contas' THEN 1 ELSE 0 END) AS total_i_1_7,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Acessibilidade Cultural' THEN 1 ELSE 0 END) AS total_i_1_8,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Culturas Populares e Diversidade' THEN 1 ELSE 0 END) AS total_i_1_9,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_1' AND i_1.meta_value = 'Cultura e Educação' THEN 1 ELSE 0 END) AS total_i_1_10,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Política Nacional de Cultura Viva (Pontos, Pontões de Cultura)' THEN 1 ELSE 0 END) AS total_i_2_1,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Sistema Nacional de Cultura (Plano, Fundo de Cultura)' THEN 1 ELSE 0 END) AS total_i_2_2,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Novo Plano Nacional de Cultura' THEN 1 ELSE 0 END) AS total_i_2_3,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Gestão compartilhada e Participação Social (Conselho Nacional de Política Cultural e Conferência Nacional de Cultura)' THEN 1 ELSE 0 END) AS total_i_2_4,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Operacionalização dos sistemas de parcerias (Convênios, Consórcios, Termos de fomento, entre outros)' THEN 1 ELSE 0 END) AS total_i_2_5,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Orientações para elaboração de projetos culturais, no âmbito da diversidade cultural' THEN 1 ELSE 0 END) AS total_i_2_6,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Orientações para execução e prestação de contas' THEN 1 ELSE 0 END) AS total_i_2_7,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Acessibilidade Cultural' THEN 1 ELSE 0 END) AS total_i_2_8,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Culturas Populares e Diversidade' THEN 1 ELSE 0 END) AS total_i_2_9,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_2' AND i_1.meta_value = 'Cultura e Educação' THEN 1 ELSE 0 END) AS total_i_2_10,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Política Nacional de Cultura Viva (Pontos, Pontões de Cultura)' THEN 1 ELSE 0 END) AS total_i_3_1,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Sistema Nacional de Cultura (Plano, Fundo de Cultura)' THEN 1 ELSE 0 END) AS total_i_3_2,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Novo Plano Nacional de Cultura' THEN 1 ELSE 0 END) AS total_i_3_3,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Gestão compartilhada e Participação Social (Conselho Nacional de Política Cultural e Conferência Nacional de Cultura)' THEN 1 ELSE 0 END) AS total_i_3_4,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Operacionalização dos sistemas de parcerias (Convênios, Consórcios, Termos de fomento, entre outros)' THEN 1 ELSE 0 END) AS total_i_3_5,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Orientações para elaboração de projetos culturais, no âmbito da diversidade cultural' THEN 1 ELSE 0 END) AS total_i_3_6,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Orientações para execução e prestação de contas' THEN 1 ELSE 0 END) AS total_i_3_7,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Acessibilidade Cultural' THEN 1 ELSE 0 END) AS total_i_3_8,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Culturas Populares e Diversidade' THEN 1 ELSE 0 END) AS total_i_3_9,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_3' AND i_1.meta_value = 'Cultura e Educação' THEN 1 ELSE 0 END) AS total_i_3_10, 
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Política Nacional de Cultura Viva (Pontos, Pontões de Cultura)' THEN 1 ELSE 0 END) AS total_i_4_1,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Sistema Nacional de Cultura (Plano, Fundo de Cultura)' THEN 1 ELSE 0 END) AS total_i_4_2,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Novo Plano Nacional de Cultura' THEN 1 ELSE 0 END) AS total_i_4_3,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Gestão compartilhada e Participação Social (Conselho Nacional de Política Cultural e Conferência Nacional de Cultura)' THEN 1 ELSE 0 END) AS total_i_4_4,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Operacionalização dos sistemas de parcerias (Convênios, Consórcios, Termos de fomento, entre outros)' THEN 1 ELSE 0 END) AS total_i_4_5,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Orientações para elaboração de projetos culturais, no âmbito da diversidade cultural' THEN 1 ELSE 0 END) AS total_i_4_6,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Orientações para execução e prestação de contas' THEN 1 ELSE 0 END) AS total_i_4_7,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Acessibilidade Cultural' THEN 1 ELSE 0 END) AS total_i_4_8,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Culturas Populares e Diversidade' THEN 1 ELSE 0 END) AS total_i_4_9,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_4' AND i_1.meta_value = 'Cultura e Educação' THEN 1 ELSE 0 END) AS total_i_4_10,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Política Nacional de Cultura Viva (Pontos, Pontões de Cultura)' THEN 1 ELSE 0 END) AS total_i_5_1,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Sistema Nacional de Cultura (Plano, Fundo de Cultura)' THEN 1 ELSE 0 END) AS total_i_5_2,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Novo Plano Nacional de Cultura' THEN 1 ELSE 0 END) AS total_i_5_3,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Gestão compartilhada e Participação Social (Conselho Nacional de Política Cultural e Conferência Nacional de Cultura)' THEN 1 ELSE 0 END) AS total_i_5_4,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Operacionalização dos sistemas de parcerias (Convênios, Consórcios, Termos de fomento, entre outros)' THEN 1 ELSE 0 END) AS total_i_5_5,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Orientações para elaboração de projetos culturais, no âmbito da diversidade cultural' THEN 1 ELSE 0 END) AS total_i_5_6,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Orientações para execução e prestação de contas' THEN 1 ELSE 0 END) AS total_i_5_7,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Acessibilidade Cultural' THEN 1 ELSE 0 END) AS total_i_5_8,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Culturas Populares e Diversidade' THEN 1 ELSE 0 END) AS total_i_5_9,
                                 SUM(CASE WHEN i_1.meta_key = 'inscricao_interesse_5' AND i_1.meta_value = 'Cultura e Educação' THEN 1 ELSE 0 END) AS total_i_5_10
                            FROM {$postTable} o
                            JOIN {$postMetaTable} io 
                              ON io.meta_value = o.ID
                             AND io.meta_key = 'inscricao_oficina_uf'
                            LEFT JOIN {$postTable} insc
                              ON insc.ID = io.post_id 
                            LEFT JOIN {$postMetaTable} i_1 
                              ON i_1.post_id = insc.ID
                             AND i_1.meta_key IN ('inscricao_interesse_1', 'inscricao_interesse_2', 'inscricao_interesse_3', 'inscricao_interesse_4', 'inscricao_interesse_5')
                             AND i_1.meta_value IN ('Política Nacional de Cultura Viva (Pontos, Pontões de Cultura)',
                                                     'Sistema Nacional de Cultura (Plano, Fundo de Cultura)',
                                                     'Novo Plano Nacional de Cultura',
                                                     'Gestão compartilhada e Participação Social (Conselho Nacional de Política Cultural e Conferência Nacional de Cultura)',
                                                     'Operacionalização dos sistemas de parcerias (Convênios, Consórcios, Termos de fomento, entre outros)', 
                                                     'Orientações para elaboração de projetos culturais, no âmbito da diversidade cultural', 
                                                     'Orientações para execução e prestação de contas', 
                                                     'Acessibilidade Cultural', 
                                                     'Culturas Populares e Diversidade', 
                                                     'Cultura e Educação')
                    ) a";

        return $wpdb->get_row($query);
    }

    public static function get_email_admin()
    {
        global $wpdb;

        $metakey = str_replace('_posts', '', $wpdb->posts);

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
        $limit = (int)$limitVal > 0 ? ' LIMIT {$limitVal} ' : '';

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
        $date = date('Y-m-d');

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
        $date = date('Y-m-d H:i:s');

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

    public static function snc_oficinas_to_finish()
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;
        $date = date('Y-m-d H:i:s');

        $query = "SELECT o.ID, 
                         o.post_title, 
                         ini.meta_value AS data_inicio
                    FROM {$postTable} o 
                    JOIN {$postMetaTable} ini
                      ON ini.post_id = o.ID
                     AND ini.meta_key = 'oficina_data_inicio'
                   WHERE o.post_type = 'oficinas'
                     AND o.post_status NOT IN ('auto-draft', 'canceled')
                     AND CONVERT_TZ(
                                    DATE_FORMAT(
         						                STR_TO_DATE(
         						                            CONCAT_WS(' ', ini.meta_value, '00:00:00'), 
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

    public static function trigger_change_finish_offices()
    {
        $listaFinaliza = self::snc_oficinas_to_finish();

        foreach ((array)$listaFinaliza as $lista) {
            $subscription = array('ID' => $lista->ID, 'post_status' => 'finish');
            wp_update_post($subscription);
        }
    }

    public static function generate_relatorio_concluidos_csv()
    {
        $filename = get_temp_dir() . 'relatorio_inscritos.csv';

        file_put_contents($filename, '');

        $fp = SNC_Oficinas_Service::generate_relatorio_concluidos_base_csv(fopen($filename, 'w+'));

        fclose($fp);

        return $filename;
    }

    public static function generate_relatorio_admin_xlsx($function)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();

        return $reader->loadFromString(SNC_Oficinas_Service::$function());
    }

    public static function generate_relatorio_concluidos_base_csv($fp)
    {
        $qtdOficinasInscritos = SNC_Oficinas_Service::get_quantitativo_inscritos_concluidos();
        $inscritos = SNC_Oficinas_Service::get_all_inscritos_concluidos();

        $oficinas = [];

        foreach ($qtdOficinasInscritos AS $oficina) {
            $oficinas[$oficina->ID] = $oficina;
        }

        $idOficina = null;

        foreach ($inscritos as $k => $inscrito) {

            if ($idOficina != $inscrito->ID) {
                if (0 < $k) {
                    fputcsv($fp, array('', '', '', '', ''), ';');
                    fputcsv($fp, array('', '', '', '', ''), ';');
                }

                fputcsv($fp, array(mb_convert_encoding($oficinas[$inscrito->ID]->post_title, 'ISO-8859-1', 'UTF-8'), '', 'Quantidade de Participantes', '', ''), ';');
                fputcsv($fp, array('', '', $oficinas[$inscrito->ID]->total_inscritos, '', ''), ';');
                fputcsv($fp, array('UF', mb_convert_encoding('Município', 'ISO-8859-1', 'UTF-8'), 'Nome do Participante', 'CPF', 'E-mail'), ';');
            }

            fputcsv($fp, array($inscrito->st_estado, mb_convert_encoding($inscrito->st_municipio, 'ISO-8859-1', 'UTF-8'), mb_convert_encoding($inscrito->display_name, 'ISO-8859-1', 'UTF-8'), $inscrito->nu_cpf, $inscrito->user_email), ';');

            $idOficina = $inscrito->ID;
        }

        return $fp;
    }

    public static function generate_relatorio_concluidos_base_xlsx()
    {
        $qtdOficinasInscritos = SNC_Oficinas_Service::get_quantitativo_inscritos_concluidos();
        $inscritos = SNC_Oficinas_Service::get_all_inscritos_concluidos();

        $oficinas = [];

        foreach ($qtdOficinasInscritos AS $oficina) {
            $oficinas[$oficina->ID] = $oficina;
        }

        $idOficina = null;

        $htmlString = "<table>";

        foreach ($inscritos as $k => $inscrito) {

            if ($idOficina != $inscrito->ID) {
                if (0 < $k) {
                    $htmlString .= "<tr><td colspan='5'>&nbsp;</td></tr>
                                    <tr><td colspan='5'>&nbsp;</td></tr>";
                }

                $htmlString .= "<tr>
                                    <th>{$oficinas[$inscrito->ID]->post_title}</th>
                                    <th>&nbsp;</th>
                                    <th>Quantidade de Participantes</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>";

                $htmlString .= "<tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>{$oficinas[$inscrito->ID]->total_inscritos}</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>";

                $htmlString .= "<tr>
                                    <th>UF</th>
                                    <th>Município</th>
                                    <th>Nome do Participante</th>
                                    <th>CPF</th>
                                    <th>E-mail</th>
                                </tr>";
            }

            $htmlString .= "<tr>
                                <td>{$inscrito->st_estado}</td>
                                <td>{$inscrito->st_municipio}</td>
                                <td>{$inscrito->display_name}</td>
                                <td>{$inscrito->nu_cpf}</td>
                                <td>{$inscrito->user_email}</td>
                            </tr>";

            $idOficina = $inscrito->ID;
        }

        $htmlString .= '</table>';

        return $htmlString;
    }

    public static function generate_relatorio_inscritos_base_xlsx()
    {
        $inscritos = SNC_Oficinas_Service::get_all_inscritos();
        $htmlString = "<table>
                            <tr>
                                <td>Oficina</td>
                                <td>Nº da Inscrição</td>
                                <td>UF</td>
                                <td>Município</td>
                                <td>Nome</td>
                                <td>CPF</td>
                                <td>RG</td>
                                <td>Telefone</td>
                                <td>E-mail</td>
                                <td>Gênero</td>
                                <td>Escolaridade</td>
                                <td>Perfil</td>
                            </tr>";


        foreach ($inscritos as $k => $inscrito) {
            $htmlString .= "<tr>
                                <td>{$inscrito->oficina}</td>
                                <td>{$inscrito->num_inscricao}</td>
                                <td>{$inscrito->st_estado}</td>
                                <td>{$inscrito->st_municipio}</td>
                                <td>{$inscrito->nome}</td>
                                <td>{$inscrito->nu_cpf}</td>
                                <td>{$inscrito->nu_rg}</td>
                                <td>{$inscrito->telefone}</td>
                                <td>{$inscrito->email}</td>
                                <td>{$inscrito->sexo}</td>
                                <td>{$inscrito->escolaridade}</td>
                                <td>{$inscrito->perfil}</td>
                            </tr>";
        }

        $htmlString .= '</table>';

        return $htmlString;
    }

    public static function generate_relatorio_interesses_base_xlsx()
    {
        $interesses = SNC_Oficinas_Service::get_all_interesses();

        $respostas = array('Política Nacional de Cultura Viva (Pontos, Pontões de Cultura)',
            'Sistema Nacional de Cultura (Plano, Fundo de Cultura)',
            'Novo Plano Nacional de Cultura',
            'Gestão compartilhada e Participação Social (Conselho Nacional de Política Cultural e Conferência Nacional de Cultura)',
            'Operacionalização dos sistemas de parcerias (Convênios, Consórcios, Termos de fomento, entre outros)',
            'Orientações para elaboração de projetos culturais, no âmbito da diversidade cultural',
            'Orientações para execução e prestação de contas',
            'Acessibilidade Cultural',
            'Culturas Populares e Diversidade',
            'Cultura e Educação');

        $htmlString = "<table>
                            <tr>
                                <td>Numere por ordem de prioridade até 5 áreas de interesse</td>
                                <td>Interesse 1 (%)</td>
                                <td>Interesse 2 (%)</td>
                                <td>Interesse 3 (%)</td>
                                <td>Interesse 4 (%)</td>
                                <td>Interesse 5 (%)</td>
                            </tr>";

        foreach ($respostas as $k => $resposta) {
            $vetor = $k + 1;
            $interesse1 = "percentual_i_1_{$vetor}";
            $interesse2 = "percentual_i_2_{$vetor}";
            $interesse3 = "percentual_i_3_{$vetor}";
            $interesse4 = "percentual_i_4_{$vetor}";
            $interesse5 = "percentual_i_5_{$vetor}";

            $htmlString .= "<tr>
                                <td>{$resposta}</td>
                                <td>{$interesses->$interesse1}</td>
                                <td>{$interesses->$interesse2}</td>
                                <td>{$interesses->$interesse3}</td>
                                <td>{$interesses->$interesse4}</td>
                                <td>{$interesses->$interesse5}</td>
                            </tr>";
        }

        $htmlString .= '</table>';

        return $htmlString;
    }

    public static function generate_relatorio_perfil_base_xlsx()
    {
        $inscritos = SNC_Oficinas_Service::get_all_inscritos();

        $htmlString = "<table>
                        <tr>
                          <td>Oficina</td>
                          <td>Nº da Inscrição</td>
                          <td>UF</td>
                          <td>Município</td>
                          <td>Nome</td>
                          <td>Cargo</td>
                          <td>Orgão</td>
                          <td>Esfera</td>
                          <td>Perfil</td>
                          <td>Interesse 1</td>
                          <td>Interesse 2</td>
                          <td>Interesse 3</td>
                          <td>Interesse 4</td>
                          <td>Interesse 5</td>
                       </tr>";

        foreach ($inscritos as $k => $inscrito) {
            $htmlString .= "<tr>
                                <td>{$inscrito->oficina}</td>
                                <td>{$inscrito->num_inscricao}</td>
                                <td>{$inscrito->st_estado}</td>
                                <td>{$inscrito->st_municipio}</td>
                                <td>{$inscrito->nome}</td>
                                <td>{$inscrito->cargo}</td>
                                <td>{$inscrito->orgao}</td>
                                <td>{$inscrito->esfera}</td>
                                <td>{$inscrito->perfil}</td>
                                <td>{$inscrito->interesse1}</td>
                                <td>{$inscrito->interesse2}</td>
                                <td>{$inscrito->interesse3}</td>
                                <td>{$inscrito->interesse4}</td>
                                <td>{$inscrito->interesse5}</td>
                            </tr>";

        }

        $htmlString .= '</table>';

        return $htmlString;
    }
}