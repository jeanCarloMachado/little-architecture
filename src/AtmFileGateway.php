<?php

namespace Atm;

class AtmFileGateway extends AtmGateway {

    public function getAvailablity() {
        $result =  json_decode(file_get_contents(__DIR__.'/../availability.json'));

        return $result;
    }
}
