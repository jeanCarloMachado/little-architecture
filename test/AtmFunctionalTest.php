<?php

use InvalidArgumentException;
use Atm\Atm;
use Atm\AtmGateway;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmFunctionalTest extends \PHPUnit\Framework\TestCase {

    public function testFunctional()
    {

        $atm = Atm::factory();

        $result = $atm->withdraw(10);
        $this->assertEquals([5, 5], $result);
    }
}


