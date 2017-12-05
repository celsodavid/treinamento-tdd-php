<?php
namespace src\br\com\caelum\leilao\dao;

use src\br\com\caelum\leilao\dominio\Leilao;

class LeilaoDao
{
    private static $leiloes;
    
    public function __construct()
    {
        static::$leiloes = [];
    }
    
    public function salva(Leilao $leilao)
    {
        static::$leiloes[] = $leilao;
    }
    
    public function encerrados()
    {
        $filtrados = [];
        foreach (static::$leiloes as $leilao) {
            if ($leilao->isEncerrado()) {
                $filtrados[] = $leilao;
            }
            
            return $filtrados;
        }
    }
    
    public function correntes()
    {
        $filtrados = [];
        foreach (static::$leiloes as $leilao) {
            if (!$leilao->isEncerrado()) {
                $filtrados[] = $leilao;
            }
            
            return $filtrados;
        }
    }
    
    public function atualiza(Leilao $leilao)
    {
        
    }
    
    public static function teste()
    {
        return "teste";
    }
}
