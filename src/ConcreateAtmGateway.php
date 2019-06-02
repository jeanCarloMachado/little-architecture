<?php

namespace Atm;

class ConcreateAtmGateway extends AtmGatewayInterface {

    public function getAvailablity() {
        $result =  json_decode(file_get_contents(__DIR__.'/../availability.json'));

        return $result;
    }

    public function removeNotesFromStorage() {
    }
}
