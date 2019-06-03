<?php

use Atm\Atm;
use InvalidArgumentException;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmTest extends \PHPUnit\Framework\TestCase
{
    static $allAvailable = [
        500 => 10,
        100 => 10,
        50 => 10,
        10 => 10,
        5 => 10,
    ];
    public function testWithdrawNothingThrowsError()
    {
        $this->expectException(InvalidArgumentException::class);
        Atm::statelessWithdraw(self::$allAvailable, null);
    }

    public function testAskForExactNoteValue()
    {
        $result = Atm::statelessWithdraw(self::$allAvailable, 5);
        $this->assertEquals([5], $result);
        $result = Atm::statelessWithdraw(self::$allAvailable, 10);
        $this->assertEquals([10], $result);
    }

    public function testComposedValues()
    {
        $result = Atm::statelessWithdraw(self::$allAvailable, 1000);
        $this->assertEquals([500, 500], $result);
        $result = Atm::statelessWithdraw(self::$allAvailable, 65);
        $this->assertEquals([50, 10, 5], $result);
    }


    public function testAvailability()
    {
        $result = Atm::statelessWithdraw([50=>1, 10=>0, 5=> 3], 65);
        $this->assertEquals([50, 5, 5, 5], $result);
    }

}


