<?php

namespace Amadeus\Client\Struct\Travel\OrderCreate;

/**
 * SelectedOfferItem
 *
 * @package Amadeus\Client\Struct\Travel\OrderCreate
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SelectedOfferItem
{
    /**
     * @var string
     */
    public $OfferItemID;

    /**
     * @var string[]
     */
    public $PaxRefID;

    /**
     * @param string $offerItemID
     * @param string[] $paxRefID
     */
    public function __construct($offerItemID, array $paxRefID)
    {
        $this->OfferItemID = $offerItemID;
        $this->PaxRefID = $paxRefID;
    }
}
