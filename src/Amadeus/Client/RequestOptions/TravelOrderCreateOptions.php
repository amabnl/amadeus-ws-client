<?php

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Travel\DataList;
use Amadeus\Client\RequestOptions\Travel\PricedOffer;

/**
 * Travel_OrderCreate Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TravelOrderCreateOptions extends AbstractTravelOptions
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
