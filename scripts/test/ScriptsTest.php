<?php
use PHPUnit\Framework\TestCase;

class ScriptsTest extends TestCase
{
    public function testCriaObjeto()
    {
        $movimentaAmbiente = new MovimentaAmbiente();
        $this->assertInstanceOf(
            MovimentaAmbiente::class,
            $movimentaAmbiente
        );
    }
    
    public function testDefineDominioOrigem()
    {
        $urlOrigem = "http://base-wp.cultura.gov.br";
        
        $movimentaAmbiente = new MovimentaAmbiente();
        $this->assertContains(
            $urlOrigem,
            $movimentaAmbiente->defineDominios($urlOrigem, '')
        );
    }

    public function testDefineDominioDestino()
    {
        $urlDestino = "http://base-wp.localhost";
        
        $movimentaAmbiente = new MovimentaAmbiente();
        $this->assertContains(
            $urlDestino,
            $movimentaAmbiente->defineDominios('', $urlDestino)
        );
    }

    public function testVerificaConexao()
    {
        $movimentaAmbiente = new MovimentaAmbiente();
        $this->assertTrue($movimentaAmbiente->conexao());
    }    

    public function testDefineConexao()
    {
        $movimentaAmbiente = new MovimentaAmbiente();
        $this->assertTrue($movimentaAmbiente->defineConexao(['host' => '', 'user' => '', 'pass' => '', 'database' => '']));
    }    

    
}