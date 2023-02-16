<?php

namespace Amadeus\Client\Struct\Travel\OrderPay;

/**
 * PaymentInfo
 *
 * @package Amadeus\Client\Struct\Travel\OrderPay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Amount
{
    /**
     * @var float
     */
    public $_ = 100;

    public $CurCode = 'USD';

    public function __construct($amount, $currencyCode)
    {
        $this->_ = $amount;
        $this->CurCode = $currencyCode;
    }
}
