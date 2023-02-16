<?php

namespace Amadeus\Client\Struct\Travel\OfferPrice;

use Amadeus\Client\Struct\Travel\AbstractSelectedOffer;

/**
 * SelectedOffer
 *
 * @package Amadeus\Client\Struct\Travel\OfferPrice
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SelectedOffer extends AbstractSelectedOffer
{
    /**
     * @var string
     */
    public $OfferRefID;

    /**
     * @param string $ownerCode
     * @param string $shoppingResponseRefID
     * @param SelectedOfferItem[] $selectedOfferItem
     * @param string $offerRefID
     */
    public function __construct($ownerCode, $shoppingResponseRefID, array $selectedOfferItem, $offerRefID)
    {
        parent::__construct($ownerCode, $shoppingResponseRefID, $selectedOfferItem);
        $this->OfferRefID = $offerRefID;
    }
}
