<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * AddOfferItems - request options
 *
 * Action to request new Offer Items for the specified Order. Maps to
 * ServiceOrder/AddOfferItems. FlightRequest is mandatory; the other elements
 * are optional shopping qualifiers.
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class AddOfferItems extends LoadParamsFromArray
{
    /**
     * Passenger information (Paxs/Pax).
     *
     * @var Pax[]|null
     */
    public $paxs;

    /**
     * Flight request (mandatory). One of the FlightRequestType choices.
     *
     * @var FlightRequest
     */
    public $flightRequest;
}
