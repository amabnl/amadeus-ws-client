<?php

namespace Amadeus\Client\RequestOptions\Travel;

use Amadeus\Client\LoadParamsFromArray;

class Sender extends LoadParamsFromArray
{
    /**
     * @var TravelAgency
     */
    public $travelAgency;
}
