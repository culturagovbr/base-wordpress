<?php
use PHPUnit\Framework\TestCase;

class ScriptsTest extends TestCase
{
    protected function setUp()
    {
        $this->movimentarAmbiente = new MovimentarAmbiente();
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
        $this->assertTrue($this->movimentarAmbiente->defineConexao(['host' => '', 'user' => '', 'pass' => '', 'database' => '']));
    }

    public function testDefineConexaoIncompleta()
    {
        $this->assertFalse($this->movimentarAmbiente->defineConexao(['host' => '', 'pass' => '', 'database' => '']));
    }
    
    public function testTentaConexao()
    {
        $this->movimentarAmbiente->defineConexao(['host' => 'localhost', 'user' => 'root', 'pass' => '', 'database' => '']);;
        $this->assertTrue($this->movimentarAmbiente->conectar());
    }

    /**
     * @depends testTentaConexao
     */
    public function testExecuteQuery()
    {
        $this->movimentarAmbiente->defineConexao(['host' => 'localhost', 'user' => 'root', 'pass' => '', 'database' => 'wpbase']);;
        $this->movimentarAmbiente->conectar();
        $this->assertTrue($this->movimentarAmbiente->executeQuery("SELECT * FROM wpminc_users"));
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