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
    
    public function testDeveDeletarUmUsuario()
    {
        $usuario = new Usuario("Zé", "ze@gmail.com");
        
        $this->usuarioDao->salvar($usuario);
        $this->usuarioDao->deletar($usuario);
        
        $usuarioDoBanco = $this->usuarioDao->porNomeEEmail($usuario->getNome(), $usuario->getEmail());
        
        $this->assertFalse($usuarioDoBanco);
    }
    
    public function testDeveAtualizarUmUsuario()
    {
        $usuario = new Usuario("Zé", "ze@gmail.com");
        $this->usuarioDao->salvar($usuario);
               
        $usuario->setNome("Celso");
        $usuario->setEmail("celso@lopes.com.br");
        $this->usuarioDao->atualizar($usuario);
        
        $usuarioDoBancoAlterado = $this->usuarioDao->porNomeEEmail("Celso", "celso@lopes.com.br");
        $usuarioDoBancoAntigo = $this->usuarioDao->porNomeEEmail("Zé", "ze@gmail.com");
        
        $this->assertFalse($usuarioDoBancoAntigo);
        $this->assertEquals($usuario->getId(), $usuarioDoBancoAlterado->getId());
    }
}

