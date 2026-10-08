<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * SelectedOfferItem
 *
 * Offer item selected by the passenger (SelectedOfferItemType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class SelectedOfferItem
{
    /**
     * @var string
     */
    public $OfferItemRefID;

    /**
     * @var string|string[]
     */
    public $PaxRefID;

    /**
     * @param string $offerItemRefId
     * @param string|string[] $paxRefId
     */
    public function __construct($offerItemRefId, $paxRefId)
    {
        $this->OfferItemRefID = $offerItemRefId;
        $this->PaxRefID = $paxRefId;
    }
}
