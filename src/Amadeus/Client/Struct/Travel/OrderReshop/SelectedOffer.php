<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\SelectedOffer as RequestSelectedOffer;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\SelectedOfferItem as RequestSelectedOfferItem;

/**
 * SelectedOffer
 *
 * Offer selected by the passenger from a previous shopping response
 * (SelectedOfferType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class SelectedOffer
{
    /**
     * @var string
     */
    public $OfferRefID;

    /**
     * @var string
     */
    public $OwnerCode;

    /**
     * @var string
     */
    public $ShoppingResponseRefID;

    /**
     * @var SelectedOfferItem[]
     */
    public $SelectedOfferItem;

    /**
     * @param RequestSelectedOffer $offer
     */
    public function __construct(RequestSelectedOffer $offer)
    {
        $this->OfferRefID = $offer->offerRefId;
        $this->OwnerCode = $offer->ownerCode;
        $this->ShoppingResponseRefID = $offer->shoppingResponseRefId;

        $this->SelectedOfferItem = array_map(
            static function (RequestSelectedOfferItem $item) {
                return new SelectedOfferItem($item->offerItemRefId, $item->paxRefId);
            },
            $offer->selectedOfferItems
        );
    }
}
