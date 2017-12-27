<?php

class MovimentarAmbiente
{
    public $urlOrigem;
    public $urlDestino;
    private $dadosConexaoDefault = array(
        'host'     => NULL,
        'database' => NULL,
        'user'     => NULL,
        'pass'     => NULL
    );

    private $db;    
    private $dadosConexao = array();
    private $conexao = NULL;
    private $protocolPattern = '/$[a-z]\:\/\//';

    public function __construct()
    {
    }

    public function __setPaths($urlOrigem, $urlDestino)
    {
        $this->__verificarUrlValida($urlOrigem);
        $this->__verificarUrlValida($urlDestino);
        
        $this->urlOrigem = $urlOrigem;
        $this->urlDestino = $urlDestino;
    }
    
    public function defineDominios($urlOrigem, $urlDestino = null)
    {
        if ($urlOrigem == $urlDestino) {
            return false;
        }
        
        $this->__setPaths(
            $urlOrigem,
            $urlDestino
        );
        
        $output = array(
            "urlOrigem" => $this->urlOrigem,
            "urlDestino" => $this->urlDestino
        );
        
        return $output;
    }

    /*
    private function __stripProtocol($url)
    {
        $url = preg_replace($this->protocolPattern, '', $url);
        
        return $url;
    }
    */

    /*
    private function __hasProtocol($url)
    {
        $response = (!empty(preg_match($this->protocolPattern, $url))) ? true : false;
        
        return $response;
    }
    */
        
    private function __verificarUrlValida($url)
    {
        $validation = filter_var($url, FILTER_VALIDATE_URL);
        if (!$validation) {
            return false;
        }
        return true;
    }

    private function __validaDadosConexao($dadosConexao)
    {
        if (!is_array($dadosConexao) || empty($dadosConexao)) {
            return false;
        }
        
        if (count(array_intersect_key($this->dadosConexaoDefault, $dadosConexao)) !== count($this->dadosConexaoDefault)) {           
            return false;
        }
        
        return true;
    }
    
    public function defineConexao($dadosConexao)
    {
        if (!$this->__validaDadosConexao($dadosConexao)) {
            return false;
        }
        
        $this->dadosConexao = $dadosConexao;
        
        return true;
    }

    public function conectar()
    {
        if (!$this->__validaDadosConexao($this->dadosConexao)) {
            return false;
        }
        
        $this->db = new PDO("mysql:dbname=" . $this->dadosConexao['database'] . ";hostname=" . $this->dadosConexao['host'], $this->dadosConexao['user'], $this->dadosConexao['pass'] );
        
        if (!$this->db) {
            return false;
        }
        
        return true;
    }
    
    public function executeQuery($sql)
    {
        $this->db->exec($sql);
        if (!$this->db->exec($sql)) {
            return false;
        }
        return true;
    }

    public function atualizarMultisite($urlOrigem = '', $urlDestino = '')
    {       
        if ((!$urlOrigem && !defined($this->urlOrigem))
        || (!$urlDestino && !defined($this->urlDestino))) {
            return false;
        }

        if ($urlOrigem || $urlDestino) {
            $this->defineDominios($urlOrigem, $urlDestino);
        }

        $this->__atualizar_wp_options();
        
        return true;
    }

    private function __atualizar_wp_options()
    {
        if (!isset($this->urlOrigem)
        || !isset($this->urlDestino)) {
            
        }
        return ;
    }
}


//$movimentaAmbiente = new MovimentaAmbiente();
//$movimentaAmbiente->defineConexao(['host' => '', 'user' => '', 'pass' => '', 'database' => '']);

/*
$urlOrigem = "http://base-wp.cultura.gov.br";
$urlDestino = "http://base-wp.localhost";

$movimentaAmbiente = new MovimentaAmbiente();

$movimentaAmbiente->atualizarMultisite(
    $urlOrigem,
    $urlDestino
);
*/
/*
$urlOrigem = "http://base-wp.cultura.gov.br";
$urlDestino = "http://base-wp.localhost";
$dominioOrigem = "cultura.gov.br";
$dominioDestino = "base-wp.localhost";

print "<h3>Atualizando: de '{$urlOrigem}' para '{$urlDestino}'<h3>";
print "<h4>Atualizações do blog 1</h4>";

$sqlUpdateOptions = "UPDATE wpminc_options SET option_value = '{$urlDestino}' WHERE option_name = 'siteurl';<br>";
$sqlUpdateOptions .= "UPDATE wpminc_options SET option_value = '{$urlDestino}' WHERE option_name = 'home';<br>";
$sqlUpdateSiteDomain = "UPDATE wpminc_site SET domain = '{$dominioDestino}' WHERE id = 1;<br>";

print $sqlUpdateOptions;
print "<br/>";
print $sqlUpdateSiteDomain;
print "<br/>";

$sqlUpdateSitemeta = "UPDATE wpminc_sitemeta SET meta_value = '{$urlDestino}' WHERE meta_key = 'siteurl';";
print $sqlUpdateSitemeta;
print "<br/>";

$sqlUpdateBlogPrincipal ="UPDATE wpminc_blogs SET domain = '{$dominioDestino}') WHERE blog_id = 1;";
print $sqlUpdateBlogPrincipal;
print "<br/>";


print "<hr>";


print "<h4>Atualizando sites para endereço local</h4>";


### Atualizando configurações de cada site

$idSite = 26;
$siteOrigem = "culturaviva.base-wp.cultura.gov.br";
$siteDestino = "culturaviva.base-wp.localhost";

print "<h5>Atualizando '$siteOrigem' para '$siteDestino'</h5>";

$sqlUpdateBlogs ="UPDATE wpminc_blogs SET domain = '{$siteDestino}' WHERE blog_id = {$idSite};";
print $sqlUpdateBlogs;
print "<br>";

$sqlDomainMap = "UPDATE wpminc_domain_mapping SET domain = '{$siteDestino}' WHERE domain = '{$siteOrigem}';";
print $sqlDomainMap;
print "<br>";

$sqlUpdateSiteOptions = "UPDATE wpminc_" . $idSite . "_options SET option_value = '{$siteDestino}' WHERE option_name = 'siteurl';<br>";
$sqlUpdateSiteOptions .= "UPDATE wpminc_" . $idSite . "_options SET option_value = '{$siteDestino}' WHERE option_name = 'home';<br>";
$sqlUpdateSiteOptions .= "UPDATE wpminc_" . $idSite . "_options SET option_value = '{$siteDestino}' WHERE option_name = 'fileupload_url';";

print $sqlUpdateSiteOptions;
print "<hr/>";



### Wp cli para o resto
/*
Caso seja necessário atualizar urls, você pode utilizar o wp cli.

    # Atualizando links e endereços em posts
    cd [path_to_wp_install]
    wp search-replace 'base-wp.cultura.gov.br' $ambienteDestino wp_posts
* /

print "wp search-replace '{$siteOrigem}' '{$siteDestino}' wp_26_options";
*/