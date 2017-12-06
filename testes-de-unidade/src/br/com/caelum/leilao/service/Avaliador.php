<?php
namespace src\br\com\caelum\leilao\service;

use src\br\com\caelum\leilao\dominio\Leilao;

class Avaliador
{
    private $maiorDeTodos = -INF;
    private $menorDeTodos = INF;
    private $valorMedio;
    private $maiores;
    
    public function avalia(Leilao $leilao)
    {
        $lances = $leilao->getLances();
        if (empty($lances)) {
            throw new \RuntimeException();
        }
        
        foreach ($lances as $lance) {
            if ($lance->getValor() > $this->maiorDeTodos) {
                $this->maiorDeTodos = $lance->getValor();
            }
            
            if ($lance->getValor() < $this->menorDeTodos) {
                $this->menorDeTodos = $lance->getValor();
            }
            
            $this->valorMedio += $lance->getValor();
        }
        
        $this->valorMedio = $this->valorMedio / count($lances);
        
        $this->pegaOsTresMaiores($leilao);              
    }
    
    /**
     * @return number
     */
    public function getMaiorDeTodos()
    {
        return $this->maiorDeTodos;
    }

    /**
     * @return string
     */
    public function getMenorDeTodos()
    {
        return $this->menorDeTodos;
    }
    /**
     * @return number
     */
    public function getValorMedio()
    {
        return $this->valorMedio;
    }
    
    private function pegaOsTresMaiores(Leilao $leilao)
    {
        $this->maiores = $leilao->getLances();
        
        usort($this->maiores, function($o1, $o2){
            if ($o1->getValor() < $o2->getValor())
                return 1;
            
            if ($o1->getValor() > $o2->getValor())
                return -1;
            
            return 0;
        });
        
        $this->maiores = array_slice($this->maiores, 0, 3);
    }
    
    /**
     * @return Ambigous <array, mixed, multitype:>
     */
    public function getMaiores()
    {
        return $this->maiores;
    }

    public function getMaiorLance()
    {
        
    }
}
