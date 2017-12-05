<?php
namespace test\src\br\com\caelum\leilao\dominio;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Usuario;
use test\src\br\com\caelum\leilao\builder\LeilaoBuilder;

/**
 * Leilao test case.
 */
class LeilaoTest extends TestCase
{
    private $leilaoBuilder;
    private $kurt;
    private $stark;
    
    public function setUp()
    {
        $this->leilaoBuilder = new LeilaoBuilder();
        
        $this->kurt = new Usuario("Kurt Cobain");
        $this->stark = new Usuario("Tony Stark");
    }
    
    public function tearDown()
    {
        
    }
    
    public function testDeveRetornarUmLance()
    {
        $leilao = $this->leilaoBuilder
            ->para("Tinder Premium")
            ->lance($this->kurt, 100.0)
            ->constroi();
        
        $this->assertEquals(1, count($leilao->getLances()));
    }
    
    public function testDeveRetornarVariosLances()
    {
        $leilao = $this->leilaoBuilder->para("Tinder Plus")
            ->lance($this->kurt, 50.0)
            ->lance($this->stark, 100.0)
            ->lance($this->kurt, 250.0)
            ->constroi();
        
        $this->assertEquals(3, count($leilao->getLances()));
    }
    
    public function testNaoDeveAceitarMaisDeCincoLancesPorUsuario()
    {
        $leilao = $this->leilaoBuilder->para("Tinder Plus")
            ->lance($this->kurt, 50.0)
            ->lance($this->stark, 100.0)
            ->lance($this->kurt, 250.0)
            ->lance($this->stark, 350.0)
            ->lance($this->kurt, 450.0)
            ->lance($this->stark, 550.0)
            ->lance($this->kurt, 650.0)
            ->lance($this->stark, 750.0)
            ->lance($this->kurt, 850.0)
            ->lance($this->stark, 950.0)
            ->lance($this->kurt, 1000.0)
            ->constroi();
               
        $this->assertEquals(10, count($leilao->getLances()));
    }
    
    public function testNaoDeveAceitarDoisLancesDoMesmoUsuario()
    {
        $leilao = $this->leilaoBuilder->para("Mackbook Pro")        
            ->lance($this->stark, 2000)       
            ->lance($this->stark, 5000)        
            ->constroi();
        
        $this->assertEquals(1, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor());
    }
    
    public function testNaoDeveAceitarUmaLanceMenorQueOAnterior()
    {
        $leilao = $this->leilaoBuilder->para("Mackbook Pro")
        ->lance($this->stark, 200)
            ->lance($this->kurt, 100)
            ->constroi();
        
        //$this->assertEquals(1, count($leilao->getLances()));
        //$this->assertEquals(200, $leilao->getLances()[0]->getValor());
    }
    
    public function testNaoDeveAceitarLeilaoSemLance()
    {
        $leilao = $this->leilaoBuilder->para("Mackbook Pro")->constroi();
        
        $this->assertEquals(0, count($leilao->getLances()));
    }
}
