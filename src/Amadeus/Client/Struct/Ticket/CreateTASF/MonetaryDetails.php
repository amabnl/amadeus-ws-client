<?php

namespace Amadeus\Client\Struct\Ticket\CreateTASF;

class MonetaryDetails
{
    /**
     * Must be always set to F
     *
     * @var string
     */
    public $typeQualifier = 'F';

    /**
     * @var string|int
     */
    public $amount;

    /**
     * @var string
     */
    public $currency;

    /**
     * MonetaryDetails constructor.
     *
     * @param string|int $amount
     * @param string $currency
     */
    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }
}
