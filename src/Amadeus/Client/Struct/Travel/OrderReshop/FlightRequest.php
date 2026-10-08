<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\FlightRequest as RequestFlightRequest;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\OriginDestRequest as RequestOriginDestRequest;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\SelectedOffer as RequestSelectedOffer;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\SpecificOriginDestRequest as RequestSpecificOriginDestRequest;
use InvalidArgumentException;

/**
 * FlightRequest
 *
 * Choice of flight information for the shopping request (FlightRequestType):
 * OriginDestRequest, SpecificOriginDestRequest, SelectedOffer or
 * ShoppingResponse.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class FlightRequest
{
    /**
     * @var OriginDestRequest[]|null
     */
    public $OriginDestRequest;

    /**
     * @var SpecificOriginDestRequest[]|null
     */
    public $SpecificOriginDestRequest;

    /**
     * @var SelectedOffer[]|null
     */
    public $SelectedOffer;

    /**
     * @var ShoppingResponse|null
     */
    public $ShoppingResponse;

    /**
     * @param RequestFlightRequest $flightRequest
     */
    public function __construct(RequestFlightRequest $flightRequest)
    {
        if (!empty($flightRequest->originDestRequests)) {
            $this->OriginDestRequest = array_map(
                static function (RequestOriginDestRequest $req) {
                    return new OriginDestRequest($req);
                },
                $flightRequest->originDestRequests
            );
        } elseif (!empty($flightRequest->specificOriginDestRequests)) {
            $this->SpecificOriginDestRequest = array_map(
                static function (RequestSpecificOriginDestRequest $req) {
                    return new SpecificOriginDestRequest($req);
                },
                $flightRequest->specificOriginDestRequests
            );
        } elseif (!empty($flightRequest->selectedOffers)) {
            $this->SelectedOffer = array_map(
                static function (RequestSelectedOffer $offer) {
                    return new SelectedOffer($offer);
                },
                $flightRequest->selectedOffers
            );
        } elseif ($flightRequest->shoppingResponseRefId !== null) {
            $this->ShoppingResponse = new ShoppingResponse($flightRequest->shoppingResponseRefId);
        } else {
            throw new InvalidArgumentException(
                'FlightRequest requires one of originDestRequests, specificOriginDestRequests, '
                . 'selectedOffers or shoppingResponseRefId'
            );
        }
    }
}
