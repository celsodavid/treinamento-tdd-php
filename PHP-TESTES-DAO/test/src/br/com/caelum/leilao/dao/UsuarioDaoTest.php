<?php
namespace test\src\br\com\caelum\leilao\dao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dao\UsuarioDao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\Factory\ConnectionFactory;

/**
 * UsuarioDao test case.
 */
class UsuarioDaoTest extends TestCase
{    
    private $usuarioDao;    
    private $conn;
    
    protected function setUp()
    {
         $this->conn = ConnectionFactory::getConnection();
         $this->conn->beginTransaction();
        
        $this->usuarioDao = new UsuarioDao($this->conn);
    }
    
    protected function tearDown()
    {
        $this->conn->rollback();
    }
    
    public function testDeveRetornarUsuarioPorNomeEEmail()
    {
        $usuario = new Usuario("Zé", "ze@gmail.com");
        $this->usuarioDao->salvar($usuario);
        
        $usuarioDoBanco = $this->usuarioDao->porNomeEEmail($usuario->getNome(), $usuario->getEmail());
        
        $this->assertEquals($usuario->getNome(), $usuarioDoBanco->getNome());
        $this->assertEquals($usuario->getEmail(), $usuarioDoBanco->getEmail());
    }
    
    public function testNaoDeveRetornarUsuarioPorNomeEEmail()
    {
        $usuario = new Usuario("Zé", "ze@gmail.com");
        
        $usuarioDoBanco = $this->usuarioDao->porNomeEEmail($usuario->getNome(), $usuario->getEmail());
        
        $this->assertFalse($usuarioDoBanco);
    }
}

