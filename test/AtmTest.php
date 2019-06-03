<?php

use Atm\Atm;
/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmTest extends \PHPUnit\Framework\TestCase
{
    static $allAvailable = [
        100 => 10,
        50 => 10,
        10 => 10,
        5 => 10,
    ];
    public function testErrorWhenAskNoMoney()
    {
        $this->expectException(InvalidArgumentException::class);
        Atm::statelessWithdraw(self::$allAvailable, null);
    }

    public function testReturnExactValueInOneNote()
    {
        $result = Atm::statelessWithdraw(self::$allAvailable, 100);
        $this->assertEquals([100], $result);

        $result = Atm::statelessWithdraw(self::$allAvailable, 50);
        $this->assertEquals([50], $result);
    }

    public function testResolveWithMultipleNotes()
    {
        $result = Atm::statelessWithdraw(self::$allAvailable, 200);
        $this->assertEquals([100, 100], $result);

        $result = Atm::statelessWithdraw(self::$allAvailable, 65);
        $this->assertEquals([50, 10, 5], $result);
    }

    public function testAvailaability()
    {
        $result = Atm::statelessWithdraw([100 => 0, 50 => 2], 100);
        $this->assertEquals([50, 50], $result);
    }
}



