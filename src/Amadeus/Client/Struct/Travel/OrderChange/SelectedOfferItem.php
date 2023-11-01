<?php

namespace Amadeus\Client\Struct\Travel\OrderChange;

/**
 * SelectedOfferItem
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SelectedOfferItem
{
    /**
     * @var string
     */
    public $OfferItemRefID;

    /**
     * @var string
     */
    public $PaxRefID;

    /**
     * @var SelectedSeat
     */
    public $SelectedSeat;

    /**
     * @param string $offerItemRefId
     * @param string $paxRefId
     */
    public function __construct($offerItemRefId, $paxRefId)
    {
        $this->OfferItemRefID = $offerItemRefId;
        $this->PaxRefID = $paxRefId;
    }

    public function setSelectedSeat(SelectedSeat $selectedSeat)
    {
        $this->SelectedSeat = $selectedSeat;
    }
}
