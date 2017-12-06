<?php
namespace test\src\br\com\caelum\leilao\service;

use PHPUnit\Framework\TestCase;
use test\src\br\com\caelum\leilao\builder\LeilaoBuilder;
use src\br\com\caelum\leilao\dominio\LeilaoCrudDao;
use src\br\com\caelum\leilao\service\EncerradorDeLeilao;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\dominio\EnviadorDeEmail;

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
//        $dao->expects($this->once())->method("atualiza");
//        $dao->expects($this->exactly(2))->method("atualiza");
        $dao->expects($this->atLeastOnce())->method("atualiza");
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);        
        
        $encerrador = new EncerradorDeLeilao($dao,$carteiroFalso);
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
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $encerrador = new EncerradorDeLeilao($dao, $carteiroFalso);
        $encerrador->encerra();
        
        $this->assertEquals(0, $encerrador->getTotalEncerrados());        
    }
    
    /*public function testDeveGarantirQueCasoNaoHajaLeiloesCriadosOEncerradorDeLeiloesNaoFazNada()
    {
        $antiga = new \DateTime("2017-12-04");
        
        $dao = $this->createMock(LeilaoCrudDao::class);
        $dao->method("correntes")->will($this->returnValue([]));
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $encerrador = new EncerradorDeLeilao($dao, $carteiroFalso);
        $encerrador->encerra();
        
        $this->assertEquals(0, $encerrador->getTotalEncerrados());
    }*/
    
    public function testQlqrLeilaoDao()
    {
        $antiga = new \DateTime("2017-12-04");
        
        $dao = $this->createMock(LeilaoDao::class);
        $dao->method("teste")->will($this->returnValue("oieeeee"));
        
        $this->assertTrue(true);
    }
    
    public function testDeveAtualizarLeiloesEncerrados()
    {
        $antiga = new \DateTime("2018-01-20");
        
        $leilao1 = $this->leilaoBuilder->para("TV de plasma")->naData($antiga)->constroi();
        
        $daoFalso = $this->createMock(LeilaoCrudDao::class);
        $daoFalso->expects($this->never())->method("atualiza");
        $daoFalso->method("correntes")->will($this->returnValue([$leilao1]));
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiroFalso);
        $encerrador->encerra();
    }
    
    public function testDeveEnviarEmailParaLeiloesEncerrados()
    {
        $antiga = new \DateTime("2017-01-20");
        
        $leilao1 = $this->leilaoBuilder->para("TV de plasma")->naData($antiga)->constroi();
        
        $daoFalso = $this->createMock(LeilaoCrudDao::class);
        $daoFalso->method("correntes")->will($this->returnValue([$leilao1]));
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        $carteiroFalso->expects($this->atLeastOnce())->method("envia");
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiroFalso);
        $encerrador->encerra();
    }
    
    public function testDeveContinuarAExecucaoMesmoQuandoDaoFalha()
    {
        $antiga = new \DateTime("1999-01-20");
        
        $leilao1 = $this->leilaoBuilder->para("Courazao do JoJo")->naData($antiga)->constroi();
        $leilao2 = $this->leilaoBuilder->para("Ferrari V8")->naData($antiga)->constroi();
        
        $daoFalso = $this->createMock(LeilaoCrudDao::class);
        $daoFalso->method("correntes")->will($this->returnValue([$leilao1, $leilao2]));
        $daoFalso->expects($this->atLeastOnce())->method("atualiza")->will($this->throwException(new \PDOException));
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        $carteiroFalso->expects($this->never())->method("envia");
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiroFalso);
        $encerrador->encerra();
        
        $this->assertEquals(2, $encerrador->getTotalEncerrados());
    }
}
