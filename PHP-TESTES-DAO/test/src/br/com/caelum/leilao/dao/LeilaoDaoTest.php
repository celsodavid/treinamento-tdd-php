<?php
namespace test\src\br\com\caelum\leilao\dao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\Factory\ConnectionFactory;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\dao\UsuarioDao;
use test\src\br\com\caelum\leilao\builder\LeilaoBuilder;
use src\br\com\caelum\leilao\dominio\Lance;

/**
 * LeilaoDao test case.
 */
class LeilaoDaoTest extends TestCase
{
    private $conn;
    private $leilaoDao;
    private $usuarioDao;    
    private $leilaoBuilder;
    private $dono;
    private $andre;
    
    /**
     * @before
     */
    public function setUp()
    {
        $this->conn = ConnectionFactory::getConnection();
        $this->leilaoDao = new LeilaoDao($this->conn);
        $this->usuarioDao = new UsuarioDao($this->conn);
        $this->leilaoBuilder = new LeilaoBuilder();
        
        $this->conn->beginTransaction();
        
        $this->dono = new Usuario("Satiro");       
        $this->andre = new Usuario("Andre");
        $this->usuarioDao->salvar($this->dono);
        $this->usuarioDao->salvar($this->andre);
        
    }
    
    public function testDeveSalvarLeilao()
    {                     
        $leilao = (new LeilaoBuilder())
            ->comNome("Hiphone")
            ->comValor(150.0)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->constroi();
        
       $res = $this->leilaoDao->salvar($leilao);
       
       $this->assertTrue($res);
    }
    
    public function testDeveRetornarApenasLeiloesNaoEncerrados()
    {
        $leilao1 = (new LeilaoBuilder())
            ->comNome("Hiphone")
            ->comValor(150.0)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->encerrado()
            ->constroi();
        
        $leilao2 = (new LeilaoBuilder())
            ->comNome("Iphone")
            ->comValor(1500.0)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->constroi();
        
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
            
        $total = $this->leilaoDao->total();
        
        $this->assertEquals(1, $total);
    }
    
    public function testNaoDeveRetornarLeiloesJaEncerrados()
    {
        $leilao1 = (new LeilaoBuilder())
            ->comNome("Hiphone")
            ->comValor(150.0)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->encerrado()
            ->constroi();
            
        $leilao2 = (new LeilaoBuilder())
            ->comNome("Iphone")
            ->comValor(1500.0)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->usado()
            ->encerrado()
            ->constroi();
        
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $total = $this->leilaoDao->total();
        
        $this->assertEquals(0, $total);
    }
    
    public function testDeveRetornarApenasLeiloesNovos()
    {
        $leilao1 = $this->leilaoBuilder->comNome("Leilao1")->constroi();        
        $leilao2 = $this->leilaoBuilder->comNome("Leilao2")->usado()->constroi();
        
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $novos = $this->leilaoDao->novos();
        
        //$this->assertEquals(1, count($novos));
    }
    
    public function testDeveRetornarApenasLeiloesCriadosAMaisDeUmaSemana()
    {
        $leilao1 = (new LeilaoBuilder())
            ->comNome("Hiphone")
            ->comValor(150.0)
            ->naData(new \DateTime("2017-11-29"))
            ->comDono($this->dono)
            ->constroi();
        
        $leilao2 = (new LeilaoBuilder())
            ->comNome("Iphone")
            ->comValor(1500.0)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->encerrado()
            ->constroi();
        
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $antigos = $this->leilaoDao->antigos();
        
        $this->assertEquals(1, count($antigos));
    }
    
    public function testDeveRetornarApenasLeiloesCriadosAExatamenteUmaSemana()
    {
        $leilao1 = (new LeilaoBuilder())
            ->comNome("Hiphone")
            ->comValor(150.0)
            ->naData(new \DateTime("2017-12-02s"))
            ->comDono($this->dono)
            ->constroi();
        
        $leilao2 = (new LeilaoBuilder())
            ->comNome("Iphone")
            ->comValor(1500.0)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->constroi();
        
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $antigos = $this->leilaoDao->antigos();
        
        //$this->assertEquals(2, count($antigos));
    }
    
    public function testDeveTrazerLeiloesNaoEncerradosNoPeriodo()
    {
        $inicio = new \DateTime();
        $inicio->sub(new \DateInterval("P10D"));
        
        $fim = new \DateTime();
        
        $dtLeilao1 = new \DateTime();
        $dtLeilao1->sub(new \DateInterval("P2D"));
        
        $dtLeilao2 = new \DateTime();
        $dtLeilao2->sub(new \DateInterval("P20D"));
        
        $leilao1 = (new LeilaoBuilder())
            ->comNome("Hiphone")
            ->comValor(150.0)
            ->naData($dtLeilao1)
            ->comDono($this->dono)
            ->constroi();
        
        $leilao2 = (new LeilaoBuilder())
            ->comNome("Iphone")
            ->comValor(1500.0)
            ->naData($dtLeilao2)
            ->comDono($this->dono)
            ->constroi();
        
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $leiloes = $this->leilaoDao->porPeriodo($inicio, $fim);
        
        $this->assertEquals(1, count($leiloes));
        $this->assertEquals("Hiphone", $leiloes[0]->getNome());
    }
    
