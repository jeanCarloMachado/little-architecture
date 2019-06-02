<?php

namespace myNamespace;

use InvalidArgumentException;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmTest extends \PHPUnit\Framework\TestCase
{
    public function testErrorWhenNothingAsked()
    {
        $this->expectException(InvalidArgumentException::class);
        $result = withdraw(0);
    }

    public function testReturnsExactNoteAsked()
    {
        $result = withdraw(5);
        $this->assertEquals($result, [5]);

        $result = withdraw(10);
        $this->assertEquals($result, [10]);

        $result = withdraw(50);
        $this->assertEquals($result, [50]);
    }

    public function testSumsSameNoteToGetValue()
    {

        $result = withdraw(1000);
        $this->assertEquals([500, 500], $result);
    }

    public function testComposesMultipleValueNotes()
    {

        $result = withdraw(15);
        $this->assertEquals([10, 5], $result);


        $result = withdraw(75);
        $this->assertEquals([50, 10, 10, 5], $result);

    }
}

function withdraw($requestedAmount) : array
{

    if (!$requestedAmount) {
        throw new InvalidArgumentException;
    }

    $availableNotes = [500, 100, 50, 10, 5];
    rsort($availableNotes);


    $returnedNotes = [];
    $valueReaminingToFullfill = $requestedAmount;

    foreach ($availableNotes as $note) {
        if ($note > $valueReaminingToFullfill) {
            continue;
        }

        $quantityOfNotes = intval($valueReaminingToFullfill / $note);
        $valueReaminingToFullfill-= $note * $quantityOfNotes;
        $returnedNotes = array_merge($returnedNotes, addNNotes($note, $quantityOfNotes));
    }

    return $returnedNotes;
}

function addNNotes($note, $n) {
    $result = [];
    for ($i = 0 ; $i < $n; $i++) {
         $result[] = $note;
    }
    return $result;
}
