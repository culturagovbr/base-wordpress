<?php

if (!defined('WPINC'))
    die();

require_once SNC_ODF_PLUGIN_PATH . '/vendor/autoload.php';

/**
 * Class SNC_Oficinas_Service
 */
final class SNC_Oficinas_Service
{
    /**
     * @param null $postIdOficina
     * @return mixed
     */
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

    /**
     * @return array|object|null
     */
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

    /**
     * @return array|object|null
     */
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

    /**
     * @param bool $concluidos
     * @return array|object|null
     */
    public static function get_all_inscritos($concluidos = false)
    {
        global $wpdb;

        $postTable = $wpdb->posts;
        $postMetaTable = $wpdb->postmeta;
        $userTable = $wpdb->users;
        $userMetaTable = $wpdb->usermeta;

        $where = "";

        if ($concluidos) {
            $where = " AND o.post_status NOT IN ('auto-draft', 'canceled') 
                       AND insc.post_status IN ('waiting_questions', 'finish')";
        }

        $query = "SELECT o.ID, 
                         o.post_title as oficina, 
                         insc.ID AS num_inscricao,
                         u.display_name AS nome,
                         u.user_email AS email,
                         nascimento.meta_value AS dt_nascimento,
                         escolar.meta_value AS escolaridade,
                         genero.meta_value AS sexo,
                         cpf.meta_value AS nu_cpf,
                         rg.meta_value AS nu_rg,
                         endereco.meta_value AS st_endereco,
                         municipio.meta_value AS st_municipio,
                         estado.meta_value AS st_estado,
                         bairro.meta_value AS st_bairro,
                         numero.meta_value AS st_numero,
                         complemento.meta_value AS st_complemento,
                         cep.meta_value AS st_cep,
                         fone.meta_value AS telefone,
                         celular.meta_value AS telefone_celular,
                         institucional.meta_value AS email_institucional, 
                         pagina.meta_value AS pagina_web, 
                         ferramenta.meta_value AS ferramentas,
                         DATE_FORMAT(STR_TO_DATE(ini.meta_value, '%Y%m%d'), '%d/%m/%Y') AS data_inicio,
                         DATE_FORMAT(STR_TO_DATE(fim.meta_value, '%Y%m%d'), '%d/%m/%Y') AS data_fim,
                         insc_perfil.meta_value AS perfil,
                         insc.post_status,
                         insc.post_type,
                         CASE insc.post_status 
                         	WHEN 'waiting_list' THEN 'Lista de Espera'
                         	WHEN 'confirmed' THEN 'Confirmada'
                         	WHEN 'canceled' THEN 'Cancelada'
                         	WHEN 'waiting_presence' THEN 'Aguardando Presença'
                         	WHEN 'waiting_questions' THEN 'Aguardando Questionário'
                         	WHEN 'finish' THEN 'Concluída'
                         	WHEN 'pending' THEN 'Pendente'
                         	ELSE ''
                         	END AS st_inscricao,
                         CONVERT(
                         	(SELECT JSON_OBJECTAGG(COALESCE(perg.post_excerpt, COALESCE(perg.post_title, '')), COALESCE(perg.post_title, ''))
							   FROM {$postTable} quest 
							   JOIN {$postTable} perg 
								 ON perg.post_parent = quest.ID 
   								AND perg.post_excerpt NOT IN ('inscricao_perfil', 'inscricao_oficina_uf', 'inscricao_autorizacao_de_uso_da_imagem')
							   JOIN {$postMetaTable} resp_ 
   								 ON resp_.meta_key = perg.post_excerpt 
 							  WHERE quest.post_excerpt = 'ficha-de-inscricao-para-as-oficinas-dos-dialogos-federativos'
 							    AND resp_.post_id = insc.ID 
 							   ORDER BY resp_.meta_id 
 							    ) USING utf8mb4) 
 							AS perguntas_inscricao, 
 						CONVERT(
                         	(SELECT JSON_OBJECTAGG(COALESCE(resp_.meta_key, ''), COALESCE(resp_.meta_value, ''))
							   FROM {$postTable} quest 
							   JOIN {$postTable} perg 
								 ON perg.post_parent = quest.ID 
								AND perg.post_excerpt NOT IN ('inscricao_perfil', 'inscricao_oficina_uf', 'inscricao_autorizacao_de_uso_da_imagem')
							   JOIN {$postMetaTable} resp_ 
   								 ON resp_.meta_key = perg.post_excerpt 
 							  WHERE quest.post_excerpt = 'ficha-de-inscricao-para-as-oficinas-dos-dialogos-federativos'
 							    AND resp_.post_id = insc.ID 
 							   ORDER BY resp_.meta_id 
 							    ) USING utf8mb4) 
 							AS respostas_inscricao, 
                         CONVERT(
                         	CASE 
	                         	WHEN insc_perfil.meta_value = 'Gestor de Cultura' THEN 
	                         		(SELECT JSON_OBJECTAGG(COALESCE(perg.post_excerpt, COALESCE(perg.post_title, '')), COALESCE(perg.post_title, ''))
									   FROM {$postTable} quest 
									   LEFT JOIN {$postTable} perg 
									     ON perg.post_parent = quest.ID
 									  WHERE quest.post_excerpt = 'questionario-perfil-gestor-de-cultura')
	                         	WHEN insc_perfil.meta_value = 'Conselheiro de Cultura' THEN 
	                         		(SELECT JSON_OBJECTAGG(COALESCE(perg.post_excerpt, COALESCE(perg.post_title, '')), COALESCE(perg.post_title, ''))
									   FROM {$postTable} quest 
									   LEFT JOIN {$postTable} perg 
									     ON perg.post_parent = quest.ID
 									  WHERE quest.post_excerpt = 'questionario-perfil-conselheiro-de-cultura')
	                         	WHEN insc_perfil.meta_value = 'Ponteiro de Cultura' THEN 
	                         		(SELECT JSON_OBJECTAGG(COALESCE(perg.post_excerpt, COALESCE(perg.post_title, '')), COALESCE(perg.post_title, ''))
									   FROM {$postTable} quest 
									   LEFT JOIN {$postTable} perg 
									     ON perg.post_parent = quest.ID
 									  WHERE quest.post_excerpt = 'questionario-perfil-ponteiro-de-cultura')
	                         	WHEN insc_perfil.meta_value = 'Sociedade Civil' THEN 
	                         		(SELECT JSON_OBJECTAGG(COALESCE(perg.post_excerpt, COALESCE(perg.post_title, '')), COALESCE(perg.post_title, ''))
									   FROM {$postTable} quest 
									   LEFT JOIN {$postTable} perg 
									     ON perg.post_parent = quest.ID
 									  WHERE quest.post_excerpt = 'questionario-perfil-sociedade')
	                         	ELSE (SELECT JSON_OBJECTAGG('', ''))
                         	END USING utf8mb4) AS perguntas_perfil,
                         CONVERT(
                         	CASE 
	                         	WHEN insc_perfil.meta_value = 'Gestor de Cultura' THEN 
	                         		(SELECT JSON_OBJECTAGG(COALESCE(resp_.meta_key, ''), COALESCE(resp_.meta_value, ''))
									   FROM {$postTable} q  
									   LEFT JOIN {$postMetaTable} resp_ 
									     ON resp_.post_id = q.ID 
									    AND resp_.meta_key IN ('oficina_questionario_necessidade_aprofundamento', 'oficina_questionario_saber_evento', 'oficina_questionario_primeira_vez', 
									   						   'oficina_questionario_facilidade', 'oficina_questionario_divulgacao', 'oficina_questionario_programacao', 
									   						   'oficina_questionario_organizacao', 'oficina_questionario_temas_abordados', 'oficina_questionario_conhecimento', 
									   						   'oficina_questionario_adequacao', 'oficina_questionario_materiais', 'oficina_questionario_recomendacao', 'oficina_questionario_comentario')
									  WHERE q.ID = quest.ID)
	                         	WHEN insc_perfil.meta_value = 'Conselheiro de Cultura' THEN 
	                         		(SELECT JSON_OBJECTAGG(COALESCE(resp_.meta_key, ''), COALESCE(resp_.meta_value, ''))
									   FROM {$postTable} q  
									   LEFT JOIN {$postMetaTable} resp_ 
									     ON resp_.post_id = q.ID 
									    AND resp_.meta_key IN ('oficina_questionario_federado_cultura', 'oficina_questionario_funcionamento', 'oficina_questionario_representantes',
															   'oficina_questionario_territorio_conselho', 'oficina_questionario_conselheiros_capacitacao', 
															   'oficina_questionario_necessidade_capacitacao', 'oficina_questionario_debates_atividades', 'oficina_questionario_saber_evento', 
															   'oficina_questionario_primeira_vez', 'oficina_questionario_facilidade', 'oficina_questionario_divulgacao', 
															   'oficina_questionario_programacao', 'oficina_questionario_organizacao', 'oficina_questionario_temas_abordados', 
															   'oficina_questionario_conhecimento', 'oficina_questionario_adequacao', 'oficina_questionario_materiais', 
															   'oficina_questionario_recomendacao', 'oficina_questionario_comentario')
									  WHERE q.ID = quest.ID)
	                         	WHEN insc_perfil.meta_value = 'Ponteiro de Cultura' THEN 
	                         		(SELECT JSON_OBJECTAGG(COALESCE(resp_.meta_key, ''), COALESCE(resp_.meta_value, ''))
									   FROM {$postTable} q  
									   LEFT JOIN {$postMetaTable} resp_ 
									     ON resp_.post_id = q.ID 
									    AND resp_.meta_key IN ('oficina_questionario_ponto_cultura', 'oficina_questionario_coletivos_culturais', 'oficina_questionario_comunicacao_direta', 
							   								   'oficina_questionario_comunicacao_cidadania', 'oficina_questionario_plano_capacitacao', 'oficina_questionario_saber_evento', 
							   								   'oficina_questionario_primeira_vez', 'oficina_questionario_facilidade', 'oficina_questionario_divulgacao', 
							   								   'oficina_questionario_programacao', 'oficina_questionario_organizacao', 'oficina_questionario_temas_abordados', 
							   								   'oficina_questionario_conhecimento', 'oficina_questionario_adequacao', 'oficina_questionario_materiais', 
							   								   'oficina_questionario_recomendacao', 'oficina_questionario_comentario')
									  WHERE q.ID = quest.ID)
	                         	WHEN insc_perfil.meta_value = 'Sociedade Civil' THEN 
	                         		(SELECT JSON_OBJECTAGG(COALESCE(resp_.meta_key, ''), COALESCE(resp_.meta_value, ''))
									   FROM {$postTable} q  
									   LEFT JOIN {$postMetaTable} resp_ 
									     ON resp_.post_id = q.ID 
									    AND resp_.meta_key IN ('oficina_questionario_saber_evento', 'oficina_questionario_primeira_vez', 'oficina_questionario_facilidade', 
							   								   'oficina_questionario_divulgacao', 'oficina_questionario_programacao', 'oficina_questionario_organizacao', 
							   								   'oficina_questionario_temas_abordados', 'oficina_questionario_conhecimento', 'oficina_questionario_adequacao', 
							   								   'oficina_questionario_materiais', 'oficina_questionario_recomendacao', 'oficina_questionario_comentario')
									  WHERE q.ID = quest.ID)
	                         	ELSE (SELECT JSON_OBJECTAGG('', ''))
                         	END USING utf8mb4) AS respostas_perfil
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
					JOIN {$userMetaTable} celular 
                      ON celular.user_id = u.ID
                     AND celular.meta_key = '_user_celphone'
                    JOIN {$userMetaTable} endereco 
                      ON endereco.user_id = u.ID
                     AND endereco.meta_key = '_user_address'
                    JOIN {$userMetaTable} estado 
                      ON estado.user_id = u.ID
                     AND estado.meta_key = '_user_state'
                    JOIN {$userMetaTable} municipio 
                      ON municipio.user_id = u.ID
                     AND municipio.meta_key = '_user_county'
                    JOIN {$userMetaTable} bairro 
                      ON bairro.user_id = u.ID
                     AND bairro.meta_key = '_user_neighborhood'
                    JOIN {$userMetaTable} complemento 
                      ON complemento.user_id = u.ID
                     AND complemento.meta_key = '_user_complement'
                    JOIN {$userMetaTable} numero 
                      ON numero.user_id = u.ID
                     AND numero.meta_key = '_user_number'
                    JOIN {$userMetaTable} genero 
                      ON genero.user_id = u.ID
                     AND genero.meta_key = '_user_gender'
                    JOIN {$userMetaTable} escolar 
                      ON escolar.user_id = u.ID
                     AND escolar.meta_key = '_user_schooling'
                    JOIN {$userMetaTable} nascimento 
                      ON nascimento.user_id = u.ID
                     AND nascimento.meta_key = '_user_birthday'
                    JOIN {$userMetaTable} cep 
                      ON cep.user_id = u.ID
                     AND cep.meta_key = '_user_zipcode'
                    JOIN {$userMetaTable} institucional 
                      ON institucional.user_id = u.ID
                     AND institucional.meta_key = '_user_institutional-email'
                    JOIN {$userMetaTable} pagina 
                      ON pagina.user_id = u.ID
                     AND pagina.meta_key = '_user_webpage'
                    JOIN {$userMetaTable} ferramenta 
                      ON ferramenta.user_id = u.ID
                     AND ferramenta.meta_key = '_user_socials'
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
                    LEFT JOIN {$postTable} quest
                      ON quest.post_parent = insc.ID 
				     AND quest.post_author = u.ID
   				     AND quest.post_type = 'participacao-oficina'
                   WHERE o.post_type = 'oficinas' 
                     {$where}
                   ORDER BY STR_TO_DATE(ini.meta_value, '%Y%m%d'), 
                            o.post_title, 
                            u.display_name";

        return $wpdb->get_results($query);
    }

