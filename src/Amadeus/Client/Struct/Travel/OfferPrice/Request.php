<?php

namespace Amadeus\Client\Struct\Travel\OfferPrice;

use Amadeus\Client\RequestOptions\Travel as TravelRequest;
use Amadeus\Client\Struct\Travel\AbstractOfferRequest;
use Amadeus\Client\Struct\Travel\PricedOffer;

/**
 * Request
 *
 * @package Amadeus\Client\Struct\Travel\OfferPrice
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Request extends AbstractOfferRequest
{
    /**
     * @var PricedOffer
     */
    public $PricedOffer;

    /**
     * @param TravelRequest\DataList[] $requestDataLists
     * @param TravelRequest\PricedOffer $requestPricedOffer
     */
    public function __construct(array $requestDataLists, TravelRequest\PricedOffer $requestPricedOffer)
    {
        $this->loadDataLists($requestDataLists);
        $this->PricedOffer = $this->makePricedOffer($requestPricedOffer);
    }

    /**
     * @param TravelRequest\SelectedOffer $selectedOffer
     * @return SelectedOffer
     */
    protected function makeSelectedOffer(TravelRequest\SelectedOffer $selectedOffer)
    {
        return new SelectedOffer(
            $selectedOffer->ownerCode,
            $selectedOffer->shoppingResponseRefID,
            array_map(static function (TravelRequest\SelectedOfferItem $selectedOfferItem) {
                return new SelectedOfferItem(
                    $selectedOfferItem->offerItemRefId,
                    $selectedOfferItem->paxRefId
                );
            }, $selectedOffer->selectedOfferItems),
            $selectedOffer->offerRefID
        );
    }
}
