<?php

namespace Amadeus\Client\Struct\Ticket\CreateTASF;

class MonetaryInformation
{
    /**
     * @var MonetaryDetails
     */
    public $monetaryDetails;

    /**
     * MonetaryInformation constructor.
     *
     * @param string|int $amount
     * @param string $currency
     */
    public function __construct($amount, $currency)
    {
        $this->monetaryDetails = new MonetaryDetails($amount, $currency);
    }
}
