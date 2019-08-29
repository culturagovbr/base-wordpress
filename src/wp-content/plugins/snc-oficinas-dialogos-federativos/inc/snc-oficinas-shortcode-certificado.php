<?php

if (!defined('WPINC'))
    die();

require_once SNC_ODF_PLUGIN_PATH . '/vendor/autoload.php';

class SNC_Oficinas_Shortcode_Certificado
{
    private $configPdf = [
        'mode' => 'utf-8',
        'default_font_size' => 0,
        'default_font' => '',
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'margin_header' => 0,
        'margin_footer' => 0,
        'orientation' => 'L',
    ];

    private $mpdf;

    private $idOficina = null;

    public function __construct($idOficina = null)
    {
        if (!is_admin()) {
            add_shortcode('snc-certificado', array($this, 'snc_impressao'));
        }

//        var_dump(get_temp_dir());
//        exit;
//        $uploadDir = wp_upload_dir();
//
//        if (!is_dir($uploadDir['path'])) {
//            mkdir($uploadDir['path'], 0777, );
//        }

        $this->configPdf['tempDir'] = get_temp_dir();//$uploadDir['path'];

        $this->mpdf = new Mpdf\Mpdf($this->configPdf);

        add_filter('page_template', array($this, 'snc_oficinas_page_template'));

        $this->idOficina = isset($_GET['idOficina']) ? $_GET['idOficina'] : $idOficina;
    }

    public function snc_impressao()
    {
        $this->_generatePdf();

        $this->mpdf->Output('certificado_oficinas_snc.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    public function uploadPdf()
    {
        $this->_generatePdf();

        $uploadDir = wp_upload_dir();

        $local = "{$uploadDir['path']}/certificado_oficinas_snc_" . time() . ".pdf";

        $this->mpdf->Output($local, \Mpdf\Output\Destination::FILE);

        return $local;
    }

    private function _generatePdf()
    {
        if (!is_user_logged_in()) {
            echo "Autenticação é obrigatória para acessar este recurso";
            return false;
        }

        global $texto;
        global $textoData;
        global $horas;
        global $url;
        global $unidade;
        global $prefixo;

        $current_user = wp_get_current_user();
        $name = $current_user->display_name;

        $oficina = get_fields($this->idOficina);
        $oficina_campos = get_fields($oficina['inscricao_oficina_uf']->ID);

        $uf = $oficina_campos['oficina_unidade_da_federacao'];

        $unidade = mb_substr($uf, 0, strpos($uf, " ("), "UTF-8");
        $sigla = mb_substr($uf, strpos($uf, "(") + 1, 2, "UTF-8");
        $prefixo = $this->_prefixo($sigla);

        $dataInicio = explode("/", $oficina_campos['oficina_data_inicio']);
        $dataFinal = explode("/", $oficina_campos['oficina_data_final']);

        $objDateIni = new DateTime("{$dataInicio[2]}-{$dataInicio[1]}-{$dataInicio[0]}");
        $objDateFinal = new DateTime("{$dataFinal[2]}-{$dataFinal[1]}-{$dataFinal[0]}");

        $dias = $objDateFinal->diff($objDateIni)->days + 1;
        $horas = ($oficina_campos['oficina_horario_termino'] - $oficina_campos['oficina_horario_inicio']) * $dias;


        $texto = "Certificamos que {$name} participou do evento “<b>Diálogos Federativos: Cultura de Ponto à Ponta</b>”";
        $texto .= ", realizado pela Secretaria da Diversidade Cultural em parceria com o Estado {$prefixo} ";
        $texto .= " {$unidade}, no período de {$oficina_campos['oficina_data_inicio']}";
        $texto .= " à {$oficina_campos['oficina_data_final']}, com carga horária total de {$horas} horas.";

        $mes = $this->_getMes((int)$dataFinal[1]);
        $textoData = "Brasília, {$dataFinal[0]} de {$mes} de $dataFinal[2].";
        $url = SNC_ODF_PLUGIN_PATH . "assets/base_cert.png";

        ob_start();

        require_once SNC_ODF_PLUGIN_PATH . '/pages/certificado-pdf.php';
        $template = ob_get_contents();

        ob_end_clean();

        $this->mpdf->WriteHTML($template);
    }

    private function _getMes($mes)
    {
        $meses = array(1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
            7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro');

        return $meses[$mes];
    }

    private function _prefixo($sigla)
    {
        $prefixoA = ['BA', 'PB'];
        $prefixoE = ['AL', 'MG', 'PE', 'RO', 'RR', 'SC', 'SE', 'SP'];
        $prefixoO = ['AC', 'AP', 'AM', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'PA', 'PI', 'PR', 'RJ', 'RN', 'RS', 'TO'];

        if (in_array($sigla, $prefixoA)) {
            return 'da';
        }

        if (in_array($sigla, $prefixoE)) {
            return 'de';
        }

        if (in_array($sigla, $prefixoO)) {
            return 'do';
        }

        return '';
    }

    public function snc_oficinas_page_template($page_template)
    {
        if (is_page('certificado')) {
            $page_template = SNC_ODF_PLUGIN_PATH . '/pages/custom-page-template.php';
            return $page_template;
        }
    }
}