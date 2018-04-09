<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * ConnectionOption
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConnectionOption
{
    /**
     * @var Connection
     */
    public $firstConnection;

    /**
     * @var Connection[]
     */
    public $secondConnection = [];

    /**
     * ConnectionOption constructor.
     *
     * @param string[] $connections
     * @param int|string|null $indicator Connection::INDICATOR_*
     */
    public function __construct($connections, $indicator = null)
    {
        foreach ($connections as $counter => $connection) {
            if ($counter === 0) {
                $this->firstConnection = new Connection($connection, $indicator);
            } else {
                $this->secondConnection[] = new Connection($connection, $indicator);
            }
        }
    }
}
