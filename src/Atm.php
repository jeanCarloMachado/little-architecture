<?php

namespace Atm;

use InvalidArgumentException;

class Atm {

    public function __construct(AtmGateway $gateway) {
        $this->gateway = $gateway;
    }

    public function withdraw(int $requestedAmount) : array
    {
        $availability = $this->gateway->getAvailablity();
        return Atm::_withdraw($availability, $requestedAmount);
    }

    static function _withdraw($availability, $requestedAmount) {
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
            $returnedNotes = Atm::appendNNotes($returnedNotes, $note, $retriveableQuantity);
        }

        return $returnedNotes;
    }

    private function appendNNotes(array $result, int $note, int $n) : array {
        for ($i = 0 ; $i < $n; $i++) {
             $result[] = $note;
        }
        return $result;
    }
}

