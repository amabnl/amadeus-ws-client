<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * AbstractSelectedOffer
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
abstract class AbstractSelectedOffer
{
    /**
     * @var string
     */
    public $OwnerCode;

    /**
     * @var string
     */
    public $ShoppingResponseRefID;

    /**
     * @var OfferPrice\SelectedOfferItem[]|OrderCreate\SelectedOfferItem[]
     */
    public $SelectedOfferItem;

    /**
     * @param string $ownerCode
     * @param string $shoppingResponseRefID
     * @param OfferPrice\SelectedOfferItem[]|OrderCreate\SelectedOfferItem[] $selectedOfferItem
     */
    public function __construct($ownerCode, $shoppingResponseRefID, array $selectedOfferItem)
    {
        $this->OwnerCode = $ownerCode;
        $this->ShoppingResponseRefID = $shoppingResponseRefID;
        $this->SelectedOfferItem = $selectedOfferItem;
    }
}
