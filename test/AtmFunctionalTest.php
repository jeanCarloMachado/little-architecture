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
        $fileGateway = new FileGateway;

        $atm = new Atm($fileGateway);

        $result = $atm->withdraw(10);
        $this->assertEquals([5, 5], $result);
    }
}


class FileGateway extends AtmGateway {

    public function getAvailablity() {
        $result =  json_decode(file_get_contents(__DIR__.'/../availability.json'));

        return $result;
    }
}
