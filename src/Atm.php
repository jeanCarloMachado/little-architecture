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

        $queryWithLogger = function($sql) use ($runQuery, $logger) {
            $logger("Running query :) ".$sql);
            return $runQuery($sql);
        };

        $gateway  = new ConcreateAtmGateway($queryWithLogger);
        return new Atm($gateway);
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
        $valueLeftToFullfil = $requestedAmount;
        foreach ($availability as $note => $quantityAvailable) {

            if ($note > $valueLeftToFullfil) {
                continue;
            }

            $quantityOfNotes = intval($valueLeftToFullfil /  $note);
            if ($quantityOfNotes > $quantityAvailable) {
                $quantityOfNotes = $quantityAvailable;
            }

            $result = self::addNNotesToResult($result, $note, $quantityOfNotes);
            $valueLeftToFullfil -= $note * $quantityOfNotes;
        }

        return $result;
    }

    static function addNNotesToResult($result, $note, $quantityOfNotes) {
            for ($i = 0 ; $i < $quantityOfNotes ; $i++) {
                $result[] = $note;
            }

            return $result;
    }
}
