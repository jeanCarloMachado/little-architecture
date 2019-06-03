<?php

use Atm\Atm;
use Atm\ConcreateAtmGateway;
/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmFunctionalTest extends \PHPUnit\Framework\TestCase
{
    public function testFunctional()
    {
        $service = Atm::factory();
        $result =  $service->withdraw(20);
        $this->assertEquals([10, 10], $result);
    }
}

