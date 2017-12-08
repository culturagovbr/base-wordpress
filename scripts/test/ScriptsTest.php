<?php
use PHPUnit\Framework\TestCase;

class ScriptsTest extends TestCase
{
    public function testCriaObjeto()
    {
        $movimentaAmbiente = new MovimentarAmbiente();
        $this->assertInstanceOf(
            MovimentarAmbiente::class,
            $movimentaAmbiente
        );
    }
    
    public function testDefineDominioOrigem()
    {
        $urlOrigem = "http://base-wp.cultura.gov.br";
        
        $movimentaAmbiente = new MovimentarAmbiente();
        $this->assertContains(
            $urlOrigem,
            $movimentaAmbiente->defineDominios($urlOrigem, '')
        );
    }

    public function testDefineDominioDestino()
    {
        $urlDestino = "http://base-wp.localhost";
        
        $movimentaAmbiente = new MovimentarAmbiente();
        $this->assertContains(
            $urlDestino,
            $movimentaAmbiente->defineDominios('', $urlDestino)
        );
    }

    public function testVerificaConexao()
    {
        $movimentaAmbiente = new MovimentarAmbiente();
        $this->assertTrue($movimentaAmbiente->conexao());
    }    

    public function testDefineConexao()
    {
        $movimentaAmbiente = new MovimentarAmbiente();
        $this->assertTrue($movimentaAmbiente->defineConexao(['host' => '', 'user' => '', 'pass' => '', 'database' => '']));
    }    

    public function testTentaConexao()
    {
        $movimentaAmbiente = new MovimentarAmbiente();
        $movimentaAmbiente->defineConexao(['host' => 'localhost', 'user' => 'root', 'pass' => '', 'database' => '']);;
        $this->assertTrue($movimentaAmbiente->conectar());
    }

    public function testExecuteQuery()
    {
        $movimentaAmbiente = new MovimentarAmbiente();
        $movimentaAmbiente->defineConexao(['host' => 'localhost', 'user' => 'root', 'pass' => '', 'database' => 'wpbase']);;
        $movimentaAmbiente->conectar();
        $this->assertTrue($movimentaAmbiente->executeQuery("SELECT * FROM wpminc_users"));
    }
}