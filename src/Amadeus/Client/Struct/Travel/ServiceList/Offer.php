<?php

namespace Amadeus\Client\Struct\Travel\ServiceList;

/**
 * Offer
 *
 * @package Amadeus\Client\Struct\Travel\ServiceList
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Offer
{
    /**
     * @var string
     */
    public $OfferID;

    /**
     * @var string
     */
    public $OwnerCode;

    /**
     * @var OfferItem
     */
    public $OfferItem;

    /**
     * @param string $offerId
     * @param string $ownerCode
     * @param OfferItem $offerItem
     */
    public function __construct($offerId, $ownerCode, OfferItem $offerItem)
    {
        $this->OfferID = $offerId;
        $this->OwnerCode = $ownerCode;
        $this->OfferItem = $offerItem;
    }
}
