<?php

namespace Amadeus\Client\RequestOptions\Travel;

use Amadeus\Client\LoadParamsFromArray;

class SelectedOffer extends LoadParamsFromArray
{
    /**
     * @var string
     */
    public $offerRefID;

    /**
     * @var string
     */
    public $ownerCode;

    /**
     * @var string
     */
    public $shoppingResponseRefID;

    /**
     * @var SelectedOfferItem[]
     */
    public $selectedOfferItems;
}
