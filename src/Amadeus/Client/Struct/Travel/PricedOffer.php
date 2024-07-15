<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * PricedOffer
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class PricedOffer
{
    /**
     * @var AbstractSelectedOffer
     */
    public $SelectedOffer;

    /**
     * @param AbstractSelectedOffer $selectedOffer
     */
    public function __construct(AbstractSelectedOffer $selectedOffer)
    {
        $this->SelectedOffer = $selectedOffer;
    }
}
