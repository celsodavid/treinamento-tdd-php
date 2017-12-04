<?php
namespace test\src\br\com\caelum\leilao\service;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\service\FiltroDeLances;
use src\br\com\caelum\leilao\dominio\Lance;

/**
 * FiltroDeLances test case.
 */
class FiltroDeLancesTest extends TestCase
{
    public function testDeveSelecionarLancesEntre1000E3000()
    {
        $joao = new Usuario("Joao");
        
        $filtro = new FiltroDeLances();
        
        $resultado = $filtro->filtra([
            new Lance($joao, 2000),
            new Lance($joao, 1000),
            new Lance($joao, 3000),
            new Lance($joao, 800)
        ]);
        
        $this->assertEquals(1, count($resultado));
        $this->assertEquals(2000, $resultado[0]->getValor(), 0.00001);
    }
    
    public function testDeveSelecionarLancesEntre500E700()
    {
        $joao = new Usuario("Joao");
        
        $filtro = new FiltroDeLances();
        
        $resultado = $filtro->filtra([
            new Lance($joao, 600),
            new Lance($joao, 500),
            new Lance($joao, 700),
            new Lance($joao, 800)
        ]);
        
        $this->assertEquals(1, count($resultado));
        $this->assertEquals(600, $resultado[0]->getValor(), 0.00001);
    }
    
    public function testDeveSelecionarLancesMaioresQue5000()
    {
        $joao = new Usuario("Joao");
        
        $filtro = new FiltroDeLances();
        
        $resultado = $filtro->filtra([
            new Lance($joao, 1000),
            new Lance($joao, 7000),
            new Lance($joao, 3000),
            new Lance($joao, 800)
        ]);
        
        $this->assertEquals(1, count($resultado));
        $this->assertEquals(7000, $resultado[0]->getValor(), 0.00001);
    }
    
    public function testDeveRetornarResultadoZeroNaoPassandoLances()
    {
        $joao = new Usuario("Joao");
        
        $filtro = new FiltroDeLances();
        
        $resultado = $filtro->filtra([]);
        
        $this->assertEquals(0, count($resultado));
    }
    
    public function testDeveSelecionarLancesMenoresQue500()
    {
        $joao = new Usuario("Joao");
        
        $filtro = new FiltroDeLances();
        
        $resultado = $filtro->filtra([
            new Lance($joao, 200),
            new Lance($joao, 100),
            new Lance($joao, 50),
            new Lance($joao, 90)
        ]);
        
        $this->assertEquals(0, count($resultado));
    }
    
    public function testDeveSelecionarLancesComValorNegativos()
    {
        $joao = new Usuario("Joao");
        
        $filtro = new FiltroDeLances();
        
        $resultado = $filtro->filtra([
            new Lance($joao, 200),
            new Lance($joao, -2000),
            new Lance($joao, 50),
            new Lance($joao, 90)
        ]);
        
        $this->assertEquals(0, count($resultado));
    }
}
