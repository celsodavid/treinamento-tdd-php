<?php
namespace test\src\br\com\caelum\leilao\builder;

use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

class LeilaoBuilder
{
    private $dono;
    private $valor;
    private $nome;
    private $usado;
    private $dataAbertura;
    private $encerrado;
        
    public function __construct()
    {
        $this->dono = new Usuario("Joao Da Silva", "joao@silva.com.br");
        $this->valor = 1500.0;
        $this->nome = "XBox One";
        $this->usado = false;
        $this->dataAbertura = new \DateTime();
    }
    
    public function comNome(string $nome)
    {
        $this->nome = $nome;
        return $this;
    }
    
    public function comValor(float $valor)
    {
        $this->valor = $valor;
        return $this;
    }
    
    public function naData(\DateTime $data)
    {
        $this->dataAbertura = $data;
        return $this;
    }
    
    public function comDono(Usuario $dono)
    {
        $this->dono = $dono;
        return $this;
    }
    
    public function usado()
    {
        $this->usado = true;
        return $this;
    }
    
    public function diasAtras(int $dias)
    {
        $data = new \DateTime();
        $data->sub(new \DateInterval("P{$dias}D"));
        
        $this->dataAbertura = $data;
        return $this;
    }
    
    public function encerrado()
    {
        $this->encerrado = true;
        return $this;
    }
    
    public function constroi()
    {
        $leilao = new Leilao($this->nome, $this->valor, $this->dono, $this->usado);
        $leilao->setDataAbertura($this->dataAbertura);
        if ($this->encerrado) $leilao->encerra();
        
        return $leilao;
    }
}

