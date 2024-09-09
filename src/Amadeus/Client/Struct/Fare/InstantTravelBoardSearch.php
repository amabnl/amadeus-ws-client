<?php

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareInstantTravelTBSearch;

class InstantTravelBoardSearch extends MasterPricerTravelBoardSearch
{
    public function __construct($options = null)
    {
        if ($options instanceof FareInstantTravelTBSearch) {
            $this->loadOptions($options);
        }
    }
}
