<?php
namespace test\src\br\com\caelum\leilao\service;

use PHPUnit\Framework\TestCase;
use test\src\br\com\caelum\leilao\builder\LeilaoBuilder;
use src\br\com\caelum\leilao\dominio\LeilaoCrudDao;
use src\br\com\caelum\leilao\service\EncerradorDeLeilao;
use src\br\com\caelum\leilao\dao\LeilaoDao;

class EncerradorDeLeiloesTest extends TestCase
{
    private $leilaoBuilder;
    
    public function setUp()
    {
        $this->leilaoBuilder = new LeilaoBuilder();
    }
    
    public function testDeveEncerrarLeiloesComMaisDeUmaSemana()
    {
        $antiga = new \DateTime("1970-01-01");
        
        $leilao = $this->leilaoBuilder->para("Courazao do JoJo")->naData($antiga)->constroi();
        
        $dao = $this->createMock(LeilaoCrudDao::class);
        $dao->method("correntes")->will($this->returnValue([$leilao]));
        
        $encerrador = new EncerradorDeLeilao($dao);
        $encerrador->encerra();
        
        $this->assertTrue($leilao->getEncerrados());
        $this->assertEquals(1, $encerrador->getTotalEncerrados());
    }
    
    public function testNaoDeveEncerrarLeiloesQueIniciaramOntem()
    {
        $antiga = new \DateTime("2017-12-04");
        
        $leilao1 = $this->leilaoBuilder->para("Courazao do JoJo")->naData($antiga)->constroi();
        $leilao2 = $this->leilaoBuilder->para("Ferrari V8")->naData($antiga)->constroi();
        
        $dao = $this->createMock(LeilaoCrudDao::class);
        $dao->method("correntes")->will($this->returnValue([$leilao1]));
        $dao->method("correntes")->will($this->returnValue([$leilao2]));
        
        $encerrador = new EncerradorDeLeilao($dao);
        $encerrador->encerra();
        
        $this->assertEquals(0, $encerrador->getTotalEncerrados());        
    }
    
    public function testDeveGarantirQueCasoNaoHajaLeiloesCriadosOEncerradorDeLeiloesNaoFazNada()
    {
        $antiga = new \DateTime("2017-12-04");
        
        $dao = $this->createMock(LeilaoCrudDao::class);
        $dao->method("correntes")->will($this->returnValue([]));
        
        $encerrador = new EncerradorDeLeilao($dao);
        $encerrador->encerra();
        
        $this->assertEquals(0, $encerrador->getTotalEncerrados());
    }
    
    public function testQlqrLeilaoDao()
    {
        $antiga = new \DateTime("2017-12-04");
        
        $dao = $this->createMock(LeilaoDao::class);
        $dao->method("teste")->will($this->returnValue("oieeeee"));
        
        $this->assertTrue(true);
    }
}
