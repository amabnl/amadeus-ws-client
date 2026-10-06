<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * RepriceOrder
 *
 * Function to reprice the entire Order (when no OrderItemRefID is supplied) or
 * a specific Order Item.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class RepriceOrder
{
    /**
     * @var string|null
     */
    public $OrderItemRefID;

    /**
     * @param string|null $orderItemRefId
     */
    public function __construct($orderItemRefId = null)
    {
        if ($orderItemRefId !== null) {
            $this->OrderItemRefID = $orderItemRefId;
        }
    }
}
