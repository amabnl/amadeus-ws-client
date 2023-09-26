<?php

namespace Amadeus\Client\RequestOptions\Travel\OfferPrice;

use Amadeus\Client\LoadParamsFromArray;
use Amadeus\Client\RequestOptions\Travel\DataList;
use Amadeus\Client\RequestOptions\Travel\PricedOffer;

class Request extends LoadParamsFromArray
{
    /**
     * @var DataList[]
     */
    public $dataLists;

    /**
     * @var PricedOffer
     */
    public $pricedOffer;
}
