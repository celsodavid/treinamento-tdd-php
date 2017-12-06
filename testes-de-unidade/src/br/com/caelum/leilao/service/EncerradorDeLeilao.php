<?php
namespace src\br\com\caelum\leilao\service;

use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\LeilaoCrudDao;
use src\br\com\caelum\leilao\dominio\EnviadorDeEmail;

class EncerradorDeLeilao
{
    private $total;
    private $dao;
    private $carteiro;
    
    public function __construct(LeilaoCrudDao $dao, EnviadorDeEmail $carteiro)
    {
        $this->dao = $dao;
        $this->carteiro = $carteiro;
    }
    
    public function encerra()
    {
        $todosOsLeiloesCorrentes = $this->dao->correntes();
        if (empty($todosOsLeiloesCorrentes)) {
            throw new \RuntimeException();
        }
        
        foreach ($todosOsLeiloesCorrentes as $leilao) {
            try {
                if ($this->comecouSemanaPassada($leilao)) {
                    $leilao->encerra();
                    $this->total++;
                    
                    $this->dao->atualiza($leilao);
                    
                    $this->carteiro->envia($leilao);
                }
            } catch (\PDOException $e) {
                // salva o erro
                // e o loop continua
            }            
        }
    }
    
    private function comecouSemanaPassada(Leilao $leilao)
    {
        return $this->diasEntre($leilao->getDataAbertura(), new \DateTime()) >= 7;
    }
    
    private function diasEntre(\DateTime $inicio, \DateTime $hoje)
    {
        $dataDoLeilao = clone $inicio;
        $diasNoIntervalo = 0;
        
        while ($dataDoLeilao < $hoje) {
            $dataDoLeilao->add(new \DateInterval('P1D'));
            $diasNoIntervalo++;
        }
        
        return $diasNoIntervalo;
    }
    
    public function getTotalEncerrados()
    {
        return $this->total;
    }
}

