<?php

use InvalidArgumentException;

use Atm\Atm;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmTest extends \PHPUnit\Framework\TestCase
{
    private static $allAvailable = [
        500 => 2,
        50 => 2,
        10 => 2,
        5 => 2,
    ];
    public function testFailsWhenWrithdrawNothing()
    {
        $this->expectException(InvalidArgumentException::class);
        Atm::statelessWithdraw([], null);
    }

    public function testReturnAskedNote()
    {
        $this->assertEquals([5], Atm::statelessWithdraw(self::$allAvailable, 5));
        $this->assertEquals([10], Atm::statelessWithdraw(self::$allAvailable, 10));
    }

    public function testReturnMoreOfSameWhenMultiple()
    {
        $this->assertEquals([500, 500], Atm::statelessWithdraw(self::$allAvailable, 1000));
    }

    public function testReturnMultipleTypesOfNotes()
    {
        $this->assertEquals([10, 5], Atm::statelessWithdraw(self::$allAvailable, 15));
    }

    public function testReturnSmallterTypeDueToNonAvailability()
    {
        $this->assertEquals([5, 5], Atm::statelessWithdraw([10=>0, 5=>2], 10));
    }
}
