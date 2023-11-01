<?php

namespace Amadeus\Client\Struct\Travel\OrderChange;

/**
 * UpdateOrderItem
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class UpdateOrderItem
{
    /**
     * @var AcceptOffer
     */
    public $AcceptOffer;

    /**
     * @param AcceptOffer $acceptOffer
     */
    public function __construct(AcceptOffer $acceptOffer)
    {
        $this->AcceptOffer = $acceptOffer;
    }
}
