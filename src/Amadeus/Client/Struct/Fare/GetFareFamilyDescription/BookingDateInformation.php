<?php

namespace Amadeus\Client\Struct\Fare\GetFareFamilyDescription;

use Amadeus\Client\Struct\Fare\PricePnr13\DateTime;

/**
 * Class BookingDateInformation
 * @package Amadeus\Client\Struct\Fare\GetFareFamilyDescription
 */
class BookingDateInformation
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * BookingDateInformation constructor.
     * @param DateTime $dateTime
     */
    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }
}