<?php

namespace Amadeus\Client\Struct\Travel\SeatAvailability;

/**
 * Request
 *
 * @package Amadeus\Client\Struct\Travel\SeatAvailability
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Offer
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
     * @param string $offerItemId
     * @param string $ownerCode
     */
    public function __construct($offerItemId, $ownerCode)
    {
        $this->OfferItemID = $offerItemId;
        $this->OwnerCode = $ownerCode;
    }
}
