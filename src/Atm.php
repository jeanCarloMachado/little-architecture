<?php


namespace Atm;

use InvalidArgumentException;

class Atm
{
    static function factory($container = null) {
        $gateway = new ConcreteAtmGateway;
        return new Atm($gateway);
    }
    public function __construct(AtmGateway $gateway) {
        $this->gateway = $gateway;
    }

    function withdraw($amount) {
        $availability = $this->gateway->getAvailability();
        return self::statelessWithdraw($availability, $amount);
    }


    static function statelessWithdraw($availability, $amount) {

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

            $result = self::addNNotesToResult($result, $note, $quantityOfNotes );

            $remainingValue-= $note * $quantityOfNotes;

        }

        return $result;
    }

    static function addNNotesToResult($result, $note, $quantityOfNotes) {
            for ($i = 0; $i < $quantityOfNotes ; $i++) {
                $result[]  = $note;
            }

            return $result;
    }
}