    /**
     * @return array|object|void|null
     */
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
                       ROUND((100 * a.total_i_5_10) / a.total_inscritos, 2) AS percentual_i_5_10,
                       a.total_i_1_1, a.total_i_1_2, a.total_i_1_3, a.total_i_1_4, a.total_i_1_5, 
                       a.total_i_1_6, a.total_i_1_7, a.total_i_1_8, a.total_i_1_9, a.total_i_1_10,
                       a.total_i_2_1, a.total_i_2_2, a.total_i_2_3, a.total_i_2_4, a.total_i_2_5, 
                       a.total_i_2_6, a.total_i_2_7, a.total_i_2_8, a.total_i_2_9, a.total_i_2_10,
                       a.total_i_3_1, a.total_i_3_2, a.total_i_3_3, a.total_i_3_4, a.total_i_3_5, 
                       a.total_i_3_6, a.total_i_3_7, a.total_i_3_8, a.total_i_3_9, a.total_i_3_10,
                       a.total_i_4_1, a.total_i_4_2, a.total_i_4_3, a.total_i_4_4, a.total_i_4_5, 
                       a.total_i_4_6, a.total_i_4_7, a.total_i_4_8, a.total_i_4_9, a.total_i_4_10,
                       a.total_i_5_1, a.total_i_5_2, a.total_i_5_3, a.total_i_5_4, a.total_i_5_5, 
                       a.total_i_5_6, a.total_i_5_7, a.total_i_5_8, a.total_i_5_9, a.total_i_5_10
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

