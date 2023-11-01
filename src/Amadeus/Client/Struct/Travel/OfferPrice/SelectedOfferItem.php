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
     * @var SelectedAlaCarteOfferItem|null
     */
    public $SelectedAlaCarteOfferItem = null;

    /**
     * @var SelectedSeat|null
     */
    public $SelectedSeat = null;

    /**
     * @param string $offerItemRefID
     * @param string[] $paxRefID
     */
    public function __construct($offerItemRefID, array $paxRefID)
    {
        $this->OfferItemRefID = $offerItemRefID;
        $this->PaxRefID = $paxRefID;
    }

    public function setSelectedAlaCarteOfferItem(SelectedAlaCarteOfferItem $SelectedAlaCarteOfferItem)
    {
        $this->SelectedAlaCarteOfferItem = $SelectedAlaCarteOfferItem;
    }

    public function setSelectedSeat(SelectedSeat $SelectedSeat)
    {
        $this->SelectedSeat = $SelectedSeat;
    }
}
