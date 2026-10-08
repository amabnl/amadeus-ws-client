<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * SelectedOfferItem - request options
 *
 * Offer item selected by the passenger (SelectedOffer/SelectedOfferItem).
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class SelectedOfferItem extends LoadParamsFromArray
{
    /**
     * Reference to the selected OfferItemID.
     *
     * @var string
     */
    public $offerItemRefId;

    /**
     * Reference(s) to the passenger(s) this item applies to.
     *
     * @var string|string[]
     */
    public $paxRefId;
}
