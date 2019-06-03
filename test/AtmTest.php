<?php


/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmTest extends \PHPUnit\Framework\TestCase
{
    public function testWithdrawNothingThrowsError()
    {
        $this->expectException(InvalidArgumentException::class);
        withdraw(null);
    }

    public function testAskForExactNoteValue()
    {
        $result = withdraw(5);
        $this->assertEquals([5], $result);
        $result = withdraw(10);
        $this->assertEquals([10], $result);
    }

    public function testComposedValues()
    {
        $result = withdraw(1000);
        $this->assertEquals([500, 500], $result);
        $result = withdraw(65);
        $this->assertEquals([50, 10, 5], $result);
    }
}


function withdraw($amount) {

    if (!$amount) {
        throw new InvalidArgumentException();
    }

    $result = [];
    $notes = [500, 50, 10, 5];

    $remainingValue = $amount;
    foreach ($notes as $note) {
        if (($remainingValue / $note) < 1)  {
            continue;
        }

        $quantityOfNotes = intval($remainingValue / $note);

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
