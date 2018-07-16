<?php

namespace Amadeus\Client\RequestOptions\Ticket;

use Amadeus\Client\LoadParamsFromArray;

class MonetaryInformation extends LoadParamsFromArray
{
    /**
     * @var string|int
     */
    public $amount;

    /**
     * @var string
     */
    public $currency;
}
