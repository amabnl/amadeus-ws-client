<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderChange;

use Amadeus\Client\LoadParamsFromArray;

class AcceptChange extends LoadParamsFromArray
{
    /**
     * @var string[]
     */
    public $orderItemRefIds;
}
