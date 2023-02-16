<?php

namespace Amadeus\Client\Struct\Travel\OrderPay;

/**
 * PaymentMethod
 *
 * @package Amadeus\Client\Struct\Travel\OrderPay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class PaymentMethod
{
    const TYPE_CASH = 'CA';

    /**
     * @var Cash|null
     */
    public $Cash;

    /**
     * @param Cash|null $cash
     */
    public function __construct($cash)
    {
        $this->Cash = $cash;
    }
}
