<?php
namespace test\src\br\com\caelum\leilao\builder;

use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

class LeilaoBuilder
{
    private $leilao;
    
    public function __construct()
    {
        $this->leilao = new Leilao("");
    }
    
    public function para(string $descricao)
    {
        $this->leilao->setDescricao($descricao);
        return $this;
    }
    
    public function lance(Usuario $usuario, float $valor)
    {
       $this->leilao->propoe(new Lance($usuario, $valor)); 
       return $this;
    }
    
    public function naData(\DateTime $date)
    {
        $this->leilao->setDataAbertura($date);
        return $this;
    }
    
    public function constroi()
    {
        return $this->leilao;
    }
}
