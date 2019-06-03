<?php

use Atm\Atm;
/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmFunctionalTest extends \PHPUnit\Framework\TestCase
{
    public function testIntegration()
    {
        $service = Atm::factory();
        $result = $service->withdraw(10);
        $this->assertEquals([10], $result);
    }
}

