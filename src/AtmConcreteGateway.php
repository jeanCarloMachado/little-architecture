<?php
declare(strict_types=1);
namespace Atm;

/**
 * @author Jean Carlo Machado <jean.machado@getyourguide.com>
 */
class AtmConcreteGateway implements AtmGateway
{
    public function __construct($db) {
        $this->db = $db;
    }
    public function getAvailability() : array {
        $result = \call_user_func($this->db, "SELECT * FROM note_availability");
        $finalResult = [];
        foreach($result as $value) {
            $finalResult[$value['note']] = $value['quantity'];
        }
        krsort($finalResult);
        return $finalResult;
    }
}

