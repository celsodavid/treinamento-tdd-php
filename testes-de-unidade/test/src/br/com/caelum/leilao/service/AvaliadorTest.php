<?php
namespace test\src\br\com\caelum\leilao\service;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\service\Avaliador;

/**
 * Avaliado test case.
 */
class AvaliadorTest extends TestCase
{
    public function testDeveEntenderLancesEmOrdermCrescente()
    {
        // cenario: lances em ordem crescente
        $joao = new Usuario("Joao");
        $jose = new Usuario("Jose");
        $maria = new Usuario("Maria");
        
        $leilao = new Leilao("PS4");
        
        $leilao->propoe(new Lance($maria, 250.0));
        $leilao->propoe(new Lance($joao, 300.0));
        $leilao->propoe(new Lance($jose, 400.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(400.0, $leiloeiro->getMaiorDeTodos());
        $this->assertEquals(250.0, $leiloeiro->getMenorDeTodos());
    }
    
    public function testDeveEntenderLancesEmOrdermDecrescente()
    {
        $joao = new Usuario("Joao");
        $jose = new Usuario("Jose");
        $maria = new Usuario("Maria");
        
        $leilao = new Leilao("PS4");
        
        $leilao->propoe(new Lance($jose, 400.0));
        $leilao->propoe(new Lance($joao, 300.0));
        $leilao->propoe(new Lance($maria, 250.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(400.0, $leiloeiro->getMaiorDeTodos());
        $this->assertEquals(250.0, $leiloeiro->getMenorDeTodos());
    }
    
    public function testDeveEntenderLancesEmOrdermAleatoria()
    {
        $joao = new Usuario("Joao");
        $jose = new Usuario("Jose");
        $maria = new Usuario("Maria");
        
        $leilao = new Leilao("PS4");
        
        $leilao->propoe(new Lance($joao, 300.0));
        $leilao->propoe(new Lance($jose, 400.0));
        $leilao->propoe(new Lance($maria, 250.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(400.0, $leiloeiro->getMaiorDeTodos());
        $this->assertEquals(250.0, $leiloeiro->getMenorDeTodos());
    }
    
    public function testDeveEntenderApenasUmLance()
    {
        $joao = new Usuario("Joao");
        $leilao = new Leilao("PS4");
        
        $leilao->propoe(new Lance($joao, 300.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(300.0, $leiloeiro->getMaiorDeTodos());
        $this->assertEquals(300.0, $leiloeiro->getMenorDeTodos());
    }
    
    public function testDeveEntenderLeilaoSemLance()
    {
        $leilao = new Leilao("PS4");
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(0, $leiloeiro->getMaiorDeTodos());
        $this->assertEquals(0, $leiloeiro->getMenorDeTodos());
    }
    
    public function testDeveDevolverValorMedioDosLances()
    {
        $joao = new Usuario("Joao");
        $jose = new Usuario("Jose");
        $maria = new Usuario("Maria");
        
        $leilao = new Leilao("PS4");
        
        $leilao->propoe(new Lance($joao, 300.0));
        $leilao->propoe(new Lance($jose, 400.0));
        $leilao->propoe(new Lance($maria, 200.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(300.0, $leiloeiro->getValorMedio());
    }
    
    public function testDeveRetornarOsTresMaioresLances()
    {
        $joao = new Usuario("Joao");
        $jose = new Usuario("Jose");
        $maria = new Usuario("Maria");
        $mariana = new Usuario("Mariana");
        
        $leilao = new Leilao("PS4");
        
        $leilao->propoe(new Lance($joao, 350.0));
        $leilao->propoe(new Lance($jose, 400.0));
        $leilao->propoe(new Lance($maria, 250.0));
        $leilao->propoe(new Lance($mariana, 450.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $maiores = $leiloeiro->getMaiores();
        
        $this->assertEquals(3, count($maiores));
        $this->assertEquals(450.0, $maiores[0]->getValor());
        $this->assertEquals(400.0, $maiores[1]->getValor());
        $this->assertEquals(350.0, $maiores[2]->getValor());
    }
}
