<?php
declare(strict_types=1);
namespace Atm;


/**
 * @author Jean Carlo Machado <jean.machado@getyourguide.com>
 */
interface AtmGateway
{
    public function getAvailability() : array;
}

