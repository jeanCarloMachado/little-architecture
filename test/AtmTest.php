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
        $result = withdraw([], 0);
    }

    public function testReturnsExactNoteAsked()
    {
        $availability = [50 => 1, 10=> 1, 5=>2];
        $result = withdraw($availability, 5);
        $this->assertEquals($result, [5]);

        $result = withdraw($availability, 10);
        $this->assertEquals($result, [10]);

        $result = withdraw($availability, 50);
        $this->assertEquals($result, [50]);
    }

    public function testSumsSameNoteToGetValue()
    {
        $availability = [500=>2];
        $result = withdraw($availability, 1000);
        $this->assertEquals([500, 500], $result);
    }

    public function testComposesMultipleValueNotes()
    {
        $availability = [50=>1,10=>2, 5=>1];

        $result = withdraw($availability, 15);
        $this->assertEquals([10, 5], $result);


        $result = withdraw($availability, 75);
        $this->assertEquals([50, 10, 10, 5], $result);
    }


    public function testAvailability()
    {
        $result = withdraw([5=>2, 10 => 0], 10);
        $this->assertEquals([5, 5], $result);
    }
}

function withdraw($availability, int $requestedAmount) : array
{
    if (!$requestedAmount) {
        throw new InvalidArgumentException;
    }

    $returnedNotes = [];
    $valueReaminingToFullfill = $requestedAmount;

    foreach ($availability as $note => $availableQuantity) {
        $retriveableQuantity = intval($valueReaminingToFullfill / $note);

        if ($availableQuantity < $retriveableQuantity) {
            $retriveableQuantity = $availableQuantity;
        }

        $valueReaminingToFullfill-= $note * $retriveableQuantity;
        $returnedNotes = appendNNotes($returnedNotes, $note, $retriveableQuantity);
    }

    return $returnedNotes;
}

function appendNNotes(array $result, int $note, int $n) : array {
    for ($i = 0 ; $i < $n; $i++) {
         $result[] = $note;
    }
    return $result;
}
