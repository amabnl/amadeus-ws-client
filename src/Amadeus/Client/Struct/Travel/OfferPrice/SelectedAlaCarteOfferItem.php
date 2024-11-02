<?php

namespace Amadeus\Client\Struct\Travel\OfferPrice;

/**
 * SelectedAlaCarteOfferItem
 *
 * @package Amadeus\Client\Struct\Travel\OfferPrice
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SelectedAlaCarteOfferItem
{
    /**
     * @var int
     */
    public $Qty;

    /**
     * @param int $quantity
     */
    public function __construct($quantity)
    {
        $this->Qty = $quantity;
    }
}
