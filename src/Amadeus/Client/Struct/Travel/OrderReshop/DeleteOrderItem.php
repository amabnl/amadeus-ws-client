<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * DeleteOrderItem
 *
 * Function to request the removal of an Order Item from a specific Order.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class DeleteOrderItem
{
    /**
     * @var string
     */
    public $OrderItemRefID;

    /**
     * @var string|string[]|null
     */
    public $RetainServiceID;

    /**
     * @param string $orderItemRefId
     * @param string|string[]|null $retainServiceId
     */
    public function __construct($orderItemRefId, $retainServiceId = null)
    {
        $this->OrderItemRefID = $orderItemRefId;

        if (!empty($retainServiceId)) {
            $this->RetainServiceID = $retainServiceId;
        }
    }
}
