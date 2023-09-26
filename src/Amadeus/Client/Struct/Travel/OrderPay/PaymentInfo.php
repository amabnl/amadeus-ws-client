<?php

namespace Amadeus\Client\Struct\Travel\OrderPay;

/**
 * PaymentInfo
 *
 * @package Amadeus\Client\Struct\Travel\OrderPay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class PaymentInfo
{
    /**
     * @var Amount
     */
    public $Amount;

    /**
     * @var string
     */
    public $TypeCode;

    /**
     * @var PaymentMethod
     */
    public $PaymentMethod;

    /**
     * @param Amount $amount
     * @param string $typeCode
     * @param PaymentMethod $paymentMethod
     */
    public function __construct($amount, $typeCode, PaymentMethod $paymentMethod)
    {
        $this->Amount = $amount;
        $this->TypeCode = $typeCode;
        $this->PaymentMethod = $paymentMethod;
    }
}
