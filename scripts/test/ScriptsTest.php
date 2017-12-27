<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\QueryTable;

require(dirname(__FILE__).'/../vendor/phpunit/dbunit/src/TestCaseTrait.php');
require(dirname(__FILE__).'/../vendor/phpunit/dbunit/src/TestCase.php');

class ScriptsTest extends PHPUnit\DBUnit\TestCase
{
    protected $db = null;
    protected $connection = null;
    protected $dataSetXmlFile = '/db/base-mysqldump.xml';
    protected $dataSetData = null;
    protected $table;
    
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

    private function getFixtureRow($table, $index)
    {
        $fixtureTable = $this->getConnection()->createDataSet()->getTable($table);
        return $fixtureTable->getRow($index);
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
    public function testExecuteSimpleQuery()
    {
        $queryTable = $this->getConnection()->createQueryTable('wpminc_site', 'SELECT * FROM wpminc_site');
        $expectedTable = $this->getConnection()->createDataSet()->getTable('wpminc_site');
        
        $this->assertTablesEqual($expectedTable, $queryTable);        
    }

    /**
     * @depends testExecuteSimpleQuery
     */
    public function testSimpleRowCount()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('wpminc_site'));
        $this->assertEquals(3, $this->getConnection()->getRowCount('wpminc_sitemeta'));
    }

    /**
     * @depends testExecuteSimpleQuery
     */
    public function testAtualizarOptions()
    {
        //$expectedTable = $this->getConnection()->createDataSet()->getTable('wpminc_site');
        //
        // testar se a classe vai conseguir atualizar para valor correto
        // 1- atualizar valor pela classe
        // 2- verificar se o valor desejado é igual ao novo

        $urlDestino = "http://base-wp.localhost";
        $urlOrigem = "http://base-wp.cultura.gov.br";        
        
        $this->movimentarAmbiente->defineDominios($urlOrigem, $urlDestino);
                
        $this->assertTrue($this->movimentarAmbiente->atualizarOptions());

        
        /*
        $queryTable = $this->getConnection()->createQueryTable('wpminc_site', 'SELECT * FROM wpminc_site');
        $expectedTable = $this->getConnection()->createDataSet()->getTable('wpminc_site');
        
        $this->assertTablesEqual($expectedTable, $queryTable);        
        */
        
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
