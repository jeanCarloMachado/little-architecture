<?php


namespace Atm;


class ConcreteAtmGateway implements AtmGateway
{
    function __construct($runQuery) {
        $this->runQuery = $runQuery;
    }
    function getAvailability()
    {
        $result = \call_user_func($this->runQuery, "SELECT * FROM note_availability");
        $finalResult = [];
        foreach($result as $value) {
            $finalResult[$value['note']] = $value['quantity'];
        }
        krsort($finalResult);
        return $finalResult;
    }
}
