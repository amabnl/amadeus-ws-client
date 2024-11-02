<?php

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Travel\DataList;
use Amadeus\Client\RequestOptions\Travel\PricedOffer;

/**
 * Travel_OfferPrice Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TravelOfferPriceOptions extends AbstractTravelOptions
{
    /**
     * @var DataList[]
     */
    public $dataLists;

    /**
     * @var PricedOffer
     */
    public $pricedOffer;

    /**
     * @var string Existing offer id
     */
    public $orderId = '';
}
