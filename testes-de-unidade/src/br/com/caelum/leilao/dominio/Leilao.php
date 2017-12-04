<?php
namespace src\br\com\caelum\leilao\dominio;

class Leilao
{
    private $message;
    private $lances;
    
    public function __construct(string $message)
    {
        $this->descricao = $message;
        $this->lances = []; 
    }
    
    public function propoe(Lance $lance)
    {        
        $this->lances[] = $lance;
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
}
