<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * Sender
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Sender
{
    /**
     * @var TravelAgency
     */
    public $TravelAgency;

    /**
     * @param TravelAgency $TravelAgency
     */
    public function __construct(TravelAgency $TravelAgency)
    {
        $this->TravelAgency = $TravelAgency;
    }
}