    /**
     * @return array|object|null
     */
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

    /**
     * @param $post_id
     * @param int $limitVal
     * @return array|object|null
     */
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

    /**
     * @param $post_id
     * @return array|object|void|null
     */
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

    /**
     * @param $post_id
     * @return array|object|null
     */
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

    /**
     * @param int $numDiasAntes
     * @return array|object|null
     */
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

    /**
     * @return array|object|null
     */
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

    /**
     * @return array|object|null
     */
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

    /**
     * @param $post_id
     * @return false|int
     */
    public static function update_list_waiting($post_id)
    {
        global $wpdb;

        $postTable = $wpdb->posts;

        return $wpdb->update($postTable, array('post_status' => 'confirmed',), array('ID' => $post_id), array('%s'), array('%d'));
    }

    /**
     * @param $post_id
     */
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

    /**
     *
     */
    public static function trigger_change_waiting_presence()
    {
        $listaFinaliza = self::snc_next_oficinas_to_finish();

        foreach ((array)$listaFinaliza as $lista) {
            $subscription = array('ID' => $lista->post_id, 'post_status' => 'waiting_presence');
            wp_update_post($subscription);
        }
    }

    /**
     *
     */
    public static function trigger_change_finish_offices()
    {
        $listaFinaliza = self::snc_oficinas_to_finish();

        foreach ((array)$listaFinaliza as $lista) {
            $subscription = array('ID' => $lista->ID, 'post_status' => 'finish');
            wp_update_post($subscription);
        }
    }

