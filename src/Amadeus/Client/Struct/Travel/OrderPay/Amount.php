<?php

namespace Amadeus\Client\Struct\Travel\OrderPay;

/**
 * Amount
 *
 * @package Amadeus\Client\Struct\Travel\OrderPay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Amount
{
    /**
     * @var float
     */
    public $_;

    /**
     * @var string
     */
    public $CurCode;

    public function __construct($amount, $currencyCode)
    {
        $this->_ = $amount;
        $this->CurCode = $currencyCode;
    }
}