    public function testNaoDeveTrazerLeiloesEncerradosNoPeriodo()
    {
        $inicio = new \DateTime();
        $inicio->sub(new \DateInterval("P10D"));
        
        $fim = new \DateTime();
        
        $dtLeilao1 = new \DateTime();
        $dtLeilao1->sub(new \DateInterval("P2D"));
        
        $leilao1 = (new LeilaoBuilder())
            ->comNome("Hiphone")
            ->comValor(150.0)
            ->naData($dtLeilao1)
            ->comDono($this->dono)
            ->encerrado()
            ->constroi();
        
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->porPeriodo($inicio, $fim);
        
        $this->assertEquals(0, count($leiloes));
    }
    
    public function testDeveRetornarLeiloesAtivosComLancesMaioresQue3()
    {
        $leilao1 = $this->leilaoBuilder
            ->comNome("Hiphone")
            ->comValor(100.0)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->constroi();
        
        $lance1 = new Lance($this->andre, 200.0, $leilao1);
        $lance2 = new Lance(new Usuario("Sergio"), 500.0, $leilao1);
        $lance3 = new Lance(new Usuario("Celso"), 600.0, $leilao1);
        
        $leilao1->propoe($lance1);
        $leilao1->propoe($lance2);
        $leilao1->propoe($lance3);
        
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->disputadosEntre(100.0, 800.0);
        
        $this->assertEquals(1, count($leiloes));
    }
    
    public function testNaoDeveRetornarLeiloesEncerradosDentroOuForaDoIntervalo()
    {
        $leilao1 = $this->leilaoBuilder
            ->comNome("Hiphone")
            ->comValor(50.00)
            ->naData(new \DateTime())
            ->comDono($this->dono)
            ->encerrado()
            ->constroi();
        
        $lance1 = new Lance($this->andre, 200.0, $leilao1);
        $lance2 = new Lance(new Usuario("Sergio"), 500.0, $leilao1);
        $lance3 = new Lance(new Usuario("Celso"), 600.0, $leilao1);
        
        $leilao1->propoe($lance1);
        $leilao1->propoe($lance2);
        $leilao1->propoe($lance3);
        
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->disputadosEntre(100.0, 800.0);
        
        $this->assertEquals(0, count($leiloes));
    }
    
    public function testNaoDeveRetornarLeiloesAtivosForaDoIntervalo()
    {
        $leilao1 = $this->leilaoBuilder
        ->comNome("Hiphone")
        ->comValor(50.00)
        ->naData(new \DateTime())
        ->comDono($this->dono)
        ->encerrado()
        ->constroi();
        
        $lance1 = new Lance($this->andre, 200.0, $leilao1);
        $lance2 = new Lance(new Usuario("Sergio"), 500.0, $leilao1);
        $lance3 = new Lance(new Usuario("Celso"), 600.0, $leilao1);
        
        $leilao1->propoe($lance1);
        $leilao1->propoe($lance2);
        $leilao1->propoe($lance3);
        
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->disputadosEntre(100.0, 800.0);
        
        $this->assertEquals(0, count($leiloes));
    }
    
    public function testDeveRetornarListaDeLeilosPorUsuarioSemRepeticao()
    {
        $leilao1 = $this->leilaoBuilder->comNome('Leilao1')->comValor(1500.0)->comDono($this->dono)->diasAtras(5)->constroi();
        $leilao2 = $this->leilaoBuilder->comNome('Leilao2')->comValor(800.)->comDono($this->dono)->diasAtras(5)->constroi();
        
        $lance1 = new Lance($this->andre, 200.0, $leilao1);
        $lance2 = new Lance($this->andre, 500.0, $leilao1);
        $lance3 = new Lance($this->andre, 600.0, $leilao2);
        
        $leilao1->propoe($lance1);
        $leilao1->propoe($lance2);
        
        $leilao2->propoe($lance3);
        
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $leiloes = $this->leilaoDao->listaLeiloesDoUsuario($this->andre);
        
        $this->assertEquals(2, count($leiloes));
    }
    
    public function testDeveRetornarOValorInicialMedioDosLancesPorUsuario()
    {
        $leilao1 = $this->leilaoBuilder->comNome('Leilao1')->comValor(1500.0)->comDono($this->dono)->diasAtras(5)->constroi();
        $leilao2 = $this->leilaoBuilder->comNome('Leilao2')->comValor(800.)->comDono($this->dono)->diasAtras(5)->constroi();
        
        $lance1 = new Lance($this->andre, 200.0, $leilao1);
        $lance2 = new Lance($this->andre, 500.0, $leilao2);
        
        $leilao1->propoe($lance1);
        $leilao2->propoe($lance2);
        
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $leiloes = $this->leilaoDao->getValorInicialMedioDoUsuario($this->andre);
        
        //$this->assertEquals(2, count($leiloes));
    }
    
    public function testDeveExcluirLeilaoEncerrado()
    {
        $leilao1 = $this->leilaoBuilder->encerrado()->constroi();       
        
        $this->leilaoDao->salvar($leilao1);
        //$this->leilaoDao->deletaEncerrados();
        $this->leilaoDao->deletar($leilao1);
        $porId = $this->leilaoDao->porId($leilao1->getId());
        
        //var_dump($porId);
        
        $this->assertEquals($porId, null);
    }
}

