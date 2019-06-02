<?php

namespace AtmTest;

use InvalidArgumentException;
use Atm\Atm;
use Atm\AtmGateway;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmTest extends \PHPUnit\Framework\TestCase
{
    public function testErrorWhenNothingAsked()
    {
        $this->expectException(InvalidArgumentException::class);
        $result = Atm::_withdraw([], 0);
    }

    public function testReturnsExactNoteAsked()
    {
        $availability = [50 => 1, 10 => 1, 5 => 2];
        $result = Atm::_withdraw($availability, 5);
        $this->assertEquals($result, [5]);

        $result = Atm::_withdraw($availability, 10);
        $this->assertEquals($result, [10]);

        $result = Atm::_withdraw($availability, 50);
        $this->assertEquals($result, [50]);
    }

    public function testSumsSameNoteToGetValue()
    {
        $availability = [500=>2];
        $result = Atm::_withdraw($availability, 1000);
        $this->assertEquals([500, 500], $result);
    }

    public function testComposesMultipleValueNotes()
    {
        $availability = [50=>1,10=>2, 5=>1];

        $result = Atm::_withdraw($availability, 15);
        $this->assertEquals([10, 5], $result);


        $result = Atm::_withdraw($availability, 75);
        $this->assertEquals([50, 10, 10, 5], $result);
    }


    public function testAvailabilityDoesNotReturnBiggerNoteWhenNoAvailability()
    {
        $result = Atm::_withdraw([5=>2, 10 => 0], 10);
        $this->assertEquals([5, 5], $result);
    }
}

