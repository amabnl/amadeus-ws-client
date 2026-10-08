<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * DeleteOrderItem - request options
 *
 * Function to request the removal of an Order Item from a specific Order.
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class DeleteOrderItem extends LoadParamsFromArray
{
    /**
     * Reference to the Order Item requested for deletion.
     *
     * @var string
     */
    public $orderItemRefId;

    /**
     * Reference(s) to the Service(s) within the Order Item that the passenger
     * would like the airline to retain in the proposed Offer Item(s).
     *
     * @var string|string[]|null
     */
    public $retainServiceId;
}
