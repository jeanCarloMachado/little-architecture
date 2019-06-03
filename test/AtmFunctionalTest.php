<?php

use Atm\Atm;
use Atm\AtmGateway;
/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmFunctionalTest extends \PHPUnit\Framework\TestCase
{
    public function testFunctional()
    {
        $atm = Atm::factory();
        $result = $atm->withdraw(20);
        $this->assertEquals([10, 10], $result);
    }
}

