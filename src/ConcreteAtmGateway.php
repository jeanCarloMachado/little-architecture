<?php


namespace Atm;


class ConcreteAtmGateway implements AtmGateway
{
    function getAvailability()
    {
        $db = new \PDO('mysql:host=127.0.0.1;dbname=test', 'gandalf', 'gandalf');
        $result = $db->query("SELECT * FROM note_availability")->fetchAll();
        $finalResult = [];
        foreach($result as $value) {
            $finalResult[$value['note']] = $value['quantity'];
        }
        krsort($finalResult);
        return $finalResult;
    }
}
