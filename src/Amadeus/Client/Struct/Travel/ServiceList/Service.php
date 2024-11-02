<?php

namespace Amadeus\Client\Struct\Travel\ServiceList;

/**
 * Service
 *
 * @package Amadeus\Client\Struct\Travel\ServiceList
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Service
{
    /**
     * @var int
     */
    public $ServiceID;

    /**
     * @param int $serviceId
     */
    public function __construct($serviceId)
    {
        $this->ServiceID = $serviceId;
    }
}
