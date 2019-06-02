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
        $service = Atm::factory();
        $result = $service->withdraw(30);
        $this->assertEquals([10, 10, 5, 5], $result);
    }
}
