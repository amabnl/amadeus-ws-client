<?php

namespace Amadeus\Client\Struct\Travel\OrderCreate;

use Amadeus\Client\Struct\Travel\AbstractSelectedOffer;

/**
 * SelectedOffer
 *
 * @package Amadeus\Client\Struct\Travel\OrderCreate
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SelectedOffer extends AbstractSelectedOffer
{
    /**
     * @var string
     */
    public $OfferID;

    /**
     * @param string $ownerCode
     * @param string $shoppingResponseRefID
     * @param SelectedOfferItem[] $selectedOfferItem
     * @param string $offerID
     */
    public function __construct($ownerCode, $shoppingResponseRefID, array $selectedOfferItem, $offerID)
    {
        parent::__construct($ownerCode, $shoppingResponseRefID, $selectedOfferItem);

        $this->OfferID = $offerID;
    }
}
