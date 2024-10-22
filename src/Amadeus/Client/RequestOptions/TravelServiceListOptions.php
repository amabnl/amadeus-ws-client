<?php

namespace Amadeus\Client\RequestOptions;

/**
 * Travel_ServiceList Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TravelServiceListOptions extends AbstractTravelOptions
{
    /**
     * @var string
     */
    public $orderId;

    /**
     * @var string
     */
    public $ownerCode;

    /**
     * @var string
     */
    public $offerId;

    /**
     * @var string
     */
    public $offerItemId;

    /**
     * @var int
     */
    public $serviceId;

    public string $shoppingResponseId = '';
}
