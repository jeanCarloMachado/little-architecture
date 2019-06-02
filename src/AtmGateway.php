<?php

namespace Atm;

abstract class AtmGateway {
    public function getAvailablity() {
        throw new \Exception("You must implement me");
    }
}
