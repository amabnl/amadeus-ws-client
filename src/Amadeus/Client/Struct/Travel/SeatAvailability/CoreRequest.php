<?php

namespace Amadeus\Client\Struct\Travel\SeatAvailability;

use Amadeus\Client\Struct\Travel\Order;

/**
 * CoreRequest
 *
 * @package Amadeus\Client\Struct\Travel\SeatAvailability
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class CoreRequest
{
    /**
     * @var Order
     */
    public $Order;

    /**
     * @var Offer
     */
    public $Offer;

    public function setOrder(Order $Order)
    {
        $this->Order = $Order;
    }

    public function setOffer(Offer $Offer)
    {
        $this->Offer = $Offer;
    }
}
