<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * FlightRequest - request options
 *
 * Choice of flight information for the shopping request. Exactly one of the
 * following should be populated (maps to the FlightRequestType choice):
 *  - $originDestRequests (OriginDestRequest[])
 *  - $specificOriginDestRequests (SpecificOriginDestRequest[])
 *  - $selectedOffers (SelectedOffer[])
 *  - $shoppingResponseRefId (ShoppingResponse/ShoppingResponseID)
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class FlightRequest extends LoadParamsFromArray
{
    /**
     * Origin/Destination based shopping request(s).
     *
     * @var OriginDestRequest[]|null
     */
    public $originDestRequests;

    /**
     * Segment-detailed shopping request(s).
     *
     * @var SpecificOriginDestRequest[]|null
     */
    public $specificOriginDestRequests;

    /**
     * Selected offer(s) from a previous shopping response.
     *
     * @var SelectedOffer[]|null
     */
    public $selectedOffers;

    /**
     * Shopping session (message response) ID.
     *
     * @var string|null
     */
    public $shoppingResponseRefId;
}
