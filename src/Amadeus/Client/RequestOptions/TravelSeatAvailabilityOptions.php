<?php

namespace Amadeus\Client\RequestOptions;

/**
 * Travel_SeatAvailability Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TravelSeatAvailabilityOptions extends AbstractTravelOptions
{
    /**
     * @var string
     */
    public $orderId;

    /**
     * @var string
     */
    public $ownerCode;

    /**
     * @var string
     */
    public $offerItemId;

    /**
     * @var string
     */
    public $shoppingResponseId;
}