    /**
     * @return string
     */
    public static function generate_relatorio_concluidos_csv()
    {
        $filename = get_temp_dir() . 'relatorio_inscritos.csv';

        file_put_contents($filename, '');

        $fp = SNC_Oficinas_Service::generate_relatorio_concluidos_base_csv(fopen($filename, 'w+'));

        fclose($fp);

        return $filename;
    }

    /**
     * @param $function
     * @param $nameXlsx
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet|null
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function generate_relatorio_admin_xlsx($function, $nameXlsx)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();

        $planilhas = SNC_Oficinas_Service::$function();

        $spreadSheet = null;

        if (!is_array($planilhas)) {
            $spreadSheet = $reader->loadFromString($planilhas);
            $spreadSheet->getSheet(0)
                ->setTitle($nameXlsx);
            return $spreadSheet;
        }

        $k = 0;

        foreach ($planilhas as $tab => $planilha) {
            if (0 == $k) {
                $spreadSheet = $reader->loadFromString($planilha);
                $spreadSheet->getSheet($k)
                    ->setTitle($tab);
            } else {
                $spreadSheetNew = $reader->loadFromString($planilha);
                $addNew = $spreadSheetNew->getSheet(0)->setTitle("{$tab}");
                $spreadSheet->addSheet($addNew, $k);
            }

            $k++;
        }

        return $spreadSheet;
    }

    /**
     * @param $fp
     * @return mixed
     */
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

    /**
     * @return string
     */
    public static function generate_relatorio_concluidos_base_xlsx()
    {
        $inscritos = SNC_Oficinas_Service::get_all_inscritos(true);

        $htmlString = [];

        foreach ($inscritos as $k => $inscrito) {
            $htmlString[$inscrito->perfil][] =
                "<tr>
                    <td>{$inscrito->oficina}</td>
                    <td>{$inscrito->num_inscricao}</td>
                    <td>{$inscrito->st_estado}</td>
                    <td>{$inscrito->st_municipio}</td>
                    <td>{$inscrito->nome}</td>
                    <td>{$inscrito->nu_cpf}</td>
                    <td>{$inscrito->email}</td>
                    <td>{$inscrito->st_inscricao}</td>" .

                self::generate_results_resp_xlsx($inscrito) . "</tr>";

            $htmlString['perguntas'][$inscrito->perfil] = self::get_array_questions($inscrito);
        }

        $cabecalhoPadrao = "<td><b>Oficina</b></td><td><b>Nº da Inscrição</b></td><td><b>UF</b></td>
                            <td><b>Município</b></td><td><b>Nome do Participante</b></td>
                            <td><b>CPF</b></td><td><b>E-mail</b></td><td><b>Situação da inscrição</b></td>";

        $planilhas["Dicionários"] = '<table border="1" cellspacing="0">';

        if (isset($htmlString["Gestor de Cultura"])) {
            self::get_html_planilha_perfil($planilhas, $cabecalhoPadrao, $htmlString, "Gestor de Cultura");
        }

        if (isset($htmlString["Conselheiro de Cultura"])) {
            self::get_html_planilha_perfil($planilhas, $cabecalhoPadrao, $htmlString, "Conselheiro de Cultura");
        }

        if (isset($htmlString["Ponteiro de Cultura"])) {
            self::get_html_planilha_perfil($planilhas, $cabecalhoPadrao, $htmlString, "Ponteiro de Cultura");
        }

        if (isset($htmlString["Sociedade Civil"])) {
            self::get_html_planilha_perfil($planilhas, $cabecalhoPadrao, $htmlString, "Sociedade Civil");
        }

        $planilhas["Dicionários"] .= "</table>";

        return $planilhas;
    }

