<?php
namespace src\br\com\caelum\leilao\service;

use src\br\com\caelum\leilao\dominio\LeilaoCrudDao;
use src\br\com\caelum\leilao\dominio\Pagamento;
use src\br\com\caelum\leilao\dominio\RepositorioDePagamentos;

class GeradorDePagamentos
{
    private $pagamentos;
    private $leiloes;
    private $avaliador;
    private $relogio;
    
    public function __construct(LeilaoCrudDao $leiloes, RepositorioDePagamentos $pagamentos, Avaliador $avaliador, Relogio $relogio = null)
    {
        $this->leiloes = $leiloes;
        $this->pagamentos = $pagamentos;
        $this->avaliador = $avaliador;
        $this->relogio = $relogio ?? new RelogioDoSistema();
    }
    
    public function gera()
    {
        $novosPagamentos = [];
        $leiloesEncerrados = $this->leiloes->encerrados();
        
        foreach ($leiloesEncerrados as $leilao) {
            $this->avaliador->avalia($leilao);
            
            $novoPagamento = new Pagamento($this->avaliador->getMaiorLance(), $this->primeiroDiaUltil());
            $novosPagamentos[] = $novoPagamento;
            $this->pagamentos->salvaTodos($novosPagamentos);
            
            return $novosPagamentos;
        }
    }
    
    public function getPagamentos()
    {
        return $this->pagamentos;
    }
    
    private function primeiroDiaUltil()
    {
        $data = $this->relogio->hoje();
        $diaDaSemana = $data->format("w");
        
        if ($diaDaSemana == 6) $data->add(new \DateInterval("P2D"));
        else if($diaDaSemana == 0) $data->add(new \DateInterval("P1D"));
        
        return $data;
    }
}

