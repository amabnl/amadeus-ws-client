<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\AddOfferItems as RequestAddOfferItems;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\Pax as RequestPax;

/**
 * AddOfferItems
 *
 * Action to request new Offer Items for the specified Order.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class AddOfferItems
{
    /**
     * @var Paxs|null
     */
    public $Paxs;

    /**
     * @var FlightRequest
     */
    public $FlightRequest;

    /**
     * @param RequestAddOfferItems $addOfferItems
     */
    public function __construct(RequestAddOfferItems $addOfferItems)
    {
        if (!empty($addOfferItems->paxs)) {
            $this->Paxs = new Paxs(
                array_map(
                    static function (RequestPax $pax) {
                        return new Pax($pax);
                    },
                    $addOfferItems->paxs
                )
            );
        }

        $this->FlightRequest = new FlightRequest($addOfferItems->flightRequest);
    }
}
