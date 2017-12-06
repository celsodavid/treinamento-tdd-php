<?php
namespace src\br\com\caelum\leilao\dominio;

class Pagamento
{
    private $valor;
    private $data;
    
    public function __construct(float $valor, \DateTime $data)
    {
        $this->valor = $valor;
        $this->data = $data;
    }
    /**
     * @return \src\br\com\caelum\leilao\dominio\float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @return \\DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \src\br\com\caelum\leilao\dominio\float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @param \\DateTime $data
     */
    public function setData(\DateTime $data)
    {
        $this->data = $data;
    }
}

