<?php
namespace src\br\com\caelum;

require_once 'vendor/autoload.php';

use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\service\Avaliador;

$leilao = new Leilao("PS4");

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

var_dump($leiloeiro->getMaiorDeTodos() == 400.0);
var_dump($leiloeiro->getMenorDeTodos() == 250.0);
