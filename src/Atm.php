<?php
declare(strict_types=1);
namespace Atm;

use InvalidArgumentException;

/**
 * @author Jean Carlo Machado <jean.machado@getyourguide.com>
 */
class Atm
{
    static function factory($container = null)
    {

        $runQuery = function($sql) {

            $db = new \PDO('mysql:host=127.0.0.1;dbname=test', 'gandalf', 'gandalf');
            return $db->query($sql)->fetchAll();
        };

       $logger = function($str) {
                file_put_contents('/tmp/log', $str."\n", FILE_APPEND);
        };

        $runQueryWithLogging = function($sql) use ($runQuery, $logger) {
            $logger("Running sql: ".$sql);
            return $runQuery($sql);
        };

        return new Atm(new AtmConcreteGateway($runQueryWithLogging));
    }

    public function __construct(AtmGateway $gateway) {
        $this->gateway = $gateway;
    }

    function withdraw($requestedAmount) {

        $availability = $this->gateway->getAvailability();

        return self::statelessWithdraw($availability, $requestedAmount);

    }

    static function statelessWithdraw($availability, $requestedAmount) {
        if (!$requestedAmount) {
            throw new InvalidArgumentException();
        }

        $result = [];
        $remainingToFullfill = $requestedAmount;
        foreach($availability as $note => $availableQuantity) {
            if (($remainingToFullfill / $note) < 1) {
                continue;
            }

            $quantityOfNotes = intval($remainingToFullfill / $note);
            if ($quantityOfNotes > $availableQuantity) {
                $quantityOfNotes = $availableQuantity;
            }
            $result = self::addNNotesToResult($result, $note, $quantityOfNotes);

            $remainingToFullfill -= $note * $quantityOfNotes;
        }




        return $result;
    }

    static function addNNotesToResult($result, $note, $quantityOfNotes) {
            for ($i =0 ; $i < $quantityOfNotes; $i++) {
                $result[] = $note;
            }

            return $result;
    }
}