    /**
     * @return string
     */
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

        $arTd = array();
        $arTdTotal = array();
        $totalGeral = $interesses->total_inscritos;

        foreach ($respostas as $k => $resposta) {
            $vetor = $k + 1;
            $interesse1 = "percentual_i_1_{$vetor}";
            $interesse2 = "percentual_i_2_{$vetor}";
            $interesse3 = "percentual_i_3_{$vetor}";
            $interesse4 = "percentual_i_4_{$vetor}";
            $interesse5 = "percentual_i_5_{$vetor}";

            $total1 = "total_i_1_{$vetor}";
            $total2 = "total_i_2_{$vetor}";
            $total3 = "total_i_3_{$vetor}";
            $total4 = "total_i_4_{$vetor}";
            $total5 = "total_i_5_{$vetor}";

            $order = $interesses->$interesse1 + $interesses->$interesse2 + $interesses->$interesse3 + $interesses->$interesse4 + $interesses->$interesse5;

            $arTd[$k] = array("td" => "<td>{$resposta}</td>
                                    <td>{$interesses->$interesse1}</td>
                                    <td>{$interesses->$interesse2}</td>
                                    <td>{$interesses->$interesse3}</td>
                                    <td>{$interesses->$interesse4}</td>
                                    <td>{$interesses->$interesse5}</td>",
                "order" => $order);

            $arTdTotal[$k] = array("td" => "<td>{$resposta}</td>
                                    <td>{$interesses->$total1}</td>
                                    <td>{$interesses->$total2}</td>
                                    <td>{$interesses->$total3}</td>
                                    <td>{$interesses->$total4}</td>
                                    <td>{$interesses->$total5}</td>",
                "order" => $order);
        }

        $func = function ($v1, $v2) {
            return $v1['order'] < $v2['order'];
        };

        $htmlString = '<table>
                            <tr><td colspan="7" align="center"><b>Por Porcentagem de Interesses</b></td></tr>
                            <tr>
                                <td><b>Rank</b></td>
                                <td><b>Numere por ordem de prioridade até 5 áreas de interesse</b></td>
                                <td><b>Interesse 1 (%)</b></td>
                                <td><b>Interesse 2 (%)</b></td>
                                <td><b>Interesse 3 (%)</b></td>
                                <td><b>Interesse 4 (%)</b></td>
                                <td><b>Interesse 5 (%)</b></td>
                            </tr>';

        usort($arTd, $func);

        foreach ($arTd as $k => $td) {
            $k++;
            $htmlString .= "<tr><td>{$k}</td>{$td['td']}</tr>";
        }

        $htmlString .= '<tr><td colspan="7">&nbsp;</td></tr><tr><td colspan="7">&nbsp;</td></tr></table>';

        $htmlString .= '<table>
                            <tr><td colspan="7" align="center"><b>Por Quantitativo de Interesses</b></td></tr>
                            <tr>
                                <td><b>Rank</b></td>
                                <td><b>Numere por ordem de prioridade até 5 áreas de interesse</b></td>
                                <td><b>Qtd. Interesse 1</b></td>
                                <td><b>Qtd. Interesse 2</b></td>
                                <td><b>Qtd. Interesse 3</b></td>
                                <td><b>Qtd. Interesse 4</b></td>
                                <td><b>Qtd. Interesse 5</b></td>
                            </tr>';

        usort($arTdTotal, $func);

        foreach ($arTdTotal as $k => $td) {
            $k++;
            $htmlString .= "<tr><td>{$k}</td>{$td['td']}</tr>";
        }

        $htmlString .= '<tr><td colspan="7">&nbsp;</td></tr><tr><td colspan="7">&nbsp;</td></tr>';
        $htmlString .= '<tr><td colspan="2" style="text-align: right;"><b>Total de Inscritos:</b></td><td>' . $totalGeral . '</td><td colspan="4"></td></tr></table>';

