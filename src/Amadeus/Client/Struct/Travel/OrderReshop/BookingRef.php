<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * BookingRef
 *
 * Information related to a booking or reservation.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class BookingRef
{
    /**
     * @var string
     */
    public $BookingID;

    /**
     * @var string|null
     */
    public $TypeCode;

    /**
     * @var BookingEntity
     */
    public $BookingEntity;

    /**
     * @param string $bookingId
     * @param string $bookingEntityAirlineDesigCode
     * @param string|null $typeCode
     */
    public function __construct($bookingId, $bookingEntityAirlineDesigCode, $typeCode = null)
    {
        $this->BookingID = $bookingId;

        if ($typeCode !== null) {
            $this->TypeCode = $typeCode;
        }

        $this->BookingEntity = new BookingEntity($bookingEntityAirlineDesigCode);
    }
}
