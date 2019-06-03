<?php

use Atm\Atm;
use Atm\AtmGateway;
/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmIntegrationTest extends \PHPUnit\Framework\TestCase
{
    public function testIntegration()
    {
        $gateway = $this->prophesize(AtmGateway::class);
        $gateway->getAvailability()->willReturn([5=>2]);
        $service = new Atm($gateway->reveal());
        $result = $service->withdraw(10);
        $this->assertEquals([5,5], $result);
    }
}

