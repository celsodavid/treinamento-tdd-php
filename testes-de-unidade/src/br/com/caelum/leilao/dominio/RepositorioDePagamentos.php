<?php
namespace src\br\com\caelum\leilao\dominio;

interface RepositorioDePagamentos
{
    public function salva(Pagamento $pagamento);
    public function salvaTodos(array $pagamentos);
}

