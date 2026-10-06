<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * SelectedOffer - request options
 *
 * Offer selected by the passenger from a previous shopping response
 * (FlightRequest/SelectedOffer).
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class SelectedOffer extends LoadParamsFromArray
{
    /**
     * Reference to the OfferID of the selected Offer.
     *
     * @var string
     */
    public $offerRefId;

    /**
     * Designator of the airline that owns these IDs.
     *
     * @var string
     */
    public $ownerCode;

    /**
     * Reference to the shopping session response ID.
     *
     * @var string
     */
    public $shoppingResponseRefId;

    /**
     * Selected offer items.
     *
     * @var SelectedOfferItem[]
     */
    public $selectedOfferItems;
}
