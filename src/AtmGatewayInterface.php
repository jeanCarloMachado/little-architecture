<?php

namespace Atm;

abstract class AtmGatewayInterface {
    public function getAvailablity() {
        throw new \Exception("You must implement me");
    }
}
