<?php

if (!defined('WPINC'))
    die();

class SNC_Oficinas_Utils
{
    static function generate_token($add = null)
    {
        return md5(uniqid(rand(), true) . $add);
    }

    static function get_status_label_subscription($status)
    {
        $label = '';

        switch ($status) {
            case 'pending':
                $label = 'Confirmação Pendente';
                break;
            case 'canceled':
                $label = 'Cancelada';
                break;
            case 'waiting_list':
                $label = 'Lista de espera';
                break;
            case 'confirmed':
                $label = 'Confirmada';
                break;
            case 'waiting_presence':
                $label = 'Aguardando Presença';
                break;
            case 'waiting_questions':
                $label = 'Aguardando Questionário';
                break;
            case 'finish':
                $label = 'Finalizada';
                break;
        }
        return $label;
    }

    static function get_text_nl2br($text, $size = 100)
    {
        $len = mb_strlen($text, 'UTF-8');

        if ($len <= $size) {
            return $text;
        }

        $lenMod = ceil($len / $size);

        $arText = array();

//        $originalText = $text;

        for ($i = 0; $i < $lenMod; $i++) {
            $baseText = mb_substr($text, 0, $size, 'UTF-8');

            $baseText = mb_substr($baseText, 0, mb_strrpos($baseText, " ", 0, 'UTF-8'), 'UTF-8');

            $arText[$i] = $baseText;

            $text = str_replace($baseText, "", $text);

            if (($i + 1) == $lenMod) {
                $arText[$i] .= $text;
            }
        }

        return trim(implode("<br>", $arText));
    }

    static function get_resp_oficina_questionario_necessidade_aprofundamento($val = null)
    {
        $array = array("1" => "Institucionalização e implementação dos componentes do Sistema de Cultura local (plano, conselho, fundo de cultura, entre outros)",
            "2" => "Acessibilidade cultural",
            "3" => "Política Nacional de Cultura Viva (Pontos, Pontões de Cultura)",
            "4" => "Operacionalização dos sistemas de parcerias (Convênios, Consórcios, Termos de fomento, entre outros)",
            "5" => "Elaboração de projetos culturais, no âmbito da diversidade cultural",
            "6" => "Orientações para execução e prestação de contas",
            "7" => "Outros");

        return null == $val ? $array : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_questionario_saber_evento($val = null)
    {
        $array = array("1" => "Divulgação do Ministério da Cidadania/Secretaria Especial da Cultura/Secretaria da Diversidade Cultural",
            "2" => "Divulgação do Estado",
            "3" => "Divulgação do meu município",
            "4" => "Jornais",
            "5" => "Internet",
            "6" => "E-mail",
            "7" => "Telefone",
            "8" => "SMS",
            "9" => "Amigos ou Conhecidos",
            "10" => "Outros");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_sim_nao($val = null)
    {
        $array = array("1" => "Sim", "0" => "Não", "2" => "Não sei");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_satisfacao($val = null)
    {
        $array = array("1" => "Insuficiente",
            "2" => "Ruim",
            "3" => "Regular",
            "4" => "Bom",
            "5" => "Ótimo",
            "0" => "Não se aplica");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_representantes($val = null)
    {
        $array = array("1" => "Processo eleitoral aberto presencial",
            "2" => "Processo eleitoral aberto digital",
            "3" => "Indicação de entidades culturais",
            "4" => "Indicação direta do gestor da pasta da cultura",
            "0" => "Outros");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_necessidade_capacitacao($val = null)
    {
        $array = array("1" => "Papel do conselheiro",
            "2" => "Legislação local",
            "3" => "Elaboração de proposições (moções, recomendações, resoluções, etc)",
            "0" => "Outros");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_ponto_cultura($val = null)
    {
        $array = array("1" => "Não, já fiz meu cadastro na Plataforma Rede Cultura Viva e aguardo Certificação Simplificada",
            "2" => "Não, ainda não fiz meu cadastro na Plataforma Rede Cultura Viva",
            "0" => "Sim");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_coletivos_culturais($val = null)
    {
        $array = array("0" => "Sim, e não tive dificuldade como participante da capacitação",
            "1" => "Sim, e tive dificuldade como participante da capacitação",
            "2" => "Não, e não soube de nenhuma capacitação",
            "3" => "Não, e não tive interesse em participar");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_comunicacao_direta($val = null)
    {
        $array = array("0" => "Sim, a comunicação é efetiva",
            "1" => "Sim, a comunicação é falha",
            "2" => "Não, e tenho dificuldade para me comunicar com outras as entidades e coletivos culturais da região",
            "3" => "Não, e não tenho interesse em me comunicar com outras as entidades e coletivos culturais da região");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }

    static function get_resp_oficina_comunicacao_cidadania($val = null)
    {
        $array = array("0" => "Sim, a comunicação é efetiva",
            "1" => "Sim, a comunicação é falha",
            "2" => "Não");

        return null == $val ? null : (isset($array[$val]) ? $array[$val] : null);
    }
}