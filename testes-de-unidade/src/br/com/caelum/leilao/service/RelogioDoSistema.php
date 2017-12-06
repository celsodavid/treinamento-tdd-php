<?php
namespace src\br\com\caelum\leilao\service;

class RelogioDoSistema implements Relogio
{
    public function hoje()
    {
        return new \DateTime();
    }
}

