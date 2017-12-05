<?php
namespace test\src\br\com\caelum\leilao\service;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\service\Avaliador;
use test\src\br\com\caelum\leilao\builder\LeilaoBuilder;

/**
 * Avaliado test case.
 */
class AvaliadorTest extends TestCase
{
    private $avaliador;
    private $leilaoBuilder;
    private $kurt;
    private $stark;
    private $alerquina;
    private $mariana;

    /**
     * @before
    */
    public function antes()
    {
        echo "Inicia" . PHP_EOL;
        $this->avaliador = new Avaliador();
        
        $this->leilaoBuilder = new LeilaoBuilder();
        
        $this->kurt = new Usuario("Kurt Cobain");
        $this->stark = new Usuario("Tony Stark");
        $this->alerquina = new Usuario("Alerquina");
        $this->mariana = new Usuario("Mariana");
    }
    
    /**
     * @after 
    */
    public function depois()
    {
        echo "Fim" . PHP_EOL;
    }
    
    /**
     * @beforeClass
     */
    public static function initClass()
    {
        echo "Inicia classe de test" . PHP_EOL;
    }
    
    /**
     * @afterClass
     */
    public static function endClass()
    {
        echo "Fim da classe de test" . PHP_EOL;
    }
    
    public function testDeveEntenderLancesEmOrdermCrescente()
    {
        // cenario: lances em ordem crescente
        $leilao = $this->leilaoBuilder->para("PS4 Slim")
            ->lance($this->alerquina, 250.0)
            ->lance($this->stark, 300.0)
            ->lance($this->kurt, 400.0)
            ->constroi();
        
        $this->avaliador->avalia($leilao);
        
        $this->assertEquals(400.0, $this->avaliador->getMaiorDeTodos());
        $this->assertEquals(250.0, $this->avaliador->getMenorDeTodos());
    }
    
    public function testDeveEntenderLancesEmOrdermDecrescente()
    {
        $leilao = $this->leilaoBuilder->para("PS4 Slim")
            ->lance($this->alerquina, 400.0)
            ->lance($this->stark, 300.0)
            ->lance($this->kurt, 250.0)
            ->constroi();
       
        $this->avaliador->avalia($leilao);
        
        $this->assertEquals(400.0, $this->avaliador->getMaiorDeTodos());
        $this->assertEquals(250.0, $this->avaliador->getMenorDeTodos());
    }
    
    public function testDeveEntenderLancesEmOrdermAleatoria()
    {
        $leilao = $this->leilaoBuilder->para("PS4 Slim")
            ->lance($this->alerquina, 300.0)
            ->lance($this->stark, 400.0)
            ->lance($this->kurt, 250.0)
            ->constroi();
        
        $this->avaliador->avalia($leilao);
        
        $this->assertEquals(400.0, $this->avaliador->getMaiorDeTodos());
        $this->assertEquals(250.0, $this->avaliador->getMenorDeTodos());
    }
    
    public function testDeveEntenderApenasUmLance()
    {
        $leilao = $this->leilaoBuilder->para("PS4 Slim")
            ->lance($this->alerquina, 300.0)
            ->constroi();
        
        $this->avaliador->avalia($leilao);
        
        $this->assertEquals(300.0, $this->avaliador->getMaiorDeTodos());
        $this->assertEquals(300.0, $this->avaliador->getMenorDeTodos());
    }
    
    /**
     * @expectedException RuntimeException
    */
    public function testDeveEntenderLeilaoSemLance()
    {
        $leilao = $this->leilaoBuilder->para("PS4 Slim")->constroi();        
        $this->avaliador->avalia($leilao);
    }
    
    public function testDeveDevolverValorMedioDosLances()
    {
        $leilao = new Leilao("PS4");
        
        $leilao->propoe(new Lance($this->stark, 300.0));
        $leilao->propoe(new Lance($this->kurt, 400.0));
        $leilao->propoe(new Lance($this->alerquina, 200.0));
        
        $this->avaliador->avalia($leilao);
        
        $this->assertEquals(300.0, $this->avaliador->getValorMedio());
    }
    
    public function testDeveRetornarOsTresMaioresLances()
    {
        $leilao = $this->leilaoBuilder->para("PS4 Slim")
            ->lance($this->stark, 350.0)
            ->lance($this->kurt, 400.0)
            ->lance($this->alerquina, 250.0)
            ->lance($this->mariana, 450.0)
            ->constroi();
        
        $this->avaliador->avalia($leilao);
        
        $maiores = $this->avaliador->getMaiores();
        
        $this->assertEquals(3, count($maiores));
        $this->assertEquals(450.0, $maiores[0]->getValor());
        $this->assertEquals(400.0, $maiores[1]->getValor());
        $this->assertEquals(350.0, $maiores[2]->getValor());
    }
}
