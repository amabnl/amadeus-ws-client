<?php

namespace Amadeus\Client\Struct\Travel\OrderChange;

/**
 * AcceptOffer
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class AcceptOffer
{
    /**
     * @var SelectedOffer
     */
    public $SelectedOffer;

    /**
     * @param SelectedOffer $selectedOffer
     */
    public function __construct(SelectedOffer $selectedOffer)
    {
        $this->SelectedOffer = $selectedOffer;
    }
}
