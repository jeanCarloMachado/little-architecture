<?php

namespace myNamespace;
use InvalidArgumentException;

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

class Atm {
    function statelessWithdraw($notesavailability, $amountrequested) {

        if (!$amountrequested) {
            throw new InvalidArgumentException;
        }

        $result = [];
        $amountrest = $amountrequested;
        foreach ($notesavailability as $note => $availablequantity) {
            if (!self::notecanreducefromamount($note, $amountrest))  {
                continue;
            }

            $quantityofnotes = intval($amountrest / $note);

            if ($quantityofnotes > $availablequantity) {
                $quantityofnotes = $availablequantity;
            }

            $result = self::addnotesquantity($result, $note, $quantityofnotes);

            $amountrest-= $note * $quantityofnotes;

        }

        return $result;
    }

    function addnotesquantity($result, $note, $quantityofnotes) {
            for ($i = 0 ; $i < $quantityofnotes ; $i++) {
                $result[] = $note;
            }
            return $result;
    }

    function notecanreducefromamount($note, $amountrest) {
        return ($amountrest / $note) >= 1;
    }
}
