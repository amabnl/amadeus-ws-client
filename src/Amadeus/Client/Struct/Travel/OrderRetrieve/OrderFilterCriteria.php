<?php

namespace Amadeus\Client\Struct\Travel\OrderRetrieve;

use Amadeus\Client\Struct\Travel\Order;

/**
 * OrderFilterCriteria
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderFilterCriteria
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