        return $htmlString;
    }

    /**
     * @return mixed
     */
    public static function generate_relatorio_perfil_base_xlsx()
    {
        $inscritos = SNC_Oficinas_Service::get_all_inscritos();

        $htmlString = [];

        foreach ($inscritos as $k => $inscrito) {
            $htmlString[$inscrito->perfil][] =
                "<tr>
                    <td>{$inscrito->oficina}</td>
                    <td>{$inscrito->num_inscricao}</td>
                    <td>{$inscrito->st_estado}</td>
                    <td>{$inscrito->st_municipio}</td>
                    <td>{$inscrito->nome}</td>
                    <td>{$inscrito->perfil}</td>
                    <td>{$inscrito->st_inscricao}</td>" .

                self::generate_results_resp_inscricao_xlsx($inscrito) . "</tr>";

            $htmlString['perguntas'][$inscrito->perfil] = self::get_array_inscricao_questions($inscrito);
        }

        $cabecalhoPadrao = "<td><b>Oficina</b></td><td><b>Nº da Inscrição</b></td><td><b>UF</b></td>
                            <td><b>Município</b></td><td><b>Nome</b></td><td><b>Perfil</b></td>
                            <td><b>Informações da inscrição</b></td>";

        $planilhas["Dicionários"] = '<table border="1" cellspacing="0">';

        if (isset($htmlString["Gestor de Cultura"])) {
            self::get_html_planilha_perfil($planilhas, $cabecalhoPadrao, $htmlString, "Gestor de Cultura", true);
        }

        if (isset($htmlString["Conselheiro de Cultura"])) {
            self::get_html_planilha_perfil($planilhas, $cabecalhoPadrao, $htmlString, "Conselheiro de Cultura", true);
        }

        if (isset($htmlString["Ponteiro de Cultura"])) {
            self::get_html_planilha_perfil($planilhas, $cabecalhoPadrao, $htmlString, "Ponteiro de Cultura", true);
        }

        if (isset($htmlString["Sociedade Civil"])) {
            self::get_html_planilha_perfil($planilhas, $cabecalhoPadrao, $htmlString, "Sociedade Civil", true);
        }

        $planilhas["Dicionários"] .= "</table>";

        return $planilhas;
    }

    /**
     * @param $planilhas
     * @param $cabecalhoPadrao
     * @param $htmlString
     * @param $perfil
     */
    final public static function get_html_planilha_perfil(&$planilhas, $cabecalhoPadrao, $htmlString, $perfil, $inscricao = false)
    {
        $cabecalho = "<td><b>" . implode("</b></td><td><b>", array_keys($htmlString['perguntas'][$perfil])) . "</b></td>";

        $html = '<table border="1" cellspacing="0">';
        $html .= "<tr>{$cabecalhoPadrao}{$cabecalho}</tr>";
        $html .= implode("", $htmlString[$perfil]);
        $html .= "</table>";

        $planilhas[$perfil] = $html;

        $planilhas["Dicionários"] .= '<tr><td colspan="2"><b>Aba ' . $perfil . ' - Dicionário</b></td></tr>';
        $planilhas["Dicionários"] .= "<tr><td><b>Cód.</b></td><td><b>Perguntas</b></td></tr>";

        foreach ($htmlString['perguntas'][$perfil] as $k => $pergunta) {
            if (!$inscricao && "CM-03" == $k) {
                $planilhas["Dicionários"] .= '<tr><td></td><td><i><b>Por favor, avalie as afirmações seguintes segundo a sua opinião sobre o 
                                            <br/>evento Diálogos Federativos: Cultura de Ponto à Ponta</b> (Ref: CM-03 à CM-10)</i></td></tr>';
            }

            $planilhas["Dicionários"] .= "<tr><td><b>{$k}</b></td><td>{$pergunta}</td></tr>";
        }

        $planilhas["Dicionários"] .= '<tr><td colspan="2"></td></tr><tr><td colspan="2"></td></tr>';
    }

    /**
     * @param $inscrito
     * @return string
     */
    final public static function generate_results_resp_xlsx($inscrito)
    {
        $respostas = json_decode($inscrito->respostas_perfil);

        switch ($inscrito->perfil) {
            case 'Gestor de Cultura' :
                $return = self::generate_gestor_results_xlsx($respostas);
                break;
            case 'Conselheiro de Cultura' :
                $return = self::generate_conselheiro_results_xlsx($respostas);
                break;
            case 'Ponteiro de Cultura':
                $return = self::generate_ponteiro_results_xlsx($respostas);
                break;
            default :
                $return = self::generate_sociedade_results_xlsx($respostas);
                break;
        }

        return $return;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_base_avaliacao_xlsx($respostas)
    {
        $html = '<td>' . SNC_Oficinas_Utils::get_resp_oficina_questionario_saber_evento($respostas->oficina_questionario_saber_evento) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_sim_nao($respostas->oficina_questionario_primeira_vez) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_facilidade) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_divulgacao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_programacao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_organizacao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_temas_abordados) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_conhecimento) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_adequacao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_materiais) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_satisfacao($respostas->oficina_questionario_recomendacao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->oficina_questionario_comentario, 60) . '</td>';

        return $html;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_gestor_results_xlsx($respostas)
    {
        $html = '<td>' . SNC_Oficinas_Utils::get_resp_oficina_questionario_necessidade_aprofundamento($respostas->oficina_questionario_necessidade_aprofundamento) . '</td>';

        $html .= self::generate_base_avaliacao_xlsx($respostas);

        return $html;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_conselheiro_results_xlsx($respostas)
    {
        $html = '<td>' . SNC_Oficinas_Utils::get_resp_oficina_sim_nao($respostas->oficina_questionario_federado_cultura) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_sim_nao($respostas->oficina_questionario_funcionamento) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_representantes($respostas->oficina_questionario_representantes) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->oficina_questionario_territorio_conselho) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_sim_nao($respostas->oficina_questionario_conselheiros_capacitacao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_necessidade_capacitacao($respostas->oficina_questionario_necessidade_capacitacao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_sim_nao($respostas->oficina_questionario_debates_atividades) . '</td>';

        $html .= self::generate_base_avaliacao_xlsx($respostas);

        return $html;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_ponteiro_results_xlsx($respostas)
    {
        $html = '<td>' . SNC_Oficinas_Utils::get_resp_oficina_ponto_cultura($respostas->oficina_questionario_ponto_cultura) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_coletivos_culturais($respostas->oficina_questionario_coletivos_culturais) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_comunicacao_direta($respostas->oficina_questionario_comunicacao_direta) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_comunicacao_cidadania($respostas->oficina_questionario_comunicacao_cidadania) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_resp_oficina_sim_nao($respostas->oficina_questionario_plano_capacitacao) . '</td>';

        $html .= self::generate_base_avaliacao_xlsx($respostas);

        return $html;
    }

    /**
     * @param $inscrito
     * @return array
     */
    final public static function get_array_questions($inscrito)
    {
        $perguntas = json_decode($inscrito->perguntas_perfil);

        switch ($inscrito->perfil) {
            case 'Gestor de Cultura' :
                $return = self::get_array_gestor_questions($perguntas);
                break;
            case 'Conselheiro de Cultura' :
                $return = self::get_array_conselheiro_questions($perguntas);
                break;
            case 'Ponteiro de Cultura':
                $return = self::get_array_ponteiro_questions($perguntas);
                break;
            default :
                $return = self::get_array_sociedade_questions($perguntas);
                break;
        }

        return $return;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_sociedade_results_xlsx($respostas)
    {
        $html = self::generate_base_avaliacao_xlsx($respostas);

        return $html;
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_base_avaliacao_questions($perguntas)
    {
        $array = [];
        $array["CM-01"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_saber_evento);
        $array["CM-02"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_primeira_vez);
        $array["CM-03"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_facilidade);
        $array["CM-04"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_divulgacao);
        $array["CM-05"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_programacao);
        $array["CM-06"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_organizacao);
        $array["CM-07"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_temas_abordados);
        $array["CM-08"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_conhecimento);
        $array["CM-09"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_adequacao);
        $array["CM-10"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_materiais);
        $array["CM-11"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_recomendacao);
        $array["CM-12"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_comentario);

        return $array;
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_gestor_questions($perguntas)
    {
        $array = [];
        $array["GE-01"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_necessidade_aprofundamento);

        return array_merge($array, self::get_array_base_avaliacao_questions($perguntas));
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_conselheiro_questions($perguntas)
    {
        $array = [];
        $array["CO-01"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_federado_cultura);
        $array["CO-02"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_funcionamento);
        $array["CO-03"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_representantes);
        $array["CO-04"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_territorio_conselho);
        $array["CO-05"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_conselheiros_capacitacao);
        $array["CO-06"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_necessidade_capacitacao);
        $array["CO-07"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_debates_atividades);

        return array_merge($array, self::get_array_base_avaliacao_questions($perguntas));
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_ponteiro_questions($perguntas)
    {
        $array = [];
        $array["PO-01"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_ponto_cultura);
        $array["PO-02"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_coletivos_culturais);
        $array["PO-03"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_comunicacao_direta);
        $array["PO-04"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_comunicacao_cidadania);
        $array["PO-05"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->oficina_questionario_plano_capacitacao);

        return array_merge($array, self::get_array_base_avaliacao_questions($perguntas));
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_sociedade_questions($perguntas)
    {
        $array = [];

        return array_merge($array, self::get_array_base_avaliacao_questions($perguntas));
    }

    /**
     * @param $inscrito
     * @return array
     */
    final public static function get_array_inscricao_questions($inscrito)
    {
        $perguntas = json_decode($inscrito->perguntas_inscricao);

        switch ($inscrito->perfil) {
            case 'Gestor de Cultura' :
                $return = self::get_array_gestor_inscricao_questions($perguntas);
                break;
            case 'Conselheiro de Cultura' :
                $return = self::get_array_conselheiro_inscricao_questions($perguntas);
                break;
            case 'Ponteiro de Cultura':
                $return = self::get_array_ponteiro_inscricao_questions($perguntas);
                break;
            default :
                $return = self::get_array_sociedade_inscricao_questions($perguntas);
                break;
        }

        return $return;
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_base_inscricao_questions($perguntas)
    {
        $array = [];
        $array["CM-01"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_interesse_1);
        $array["CM-02"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_interesse_2);
        $array["CM-03"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_interesse_3);
        $array["CM-04"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_interesse_4);
        $array["CM-05"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_interesse_5);

        return $array;
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_gestor_inscricao_questions($perguntas)
    {
        $array = self::get_array_base_inscricao_questions($perguntas);

        $array["GE-01"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_cargo);
        $array["GE-02"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_orgao);
        $array["GE-03"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_tipo);
        $array["GE-04"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_ja_institucionalizou);
        $array["GE-05"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_ente_possui_cultura_viva);
        $array["GE-06"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_participou_capacitacao_governo_federal);
        $array["GE-07"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_ente_oferta_capacitacao);
        $array["GE-08"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_participou_de_curso);
        $array["GE-09"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_ente_possui_pontos_de_cultura);
        $array["GE-10"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_interesse_pontos_de_cultura_regiao);
        $array["GE-11"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_ente_possui_conselho_de_cultura_em_funcionamento);
        $array["GE-12"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_gestor_ente_realizou_conferencia_de_cultura);

        return $array;
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_conselheiro_inscricao_questions($perguntas)
    {
        return self::get_array_base_inscricao_questions($perguntas);
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_ponteiro_inscricao_questions($perguntas)
    {
        $array = self::get_array_base_inscricao_questions($perguntas);

        $array["PO-01"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_ponteiro_entidade);
        $array["PO-02"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_ponteiro_localidade);
        $array["PO-03"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_ponteiro_funcao);
        $array["PO-04"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_ponteiro_possui_certificacao_cultura_viva);
        $array["PO-05"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_ponteiro_participou_capacitacao_estado_municipio);

        return $array;
    }

    /**
     * @param $perguntas
     * @return array
     */
    final public static function get_array_sociedade_inscricao_questions($perguntas)
    {
        $array = self::get_array_base_inscricao_questions($perguntas);

        $array["SC-01"] = SNC_Oficinas_Utils::get_text_nl2br($perguntas->inscricao_sociedade_area_atuacao);

        return $array;
    }

    /**
     * @param $inscrito
     * @return string
     */
    final public static function generate_results_resp_inscricao_xlsx($inscrito)
    {
        $respostas = json_decode($inscrito->respostas_inscricao);

        switch ($inscrito->perfil) {
            case 'Gestor de Cultura' :
                $return = self::generate_gestor_results_inscricao_xlsx($respostas);
                break;
            case 'Conselheiro de Cultura' :
                $return = self::generate_conselheiro_results_inscricao_xlsx($respostas);
                break;
            case 'Ponteiro de Cultura':
                $return = self::generate_ponteiro_results_inscricao_xlsx($respostas);
                break;
            default :
                $return = self::generate_sociedade_results_inscricao_xlsx($respostas);
                break;
        }

        return $return;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_base_respostas_inscricao_xlsx($respostas)
    {
        $html = '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_interesse_1) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_interesse_2) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_interesse_3) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_interesse_4) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_interesse_5) . '</td>';

        return $html;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_gestor_results_inscricao_xlsx($respostas)
    {
        $html = self::generate_base_respostas_inscricao_xlsx($respostas);

        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_cargo) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_orgao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_tipo) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_ja_institucionalizou) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_ente_possui_cultura_viva) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_participou_capacitacao_governo_federal) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_ente_oferta_capacitacao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_participou_de_curso) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_ente_possui_pontos_de_cultura) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_interesse_pontos_de_cultura_regiao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_ente_possui_conselho_de_cultura_em_funcionamento) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_gestor_ente_realizou_conferencia_de_cultura) . '</td>';

        return $html;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_conselheiro_results_inscricao_xlsx($respostas)
    {
        $html = self::generate_base_respostas_inscricao_xlsx($respostas);

        return $html;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_ponteiro_results_inscricao_xlsx($respostas)
    {
        $html = self::generate_base_respostas_inscricao_xlsx($respostas);

        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_ponteiro_entidade) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_ponteiro_localidade) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_ponteiro_funcao) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_ponteiro_possui_certificacao_cultura_viva) . '</td>';
        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_ponteiro_participou_capacitacao_estado_municipio) . '</td>';

        return $html;
    }

    /**
     * @param $respostas
     * @return string
     */
    final public static function generate_sociedade_results_inscricao_xlsx($respostas)
    {
        $html = self::generate_base_respostas_inscricao_xlsx($respostas);

        $html .= '<td>' . SNC_Oficinas_Utils::get_text_nl2br($respostas->inscricao_sociedade_area_atuacao) . '</td>';

        return $html;
    }
}