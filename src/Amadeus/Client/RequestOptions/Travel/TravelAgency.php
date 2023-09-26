<?php

namespace Amadeus\Client\RequestOptions\Travel;

use Amadeus\Client\LoadParamsFromArray;

class TravelAgency extends LoadParamsFromArray
{
    /**
     * @var string
     */
    public $agencyId;

    /**
     * @var string
     */
    public $pseudoCityId;
}
