<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelOrderReshopOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Travel\OrderReshop\Request;

/**
 * Travel_OrderReshop message structure
 *
 * @package Amadeus\Client\Struct\Travel
 */
class OrderReshop extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var Request
     */
    public $Request;

    public function __construct(TravelOrderReshopOptions $options)
    {
        $this->Party = new Party($options->party);
        $this->Request = new Request($options);
    }
}
