<?php

namespace Atm;

use InvalidArgumentException;

class Atm {

    public static function factory($container = null) {
        $logger = function($str) {
            file_put_contents('/tmp/log', $str, FILE_APPEND);
        };

        $gateway = new class($logger) extends ConcreateAtmGateway {
            public function __construct($logger) {
                $this->logger = $logger;
            }
            function getAvailablity()
            {
                $logger = $this->logger;
                $logger('call get availability');
                return parent::getAvailablity();
            }
        };
        $atm = new Atm($gateway);
        return $atm;
    }
    public function __construct(AtmGatewayInterface $gateway) {
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

