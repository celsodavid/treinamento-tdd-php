<?php
namespace src\br\com\caelum\leilao\dominio;

class Leilao
{
    private $message;
    private $lances;
    private $dataAbertura;
        
    public function __construct(string $message)
    {
        $this->descricao = $message;
        $this->lances = []; 
    }
    
    public function propoe(Lance $lance)
    {   
        if (empty($this->lances) || $this->podeDarLance($lance->getUsuario(), $lance)) {
            $this->lances[] = $lance;
        }        
    }
    
    public function encerra()
    {
        
    }
    
    public function getEncerrados()
    {
        return true;
    }
    
    private function ultimoLanceDado()
    {
        return $this->lances[count($this->lances) - 1];
    }
    
    private function verificaQuantidadePor(Usuario $usuario)
    {
        $total = 0;
        foreach ($this->lances as $lance) {
            ($usuario == $lance->getUsuario()) ? $total++ : "";
        }
        
        return $total;
    }
    
    private function verificaLanceAnteriorMenor(Lance $lance)
    {
        return ($lance->getValor() < $this->lances[count($this->lances) - 1]->getValor());
    }
    
    private function podeDarLance(Usuario $usuario, Lance $lance)
    {
        return $this->ultimoLanceDado()->getUsuario() != $usuario
        && $this->verificaQuantidadePor($usuario) < 5;
        // && !$this->verificaLanceAnteriorMenor($lance);
    }
        
    /**
     * @param mixed $message
     */
    public function setDescricao($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getLances()
    {
        return $this->lances;
    }
    
    /**
     * @return mixed
     */
    public function getDataAbertura()
    {
        return $this->dataAbertura;
    }
    
    /**
     * @param mixed $dataAbertura
     */
    public function setDataAbertura(\DateTime $dataAbertura)
    {
        $this->dataAbertura = $dataAbertura;
    }    
}
