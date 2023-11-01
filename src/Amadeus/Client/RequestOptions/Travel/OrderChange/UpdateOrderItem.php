<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderChange;

use Amadeus\Client\LoadParamsFromArray;
use Amadeus\Client\RequestOptions\Travel\SelectedOffer;

class UpdateOrderItem extends LoadParamsFromArray
{
    /**
     * @var SelectedOffer
     */
    public $offer;
}
