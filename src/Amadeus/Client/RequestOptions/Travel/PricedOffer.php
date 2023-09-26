<?php

namespace Amadeus\Client\RequestOptions\Travel;

use Amadeus\Client\LoadParamsFromArray;

class PricedOffer extends LoadParamsFromArray
{
    /**
     * @var SelectedOffer
     */
    public $selectedOffer;
}
