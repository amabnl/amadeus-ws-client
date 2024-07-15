<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\Travel as TravelRequest;

/**
 * AbstractOfferRequest
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
abstract class AbstractOfferRequest
{
    /**
     * @var DataList
     */
    public $DataLists;

    /**
     * @param TravelRequest\DataList[] $requestDataLists
     * @return void
     */
    protected function loadDataLists(array $requestDataLists)
    {
        foreach ($requestDataLists as $requestDataList) {
            $this->DataLists = new DataList(new PaxList($requestDataList->paxList));
        }
    }

    /**
     * @param TravelRequest\PricedOffer $requestPricedOffer
     * @return PricedOffer
     */
    protected function makePricedOffer(TravelRequest\PricedOffer $requestPricedOffer)
    {
        return new PricedOffer($this->makeSelectedOffer($requestPricedOffer->selectedOffer));
    }

    /**
     * @param TravelRequest\SelectedOffer $selectedOffer
     * @return AbstractSelectedOffer
     */
    abstract protected function makeSelectedOffer(TravelRequest\SelectedOffer $selectedOffer);
}
