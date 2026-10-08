<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * BookingRef - request options
 *
 * Information related to a booking or reservation.
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class BookingRef extends LoadParamsFromArray
{
    /**
     * Existing booking reference identifier.
     *
     * @var string
     */
    public $bookingId;

    /**
     * Booking reference type (PADIS 1153). Example: 6
     *
     * @var string|null
     */
    public $typeCode;

    /**
     * Airline or organization assigning the booking information (BookingEntity).
     * Example: an airline designator code such as "6X".
     *
     * @var string
     */
    public $bookingEntityAirlineDesigCode;
}
