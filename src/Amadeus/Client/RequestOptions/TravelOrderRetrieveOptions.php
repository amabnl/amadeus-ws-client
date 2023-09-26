<?php

namespace Amadeus\Client\RequestOptions;

/**
 * Travel_OrderRetrieve Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TravelOrderRetrieveOptions extends AbstractTravelOptions
{
    /**
     * @var string
     */
    public $orderId;

    /**
     * @var string
     */
    public $ownerCode;
}
