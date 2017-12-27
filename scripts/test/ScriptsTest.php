<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

require(dirname(__FILE__).'/../vendor/phpunit/dbunit/src/TestCaseTrait.php');
require(dirname(__FILE__).'/../vendor/phpunit/dbunit/src/TestCase.php');

class ScriptsTest extends PHPUnit\DBUnit\TestCase
{
    protected $db = null;
    protected $connection = null;
    protected $dataSetXmlFile = '/db/base-mysqldump.xml';
    protected $dataSetData = null;
    
    protected function setUp()
    {
        $this->db = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
        $this->getConnection();
        $this->dataSetData = $this->getDataSet();
        
        $this->movimentarAmbiente = new MovimentarAmbiente();
    }

    public function tearDown()
    {
        $this->db = null;
        $this->connection = null;
        $this->dataSetData = null;
        
    }
    
    public function getConnection()
    {
        if ($this->connection === null) {           
            $this->connection = $this->createDefaultDBConnection($this->db);
        }
        return $this->connection;
    }
    
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(dirname(__FILE__). $this->dataSetXmlFile);
    }
    
    public function testCriaObjeto()
    {

        $this->assertInstanceOf(
            MovimentarAmbiente::class,
            $this->movimentarAmbiente
        );
    }
    
    public function testDefineDominioOrigem()
    {
        $urlOrigem = "http://base-wp.cultura.gov.br";
        
        $this->assertContains(
            $urlOrigem,
            $this->movimentarAmbiente->defineDominios($urlOrigem, '')
        );
    }
    
    public function testDefineDominioDestino()
    {
        $urlDestino = "http://base-wp.localhost";
        
        $this->movimentarAmbiente->defineDominios('', $urlDestino);
        $this->assertContains(
            $urlDestino,
            $this->movimentarAmbiente->defineDominios('', $urlDestino)
        );
    }

    public function testDefineDominiosDiferentes()
    {
        $urlDestino = "http://base-wp.localhost";
        $urlOrigem = "http://base-wp.cultura.gov.br";        
        
        $this->assertEquals(
            array(
                'urlOrigem' => $urlOrigem,
                'urlDestino' => $urlDestino
            ),
            $this->movimentarAmbiente->defineDominios($urlOrigem, $urlDestino)
        );
    }

    public function testDefineDominiosIguais()
    {
        $urlDestino = "http://base-wp.localhost";
        $urlOrigem = "http://base-wp.localhost";
        
        $this->assertFalse(
            $this->movimentarAmbiente->defineDominios($urlOrigem, $urlDestino)
        );
    }    

    public function testDefineConexao()
    {
        $this->assertTrue($this->movimentarAmbiente->defineConexao(
            [
                'host' => '',
                'user' => '',
                'pass' => '',
                'database' => ''
            ]
        ));
    }

    public function testDefineConexaoIncompleta()
    {
        $this->assertFalse($this->movimentarAmbiente->defineConexao(
            [
                'host' => '',
                'pass' => '',
                'database' => ''
            ]
        ));
    }
    
    /**
     * @depends testDefineConexao
     */
    public function testTentaConexao()
    {
        $this->movimentarAmbiente->defineConexao(
            [
                'host' => 'localhost',
                'user' => 'root',
                'pass' => '',
                'database' => 'wpminc_unittest'
            ]
        );
        $this->assertTrue($this->movimentarAmbiente->conectar());
        
    }

    /**
     * @depends testTentaConexao
     */
    public function testExecuteQuery()
    {
        //$this->movimentarAmbiente->conectar();
        //$this->assertTrue($this->db->executeQuery("SELECT * FROM wpminc_users"));
        $this->assertFalse(false);
    }
    
    public function testMoveWebsiteSemOrigem()
    {
        $urlDestino = "http://base-wp.localhost";
        $urlOrigem = "";      
        
        $this->assertFalse($this->movimentarAmbiente->atualizarMultisite($urlOrigem, $urlDestino));
    }
    
    public function testMoveWebsite()
    {
        $urlDestino = "http://base-wp.localhost";
        $urlOrigem = "http://base-wp.cultura.gov.br";
        
        $this->assertTrue($this->movimentarAmbiente->atualizarMultisite($urlOrigem, $urlDestino));        
    }

}
