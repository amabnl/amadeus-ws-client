<?php

namespace Amadeus\Client\Struct\Travel\OrderChange;

/**
 * SelectedOffer
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SelectedOffer
{
    /**
     * @var string
     */
    public $OfferID;

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
     * @param string $offerId
     * @param string $ownerCode
     * @param string $shoppingResponseRefId
     * @param SelectedOfferItem[] $selectedOfferItem
     */
    public function __construct($offerId, $ownerCode, $shoppingResponseRefId, array $selectedOfferItem)
    {
        $this->OfferID = $offerId;
        $this->OwnerCode = $ownerCode;
        $this->ShoppingResponseRefID = $shoppingResponseRefId;
        $this->SelectedOfferItem = $selectedOfferItem;
    }
}
