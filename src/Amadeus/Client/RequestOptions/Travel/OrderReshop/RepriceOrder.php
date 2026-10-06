<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * RepriceOrder - request options
 *
 * Function to reprice the entire Order (when no OrderItemRefID is supplied) or
 * a specific Order Item (when the reference is provided).
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class RepriceOrder extends LoadParamsFromArray
{
    /**
     * Optionally specify which OrderItem needs to be repriced.
     *
     * @var string|null
     */
    public $orderItemRefId;
}
