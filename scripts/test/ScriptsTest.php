<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\QueryTable;

require(dirname(__FILE__).'/../vendor/phpunit/dbunit/src/TestCaseTrait.php');
require(dirname(__FILE__).'/../vendor/phpunit/dbunit/src/TestCase.php');

class ScriptsTest extends PHPUnit\DBUnit\TestCase
{
    protected $pdo = null;
    protected $connection = null;
    protected $dataSetXmlFile = '/db/base-mysqldump.xml';
    protected $dataSetData = null;
    protected $table;
    
    protected function setUp()
    {
        $this->getConnection();
        $this->dataSetData = $this->getDataSet();
        
        $this->movimentarAmbiente = new MovimentarAmbiente();
    }

    public function tearDown()
    {
        $this->pdo = null;
        $this->connection = null;
        $this->dataSetData = null;
        
    }
    
    public function getConnection()
    {
        if ($this->connection === null) {
            $this->pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection = $this->createDefaultDBConnection($this->pdo, $GLOBALS['DB_DBNAME']);
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

    public function testStripProtocol()
    {
        $url = "https://base-wp.localhost";
        $urlExpected = "base-wp.localhost";

        $this->assertEquals($urlExpected, $this->movimentarAmbiente->stripProtocol($url));
    }
    
    
    /*
     *
     *
     *     DATABASE TESTS
     *
     *
     */

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
        $this->assertInstanceOf(PDO::class, $this->movimentarAmbiente->conectar());        
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
    public function testAtualizarCampo()
    {
        $this->movimentarAmbiente->defineConexao(
            [
                'host' => 'localhost',
                'user' => 'root',
                'pass' => '',
                'database' => 'wpminc_unittest'
            ]
        );
                
        $urlOrigem = "http://base-wp.localhost";
        $urlDestino = "http://base-wp.cultura.gov.br";        
        
        $this->movimentarAmbiente->defineDominios(
            $urlOrigem,
            $urlDestino
        );
        
        $this->assertTrue(
            $this->movimentarAmbiente->atualizarCampo(
                'wpminc_options',
                array(
                    array('option_value' => $urlDestino)
                ),
                array(
                    array('option_name' => 'siteurl')
                )
            )
        );

        $this->assertTrue(
            $this->movimentarAmbiente->atualizarCampo(
                'wpminc_options',
                array(
                    array('option_value' => $urlDestino)
                ),
                array(
                    array('option_name' => 'home')
                )
            )
        );        
        
        $actualTable = $this->getConnection()->createQueryTable(
            'wpminc_options',
            'SELECT * FROM wpminc_options'
        );

        $expectedTable = $this->createFlatXmlDataSet(dirname(__FILE__) . '/db/expected-wpminc_options.xml')
                              ->getTable('wpminc_options');
        
        $sqlUpdateOptions = "UPDATE wpminc_options SET option_value = '{$urlDestino}' WHERE option_name = 'siteurl'";            
        
        $this->assertTablesEqual($actualTable, $expectedTable);
    }

    public function testGetOptionInexistente()
    {
        $this->movimentarAmbiente->defineConexao(
            [
                'host' => 'localhost',
                'user' => 'root',
                'pass' => '',
                'database' => 'wpminc_unittest'
            ]
        );
                
        $urlOrigem = "http://base-wp.localhost";
        $urlDestino = "http://base-wp.cultura.gov.br";        
        
        $this->movimentarAmbiente->defineDominios($urlOrigem, $urlDestino);
        
        $inexistente = $this->movimentarAmbiente->getOptionValue('inexistente');
        
        $this->assertNull($inexistente);
    }

    public function testGetOptionSitename()
    {
        $this->movimentarAmbiente->defineConexao(
            [
                'host' => 'localhost',
                'user' => 'root',
                'pass' => '',
                'database' => 'wpminc_unittest'
            ]
        );
                
        $urlOrigem = "http://base-wp.localhost";
        $urlDestino = "http://base-wp.cultura.gov.br";        
        
        $this->movimentarAmbiente->defineDominios($urlOrigem, $urlDestino);
        
        $blogdescription = $this->movimentarAmbiente->getOptionValue('blogdescription');
        
        $this->assertEquals('Teste', $blogdescription);
    }
    
    public function testMoveWebsiteSemOrigem()
    {
        $urlDestino = "http://base-wp.localhost";
        $urlOrigem = "";      
        
        $this->assertFalse($this->movimentarAmbiente->atualizarMultisite($urlOrigem, $urlDestino));
    }
    
    public function testMoveWebsite()
    {
        $this->movimentarAmbiente->defineConexao(
            [
                'host' => 'localhost',
                'user' => 'root',
                'pass' => '',
                'database' => 'wpminc_unittest'
            ]
        );
        
        $urlDestino = "http://base-wp.localhost";
        $urlOrigem = "http://base-wp.cultura.gov.br";
        
        $this->assertTrue($this->movimentarAmbiente->atualizarMultisite($urlOrigem, $urlDestino));        
    }
    
}
