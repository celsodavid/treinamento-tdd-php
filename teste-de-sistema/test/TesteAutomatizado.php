<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase as SeleniumTestCase;

class TesteAutomatizado extends SeleniumTestCase
{
    /**
     * @before
     */
    public function setUp()
    {
        $this->setBrowserUrl("http://");
    }
    
    public function testDeveBuscarAPalavraCaelumNoGoogle()
    {
        $this->url("google.com/");
        $campoDeTexto = $this->byName("q");
        $campoDeTexto->value("Caelum");
        $campoDeTexto->submit();
        sleep(1);
    }
    
    public function testDeveBuscarAPalavraCaelumNoBing()
    {
        $this->url("www.bing.com/");
        $campoDeTexto = $this->byName("q");
        $campoDeTexto->value("Caelum");
        $campoDeTexto->submit();
        sleep(1);
    }
}
