<?php
namespace Atm;
use InvalidArgumentException;

class Atm {
    public function __construct(AtmGateway $gateway) {
        $this->gateway = $gateway;
    }
    function withdraw($quantity) {
        $availability = $this->gateway->queryAvailability();

        return $this->statelessWithdraw($availability, $quantity);

    }
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

