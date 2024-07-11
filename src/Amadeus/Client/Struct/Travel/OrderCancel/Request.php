<?php

namespace Amadeus\Client\Struct\Travel\OrderCancel;

use Amadeus\Client\Struct\Travel\Order;

/**
 * Request
 *
 * @package Amadeus\Client\Struct\Travel\OrderCancel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Request
{
    /**
     * @var Order
     */
    public $Order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->Order = $order;
    }
}
