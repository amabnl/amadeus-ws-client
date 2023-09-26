<?php

namespace Amadeus\Client\Struct\Travel\OrderPay;

use Amadeus\Client\Struct\Travel\Order;

/**
 * Request
 *
 * @package Amadeus\Client\Struct\Travel\OrderPay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Request
{
    /**
     * @var PaymentInfo
     */
    public $PaymentInfo;

    /**
     * @var Order
     */
    public $Order;

    /**
     * @param Order $order
     * @param PaymentInfo $paymentInfo
     */
    public function __construct(PaymentInfo $paymentInfo, Order $order)
    {
        $this->PaymentInfo = $paymentInfo;
        $this->Order = $order;
    }
}
