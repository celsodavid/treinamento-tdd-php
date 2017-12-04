<?php
namespace src\br\com\caelum\leilao\service;

class FiltroDeLances
{
    public function filtra(array $lances)
    {
        $resultado = [];
        
        foreach ($lances as $lance) {
            if ($lance->getValor() > 1000 && $lance->getValor() < 3000) {
                $resultado[] = $lance;
            }
            elseif ($lance->getValor() > 500 && $lance->getValor() < 700) {
                $resultado[] = $lance;
            }
            elseif ($lance->getValor() > 5000) {
                $resultado[] = $lance;
            }
        }
        
        return $resultado;
    }
}
