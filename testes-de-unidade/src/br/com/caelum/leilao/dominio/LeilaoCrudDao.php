<?php
namespace src\br\com\caelum\leilao\dominio;

interface LeilaoCrudDao
{
    public function salva(Leilao $leilao);
    public function encerrados();
    public function correntes();
    public function atualiza(Leilao $leilao);
}
