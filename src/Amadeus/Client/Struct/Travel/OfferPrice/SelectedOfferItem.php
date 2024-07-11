<?php

namespace Amadeus\Client\Struct\Travel\OfferPrice;

/**
 * SelectedOfferItem
 *
 * @package Amadeus\Client\Struct\Travel\OfferPrice
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SelectedOfferItem
{
    /**
     * @var string
     */
    public $OfferItemRefID;

    /**
     * @var string[]
     */
    public $PaxRefID;

    /**
     * @param string $offerItemRefID
     * @param string[] $paxRefID
     */
    public function __construct($offerItemRefID, array $paxRefID)
    {
        $this->OfferItemRefID = $offerItemRefID;
        $this->PaxRefID = $paxRefID;
    }
}
