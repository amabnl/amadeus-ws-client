<?php

namespace Amadeus\Client\RequestOptions;

/**
 * Travel_OrderPay Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TravelOrderPayOptions extends AbstractTravelOptions
{
    const PAYMENT_TYPE_CASH = 'CA';

    /**
     * @var string
     */
    public $orderId;

    /**
     * @var string
     */
    public $ownerCode;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var string
     */
    public $currencyCode;

    /**
     * @var string self::PAYMENT_TYPE_*
     */
    public $type;
}
