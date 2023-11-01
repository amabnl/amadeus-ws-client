<?php

namespace Amadeus\Client\Struct\Travel\ServiceList;

/**
 * OfferItem
 *
 * @package Amadeus\Client\Struct\Travel\ServiceList
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OfferItem
{
    /**
     * @var string
     */
    public $OfferItemID;

    /**
     * @var string
     */
    public $OwnerCode;

    /**
     * @var Service
     */
    public $Service;

    /**
     * @param string $offerItemId
     * @param string $ownerCode
     * @param Service $service
     */
    public function __construct($offerItemId, $ownerCode, Service $service)
    {
        $this->OfferItemID = $offerItemId;
        $this->OwnerCode = $ownerCode;
        $this->Service = $service;
    }
}
