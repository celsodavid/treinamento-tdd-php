<?php
namespace src\br\com\caelum\leilao\dominio;

interface EnviadorDeEmail
{
    public function envia(Leilao $leilao);
}
