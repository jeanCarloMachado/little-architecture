<?php
namespace Atm;

class AtmGatewayConcrete extends AtmGateway {
    function queryAvailability() {
        return json_decode(file_get_contents(__DIR__.'/../data.json'));
    }
}

