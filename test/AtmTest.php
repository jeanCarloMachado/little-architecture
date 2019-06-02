<?php

namespace myNamespace;
use InvalidArgumentException;

/**
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
class AtmTest extends \PHPUnit\Framework\TestCase
{
    public function testFailsWhenWrithdrawNothing()
    {
        $this->expectException(InvalidArgumentException::class);
        withdraw(null);
    }

    public function testReturnAskedNote()
    {
        $this->assertEquals([5], withdraw(5));
        $this->assertEquals([10], withdraw(10));
    }

    public function testReturnMoreOfSameWhenMultiple()
    {
        $this->assertEquals([500, 500], withdraw(1000));
    }

    public function testReturnMultipleTypesOfNotes()
    {
        $this->assertEquals([10, 5], withdraw(15));
    }
}

function withdraw($amountRequested) {

    if (!$amountRequested) {
        throw new InvalidArgumentException;
    }

    $notes  = [500, 100, 50, 10, 5];

    $result = [];
    $amountRest = $amountRequested;
    foreach ($notes as $note) {
        if (!noteCanReduceFromAmount($note, $amountRest))  {
            continue;
        }

        $quantityOfNotes = intval($amountRest / $note);
        $result = addNotesQuantity($result, $note, $quantityOfNotes);

        $amountRest-= $note * $quantityOfNotes;

    }

    return $result;
}

function addNotesQuantity($result, $note, $quantityOfNotes) {
        for ($i = 0 ; $i < $quantityOfNotes ; $i++) {
            $result[] = $note;
        }
        return $result;
}

function noteCanReduceFromAmount($note, $amountRest) {
    return ($amountRest / $note) >= 1;
}
