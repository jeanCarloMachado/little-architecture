<?php

use Atm\Atm;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br> */
class AtmTest extends \PHPUnit\Framework\TestCase
{
    static $allAvailable = [
        100 => 10,
        50 => 10,
        10 => 10,
        5 => 10,
    ];

    public function testThrowsErrorWhenWithdrawNothing()
    {
        $this->expectException(InvalidArgumentException::class);
        Atm::statelessWithdraw(self::$allAvailable, null);
    }

    public function testAskForOneNoteValueGetOne()
    {
        $result = Atm::statelessWithdraw(self::$allAvailable, 10);
        $this->assertEquals([10], $result);
    }

    public function testComposedValueReturnsMultipleNotes()
    {
        $result = Atm::statelessWithdraw(self::$allAvailable, 200);
        $this->assertEquals([100, 100], $result);
    }

    public function testComposedValueOfMultipleNotes()
    {
        $result = Atm::statelessWithdraw(self::$allAvailable, 65);
        $this->assertEquals([50, 10, 5], $result);
    }

    public function testAvailability()
    {
        $result = Atm::statelessWithdraw([100 => 0, 50 => 2], 100);
        $this->assertEquals([50, 50], $result);
    }
}



