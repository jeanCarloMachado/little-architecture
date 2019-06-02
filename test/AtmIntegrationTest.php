<?php

use Atm\Atm;
use Atm\AtmGatewayInterface;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmIntegrationTest extends \PHPUnit\Framework\TestCase {

    public function testIntegration()
    {
        $gateway = $this->prophesize(AtmGatewayInterface::class);
        $gateway->getAvailablity()->willReturn([5=>2, 10 => 0]);
        $gateway->removeNotesFromStorage([5, 5])->shouldBeCalled();

        $atm = new Atm($gateway->reveal());

        $result = $atm->withdraw(10);
        $this->assertEquals([5, 5], $result);
    }
}
