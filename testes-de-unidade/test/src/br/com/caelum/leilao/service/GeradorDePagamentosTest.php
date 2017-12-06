<?php
namespace test\src\br\com\caelum\leilao\service;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\RepositorioDePagamentos;
use src\br\com\caelum\leilao\dominio\LeilaoCrudDao;
use src\br\com\caelum\leilao\service\Avaliador;
use src\br\com\caelum\leilao\service\GeradorDePagamentos;
use test\src\br\com\caelum\leilao\builder\LeilaoBuilder;
use src\br\com\caelum\leilao\service\Relogio;

/**
 * GeradorDePagamentos test case.
 */
class GeradorDePagamentosTest extends TestCase
{
    public function testDeveGerarPagamentoEmDiaUtil()
    {
        $pagamentos = $this->createMock(RepositorioDePagamentos::class);
        $leiloes = $this->createMock(LeilaoCrudDao::class);
        $avaliador = $this->createMock(Avaliador::class);
        $relogio = $this->createMock(Relogio::class);
        
        $sabado = new \DateTime('2017-12-02');
        
        $geradorDePagamentos = new GeradorDePagamentos($leiloes, $pagamentos, $avaliador, $relogio);
        
        $leilao = (new LeilaoBuilder())->para("Iphone 8")
            ->naData($sabado)
            ->constroi();
        
        $leiloes->method("encerrados")->will($this->returnValue([$leilao]));
        
        $avaliador->method("getMaiorLance")->willReturn(5.0);
        
        $relogio->method('hoje')->will($this->returnValue($sabado));
        
        $novoPagamento = $geradorDePagamentos->gera();
        
        $this->assertEquals(new \DateTime('2017-12-04'), $novoPagamento[0]->getData());
    }
    
    public function testCasoHojeSejaDomingoGerarPagamentoNoProximoDiaUtil()
    {
        $pagamentos = $this->createMock(RepositorioDePagamentos::class);
        $leiloes = $this->createMock(LeilaoCrudDao::class);
        $avaliador = $this->createMock(Avaliador::class);
        $relogio = $this->createMock(Relogio::class);
        
        $sabado = new \DateTime('2017-12-05');
        
        $geradorDePagamentos = new GeradorDePagamentos($leiloes, $pagamentos, $avaliador, $relogio);
        
        $leilao = (new LeilaoBuilder())->para("Iphone 8")
        ->naData($sabado)
        ->constroi();
        
        $leiloes->method("encerrados")->will($this->returnValue([$leilao]));
        
        $avaliador->method("getMaiorLance")->willReturn(5.0);
        
        $relogio->method('hoje')->will($this->returnValue($sabado));
        
        $novoPagamento = $geradorDePagamentos->gera();
        
        $this->assertEquals(new \DateTime('2017-12-05'), $novoPagamento[0]->getData());
    }
    
    public function testCasoHojeSejaSabadoGerarPagamentoNoProximoDiaUtil()
    {
        $pagamentos = $this->createMock(RepositorioDePagamentos::class);
        $leiloes = $this->createMock(LeilaoCrudDao::class);
        $avaliador = $this->createMock(Avaliador::class);
        $relogio = $this->createMock(Relogio::class);
        
        $sabado = new \DateTime('2017-12-02');
        
        $geradorDePagamentos = new GeradorDePagamentos($leiloes, $pagamentos, $avaliador, $relogio);
        
        $leilao = (new LeilaoBuilder())->para("Iphone 8")
        ->naData($sabado)
        ->constroi();
        
        $leiloes->method("encerrados")->will($this->returnValue([$leilao]));
        
        $avaliador->method("getMaiorLance")->willReturn(5.0);
        
        $relogio->method('hoje')->will($this->returnValue($sabado));
        
        $novoPagamento = $geradorDePagamentos->gera();
        
        $this->assertEquals(new \DateTime('2017-12-04'), $novoPagamento[0]->getData());
    }
}

