<?php
namespace src\br\com\caelum\leilao\service;

use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\LeilaoCrudDao;

class EncerradorDeLeilao
{
    private $total;
    private $dao;
    
    public function __construct(LeilaoCrudDao $dao)
    {
        $this->dao = $dao;
    }
    
    public function encerra()
    {
        $todosOsLeiloesCorrentes = $this->dao->correntes();
        
        foreach ($todosOsLeiloesCorrentes as $leilao) {
            if ($this->comecouSemanaPassada($leilao)) {
                $leilao->encerra();
                $this->total++;
                
                $this->dao->atualiza($leilao);
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

