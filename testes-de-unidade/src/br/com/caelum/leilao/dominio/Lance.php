<?php
namespace src\br\com\caelum\leilao\dominio;

class Lance
{
    private $usuario;
    private $valor;
    
    public function __construct(Usuario $usuario, float $valor)
    {
        $this->usuario = $usuario;
        $this->valor = $valor;
    }
    /**
     * @return \src\br\com\caelum\dominio\leilao\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @return \src\br\com\caelum\leilao\dominio\float
     */
    public function getValor()
    {
        return $this->valor;
    }
}
