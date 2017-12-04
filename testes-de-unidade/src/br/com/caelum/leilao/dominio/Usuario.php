<?php
namespace src\br\com\caelum\leilao\dominio;

class Usuario
{
    private $nome;
    private $id;
    
    public function __construct(string $nome, int $id = 0)
    {
        $this->nome = $nome;
        $this->id = $id;
    }
    /**
     * @return \src\br\com\caelum\dominio\string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return \src\br\com\caelum\dominio\int
     */
    public function getId()
    {
        return $this->id;
    }
}
