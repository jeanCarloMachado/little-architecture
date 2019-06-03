<?php


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
        withdraw(self::$allAvailable, null);
    }

    public function testAskForExactNoteValue()
    {
        $result = withdraw(self::$allAvailable, 5);
        $this->assertEquals([5], $result);
        $result = withdraw(self::$allAvailable, 10);
        $this->assertEquals([10], $result);
    }

    public function testComposedValues()
    {
        $result = withdraw(self::$allAvailable, 1000);
        $this->assertEquals([500, 500], $result);
        $result = withdraw(self::$allAvailable, 65);
        $this->assertEquals([50, 10, 5], $result);
    }


    public function testAvailability()
    {
        $result = withdraw([50=>1, 10=>0, 5=> 3], 65);
        $this->assertEquals([50, 5, 5, 5], $result);
    }

}


function withdraw($availability, $amount) {

    if (!$amount) {
        throw new InvalidArgumentException();
    }

    $result = [];

    $remainingValue = $amount;
    foreach ($availability as $note => $noteAvailability) {
        if (($remainingValue / $note) < 1)  {
            continue;
        }

        $quantityOfNotes = intval($remainingValue / $note);
        if ($quantityOfNotes > $noteAvailability) {
            $quantityOfNotes = $noteAvailability;
        }

        $result = addNNotesToResult($result, $note, $quantityOfNotes );

        $remainingValue-= $note * $quantityOfNotes;

    }

    return $result;

}

function addNNotesToResult($result, $note, $quantityOfNotes) {
        for ($i = 0; $i < $quantityOfNotes ; $i++) {
            $result[]  = $note;
        }

        return $result;
}

