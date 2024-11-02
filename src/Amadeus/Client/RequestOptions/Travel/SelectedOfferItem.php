<?php

namespace Amadeus\Client\RequestOptions\Travel;

use Amadeus\Client\LoadParamsFromArray;

class SelectedOfferItem extends LoadParamsFromArray
{
    /**
     * @var string
     */
    public $offerItemRefId;

    /**
     * @var string[]
     */
    public $paxRefId;

    /**
     * @var SelectedAlaCarteOfferItem|null
     */
    public $selectedAlaCarteOfferItem = null;

    /**
     * @var SelectedSeat|null
     */
    public $selectedSeat = null;
}
