<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * Order
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Order
{
    /**
     * @var string
     */
    public $OrderID;

    /**
     * @var string
     */
    public $OwnerCode;

    /**
     * @param string $orderId
     * @param string $ownerCode
     */
    public function __construct($orderId, $ownerCode)
    {
        $this->OrderID = $orderId;
        $this->OwnerCode = $ownerCode;
    }
}
