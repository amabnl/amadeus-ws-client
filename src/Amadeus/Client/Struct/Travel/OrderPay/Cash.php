<?php

namespace Amadeus\Client\Struct\Travel\OrderPay;

/**
 * Cash
 *
 * @package Amadeus\Client\Struct\Travel\OrderPay
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Cash
{
    /**
     * @var bool
     */
    public $CashInd;

    /**
     * @param bool $cashInd
     */
    public function __construct($cashInd)
    {
        $this->CashInd = $cashInd;
    }
}
